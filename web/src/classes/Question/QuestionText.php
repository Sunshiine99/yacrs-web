<?php

class QuestionText extends Question
{

    /**
     * QuestionText constructor.
     */
    public function __construct($question) {
        parent::__construct($question);
        $this->type = "text";
    }
}