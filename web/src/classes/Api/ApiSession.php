<?php

class ApiSession
{
    /**
     * Sessions API page
     */
    public static function listSessions() {

        // Required parameters
        $key = Api::checkParameter("key");

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Get user from API
        $user = Api::checkApiKey($key, $mysqli);

        // Check the API Key and get the username of the user
        if(!$user) {
            ApiError::invalidApiKey();
            die();
        }

        $output = [];
        $i = 0;
        foreach(DatabaseSession::loadUserSessions($user->getId(), $mysqli) as $session) {
            $output[$i] = $session->toArray();
            $i++;
        }

        Api::output($output);
    }

    public static function details($sessionID) {

        // Required parameters
        $key = Api::checkParameter("key");

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        $user = Api::checkApiKey($key, $mysqli);

        // Check the API Key and get the username of the user
        if(!$user) {
            ApiError::invalidApiKey();
        }

        // Load session
        $session = DatabaseSession::loadSession($sessionID, $mysqli);

        // If a session was not loaded, output error
        if(!$session) {
            $output["error"]["code"]    = "invalidSessionId";
            $output["error"]["message"] = "Invalid Session ID";
            Api::output($output);
            die();
        }

        if(!$session->checkIfUserCanEdit($user)) {
            ApiError::permissionDenied();
        }



        $output = $session->toArray();

        Api::output($output);
    }
}