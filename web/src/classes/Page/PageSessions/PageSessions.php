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

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."sessions/");
        $breadcrumbs->addItem($sessionID);

        $data["question"] = $question;
        $data["breadcrumbs"] = $breadcrumbs;
        $data["user"] = $user;
        echo $templates->render("sessions/view", $data);
    }
}