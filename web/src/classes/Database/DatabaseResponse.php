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

        $sql = "INSERT INTO `yacrs_response` (`time`, `sessionQuestionID`, `userID`, `response`) 
                VALUES ('".time()."', '$sessionQuestionID', '$userID', '$response')";
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
                SET
                    `response` = '$response',
                    `time` = '".time()."'
                WHERE `yacrs_response`.`ID` = $responseID";
        $result = $mysqli->query($sql);

        if($result) {
            return $responseID;
        }
        else {
            return null;
        }
    }

    /**
     * Load an array of responses for a question
     * @param $sessionQuestionID
     * @param $mysqli
     * @return Response[]|null
     */
    public static function loadResponses($sessionQuestionID, $mysqli) {
        $sessionQuestionID = Database::safe($sessionQuestionID, $mysqli);

        $sql = "SELECT username, time, response
                FROM
                    `yacrs_response` as r,
                    `yacrs_user` as u
                WHERE r.`sessionQuestionID` = $sessionQuestionID
                  AND r.`userID` = u.`userID`";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        $responses = [];

        // Foreach row returned
        while($row = $result->fetch_assoc()) {

            $response = new Response();
            $response->setResponseID($row["ID"]);
            $response->setResponse($row["response"]);
            $response->setTime($row["time"]);
            $response->setUsername($row["username"]);
            $responses[] = $response;
        }

        return $responses;
    }

    /**
     * Load an array of responses for a question
     * @param $sessionQuestionID
     * @param $mysqli
     * @return array|null
     */
    public static function loadMrqResponses($sessionQuestionID, $mysqli) {
        $sessionQuestionID = Database::safe($sessionQuestionID, $mysqli);

        $sql = "SELECT r.userID, username, time, choice
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
                    $flag = 1;
                    break;
                }
            }
            if($flag == 0) {
                $response = new Response();
                $response->setResponse($row["choice"]);
                $response->setTime($row["time"]);
                $response->setUsername($row["username"]);
                $response->setResponseID($row["userID"]);
                $responses[] = $response;
            }
        }

        return $responses;
    }

    public static function loadWordCloud($sessionQuestionID, $mysqli) {
        $sessionQuestionID = Database::safe($sessionQuestionID, $mysqli);

        $sql = "SELECT response
                FROM `yacrs_response` as r
                WHERE r.`sessionQuestionID` = $sessionQuestionID";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        $dict = [];

        // Foreach row returned
        while($row = $result->fetch_assoc()) {

            // Get the response
            $response = $row["response"];

            $responseExplode = explode(" ", $response);

            foreach($responseExplode as $r) {

                // Remove everything except letters
                $r = preg_replace("/[^a-z]+/i", "", $r);

                // Make only the first letter uppercase
                $r = strtolower($r);

                $dict[$r] = isset($dict[$r]) ? $dict[$r] + 1 : 1;
            }
        }

        $output = [];

        foreach($dict as $key => $value) {
            $word = [];
            $word["text"] = $key;
            $word["size"] = $value;
            $word["alert"] = "The word is '$key'";
            $output[] = $word;
        }

        // Sort the words by size descending
        $output = self::arraySort($output, "size", SORT_DESC);

        return $output;
    }

    private static function arraySort($array, $on, $order=SORT_ASC) {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[] = $array[$k];
            }
        }

        return $new_array;
    }
}
