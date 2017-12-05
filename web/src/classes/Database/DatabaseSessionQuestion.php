<?php

class DatabaseSessionQuestion
{

    /**
     * @param int $sessionID
     * @param int $questionID
     * @param mysqli $mysqli
     * @return int
     */
    public static function insert($sessionID, $questionID, $mysqli) {

        // Make items database safe
        $sessionID  = Database::safe($sessionID, $mysqli);
        $questionID = Database::safe($questionID, $mysqli);

        $sql = "INSERT INTO `yacrs_sessionQuestions` (
                    `sessionID`,
                    `questionID`
                )
                VALUES (
                    '$sessionID',
                    '$questionID'
                )";
        $result = $mysqli->query($sql);

        return $mysqli->insert_id;
    }

    /**
     * @param int $sessionID
     * @param mysqli $mysqli
     * @return Question[]
     */
    public static function loadSessionQuestions($sessionID, $mysqli) {
        $sessionID  = Database::safe($sessionID, $mysqli);

        $sql = "SELECT sq.`ID` as sessionQuestionID, sq.`questionID`, q.`question`, qt.`name` as type, sq.`active`, q.`created`, q.`lastUpdate`
                FROM
                    `yacrs_sessionQuestions` as sq,
                    `yacrs_questions` as q,
                    `yacrs_questionTypes` as qt
                WHERE sq.`questionID` = q.`questionID`
                  AND sq.`sessionID` = $sessionID
                  AND qt.`ID` = q.`type`
                ORDER BY sq.`ID` DESC";
        $result = $mysqli->query($sql);

        $output["questions"] = [];
        $output["active"] = false;

        // Loop for each row in result
        while($row = $result->fetch_assoc()) {

            $question = new Question($row);

            if($row["active"]) {
                $output["active"] = true;
            }

            array_push($output["questions"], $question);
        }

        return $output;
    }

    /**
     * @param int $sessionQuestionID
     * @param mysqli $mysqli
     * @return Question|null
     */
    public static function loadQuestion($sessionQuestionID, $mysqli) {
        $sessionQuestionID = Database::safe($sessionQuestionID, $mysqli);

        // Run SQL query to get question ID
        $sql = "SELECT `ID`, `questionID`, `active`
                FROM `yacrs_sessionQuestions` as sq
                WHERE sq.`ID` = $sessionQuestionID
                LIMIT 1";
        $result = $mysqli->query($sql);

        // Fetch the row returned from the table
        $row = $result->fetch_assoc();

        $question = DatabaseQuestion::load($row["questionID"], $mysqli);
        $question->setSessionQuestionID($row["ID"]);
        $question->setActive($row["active"]);

        return $question;
    }

    /**
     * @param int $sessionID
     * @param int $startingAtID The SessionQuestion ID to look
     * @param mysqli $mysqli
     * @return Question|null
     */
    public static function loadActiveQuestion($sessionID, $startingAtID = 0, $mysqli) {
        $sessionID      = Database::safe($sessionID, $mysqli);
        $startingAtID   = Database::safe($startingAtID, $mysqli);

        // Run SQL query to get active question
        $sql = "SELECT `ID`, `questionID`
                FROM `yacrs_sessionQuestions` as sq
                WHERE sq.`sessionID` = $sessionID
                AND sq.`active` = 1
                AND sq.`ID` >= $startingAtID
                LIMIT 1";
        $result = $mysqli->query($sql);

        // Fetch the row returned from the table
        $row = $result->fetch_assoc();

        if($result->num_rows <= 0) {
            return null;
        }

        $question = DatabaseQuestion::load($row["questionID"], $mysqli);
        $question->setSessionQuestionID($row["ID"]);

        return $question;
    }

    /**
     * @param int $sessionQuestionID
     * @param bool $active
     * @param mysqli $mysqli
     * @return bool
     */
    public static function questionActivate($sessionQuestionID, $active = true, $mysqli) {
        $sessionQuestionID = Database::safe($sessionQuestionID, $mysqli);
        $active = $active ? "1" : "0";

        $sql = "UPDATE `yacrs_sessionQuestions`
                SET `active` = '$active'
                WHERE `yacrs_sessionQuestions`.`ID` = $sessionQuestionID";
        $result = $mysqli->query($sql);

        return isset($result);
    }
}