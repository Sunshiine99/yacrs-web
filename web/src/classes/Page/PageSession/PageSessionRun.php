<?php

class PageSessionRun extends PageSession
{

    public static function run($sessionID) {
        /**
         * Setup basic session variables (Type hinting below to avoid IDE error messages)
         * @var $templates League\Plates\Engine
         * @var $data array
         * @var $config array
         * @var $user User
         * @var $mysqli mysqli
         * @var $session Session
         */
        extract(self::setup($sessionID));

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
        /**
         * Setup basic session variables (Type hinting below to avoid IDE error messages)
         * @var $templates League\Plates\Engine
         * @var $data array
         * @var $config array
         * @var $user User
         * @var $mysqli mysqli
         * @var $session Session
         */
        extract(self::setup($sessionID));

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

    /**
     * Loads basic variables ensuring correct permissions. (I.e. User is logged in and that they can edit this session)
     * @param $sessionID
     * @return array
     */
    protected static function setup($sessionID) {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Loads the session
        $session = DatabaseSession::loadSession($sessionID, $mysqli);

        // If this session does not exist or the user cannot edit this session, go home
        if($session==null || !$session->checkIfUserCanEdit($user)) {
            header("Location: " . $config["baseUrl"]);
            die();
        }

        return [
            "templates" => $templates,
            "data" => $data,
            "config" => $config,
            "user" => $user,
            "mysqli" => $mysqli,
            "session" => $session,
        ];
    }
}