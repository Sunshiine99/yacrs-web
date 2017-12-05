<?php

class QuestionGeneric
{

    public static function create($id) {
        switch($id) {
            case "mcq_d":
                return self::mcq(4);
                break;
            case "mcq_e":
                return self::mcq(4);
                break;
            case "mcq_f":
                return self::mcq(4);
                break;
            case "mcq_g":
                return self::mcq(4);
                break;
            case "mcq_h":
                return self::mcq(4);
                break;
            case "trueFalse":
                return self::trueFalse();
                break;
            case "trueFalseDontKnow":
                return self::trueFalseDontKnow();
                break;
            case "text":
                return self::text();
                break;
            case "textLong":
                return self::textLong();
                break;
            default:
                throw new Exception("QuestionGeneric_QuestionNotFoundException");
        }
    }

    public static function getQuestions() {
        return [
            [
                "name" => "Multiple Choice Question A-D",
                "nameShort" => "MCQ A-D",
                "id" => "mcq_d",
            ],
            [
                "name" => "Multiple Choice Question A-E",
                "nameShort" => "MCQ A-E",
                "id" => "mcq_e",
            ],
            [
                "name" => "Multiple Choice Question A-F",
                "nameShort" => "MCQ A-F",
                "id" => "mcq_f",
            ],
            [
                "name" => "Multiple Choice Question A-G",
                "nameShort" => "MCQ A-G",
                "id" => "mcq_g",
            ],
            [
                "name" => "Multiple Choice Question A-H",
                "nameShort" => "MCQ A-H",
                "id" => "mcq_h",
            ],
            [
                "name" => "True/False",
                "nameShort" => "True/False",
                "id" => "trueFalse",
            ],
            [
                "name" => "True/False/Don't Know",
                "nameShort" => "True/False/Don't Know",
                "id" => "trueFalseDontKnow",
            ],
            [
                "name" => "Text",
                "nameShort" => "Text",
                "id" => "text",
            ],
            [
                "name" => "Long Text",
                "nameShort" => "Long Text",
                "id" => "textLong",
            ],
        ];
    }

    /*
     * Creates a generic multiple choice question
     */
    private static function mcq($n = 4) {

        // If invalid $n, use default
        if($n <= 0 || $n > 26)
            $n = 4;

        // Create a new question
        $question = new QuestionMcq();

        // Loop for each option
        for($i = 0; $i <= $n; $i++) {

            // Add choice
            $choice = chr(65 + $i);
            $question->addChoice($choice);
        }

        return $question;
    }

    /**
     * True False Question
     * @return QuestionMcq
     */
    public static function trueFalse() {
        $question = new QuestionMcq();
        $question->addChoice("True");
        $question->addChoice("False");
        return $question;
    }

    /**
     * True False Don't Know Question
     * @return QuestionMcq
     */
    public static function trueFalseDontKnow() {
        $question = new QuestionMcq();
        $question->addChoice("True");
        $question->addChoice("False");
        return $question;
    }

    /**
     * Text Question
     * @return QuestionText
     */
    public static function text() {
        $question = new QuestionText();
        return $question;
    }

    /**
     * Long Text Question
     * @return QuestionTextLong
     */
    public static function textLong() {
        $question = new QuestionTextLong();
        return $question;
    }
}