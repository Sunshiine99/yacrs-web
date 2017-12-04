<?php

class Alert
{
    private $title = null;
    private $message = null;
    private $type = null;

    public function __construct($array = []) {
        $this->title    = isset($array["title"])    ? $array["title"]   : $this->title;
        $this->message  = isset($array["message"])  ? $array["message"] : $this->message;
        $this->type     = isset($array["type"])     ? $array["type"]    : $this->type;
    }

    public function toArray() {
        $output["title"]    = $this->title;
        $output["message"]  = $this->message;
        $output["type"]     = $this->type;
        return $output;
    }

    /**
     * Puts an alert
     * @param Alert $alert
     * @param int $expire
     */
    public static function displayAlertSession($alert, $expire = null) {
        if($expire == null) {
            $expire = time() + 30;
        }

        $_SESSION["yacrs_alert"]["alert"] = $alert->toArray();
        $_SESSION["yacrs_alert"]["expire"] = $expire;
    }

    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type) {
        $this->type = $type;
    }
}