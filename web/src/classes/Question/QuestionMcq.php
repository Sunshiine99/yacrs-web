<?php

class QuestionMcq extends Question
{
    /** @var array */
    private $choices = [];

    /**
     * QuestionMcq constructor.
     * @param $question
     * @param array $choices
     */
    public function __construct($question = null, $choices = null) {
        parent::__construct($question);
        $this->type = "mcq";
        $this->choices = $choices!=null ? $choices : [];
    }

    /**
     * @return array
     */
    public function toArray() {
        $output = parent::toArray();

        $output["choices"] = [];
        foreach($this->choices as $choice) {
            array_push($output["choices"], $choice->toArray());
        }

        return $output;
    }

    /**
     * @param $choice
     * @param bool $correct
     */
    public function addChoice($choice, $correct = false) {
        array_push($this->choices, new QuestionMcqChoice($choice, $correct));
    }

    /**
     * @return QuestionMcqChoice[]
     */
    public function getChoices() {
        return $this->choices;
    }

    /**
     * @param QuestionMcqChoice[] $choices
     */
    public function setChoices($choices) {
        $this->choices = $choices;
    }
}