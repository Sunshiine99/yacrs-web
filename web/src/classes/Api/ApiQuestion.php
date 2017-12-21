<?php

class ApiQuestion
{

    public static function listSessionQuestion($sessionID) {

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

        $questions = DatabaseSessionQuestion::loadSessionQuestions(2, $mysqli);
        $output = [];

        foreach ($questions["questions"] as $question) {
            $output[] = $question->toArray();
        }

        Api::output($output);
    }

    public static function viewSessionQuestion($sessionID, $sessionQuestionID) {

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

        // Load the question
        $question = DatabaseSessionQuestion::loadQuestion($sessionQuestionID, $mysqli);

        // If this question does not belong to this session
        if($question->getSessionID() != $sessionID) {

            // TODO: Implement nicer error
            ApiError::unknown();
            die();
        }

        Api::output($question->toArray());
    }

    public static function deleteSessionQuestion($sessionID, $sessionQuestionID) {

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

        // Delete session question
        $result = DatabaseSessionQuestion::delete($sessionQuestionID, $mysqli);

        // If error deleting session question
        if(!$result) {
            ApiError::unknown();
            die();
        }

        Api::output(["success" => true]);
    }
}