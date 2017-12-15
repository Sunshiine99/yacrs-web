<?php

class PageSession
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

    public static function view($sessionID) {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        $session = DatabaseSession::loadSession($sessionID, $mysqli);

        // If invalid session, forward home with error
        if(!$session) {

            $alert = new Alert();
            $alert->setType("danger");
            $alert->setDismissable(true);
            $alert->setTitle("Error");
            $alert->setMessage("Invalid Session ID");
            Alert::displayAlertSession($alert);

            header("Location: " . $config["baseUrl"]);
            die();
        }

        $question = DatabaseSessionQuestion::loadActiveQuestion($sessionID, 0, $mysqli);

        // If a question is active
        if($question) {

            // If MCQ
            if($question->getType() == "mcq") {
                $response = DatabaseResponseMcq::loadUserResponse($question->getSessionQuestionID(), $user->getId(), $mysqli);
            }

            else {
                $response = DatabaseResponse::loadUserResponse($question->getSessionQuestionID(), $user->getId(), $mysqli);
            }
        }

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."session/");
        $breadcrumbs->addItem($session->getTitle() . " (#$sessionID)");

        $data["session"] = $session;
        $data["response"] = $response;
        $data["question"] = $question;
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

        // Load database session question
        $question = DatabaseSessionQuestion::loadQuestion($_POST["sessionQuestionID"], $mysqli);

        // If question is not active
        if(!$question->isActive()) {

            // Create a new alert to display next time the user views a page
            $alert = new Alert();
            $alert->setType("danger");
            $alert->setTitle("Error Updating Answer");
            $alert->setMessage("The answer you submitted was for a question which is no longer active");
            Alert::displayAlertSession($alert);

            // Forward the user back
            header("Location: .");
            die();
        }

        // If MCQ
        if($question->getType() == "mcq") {

            // Get the choice submitted
            $choice = intval($_POST["answer"]) - 1;

            // If choice is invalid, show an error
            if($choice < 0 || $choice >= count($question->getChoices())) {
                die("Error"); // TODO
            }

            // Get the choice chosen by the user
            $choice = $question->getChoices()[$choice];

            // Load existing response, if it exists
            $response = DatabaseResponseMcq::loadUserResponse($_POST["sessionQuestionID"], $user->getId(), $mysqli);

            // If an existing response was found
            if($response) {
                DatabaseResponseMcq::update($response->getResponseID(), $choice->getChoiceID(), $mysqli);
            }

            // Otherwise, insert the response
            else {
                DatabaseResponseMcq::insert($_POST["sessionQuestionID"], $user->getId(), $choice->getChoiceID(), $mysqli);
            }
        }

        else {

            // Load existing response, if it exists
            $response = DatabaseResponse::loadUserResponse($_POST["sessionQuestionID"], $user->getId(), $mysqli);

            // If an existing response was found
            if($response) {
                DatabaseResponse::update($response->getResponseID(), $_POST["answer"], $mysqli);
            }

            // Otherwise, insert the response
            else {
                DatabaseResponse::insert($_POST["sessionQuestionID"], $user->getId(), $_POST["answer"], $mysqli);
            }
        }

        header("Location: .");
        die();
    }
}