<?php

class PageSessions
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
        echo $templates->render("sessions/sessions", $data);
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

        if(!$session) {
            header("Location: " . $config["baseUrl"]);
            die();
        }

        $question = DatabaseSessionQuestion::loadActiveQuestion($sessionID, 0, $mysqli);

        // If a question is active
        if($question) {

            // If MCQ
            if($question->getType() == "mcq") {
                $response = DatabaseResponseMcq::load($question->getSessionQuestionID(), $user->getId(), $mysqli);
            }
            else {
                die("NOT IMPLEMENTED");
            }
        }

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."sessions/");
        $breadcrumbs->addItem($sessionID);

        $data["response"] = $response;
        $data["question"] = $question;
        $data["breadcrumbs"] = $breadcrumbs;
        $data["user"] = $user;
        echo $templates->render("sessions/view", $data);
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
            die("Error");
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
            $response = DatabaseResponseMcq::load($_POST["sessionQuestionID"], $user->getId(), $mysqli);

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
            die("NOT IMPLEMENTED");
        }

        header("Location: .");
        die();
    }
}