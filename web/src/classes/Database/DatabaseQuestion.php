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
                    q.`question` as question,
                    qt.`name` as type
                FROM
                    `yacrs_questions` as q,
                    `yacrs_questionTypes` as qt
                WHERE q.`questionID` = '$questionID'
                  AND q.`type` = qt.`ID`";
        $result = $mysqli->query($sql);

        // Get the row from the database
        $row = $result->fetch_assoc();

        // Setup new question
        try {
            $question = QuestionFactory::create($row["type"], $row["question"]);
        }
        catch(Exception $e) { return null; }

        // Load question type specific details
        switch($row["type"]) {
            case "mcq":
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

        // Get the question ID
        $questionID = Database::safe($mysqli->insert_id, $mysqli);

        switch($question->getType()) {
            case "mcq":
                self::insertMcq($question, $questionID, $mysqli);
        }

        return $questionID;
    }

    /**
     * @param QuestionMcq $question
     * @param $questionID
     * @param mysqli $mysqli
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
            echo $sql . "</br></br></br>";
        }
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
        }
    }
}