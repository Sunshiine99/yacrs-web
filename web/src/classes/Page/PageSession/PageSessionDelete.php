<?php

class PageSessionDelete
{
    public static function delete($sessionID){
        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Load session details
        $session = DatabaseSession::loadSession($sessionID, $mysqli);

        // If the session is invalid or the user cannot edit this page, forward home
        if($session === null || !$session->checkIfUserCanEdit($user)) {
            header("Location: "  . $config["baseUrl"]);
            die();
        }

        DatabaseSession::delete($sessionID, $mysqli);

        header("Location: "  . $config["baseUrl"]);
        die();
    }
}