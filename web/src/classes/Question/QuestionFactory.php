<?php

class QuestionFactory
{
    /**
     * Returns a new instance of a question type object from a given type.
     * @param $type string Type of question
     * @return Question
     * @throws Exception 'QuestionFactory_ClassNotFoundException': Given type does not translate to login object
     */
    public static function create($type, $question)
    {
        switch ($type) {
            case "mcq":
                return new QuestionMcq($question);
                break;
            case "text":
                return new QuestionText($question);
                break;
            case "textlong":
                return new QuestionTextLong($question);
                break;
            default:
                throw new Exception("QuestionFactory_ClassNotFoundException");
        }
    }
}