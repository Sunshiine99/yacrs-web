<?php

class ApiSessionLive
{

    /**
     * API to get live view details
     * @param int $sessionIdentifier
     */
    public static function live($sessionIdentifier) {

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

        // Load the active question
        $questionCurrent = DatabaseSessionQuestion::loadActiveQuestion($session->getSessionID(), 0, $mysqli);

        // Load all session questions
        $questions = DatabaseSessionQuestion::loadSessionQuestions($session->getSessionID(), $mysqli);

        // Reverse questions
        $questions = array_reverse($questions["questions"]);

        $questionNext = null;

        // If no current question, use first question as next question
        if(!$questionCurrent) {
            if(count($questions) >= 1) {
                $questionNext = $questions[0];
            }
        }

        // Otherwise, find the next question
        else {

            $prevFound = false;

            // Loop foreach question
            foreach($questions as $question) {
                /** @var $question Question */

                // If the previous question was active
                if($prevFound) {
                    $questionNext = $question;
                    break;
                }

                // Otherwise,
                else {

                    // If this is the current question
                    if($question->getSessionQuestionID() == $questionCurrent->getSessionQuestionID()) {
                        $prevFound = true;
                    }
                }
            }
        }

        // Load the active users
        $users = $questionCurrent ? DatabaseSessionQuestion::users($session->getSessionID(), $questionCurrent->getSessionQuestionID(), $mysqli) : null;

        $output = [];

        $output["active"] = $questionCurrent ? true : false;
        $output["sessionQuestionID"] = $questionCurrent ? $questionCurrent->getSessionQuestionID() : null;
        $output["nextSessionQuestionID"] = $questionNext ? $questionNext->getSessionQuestionID() : null;
        $output["question"] = $questionCurrent ? $questionCurrent->getQuestion() : null;
        $output["users"] = $users;

        Api::output($output);
    }

    /**
     * @param Session $session
     * @param mysqli $mysqli
     */
    private static function next($session, $mysqli) {

        // Load all session questions
        $questions = DatabaseSessionQuestion::loadSessionQuestions($session->getSessionID(), $mysqli);

        if(!$questions) ApiError::unknown();

        // Reverse questions
        $questions = array_reverse($questions["questions"]);

        $prevActive = false;
        $questionPrev = null;
        $questionNext = null;

        // Loop foreach question
        foreach($questions as $question) {
            /** @var $question Question */

            // If the previous question was active
            if($prevActive) {
                $questionNext = $question;
                break;
            }

            // Otherwise,
            else {

                // If this question is active
                if($question->isActive()) {
                    $prevActive = true;
                    $questionPrev = $question;
                }
            }
        }

        // If there is no next question, use the first question as the next question
        if(!$questionPrev) {

            // If there is a first question
            if(count($questions) >= 1) {
                $questionNext = $questions[0];
            }
        }

        // If there is no next question, output error
        if(!$questionNext) {
            $output = [];
            $output["error"]["code"] = "noNextQuestion";
            $output["error"]["message"] = "No next question";
            Api::output($output);
            die();
        }

        // Deactivate previous question
        if($questionPrev) {
            $questionPrev->setActive(false);
            $result = DatabaseSessionQuestion::update($questionPrev, $session, $mysqli);

            if(!$result) ApiError::unknown();
        }

        // Make the next question active
        $questionNext->setActive(true);
        $result = DatabaseSessionQuestion::update($questionNext, $session, $mysqli);

        if(!$result) ApiError::unknown();
    }
}