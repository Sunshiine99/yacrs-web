<?php

class Session
{
    /** @var int */
    private $sessionID = null;

    /** @var string */
    private $owner = null;

    /** @var string */
    private $title = "";

    /** @var string */
    private $courseID = "";

    /** @var bool */
    private $allowGuests = false;

    /** @var bool */
    private $onSessionList = true;

    /** @var int */
    private $questionControlMode = 0; // 0 = Teacher Led, 1 = Student Paced

    /** @var int */
    private $defaultTimeLimit = 0;

    /** @var bool */
    private $allowModifyAnswer = false;

    /** @var bool */
    private $allowQuestionReview = false;

    /** @var bool */
    private $classDiscussionEnabled = false;

    /** @var array */
    private $additionalUsers = [];

    /** @var int */
    private $created = null;

    /** @var int */
    private $lastUpdate = null;

    /**
     * Session constructor.
     * @param array|null $array
     */
    public function __construct($array = null) {
        if($array!==null) {
            $this->fromArray($array);
        }

        $this->created      = $this->created === null       ? time()    : $this->created;
        $this->lastUpdate   = $this->lastUpdate === null    ? time()    : $this->lastUpdate;
    }

    /**
     * @param array $array
     */
    private function fromArray($array = []) {
        $this->sessionID                = isset($array["sessionID"])                ? $array["sessionID"]               : $this->sessionID;
        $this->owner                    = isset($array["owner"])                    ? $array["owner"]                   : $this->owner;
        $this->title                    = isset($array["title"])                    ? $array["title"]                   : $this->title;
        $this->courseID                 = isset($array["courseID"])                 ? $array["courseID"]                : $this->courseID;
        $this->allowGuests              = isset($array["allowGuests"])              ? $array["allowGuests"]             : $this->allowGuests;
        $this->onSessionList            = isset($array["onSessionList"])            ? $array["onSessionList"]           : $this->onSessionList;
        $this->questionControlMode      = isset($array["questionControlMode"])      ? $array["questionControlMode"]     : $this->questionControlMode;
        $this->defaultTimeLimit         = isset($array["defaultTimeLimit"])         ? $array["defaultTimeLimit"]        : $this->defaultTimeLimit;
        $this->allowModifyAnswer        = isset($array["allowModifyAnswer"])        ? $array["allowModifyAnswer"]       : $this->allowModifyAnswer;
        $this->allowQuestionReview      = isset($array["allowQuestionReview"])      ? $array["allowQuestionReview"]     : $this->allowQuestionReview;
        $this->classDiscussionEnabled   = isset($array["classDiscussionEnabled"])   ? $array["classDiscussionEnabled"]  : $this->classDiscussionEnabled;
        $this->additionalUsers          = isset($array["additionalUsers"])          ? $array["additionalUsers"]         : $this->additionalUsers;
        $this->created                  = isset($array["created"])                  ? $array["created"]                 : $this->created;
        $this->lastUpdate               = isset($array["lastUpdate"])               ? $array["lastUpdate"]              : $this->lastUpdate;

        if(isset($array["additionalUsersCsv"])) {
            $this->setAdditionalUsersCsv($array["additionalUsersCsv"]);
        }
    }

    public function toArray() {
        $array["sessionID"]                 = $this->sessionID;
        $array["owner"]                     = $this->owner;
        $array["title"]                     = $this->title;
        $array["courseID"]                  = $this->courseID;
        $array["allowGuests"]               = $this->allowGuests;
        $array["onSessionList"]             = $this->onSessionList;
        $array["questionControlMode"]       = $this->questionControlMode;
        $array["defaultTimeLimit"]          = $this->defaultTimeLimit;
        $array["allowModifyAnswer"]         = $this->allowModifyAnswer;
        $array["allowQuestionReview"]       = $this->allowQuestionReview;
        $array["classDiscussionEnabled"]    = $this->classDiscussionEnabled;
        $array["additionalUsers"]           = $this->additionalUsers;
        return $array;
    }

    /**
     * Check if a user is allowed to edit this session
     * @param User $user
     * @return bool
     */
    public function checkIfUserCanEdit($user) {
        return $this->owner==$user->getUsername() || $this->hasAdditionalUser($user);
    }

    /**
     * @return int
     */
    public function getSessionID() {
        return $this->sessionID;
    }

    /**
     * @param int $sessionID
     */
    public function setSessionID($sessionID) {
        $this->sessionID = $sessionID;
    }

    /**
     * @return string
     */
    public function getOwner() {
        return $this->owner;
    }

    /**
     * @param string $owner
     */
    public function setOwner($owner) {
        $this->owner = $owner;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getCourseID() {
        return $this->courseID;
    }

    /**
     * @param string $courseID
     */
    public function setCourseID($courseID) {
        $this->courseID = $courseID;
    }

    /**
     * @return bool
     */
    public function getAllowGuests() {
        return $this->allowGuests;
    }

    /**
     * @param bool $allowGuests
     */
    public function setAllowGuests($allowGuests) {
        $this->allowGuests = $allowGuests;
    }

    /**
     * @return bool
     */
    public function getOnSessionList() {
        return $this->onSessionList;
    }

    /**
     * @param bool $onSessionList
     */
    public function setOnSessionList($onSessionList) {
        $this->onSessionList = $onSessionList;
    }

    /**
     * @return int
     */
    public function getQuestionControlMode() {
        return $this->questionControlMode;
    }

    /**
     * @param int $questionControlMode
     */
    public function setQuestionControlMode($questionControlMode) {
        $this->questionControlMode = $questionControlMode;
    }

    /**
     * @return int
     */
    public function getDefaultTimeLimit() {
        return $this->defaultTimeLimit;
    }

    /**
     * @param int $defaultTimeLimit
     */
    public function setDefaultTimeLimit($defaultTimeLimit) {
        $this->defaultTimeLimit = $defaultTimeLimit;
    }

    /**
     * @return bool
     */
    public function getAllowModifyAnswer() {
        return $this->allowModifyAnswer;
    }

    /**
     * @param bool $allowModifyAnswer
     */
    public function setAllowModifyAnswer($allowModifyAnswer) {
        $this->allowModifyAnswer = $allowModifyAnswer;
    }

    /**
     * @return bool
     */
    public function getAllowQuestionReview() {
        return $this->allowQuestionReview;
    }

    /**
     * @param bool $allowQuestionReview
     */
    public function setAllowQuestionReview($allowQuestionReview) {
        $this->allowQuestionReview = $allowQuestionReview;
    }

    /**
     * @return bool
     */
    public function getClassDiscussionEnabled() {
        return $this->classDiscussionEnabled;
    }

    /**
     * @param bool $classDiscussionEnabled
     */
    public function setClassDiscussionEnabled($classDiscussionEnabled) {
        $this->classDiscussionEnabled = $classDiscussionEnabled;
    }

    /**
     * @return array
     */
    public function getAdditionalUsers() {
        return $this->additionalUsers;
    }

    /**
     * @return string
     */
    public function getAdditionalUsersCsv() {
        return implode(",", $this->additionalUsers);
    }

    /**
     * @param array $additionalUsers
     */
    public function setAdditionalUsers($additionalUsers) {
        $this->additionalUsers = $additionalUsers;
    }

    /**
     * @param String $additionalUsers
     */
    public function setAdditionalUsersCsv($additionalUsers) {
        $this->additionalUsers = explode(",", $additionalUsers);
    }

    /**
     * Add an additional user
     * @param $username
     */
    public function addAdditionalUser($username) {
        array_push($this->additionalUsers, $username);
    }

    /**
     * Has additional user
     * @param $username
     * @return bool
     */
    public function hasAdditionalUser($username) {
        return in_array($username, $this->additionalUsers);
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
        $this->created = $created;
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
        $this->lastUpdate = $lastUpdate;
    }
}