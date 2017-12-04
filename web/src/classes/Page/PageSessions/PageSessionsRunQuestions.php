<?php

class PageSessionsRunQuestions
{

    public static function add($sessionID) {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        $session = DatabaseSession::loadSession($sessionID, $mysqli);

        // If user cannot edit this session, go gin
        if($session==null || !$session->checkIfUserCanEdit($user)) {
            header("Location: " . $config["baseUrl"]);
            die();
        }

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."sessions/");
        $breadcrumbs->addItem($sessionID, $config["baseUrl"]."sessions/$sessionID/");
        $breadcrumbs->addItem("Run", $config["baseUrl"]."sessions/$sessionID/run/");
        $breadcrumbs->addItem("Questions", $config["baseUrl"]."sessions/$sessionID/run/questions/");
        $breadcrumbs->addItem("New");

        $data["session"] = $session;
        $data["breadcrumbs"] = $breadcrumbs;
        $data["user"] = $user;
        echo $templates->render("sessions/run/questions/add", $data);
    }

    public static function addSubmit($sessionID) {
        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        $session = DatabaseSession::loadSession($sessionID, $mysqli);

        // If user cannot edit this session, go gin
        if($session==null || !$session->checkIfUserCanEdit($user)) {
            header("Location: " . $config["baseUrl"]);
            die();
        }

        $question = new QuestionMcq($_POST["question"]);

        if($_POST["questionType"] == "mcq") {

            foreach($_POST as $key => $value) {

                // If this is one of the MCQ choices
                if(substr($key, 0, 11) == "mcq-choice-") {
                    $question->addChoice($value);
                }
            }
        }

        // Insert question into the database
        $questionID = DatabaseQuestion::insert($question, $mysqli);

        // Insert question session combo into DatabaseSession
        DatabaseSessionQuestion::insert($sessionID, $questionID, $mysqli);

        header("Location: " . $config["baseUrl"] . "sessions/$sessionID/run/");
        die();
    }

    public static function edit($sessionID, $questionID) {

        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        $session = DatabaseSession::loadSession($sessionID, $mysqli);

        // If user cannot edit this session, go gin
        if($session==null || !$session->checkIfUserCanEdit($user)) {
            header("Location: " . $config["baseUrl"]);
            die();
        }
        // Get the question
        $question = DatabaseQuestion::load($questionID, $mysqli);
        // If it is null go to home
        if($question == null){
            header("Location: " . $config["baseUrl"]);
            die();
        }
        //Get the choices and the question text
        $sql = "SELECT
                    q.`question` as question,
                    qc.`choice` as choice
                FROM
                    `yacrs_questions` as q,
                    `yacrs_questionsMcqChoices` as qc
                WHERE q.`questionID` = '$questionID'
                  AND q.`questionID` = qc.`questionID`";
        $result = $mysqli->query($sql);

        //create an array of choices
        $data['choices'] = array();
        // for every row from the database get the choice
        while($row = $result->fetch_assoc()) {
            array_push($data['choices'], $row["choice"]);
            $data['question'] = $row["question"];
        }
        //TODO need to display the question text and options in the edit question page

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."sessions/");
        $breadcrumbs->addItem($sessionID, $config["baseUrl"]."sessions/$sessionID/");
        $breadcrumbs->addItem("Run", $config["baseUrl"]."sessions/$sessionID/run/");
        $breadcrumbs->addItem("Questions", $config["baseUrl"]."sessions/$sessionID/run/questions/");
        $breadcrumbs->addItem("Edit");

        $data["session"] = $session;
        $data["breadcrumbs"] = $breadcrumbs;
        $data["user"] = $user;
        echo $templates->render("sessions/run/questions/edit", $data);
    }

    public static function editSubmit($sessionID, $questionID) {
        echo $questionID;
    }
}