<?php

class DatabaseQuestion
{

    /**
     * @param int $questionID
     * @param mysqli $mysqli
     * @return Question|null
     */
    public static function load($questionID, $mysqli) {
        $questionID = Database::safe____new($questionID, $mysqli, 11, 1);

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
        $questionID = Database::safe____new($questionID, $mysqli, 11, 1);

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
        $text       = Database::safe____new($question->getQuestion(), $mysqli, 80);
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
        $questionID = Database::safe____new($mysqli->insert_id, $mysqli, 11, 1);

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

        $questionID = Database::safe____new($questionID, $mysqli, 11, 1);

        // Foreach choice
        foreach($question->getChoices() as $choice) {

            // Make text safe for database
            $text = Database::safe____new($choice->getChoice(), $mysqli, 80);

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
        $questionText = Database::safe____new($question->getQuestion(), $mysqli, 80);
        $questionID = Database::safe____new($question->getQuestionID(), $mysqli, 11, 1);

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

    /**
     * @param QuestionMcq $question
     * @param mysqli $mysqli
     * @return bool
     */
    private static function updateMcq($question, $mysqli) {
        $questionID = Database::safe____new($question->getQuestionID(), $mysqli, 11, 1);

        // Get the new choices
        $choices = $question->getChoices();

        // Run query to get the old choices
        $sql = "SELECT *
                FROM `yacrs_questionsMcqChoices`
                WHERE `yacrs_questionsMcqChoices`.questionID = $questionID";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        // Index used for looping through new choices
        $i = 0;

        // Loop for every old choice
        while($row = $result->fetch_assoc()) {

            // If choice does not exist, or it is equal to null return error
            if(!isset($choices[$i]) || !$choices[$i]) return null;

            // If this old choice is not the same as the new choice in this position
            if($row["ID"] != $choices[$i]->getChoiceID()) {

                // Make the old choice ID database safe
                $choiceID = Database::safe____new($row["ID"], $mysqli, 11, 1);

                // Delete this old choice
                $sql = "DELETE FROM `yacrs_questionsMcqChoices`
                        WHERE `yacrs_questionsMcqChoices`.`questionID` = $questionID
                          AND `yacrs_questionsMcqChoices`.`ID` = $choiceID";
                $result2 = $mysqli->query($sql);

                if(!$result2) return null;
            }

            else {


                // Make the choice text and choice ID database safe
                $choice = Database::safe____new($choices[$i]->getChoice(), $mysqli, 80);
                $correct = Database::safe(bool2dbString($choices[$i]->isCorrect()), $mysqli);
                $choiceID = Database::safe____new($choices[$i]->getChoiceID(), $mysqli, 11, 1);

                // Update the old choice
                $sql = "UPDATE `yacrs_questionsMcqChoices`
                        SET `choice` = '$choice', `correct` = '$correct'
                        WHERE `yacrs_questionsMcqChoices`.`questionID` = $questionID
                          AND `yacrs_questionsMcqChoices`.`ID` = $choiceID";
                $result2 = $mysqli->query($sql);

                if(!$result2) return null;

                $i++;
            }
        }

        // Loop through the remaining new choices
        while($i < count($choices)) {

            // Make this choice text database safe
            $choice = Database::safe____new($choices[$i]->getChoice(), $mysqli, 80);

            // Add this new choice
            $sql = "INSERT INTO `yacrs_questionsMcqChoices` (`questionID`, `choice`)
                    VALUES ($questionID, '$choice')";
            $result = $mysqli->query($sql);

            if(!$result) return null;

            $i++;
        }

        return true;
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