<?php

class DatabaseResponseMcq
{

    /**
     * @param int $sessionQuestionID
     * @param int $userID
     * @param int $choiceID
     * @param mysqli $mysqli
     * @return int|null
     */
    public static function insert($sessionQuestionID, $userID, $choiceID, $mysqli) {
        $sessionQuestionID  = Database::safe($sessionQuestionID, $mysqli);
        $userID             = Database::safe($userID, $mysqli);
        $choiceID           = Database::safe($choiceID, $mysqli);

        $sql = "INSERT INTO `yacrs_responseMcq` (`sessionQuestionID`, `userID`, `choiceID`) 
                VALUES ('$sessionQuestionID', '$userID', '$choiceID')";
        $result = $mysqli->query($sql);

        if(!$result) {
            return null;
        }

        return $mysqli->insert_id;
    }

    /**
     * @param int $sessionQuestionID
     * @param int $userID
     * @param mysqli $mysqli
     * @return Response|null ID of existing response
     */
    public static function load($sessionQuestionID, $userID, $mysqli) {
        $sessionQuestionID  = Database::safe($sessionQuestionID, $mysqli);
        $userID             = Database::safe($userID, $mysqli);

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

        $row = $result->fetch_assoc();

        $response = new Response();
        $response->setResponseID($row["ID"]);
        $response->setResponse($row["choiceID"]);

        return $response;
    }

    public static function update($responseID, $choiceID, $mysqli) {
        $responseID = Database::safe($responseID, $mysqli);
        $choiceID   = Database::safe($choiceID, $mysqli);

        $sql = "UPDATE `yacrs_responseMcq`
                SET `choiceID` = '$choiceID'
                WHERE `yacrs_responseMcq`.`ID` = $responseID";
        $result = $mysqli->query($sql);

        if($result) {
            return $responseID;
        }
        else {
            return null;
        }
    }
}