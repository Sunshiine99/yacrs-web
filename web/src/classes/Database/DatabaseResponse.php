<?php

class DatabaseResponse
{

    /**
     * @param int $sessionQuestionID
     * @param int $userID
     * @param string $response
     * @param mysqli $mysqli
     * @return int|null
     */
    public static function insert($sessionQuestionID, $userID, $response, $mysqli) {
        $sessionQuestionID  = Database::safe($sessionQuestionID, $mysqli);
        $userID             = Database::safe($userID, $mysqli);
        $response           = Database::safe($response, $mysqli);

        $sql = "INSERT INTO `yacrs_response` (`sessionQuestionID`, `userID`, `response`) 
                VALUES ('$sessionQuestionID', '$userID', '$response')";
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
    public static function loadUserResponse($sessionQuestionID, $userID, $mysqli) {
        $sessionQuestionID  = Database::safe($sessionQuestionID, $mysqli);
        $userID             = Database::safe($userID, $mysqli);

        // Run query to get the ID
        $sql = "SELECT r.`ID`, r.`response`
                FROM `yacrs_response` as r
                WHERE r.`sessionQuestionID` = $sessionQuestionID
                AND r.`userID` = $userID";
        $result = $mysqli->query($sql);

        // If the user hasn't submitted a response, return null
        if($result->num_rows <= 0) {
            return null;
        }

        $row = $result->fetch_assoc();

        $response = new Response();
        $response->setResponseID($row["ID"]);
        $response->setResponse($row["response"]);

        return $response;
    }

    public static function update($responseID, $response, $mysqli) {
        $responseID = Database::safe($responseID, $mysqli);
        $response   = Database::safe($response, $mysqli);

        $sql = "UPDATE `yacrs_response`
                SET `response` = '$response'
                WHERE `yacrs_response`.`ID` = $responseID";
        $result = $mysqli->query($sql);

        if($result) {
            return $responseID;
        }
        else {
            return null;
        }
    }
}