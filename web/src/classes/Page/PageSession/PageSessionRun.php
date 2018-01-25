<?php

class PageSessionRun extends PageSession
{

    public static function run($sessionIdentifier) {
        /**
         * Setup basic session variables (Type hinting below to avoid IDE error messages)
         * @var $templates League\Plates\Engine
         * @var $data array
         * @var $config array
         * @var $user User
         * @var $mysqli mysqli
         * @var $session Session
         */
        extract(self::setup($sessionIdentifier));

        // Add to session history
        DatabaseSessionHistory::insert($user, $session, $mysqli);

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."session/");
        $breadcrumbs->addItem(($session->getTitle() ? $session->getTitle() : "Session") . " (#$sessionIdentifier)", $config["baseUrl"]."session/$sessionIdentifier/");
        $breadcrumbs->addItem("Run");

        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // Load questions from the database
        $questions = DatabaseSessionQuestion::loadSessionQuestions($sessionID, $mysqli);

        $data["session"] = $session;
        $data["questions"] = $questions;
        $data["breadcrumbs"] = $breadcrumbs;
        $data["user"] = $user;
        echo $templates->render("session/run/run", $data);
    }

    public static function classMode($sessionIdentifier) {
        /**
         * Setup basic session variables (Type hinting below to avoid IDE error messages)
         * @var $templates League\Plates\Engine
         * @var $data array
         * @var $config array
         * @var $user User
         * @var $mysqli mysqli
         * @var $session Session
         */
        extract(self::setup($sessionIdentifier));

        $data["session"] = $session;
        echo $templates->render("session/run/class", $data);
    }

    /**
     * Loads basic variables ensuring correct permissions. (I.e. User is logged in and that they can edit this session)
     * @param $sessionIdentifier
     * @return array
     */
    protected static function setup($sessionIdentifier) {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // If invalid session identifier, display 404
        if(!$sessionID) {
            PageError::error404();
            die();
        }

        // Loads the session
        $session = DatabaseSession::loadSession($sessionID, $mysqli);
        $session->setSessionIdentifier($sessionIdentifier);

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