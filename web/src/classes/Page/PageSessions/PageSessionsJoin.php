<?php

class PageSessionsJoin
{

    /**
     * Users cannot join a session here. Forward home
     */
    public static function join() {
        $config = Flight::get("config");
        header("Location: " . $config["baseUrl"]);
        die();
    }

    public static function submit() {
        $config = Flight::get("config");

        // Get the session ID
        $sessionID = $_POST["sessionID"];

        // If invalid session ID, forward home
        if(!preg_match("/^[0-9]*$/", $sessionID)) {
            header("Location: " . $config["baseUrl"]);
            die();
        }

        // Forward the user to the session page
        header("Location: " . $config["baseUrl"] . "sessions/$sessionID/");
        die();
    }
}