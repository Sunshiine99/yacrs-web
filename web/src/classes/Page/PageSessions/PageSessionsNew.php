<?php

class PageSessionsNew
{

    // aka new session
    public static function add() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Ensure user is allowed to create sessions
        Page::ensureUserIsSessionCreator($user, $config);

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."session/");
        $breadcrumbs->addItem("New");

        $data["session"] = new Session();
        $data["user"] = $user;
        $data["breadcrumbs"] = $breadcrumbs;
        echo $templates->render("session/edit", $data);
    }

    public static function submit() {
        $config = Flight::get("config");

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Ensure user is allowed to create sessions
        Page::ensureUserIsSessionCreator($user, $config);

        // Setup session from submitted data
        $session = new Session($_POST);

        $session->setOwner($user->getId());

        $sessionID = DatabaseSession::insert($session, $mysqli);

        header("Location: "  .$config["baseUrl"] . "session/$sessionID/run/");
        die();
    }
}