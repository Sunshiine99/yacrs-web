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
     * @param $sessionIdentifier
     */
    public static function details($sessionIdentifier) {

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
        $session = DatabaseSessionIdentifier::loadSession($sessionIdentifier, $mysqli);

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

    public static function edit($sessionIdentifier = null) {

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
        $data["sessionID"] = $sessionIdentifier;

        $output = [];

        // If this is an existing session
        if($sessionIdentifier) {
            $session = DatabaseSession::loadSession($sessionIdentifier, $mysqli);
            $session->fromArray($data);
            DatabaseSession::update($session, $mysqli);
        }

        // Otherwise this is a new session
        else {
            $session = new Session($data);
            $session->setOwner($user->getId());
            $sessionIdentifier = DatabaseSession::insert($session, $mysqli);
        }
        $session = DatabaseSession::loadSession($sessionIdentifier, $mysqli);
        $output = $session->toArray();
        Api::output($output);
    }

    /**
     * Delete a session
     * @param $sessionIdentifier
     */
    public static function delete($sessionIdentifier) {

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Get user from API
        $user = Api::checkApiKey($_REQUEST["key"], $mysqli);

        // Check the API Key and get the username of the user
        if(!$user) {
            ApiError::invalidApiKey();
        }

        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // If invalid session identifier, display 404
        if(!$sessionID) {
            PageError::error404();
            die();
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

        // If user cannot delete this session, display correct error
        if(!$session->checkIfUserCanDelete($user)) {
            ApiError::permissionDenied();
        }
        
        // Delete session, if error
        if(!DatabaseSessionIdentifier::delete($sessionIdentifier, $mysqli)) {
            ApiError::unknown();
        }

        $output["success"] = true;
        Api::output($output);
    }

    public static function startSession($sessionIdentifier){

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Get user from API
        $user = Api::checkApiKey($_REQUEST["key"], $mysqli);

        // Check the API Key and get the username of the user
        if(!$user) {
            ApiError::invalidApiKey();
        }

        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // If invalid session identifier, display 404
        if(!$sessionID) {
            PageError::error404();
            die();
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

        // If user cannot start this session
        if($session->getOwner() !== $user->getUsername()) {
            ApiError::permissionDenied();
        }

        $questions = DatabaseSessionQuestion::loadSessionQuestions($sessionID, $mysqli)["questions"];

        //Activate first question
        $sessionQuestionID = $questions[count($questions)-1]->toArray()["sessionQuestionID"];
        $result = DatabaseSessionQuestion::questionActivate($sessionQuestionID, true, $mysqli);

        if($result == true){
            Api::output(true);
        }
        else{
            ApiError::unknown();
        }
    }

    public static function stopSession($sessionIdentifier){

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Get user from API
        $user = Api::checkApiKey($_REQUEST["key"], $mysqli);

        // Check the API Key and get the username of the user
        if(!$user) {
            ApiError::invalidApiKey();
        }

        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // If invalid session identifier, display 404
        if(!$sessionID) {
            PageError::error404();
            die();
        }

        // Load session
        $session = DatabaseSessionIdentifier::loadSession($sessionIdentifier, $mysqli);

        // If a session was not loaded, output error
        if(!$session) {
            $output["error"]["code"]    = "invalidSessionId";
            $output["error"]["message"] = "Invalid Session ID";
            Api::output($output);
            die();
        }

        // If user cannot stop this session
        if($session->getOwner() !== $user->getUsername()) {
            ApiError::permissionDenied();
        }

        $activeQuestion = DatabaseSessionQuestion::loadAllActiveQuestions($sessionID, $mysqli);
        //If there is no active question return false
        if(count($activeQuestion) == 0){
            Api::output(false);
        }
        else{
            //Get the active question's id and stop it
            $activeID = $activeQuestion[0]->toArray()["sessionQuestionID"];
            $result = DatabaseSessionQuestion::questionActivate($activeID, 0, $mysqli);
            Api::output(true);
        }
    }

    public static function getActiveSessions(){
        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Get user from API
        $user = Api::checkApiKey($_REQUEST["key"], $mysqli);

        // Check the API Key and get the username of the user
        if(!$user) {
            ApiError::invalidApiKey();
        }

        $userID = $user->getId();

        $sessions = DatabaseSession::loadUserActiveSessions($userID, $mysqli);
        Api::output($sessions);
    }
}