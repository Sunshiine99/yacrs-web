<?php

class QuestionMcqChoice
{
    private $choice = null;
    private $correct = false;

    /**
     * QuestionMcqChoice constructor.
     * @param null $choice
     * @param bool $correct
     */
    public function __construct($choice, $correct = false) {
        $this->choice = $choice;
        $this->correct = $correct;
    }

    public function toArray() {
        $output["choice"] = $this->choice;
        $output["correct"] = $this->correct;
        return $output;
    }

    /**
     * @return null
     */
    public function getChoice() {
        return $this->choice;
    }

    /**
     * @param null $choice
     */
    public function setChoice($choice) {
        $this->choice = $choice;
    }

    /**
     * @return bool
     */
    public function isCorrect() {
        return $this->correct;
    }

    /**
     * @param bool $correct
     */
    public function setCorrect($correct) {
        $this->correct = $correct;
    }
}