<?php

class PageSession extends Page
{

    public static function sessions() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        $data["sessions"] = DatabaseSession::loadUserSessions($user->getId(), $mysqli);

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions");

        $data["breadcrumbs"] = $breadcrumbs;
        $data["user"] = $user;
        echo $templates->render("session/sessions", $data);
    }

    public static function view($sessionIdentifier)
    {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // If invalid session, forward home with error
        if (!$sessionID) {

            $alert = new Alert();
            $alert->setType("danger");
            $alert->setDismissable(true);
            $alert->setTitle("Error Joining Session");
            $alert->setMessage("The session ID provided was invalid");
            Alert::displayAlertSession($alert);

            header("Location: " . $config["baseUrl"]);
            die();
        }

        $session = DatabaseSession::loadSession($sessionID, $mysqli);
        $session->setSessionIdentifier($sessionIdentifier);

        // If user cannot view this session, display an error
        if(!$session->checkIfUserCanView($user)) {

            $alert = new Alert();
            $alert->setType("danger");
            $alert->setDismissable(true);
            $alert->setTitle("Error Joining Session");
            $alert->setMessage("You do not have permission to join this session. This may be caused by logging in as a guest user");
            Alert::displayAlertSession($alert);

            header("Location: " . $config["baseUrl"]);
            die();
            /*
            PageError::generic(
                "Error Joining Session",
                "You do not have permission to join this session. This may be caused by logging in as a guest user",
                403,
                true
            );
            die();
            */
        }

        // If this is a student paced session
        if ($session->getQuestionControlMode() == 1) {

            // Get total number of questions
            $totalQuestions = DatabaseSessionQuestion::countActiveQuestions($sessionID, $mysqli);

            // Get current question number
            $questionNumber = isset($_GET["q"]) ? intval($_GET["q"]) - 1 : 0;

            // If the requested question is before the first, forward to the first question
            if($questionNumber < 0) {
                header("Location: .");
                die();
            }

            // If the requested question is after the last, forward to the last question
            elseif($questionNumber > $totalQuestions - 1 && $totalQuestions > 0) {
                header("Location: .?q=$totalQuestions");
                die();
            }
        }

        // Otherwise, this is teacher led so only one question (the first) is visible at one time
        else {
            $totalQuestions = 1;
            $questionNumber = 0;
        }

        // Load active question
        $question = DatabaseSessionQuestion::loadActiveQuestion($sessionID, $questionNumber, $mysqli);

        $responses = null;

        // If a question is active
        if($question) {

            // If MCQ, load response
            if(get_class($question) == "QuestionMcq") {
                $response = DatabaseResponseMcq::loadUserResponse($question->getSessionQuestionID(), $user->getId(), $mysqli);
            }

            // If MRQ load array of Response
            elseif(get_class($question) == "QuestionMrq") {
                $responses = DatabaseResponseMrq::loadUserResponses($question->getSessionQuestionID(), $user->getId(), $mysqli);

                // Ensures there is always **a** response in the $response variable. Would only be used if a bug occurs.
                // Probably not really needed TODO maybe?
                if(count($responses) >= 1) $response = $responses[0];
                else $response = null;
            }

            else {
                $response = DatabaseResponse::loadUserResponse($question->getSessionQuestionID(), $user->getId(), $mysqli);
            }
        }

        else {
            $response = null;
        }

        // Add to session history
        DatabaseSessionHistory::insert($user, $session, $mysqli);

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."session/");
        $breadcrumbs->addItem($session->getTitle() . " (#$sessionIdentifier)");

        $data["session"] = $session;
        $data["response"] = $response;
        $data["responses"] = $responses;
        $data["question"] = $question;
        $data["totalQuestions"] = $totalQuestions;
        $data["questionNumber"] = $questionNumber;
        $data["breadcrumbs"] = $breadcrumbs;
        $data["user"] = $user;

        echo $templates->render("session/view", $data);
    }

    public static function viewSubmit($sessionID) {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Load the session
        $session = DatabaseSession::loadSession($sessionID, $mysqli);

        // Load database session question
        $question = DatabaseSessionQuestion::loadQuestion($_POST["sessionQuestionID"], $mysqli);

        // Get the question number
        $questionNumber = intval($_POST["questionNumber"]);

        // If question is not active
        if(!$question->isActive()) {

            // Create a new alert to display next time the user views a page
            $alert = new Alert();
            $alert->setType("danger");
            $alert->setTitle("Error");
            $alert->setMessage("The answer you submitted was for a question which is no longer active");
            Alert::displayAlertSession($alert);

            // Forward the user back
            header("Location: ." . ($questionNumber>0 ? "?q=".($questionNumber+1) : ""));
            die();
        }

        if($question->getType() == "mrq") {

            // Loop through post data to get answers
            $choices = [];
            foreach($_POST as $key => $value) {
                if(substr($key, 0,7) == "answer-") {
                    $choice = intval($value) - 1;

                    // Ensure choice is valid
                    if($choice < 0 || $choice >= count($question->getChoices()))
                        die("Error"); // TODO

                    array_push($choices, $choice);
                }
            }

            // If no choice was made, display an error
            if(count($choices) <= 0)
                die(self::noChoiceMade($questionNumber));

            // Load existing response, if it exists
            $response = DatabaseResponseMcq::loadUserResponse($_POST["sessionQuestionID"], $user->getId(), $mysqli);

            // If a response already exists and this session does not allow answers to be modified
            if($response && !$session->getAllowModifyAnswer()) {

                // Display an error
                self::cannotChangeAnswer();
                die();
            }

            // If an existing response was found
            elseif($response) {
                if(!DatabaseResponseMrq::update($_POST["sessionQuestionID"], $user->getId(), $choices, $question, $mysqli)) {

                    // If error updating, display error
                    PageError::error500("DatabaseResponseMrq::update error on line " . __LINE__ . " of file " . __FILE__);
                    die();
                }
            }

            // Otherwise, insert the response
            else {
                if(!DatabaseResponseMrq::insert($_POST["sessionQuestionID"], $user->getId(), $choices, $question, $mysqli)) {

                    // If error inserting, display error
                    PageError::error500("DatabaseResponseMrq::insert error on line " . __LINE__ . " of file " . __FILE__);
                    die();
                }
            }
        }

        // If MCQ
        elseif($question->getType() == "mcq") {

            // Get the choice submitted
            $choice = intval($_POST["answer"]) - 1;

            // If no choice was made, display an error
            if(!isset($_POST["answer"]))
                die(self::noChoiceMade($questionNumber));

            // If choice is invalid, show an error
            if($choice < 0 || $choice >= count($question->getChoices())) {
                die("Error"); // TODO
            }

            // Get the choice chosen by the user
            $choice = $question->getChoices()[$choice];

            // Load existing response, if it exists
            $response = DatabaseResponseMcq::loadUserResponse($_POST["sessionQuestionID"], $user->getId(), $mysqli);

            // If a response already exists and this session does not allow answers to be modified
            if($response && !$session->getAllowModifyAnswer()) {

                // Display an error
                self::cannotChangeAnswer();
                die();
            }

            // If an existing response was found
            elseif($response) {

                if(!DatabaseResponseMcq::update($response->getResponseID(), $choice->getChoiceID(), $mysqli)) {

                    // If error updating, display error
                    PageError::error500("DatabaseResponseMcq::update error on line " . __LINE__ . " of file " . __FILE__);
                    die();
                }
            }

            // Otherwise, insert the response
            else {
                if(!DatabaseResponseMcq::insert($_POST["sessionQuestionID"], $user->getId(), $choice->getChoiceID(), $mysqli)) {

                    // If error inserting, display error
                    PageError::error500("DatabaseResponseMcq::insert error on line " . __LINE__ . " of file " . __FILE__);
                    die();
                }
            }
        }

        else {

            // Load existing response, if it exists
            $response = DatabaseResponse::loadUserResponse($_POST["sessionQuestionID"], $user->getId(), $mysqli);

            // If a response already exists and this session does not allow answers to be modified
            if($response && !$session->getAllowModifyAnswer()) {

                // Display an error
                self::cannotChangeAnswer();
                die();
            }

            // If an existing response was found
            elseif($response) {
                if(!DatabaseResponse::update($response->getResponseID(), $_POST["answer"], $mysqli)) {

                    // If error updating, display error
                    PageError::error500("DatabaseResponse::update error on line " . __LINE__ . " of file " . __FILE__);
                    die();
                }
            }

            // Otherwise, insert the response
            else {
                if(!DatabaseResponse::insert($_POST["sessionQuestionID"], $user->getId(), $_POST["answer"], $mysqli)) {

                    // If error inserting, display error
                    PageError::error500("DatabaseResponse::insert error on line " . __LINE__ . " of file " . __FILE__);
                    die();
                }
            }
        }

        header("Location: ." . ($questionNumber>0 ? "?q=".($questionNumber+1) : ""));
        die();
    }

    private static function noChoiceMade($questionNumber) {
        $alert = new Alert();
        $alert->setType("danger");
        $alert->setDismissable(true);
        $alert->setTitle("Error");
        $alert->setMessage("You did not select a choice");
        Alert::displayAlertSession($alert);

        header("Location: ." . ($questionNumber>0 ? "?q=".($questionNumber+1) : ""));
        die();
    }

    private static function cannotChangeAnswer() {
        $alert = new Alert();
        $alert->setType("danger");
        $alert->setDismissable(true);
        $alert->setTitle("Error");
        $alert->setMessage("This session does not allow you to update your answer");
        Alert::displayAlertSession($alert);

        header("Location: .");
        die();
    }
}