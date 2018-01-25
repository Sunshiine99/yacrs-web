<?php

class DatabaseQuestion
{

    /**
     * @param int $questionID
     * @param mysqli $mysqli
     * @return Question|null
     */
    public static function load($questionID, $mysqli) {
        $questionID = Database::safe($questionID, $mysqli);

        // Run SQL query to get question
        $sql = "SELECT
                    q.`questionID` as questionID,
                    q.`question` as question,
                    q.`created` as created,
                    q.`lastUpdate` as lastUpdate,
                    qt.`name` as type
                FROM
                    `yacrs_questions` as q,
                    `yacrs_questionTypes` as qt
                WHERE q.`questionID` = '$questionID'
                  AND q.`type` = qt.`ID`";
        $result = $mysqli->query($sql);

        // Check if successful, display and log error if not
        Database::checkError($mysqli, __LINE__, __FILE__);

        if($result->num_rows==0) {
            return null;
        }

        // Get the row from the database
        $row = $result->fetch_assoc();

        // Setup new question
        try {
            $question = QuestionFactory::create($row["type"], $row);
        }
        catch(Exception $e) { return null; }

        // Load question type specific details
        switch(get_class($question)) {
            case "QuestionMcq":
            case "QuestionMrq":
                $question = self::loadMcq($question, $questionID, $mysqli);
        }

        return $question;
    }

    /**
     * @param QuestionMcq $question
     * @param int $questionID
     * @param mysqli $mysqli
     * @return QuestionMcq|null
     */
    public static function loadMcq($question, $questionID, $mysqli) {
        $questionID = Database::safe($questionID, $mysqli);

        // Run SQL query to get MCQ choices
        $sql = "SELECT `ID`, `choice`, `correct`
                FROM `yacrs_questionsMcqChoices` as qmcqc
                WHERE qmcqc.`questionID` = $questionID";
        $result = $mysqli->query($sql);

        // Check if successful, display and log error if not
        Database::checkError($mysqli, __LINE__, __FILE__);

        // Loop for every MCQ choice
        while($row = $result->fetch_assoc()) {
            $question->addChoice($row["choice"], $row["correct"], $row["ID"]);
        }

        return $question;
    }


    /**
     * Add new question to database
     * @param Question $question Question as Question object
     * @param mysqli $mysqli Database connection
     * @return int Session ID
     */
    public static function insert($question, $mysqli) {

        // Make variables safe for database use
        $text       = Database::safe($question->getQuestion(), $mysqli);
        $type       = self::questionTypeToId($question->getType());

        // Run query to insert into yacrs_questions table
        $sql = "INSERT INTO `yacrs_questions` (
                    `question`,
                    `type`,
                    `created`,
                    `lastUpdate`
                )
                VALUES (
                    '$text',
                    '$type',
                    ".time().",
                    ".time().")";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        // Get the question ID
        $questionID = Database::safe($mysqli->insert_id, $mysqli);

        switch(get_class($question)) {
            case "QuestionMcq":
            case "QuestionMrq":
                self::insertMcq($question, $questionID, $mysqli);
        }

        return $questionID;
    }

    /**
     * @param QuestionMcq $question
     * @param $questionID
     * @param mysqli $mysqli
     * @return bool
     */
    private static function insertMcq($question, $questionID, $mysqli) {

        $questionID = Database::safe($questionID, $mysqli);

        // Foreach choice
        foreach($question->getChoices() as $choice) {

            // Make text safe for database
            $text = Database::safe($choice->getChoice(), $mysqli);

            // Get database representation of correct boolean
            $correct = $choice->isCorrect() ? "1" : "0";

            // Run query to insert into yacrs_questions table
            $sql = "INSERT INTO `yacrs_questionsMcqChoices` (
                    `questionID`,
                    `choice`,
                    `correct`
                )
                VALUES (
                    '$questionID',
                    '$text',
                    '$correct')";
            $result = $mysqli->query($sql);
        }

        return true;
    }

    /**
     * @param Question $question
     * @param mysqli $mysqli
     * @return bool
     */
    public static function update($question, $mysqli) {
        $questionText = Database::safe($question->getQuestion(), $mysqli);
        $questionID = Database::safe($question->getQuestionID(), $mysqli);

        $sql = "UPDATE `yacrs_questions`
                SET
                  `question` = '$questionText',
                  `lastUpdate` = '".time()."'
                WHERE `yacrs_questions`.`questionID` = $questionID";
        $result = $mysqli->query($sql);

        if(!$result) {
            die("Error " . $mysqli->error);
        }

        // Check if successful, display and log error if not
        Database::checkError($mysqli, __LINE__, __FILE__);

        switch(get_class($question)) {
            case "QuestionMcq":
            case "QuestionMrq":
                self::updateMcq($question, $mysqli);
        }

        return $result?true:false;
    }

    private static function updateMcq($question, $mysqli) {
        $questionID = Database::safe($question->getQuestionID(), $mysqli);

        // Delete All MCQ Choices for question
        // TODO: Actually update rather than delete
        $sql = "DELETE FROM `yacrs_questionsMcqChoices`
                WHERE `yacrs_questionsMcqChoices`.`questionID` = $questionID";
        $result = $mysqli->query($sql);

        // Check if successful, display and log error if not
        Database::checkError($mysqli, __LINE__, __FILE__);

        return self::insertMcq($question, $question->getQuestionID(), $mysqli);
    }

    private static function questionTypeToId($type) {
        switch($type) {
            case "mcq":
                return 1;
                break;
            case "text":
                return 2;
                break;
            case "textlong":
                return 3;
                break;
            case "mrq":
                return 4;
                break;
        }
    }
}