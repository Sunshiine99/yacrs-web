<?php

class DatabaseResponseMrq
{

    /**
     * @param int $sessionQuestionID
     * @param int $userID
     * @param int[] $choices
     * @param Question|QuestionMrq $question
     * @param mysqli $mysqli
     * @return bool
     */
    public static function insert($sessionQuestionID, $userID, $choices, $question, $mysqli) {
        foreach($choices as $c) {
            $choice = $question->getChoices()[$c];
            DatabaseResponseMcq::insert($sessionQuestionID, $userID, $choice->getChoiceID(), $mysqli);
        }
        return true;
    }

    /**
     * @param int $sessionQuestionID
     * @param int $userID
     * @param array $choices
     * @param mysqli $mysqli
     * @return bool
     */
    public static function update($sessionQuestionID, $userID, $choices, $question, $mysqli) {
        $sessionQuestionID  = Database::safe____new($sessionQuestionID, $mysqli, 11, 1);
        $userID             = Database::safe____new($userID, $mysqli, 11, 1);

        // SQL query to delete existing choices
        // TODO: Actually update
        $sql = "DELETE FROM `yacrs_responseMcq`
                WHERE `yacrs_responseMcq`.`sessionQuestionID` = $sessionQuestionID
                  AND `yacrs_responseMcq`.`userID` = $userID;";
        $result = $mysqli->query($sql);

        if(!$result)
            return false;

        self::insert($sessionQuestionID, $userID, $choices, $question, $mysqli);

        return true;
    }

    /**
     * Loads the user response to this question
     * @param int $sessionQuestionID
     * @param int $userID
     * @param mysqli $mysqli
     * @return Response[]
     */
    public static function loadUserResponses($sessionQuestionID, $userID, $mysqli) {
        $sessionQuestionID  = Database::safe____new($sessionQuestionID, $mysqli, 11, 1);
        $userID             = Database::safe____new($userID, $mysqli, 11, 1);

        // Run query to get the ID
        $sql = "SELECT rmcq.`ID`, rmcq.`choiceID`
                FROM `yacrs_responseMcq` as rmcq
                WHERE rmcq.`sessionQuestionID` = $sessionQuestionID
                AND rmcq.`userID` = $userID";
        $result = $mysqli->query($sql);

        // If the user hasn't submitted a response, return null
        if($result->num_rows <= 0) {
            return null;
        }

        $responses = [];

        while($row = $result->fetch_assoc()) {
            $response = new Response();
            $response->setResponseID($row["ID"]);
            $response->setResponse($row["choiceID"]);
            array_push($responses, $response);
        }

        return $responses;
    }

    /**
     * Load an array of responses for a question
     * @param $sessionQuestionID
     * @param $mysqli
     * @return array|null
     */
    public static function loadResponses($sessionQuestionID, $mysqli) {
        $sessionQuestionID = Database::safe____new($sessionQuestionID, $mysqli, 11, 1);

        $sql = "SELECT r.userID, username, time, choice, r.choiceID
                FROM
                    `yacrs_responseMcq` as r,
                    `yacrs_user` as u,
                    `yacrs_questionsMcqChoices` as m
                WHERE r.`sessionQuestionID` = $sessionQuestionID
                  AND r.`userID` = u.`userID`
                  AND m.`ID` = r.`choiceID`";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        $responses = [];

        // Foreach row returned
        while($row = $result->fetch_assoc()){
            //if flag == 0 the response has not been found
            $flag = 0;
            foreach($responses as $response){
                //if the user has more than one response add the choice to the responses
                if($response->getResponseID() == $row["userID"]){
                    $response->setResponse($response->getResponse() . ", " . $row["choice"]);
                    $response->setChoiceID($response->getChoiceID() . ", " . $row["choiceID"]);
                    $flag = 1;
                    break;
                }
            }
            if($flag == 0) {
                $response = new Response();
                $response->setResponse($row["choice"]);
                $response->setTime($row["time"]);
                $response->setChoiceID($row["choiceID"]);
                $response->setUsername($row["username"]);
                $response->setUser(DatabaseUser::loadDetailsFromUserID($row["userID"], $mysqli));
                $response->setResponseID($row["userID"]);
                $responses[] = $response;
            }
        }

        return $responses;
    }

    public static function getCorrectChoices($questionID, $mysqli){
        $questionID  = Database::safe____new($questionID, $mysqli, 11, 1);

        $sql = "SELECT choice
                FROM
                    `yacrs_questionsMcqChoices`
                WHERE
                    `correct` = 1
                    AND `questionID` = $questionID";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        $choices = "";

        while($row = $result->fetch_assoc()){
            $choices = $choices . $row["choice"] . " ";
        }

        return $choices;
    }
}