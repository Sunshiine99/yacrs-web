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

        if(!$result) return null;

        return $mysqli->insert_id;
    }

    /**
     * @param int $sessionQuestionID
     * @param mysqli $mysqli
     * @return int
     */
    public static function delete($sessionQuestionID, $mysqli) {

        // Make items database safe
        $sessionQuestionID  = Database::safe($sessionQuestionID, $mysqli);

        $sql = "DELETE FROM `yacrs_sessionQuestions`
                WHERE `yacrs_sessionQuestions`.`ID` = $sessionQuestionID";
        $result = $mysqli->query($sql);

        $sql = "DELETE FROM `yacrs_questionsMcqChoices`
                WHERE `yacrs_questionsMcqChoices`.`questionID` = $sessionQuestionID";
        $result = $mysqli->query($sql);

        return $result ? true : false;
    }

    /**
     * Update a question
     * @param Question $question
     * @param Session $session
     * @param mysqli $mysqli
     * @return bool success?
     */
    public static function update($question, $session, $mysqli) {
        $active = Database::safe(bool2dbString($question->isActive()), $mysqli);
        $sessionID = Database::safe($session->getSessionID(), $mysqli);
        $sessionQuestionID = Database::safe($question->getSessionQuestionID(), $mysqli);

        // If activating question and this is a teacher led question
        if($question->isActive() && $session->getQuestionControlMode() === 0) {

            // Disable all questions
            $sql = "UPDATE `yacrs_sessionQuestions`
                    SET `active` = '0'
                    WHERE `yacrs_sessionQuestions`.`sessionID` = $sessionID";
            $result = $mysqli->query($sql);

            if(!$result)
                return false;
        }

        // Activate question
        $sql = "UPDATE `yacrs_sessionQuestions`
                SET `active` = '$active'
                WHERE `yacrs_sessionQuestions`.`ID` = $sessionQuestionID";
        $result = $mysqli->query($sql);

        if(!$result)
            return false;

        return DatabaseQuestion::update($question, $mysqli);
    }

    /**
     * @param int $sessionID
     * @param mysqli $mysqli
     * @return array|null
     */
    public static function loadSessionQuestions($sessionID, $mysqli) {
        $sessionID  = Database::safe($sessionID, $mysqli);

        $sql = "SELECT sq.`ID` as `sessionQuestionID`, q.`questionID`, sq.`active`
                FROM
                    `yacrs_sessionQuestions` as sq,
                    `yacrs_questions` as q
                WHERE sq.`questionID` = q.`questionID`
                  AND sq.`sessionID` = '$sessionID'
                ORDER BY sq.`ID` DESC";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        $output["questions"] = [];
        $output["active"] = false;

        // Loop for each row in result
        while($row = $result->fetch_assoc()) {

            $question = DatabaseQuestion::load($row["questionID"], $mysqli);
            $question->setSessionQuestionID($row["sessionQuestionID"]);
            $question->setSessionID($sessionID);

            if($row["active"]) {
                $output["active"] = true;
                $output["activeSessionQuestionID"] = $row["sessionQuestionID"];
                $question->setActive(true);
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
        $sql = "SELECT `ID`, `sessionID`, `questionID`, `active`
                FROM `yacrs_sessionQuestions` as sq
                WHERE sq.`ID` = $sessionQuestionID
                LIMIT 1";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        // Fetch the row returned from the table
        $row = $result->fetch_assoc();

        $question = DatabaseQuestion::load($row["questionID"], $mysqli);

        if(!$question) return $question;

        $question->setSessionID($row["sessionID"]);
        $question->setSessionQuestionID($row["ID"]);
        $question->setActive($row["active"]);

        return $question;
    }

    /**
     * Loads a single active question
     * @param int $sessionID
     * @param int $questionNumber The question number (starting at 0)
     * @param mysqli $mysqli
     * @return Question|null
     */
    public static function loadActiveQuestion($sessionID, $questionNumber = 0, $mysqli) {
        $sessionID      = Database::safe($sessionID, $mysqli);
        $questionNumber = Database::safe($questionNumber, $mysqli);

        // Run SQL query to get active question
        $sql = "SELECT `ID`, `questionID`
                FROM `yacrs_sessionQuestions` as sq
                WHERE sq.`sessionID` = $sessionID
                AND sq.`active` = 1
                LIMIT $questionNumber,1";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        // Fetch the row returned from the table
        $row = $result->fetch_assoc();
        if(!$row) return null;
        $question = DatabaseQuestion::load($row["questionID"], $mysqli);
        $question->setSessionQuestionID($row["ID"]);

        return $question;
    }

    /**
     * Loads all active questions for a session
     * @param int $sessionID
     * @param mysqli $mysqli
     * @return Question[]|null
     */
    public static function loadAllActiveQuestions($sessionID, $mysqli) {
        $sessionID      = Database::safe($sessionID, $mysqli);

        // Run SQL query to get all active questions
        $sql = "SELECT `ID`, `questionID`
                FROM `yacrs_sessionQuestions` as sq
                WHERE sq.`sessionID` = $sessionID
                  AND sq.`active` = 1";
        $result = $mysqli->query($sql);

        // If error, return NULL
        if(!$result) return null;

        $output = [];

        // Loop for every active question
        while($row = $result->fetch_assoc()) {

            // Load the question, if successful add to the output
            if($question = DatabaseSessionQuestion::loadQuestion($row["ID"], $mysqli)) {
                $output[] = $question;
            }
        }

        return $output;
    }

    public static function countActiveQuestions($sessionID, $mysqli) {
        $sessionID = Database::safe($sessionID, $mysqli);

        // Run SQL query to get number of questions
        $sql = "SELECT count(`ID`) as count
                FROM `yacrs_sessionQuestions` as sq
                WHERE sq.`sessionID` = $sessionID
                  AND sq.`active` = 1";
        $result = $mysqli->query($sql);

        if($result->num_rows <= 0) {
            return null;
        }

        // Fetch the row returned from the table
        $row = $result->fetch_assoc();

        return $row["count"];
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

    /**
     * Get the total number of users in a session and the number of users who have answered this question
     * @param int $sessionID
     * @param int $sessionQuestionID
     * @param mysqli $mysqli
     * @return int[]
     */
    public static function users($sessionID, $sessionQuestionID, $mysqli) {
        $sessionID = Database::safe($sessionID, $mysqli);
        $sessionQuestionID = Database::safe($sessionQuestionID, $mysqli);

        $sql = "SELECT answered.answered, total.total
                FROM
                (
                    SELECT count(time) as answered
                    FROM
                    (
                        (
                            SELECT r.time, r.sessionQuestionID, r.userID
                            FROM `yacrs_response` as r
                            WHERE r.sessionQuestionID = $sessionQuestionID
                        )
                        UNION
                        (
                            SELECT r.time, r.sessionQuestionID, r.userID
                            FROM `yacrs_responseMcq` as r
                            WHERE r.sessionQuestionID = $sessionQuestionID
                        )
                    ) as answeredCount
                ) AS answered,
                (
                    SELECT count(totalCount.userID) as total
                    FROM
                    (
                        SELECT userID
                        FROM `yacrs_sessionHistory` as sh
                        WHERE sh.`sessionID` = $sessionID
                        GROUP BY userID
                    ) as totalCount
                ) as total";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        // Fetch the row returned from the table
        $row = $result->fetch_assoc();

        $output = [];
        $output["answered"] = intval($row["answered"]);
        $output["total"] = intval($row["total"]) - 1; // Remove 1 as this includes owner

        // If owner has answered the question, increase total
        if($output["answered"] > $output["total"]) {
            $output["total"] = $output["answered"];
        }

        try {
            $rand = rand(100, 999);
        }
        catch(Exception $e) {
            $rand = 99;
        }

        $output["answered"] = (time() % 100) * 5;
        $output["total"] = 500;

        return $output;
    }
}