<?php

class ApiSessionQuestion
{

    public static function listSessionQuestion($sessionIdentifier) {

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // If invalid session identifier, display error
        if(!$sessionID) {
            ApiError::notFoundCustom("Session Not Found");
            die();
        }

        // Get user from API
        $user = Api::checkApiKey($_REQUEST["key"], $mysqli);

        // Check the API Key and get the username of the user
        if(!$user) {
            ApiError::invalidApiKey();
            die();
        }

        $questions = DatabaseSessionQuestion::loadSessionQuestions($sessionID, $mysqli);
        $output = [];

        foreach ($questions["questions"] as $question) {
            $output[] = $question->toArray();
        }

        Api::output($output);
    }

    /**
     * Load session question IDs for all active questions in a session. Any logged in user can access this API as this
     * is used in the website javascript frontend.
     * @param int $sessionIdentifier
     */
    public static function activeSessionQuestion($sessionIdentifier) {

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // If invalid session identifier, display error
        if(!$sessionID) {
            ApiError::notFoundCustom("Session Not Found");
            die();
        }

        // Get user from API
        $user = Api::checkApiKey($_REQUEST["key"], $mysqli);

        // Check the API Key and get the username of the user
        if(!$user) {
            ApiError::invalidApiKey();
            die();
        }

        $questions = DatabaseSessionQuestion::loadAllActiveQuestions($sessionID, $mysqli);

        // Add all of the session question IDs to the output
        $output = [];
        foreach($questions as $question) {
            $output[] = $question->getSessionQuestionID();
        }

        Api::output($output);
    }

    public static function viewSessionQuestion($sessionIdentifier, $sessionQuestionID) {
        /**
         * Setup basic session question variables (Type hinting below to avoid IDE error messages)
         * @var $mysqli mysqli
         * @var $user User
         * @var $session Session
         * @var $question Question
         */
        extract(self::setup($sessionIdentifier, $sessionQuestionID));

        Api::output($question->toArray());
    }

    /**
     * Edit a session question
     * @param int $sessionIdentifier
     * @param int $sessionQuestionID
     */
    public static function edit($sessionIdentifier, $sessionQuestionID) {
        /**
         * Setup basic session question variables (Type hinting below to avoid IDE error messages)
         * @var $mysqli mysqli
         * @var $user User
         * @var $session Session
         * @var $question Question
         */
        extract(self::setup($sessionIdentifier, $sessionQuestionID));

        $question->fromArray($_REQUEST);

        $result = DatabaseSessionQuestion::update($question, $session, $mysqli);

        // Update question in database, if error updating display error
        if(!$result) {

            ApiError::unknown();
            die();
        }

        Api::output($question->toArray());
    }

    /**
     * Delete question from session
     * @param int $sessionIdentifier
     * @param int $sessionQuestionID
     */
    public static function deleteSessionQuestion($sessionIdentifier, $sessionQuestionID) {
        /**
         * Setup basic session question variables (Type hinting below to avoid IDE error messages)
         * @var $mysqli mysqli
         * @var $user User
         * @var $session Session
         * @var $question Question
         */
        extract(self::setup($sessionIdentifier, $sessionQuestionID));

        // Delete session question
        $result = DatabaseSessionQuestion::delete($sessionQuestionID, $mysqli);

        // If error deleting session question
        if(!$result) {
            ApiError::unknown();
            die();
        }

        Api::output(["success" => true]);
    }

    /**
     * Setup session question whilst ensuring permissions are kept
     * @param $sessionIdentifier
     * @param $sessionQuestionID
     * @return array
     */
    private static function setup($sessionIdentifier, $sessionQuestionID) {

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Get user from API key
        $user = Api::checkApiKey($_REQUEST["key"], $mysqli);

        // If user was not loaded, output error
        if(!$user) {
            ApiError::invalidApiKey();
            die();
        }

        // Get the session ID for this
        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // If invalid session identifier, display error
        if(!$sessionID) {
            ApiError::notFoundCustom("Session Not Found");
            die();
        }

        // Load the session
        $session = DatabaseSession::loadSession($sessionID, $mysqli);

        // If user cannot edit this session, output permission denied
        if(!$session->checkIfUserCanEdit($user)) {
            ApiError::permissionDenied();
        }

        // Load the question
        $question = DatabaseSessionQuestion::loadQuestion($sessionQuestionID, $mysqli);

        // If this question does not belong to this session
        if(!$question || $question->getSessionID() != $sessionID) {

            // TODO: Implement nicer error
            ApiError::unknown();
            die();
        }

        return [
            "mysqli" => $mysqli,
            "user" => $user,
            "session" => $session,
            "question" => $question
        ];
    }
}