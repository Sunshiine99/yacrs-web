<?php

class PageSessionEditProperties
{
    public static function properties($sessionIdentifier) {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Load session details
        $session = DatabaseSessionIdentifier::loadSession($sessionIdentifier, $mysqli);

        // If the session is invalid or the user cannot edit this page, forward home
        if($session === null || !$session->checkIfUserCanEdit($user)) {
            header("Location: "  . $config["baseUrl"]);
            die();
        }

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."session/");
        $breadcrumbs->addItem(($session->getTitle() ? $session->getTitle() : "Session") . " (#$sessionIdentifier)", $config["baseUrl"]."session/$sessionID/");
        $breadcrumbs->addItem("Properties");

        //$data = array_merge($data, $session->toArray());

        $data["session"] = $session;
        $data["additionalUsersCsv"] = $session->getAdditionalUsersCsv();
        $data["user"] = $user;
        $data["breadcrumbs"] = $breadcrumbs;

        echo $templates->render("session/edit/properties", $data);
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

        DatabaseSession::update($session, $mysqli);

        header("Location: "  . $config["baseUrl"] . "session/" . $session->getSessionIdentifier() . "/edit/");
        die();
    }
}