<?php

class PageSessionRun
{
    public static function run($sessionID) {
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
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."session/");
        $breadcrumbs->addItem($sessionID, $config["baseUrl"]."session/$sessionID/");
        $breadcrumbs->addItem("Run");

        // Load questions from the database
        $questions = DatabaseSessionQuestion::loadSessionQuestions($sessionID, $mysqli);

        $data["session"] = $session;
        $data["questions"] = $questions;
        $data["breadcrumbs"] = $breadcrumbs;
        $data["user"] = $user;
        echo $templates->render("session/run/run", $data);
    }

    public static function runSubmit($sessionID) {
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

        // Control column of questions table
        if($_POST["field"] == "control") {
            switch($_POST["value"]) {
                case "activate":
                    DatabaseSessionQuestion::questionActivate($_POST["sqid"], true, $mysqli);
                    break;
                case "deactivate":
                    DatabaseSessionQuestion::questionActivate($_POST["sqid"], false, $mysqli);
                    break;
            }
        }

        // Forward here
        header("Location: .");
        die();
    }
}