<?php

class Question
{
    /** @var int */
    private $questionID;

    /** @var int */
    private $sessionQuestionID;

    /** @var string */
    protected $type;

    /** @var string */
    protected $question;

    /** @var int */
    private $created = null;

    /** @var int */
    private $lastUpdate = null;

    /** @var bool */
    private $active = false;

    /**
     * Question constructor.
     * @param $question
     */
    public function __construct($question) {
        $this->question = $question;
    }

    public function toArray() {
        $output["type"] = $this->type;
        $output["question"] = $this->question;
        $output["created"] = $this->created;
        $output["lastUpdate"] = $this->lastUpdate;
        $output["active"] = $this->active;
        return $output;
    }

    /**
     * @return int
     */
    public function getQuestionID() {
        return $this->questionID;
    }

    /**
     * @param int $questionID
     */
    public function setQuestionID($questionID) {
        $this->questionID = intval($questionID);
    }

    /**
     * @return int
     */
    public function getSessionQuestionID() {
        return $this->sessionQuestionID;
    }

    /**
     * @param int $sessionQuestionID
     */
    public function setSessionQuestionID($sessionQuestionID) {
        $this->sessionQuestionID = intval($sessionQuestionID);
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getQuestion() {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question) {
        $this->question = $question;
    }

    /**
     * @return int
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @param int $created
     */
    public function setCreated($created) {
        $this->created = intval($created);
    }

    /**
     * @return int
     */
    public function getLastUpdate() {
        return $this->lastUpdate;
    }

    /**
     * @param int $lastUpdate
     */
    public function setLastUpdate($lastUpdate) {
        $this->lastUpdate = intval($lastUpdate);
    }

    /**
     * @return bool
     */
    public function isActive() {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive($active) {
        $this->active = boolval($active);
    }
}