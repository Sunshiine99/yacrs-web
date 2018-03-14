<?php

class ApiSessionLive
{

    /**
     * API to get live view details
     * @param int $sessionIdentifier
     * @param int|null $sessionQuestionID
     */
    public static function live($sessionIdentifier, $sessionQuestionID = null) {

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Load the session
        $session = DatabaseSessionIdentifier::loadSession($sessionIdentifier, $mysqli);

        // If this is not a teacher led session, display error
        if($session->getQuestionControlMode() !== 0) {
            $output = [];
            $output["error"]["code"] = "notTeacherLed";
            $output["error"]["message"] = "Live view is only supported for teacher led sessions";
            Api::output($output);
            die();
        }


        // If a session question ID is given, load that question
        if($sessionQuestionID) {

            $question = DatabaseSessionQuestion::loadQuestion($sessionQuestionID, $mysqli);

            if(!$question || $question->getSessionID() != $session->getSessionID()) {
                ApiError::unknown();
            }
        }

        // Otherwise, load the active question
        else {

            // Load the active question
            $question = DatabaseSessionQuestion::loadActiveQuestion($session->getSessionID(), 0, $mysqli);
        }

        // Load the active users
        $users = $question ? DatabaseSessionQuestion::users($session->getSessionID(), $question->getSessionQuestionID(), $mysqli) : null;

        $output = [];

        $output["active"] = $question ? $question->isActive() : false;
        $output["sessionQuestionID"] = $question ? $question->getSessionQuestionID() : null;
        $output["question"] = $question ? $question->getQuestion() : null;
        $output["users"] = $users;

        Api::output($output);
    }
}