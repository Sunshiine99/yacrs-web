<?php

class ApiSession
{
    /**
     * List sessions
     */
    public static function listSessions() {

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Get user from API
        $user = Api::checkApiKey($_REQUEST["key"], $mysqli);

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

    /**
     * View Session Details
     * @param $sessionID
     */
    public static function details($sessionID) {

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Get user from API
        $user = Api::checkApiKey($_REQUEST["key"], $mysqli);

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

    public static function edit($sessionID = null) {

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Get user from API
        $user = Api::checkApiKey($_REQUEST["key"], $mysqli);

        // Check the API Key and get the username of the user
        if(!$user) {
            ApiError::invalidApiKey();
        }

        $data = $_REQUEST;
        $data["sessionID"] = $sessionID;

        $output = [];

        // If this is an existing session
        if($sessionID) {
            $session = DatabaseSession::loadSession($sessionID, $mysqli);
            $session->fromArray($data);
            DatabaseSession::update($session, $mysqli);
        }

        // Otherwise this is a new session
        else {
            $session = new Session($data);
            $session->setOwner($user->getId());
            $sessionID = DatabaseSession::insert($session, $mysqli);
        }

        $session = DatabaseSession::loadSession($sessionID, $mysqli);
        $output = $session->toArray();
        Api::output($output);
    }

    /**
     * Delete a session
     * @param $sessionID
     */
    public static function delete($sessionID) {

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Get user from API
        $user = Api::checkApiKey($_REQUEST["key"], $mysqli);

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

        // If user cannot edit this session, display correct error
        if(!$session->checkIfUserCanEdit($user)) {
            ApiError::permissionDenied();
        }

        // Delete session, if error
        if(!DatabaseSession::delete($sessionID, $mysqli)) {
            ApiError::unknown();
        }

        $output["success"] = "true";
        Api::output($output);
    }
}