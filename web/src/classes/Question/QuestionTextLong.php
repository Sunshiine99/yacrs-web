<?php

class QuestionTextLong extends QuestionText
{
    /**
     * QuestionText constructor.
     */
    public function __construct($question) {
        parent::__construct($question);
        $this->type = "textlong";
    }
}