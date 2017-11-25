<?php

class DatabaseSession
{
    // TODO: Make these private
    var $id; //primary key
    var $ownerID;
    var $title;
    var $created;
    var $questions;
    var $currentQuestion;
    var $questionMode;
    var $endTime;
    var $sessionStartTime;
    var $sessionOpen;
    var $sessionEndTime;
    var $visible;
    var $allowGuests;
    var $multiSession;
    var $ublogRoom;
    var $maxMessagelength;
    var $allowQuReview;
    var $allowTeacherQu;
    var $courseIdentifier;
    var $defaultQuActiveSecs;
    var $extras;

    /**
     * DatabaseSession constructor.
     * @param null $asArray
     */
    public function __construct($asArray=null) {
        $this->id = null; //primary key
        $this->ownerID = "";
        $this->title = "";
        $this->created = time();
        $this->questions = "";
        $this->currentQuestion = "0";
        $this->questionMode = "0";
        $this->endTime = time();
        $this->sessionStartTime = time();
        $this->sessionOpen = false;
        $this->sessionEndTime = time();
        $this->visible = false;
        $this->allowGuests = false;
        $this->multiSession = false;
        $this->ublogRoom = "0";
        $this->maxMessagelength = "0";
        $this->allowQuReview = false;
        $this->allowTeacherQu = false;
        $this->courseIdentifier = "";
        $this->defaultQuActiveSecs = "0";
        $this->extras = false;
        if($asArray!==null)
            $this->fromArray($asArray);
    }

    /**
     * Load session details from array
     * @param $asArray
     */
    private function fromArray($asArray) {
        $this->id = (int)$asArray['id'];
        $this->ownerID = $asArray['ownerID'];
        $this->title = $asArray['title'];
        $this->created = DatabaseAccess::db2time($asArray['created']);
        $this->questions = $asArray['questions'];
        $this->currentQuestion = (int)$asArray['currentQuestion'];
        $this->questionMode = (int)$asArray['questionMode'];
        $this->endTime = DatabaseAccess::db2time($asArray['endTime']);
        $this->sessionStartTime = DatabaseAccess::db2time($asArray['sessionStartTime']);
        $this->sessionOpen = ($asArray['sessionOpen']==0)?false:true;
        $this->sessionEndTime = DatabaseAccess::db2time($asArray['sessionEndTime']);
        $this->visible = ($asArray['visible']==0)?false:true;
        $this->allowGuests = ($asArray['allowGuests']==0)?false:true;
        $this->multiSession = ($asArray['multiSession']==0)?false:true;
        $this->ublogRoom = (int)$asArray['ublogRoom'];
        $this->maxMessagelength = (int)$asArray['maxMessagelength'];
        $this->allowQuReview = ($asArray['allowQuReview']==0)?false:true;
        $this->allowTeacherQu = ($asArray['allowTeacherQu']==0)?false:true;
        $this->courseIdentifier = $asArray['courseIdentifier'];
        $this->defaultQuActiveSecs = $asArray['defaultQuActiveSecs'];
        $this->extras = unserialize($asArray['extras']);
    }

    public function toArray() {
        $output = array();
        $output["id"] = $this->id;
        $output["ownerID"] = $this->ownerID;
        $output["title"] = $this->title;
        $output["created"] = $this->created;
        $output["questions"] = $this->questions;
        $output["currentQuestion"] = $this->currentQuestion;
        $output["questionMode"] = $this->questionMode;
        $output["endTime"] = $this->endTime;
        $output["sessionStartTime"] = $this->sessionStartTime;
        $output["sessionOpen"] = $this->sessionOpen;
        $output["sessionEndTime"] = $this->sessionEndTime;
        $output["visible"] = $this->visible;
        $output["allowGuests"] = $this->allowGuests;
        $output["multiSession"] = $this->multiSession;
        $output["ublogRoom"] = $this->ublogRoom;
        $output["maxMessagelength"] = $this->maxMessagelength;
        $output["allowQuReview"] = $this->allowQuReview;
        $output["allowTeacherQu"] = $this->allowTeacherQu;
        $output["courseIdentifier"] = $this->courseIdentifier;
        $output["defaultQuActiveSecs"] = $this->defaultQuActiveSecs;
        $output["extras"] = serialize($this->extras);
        return $output;
    }

    public static function retrieveSession($id) {
        $query = "SELECT * FROM yacrs_session WHERE id='".DatabaseAccess::safe($id)."';";
        $result = DatabaseAccess::runQuery($query);
        if(sizeof($result)!=0) {
            return new DatabaseSession($result[0]);
        }
        else
            return false;
    }

    public static function retrieveSessionMatching($field, $value, $from=0, $count=-1, $sort=null) {
        if(preg_replace('/\W/','',$field)!== $field)
            return false; // not a permitted field name;
        $query = "SELECT * FROM yacrs_session WHERE $field='".DatabaseAccess::safe($value)."'";
        if(($sort !== null)&&(preg_replace('/\W/','',$sort)!== $sort))
            $query .= " ORDER BY ".$sort;
        if(($count != -1)&&(is_int($count))&&(is_int($from)))
            $query .= " LIMIT ".$count." OFFSET ".$from;
        $query .= ';';
        $result = DatabaseAccess::runQuery($query);
        if(sizeof($result)!=0)
        {
            $output = array();
            foreach($result as $r)
                $output[] = new DatabaseSession($r);
            return $output;
        }
        else
            return false;
    }

    public function insert() {
        //#Any required insert methods for foreign keys need to be called here.
        $query = "INSERT INTO yacrs_session(ownerID, title, created, questions, currentQuestion, questionMode, endTime, sessionStartTime, sessionOpen, sessionEndTime, visible, allowGuests, multiSession, ublogRoom, maxMessagelength, allowQuReview, allowTeacherQu, courseIdentifier, defaultQuActiveSecs, extras) VALUES(";
        $query .= "'".DatabaseAccess::safe($this->ownerID)."', ";
        $query .= "'".DatabaseAccess::safe($this->title)."', ";
        $query .= "'".DatabaseAccess::time2db($this->created)."', ";
        $query .= "'".DatabaseAccess::safe($this->questions)."', ";
        $query .= "'".DatabaseAccess::safe($this->currentQuestion)."', ";
        $query .= "'".DatabaseAccess::safe($this->questionMode)."', ";
        $query .= "'".DatabaseAccess::time2db($this->endTime)."', ";
        $query .= "'".DatabaseAccess::time2db($this->sessionStartTime)."', ";
        $query .= "'".(($this->sessionOpen===false)?0:1)."', ";
        $query .= "'".DatabaseAccess::time2db($this->sessionEndTime)."', ";
        $query .= "'".(($this->visible===false)?0:1)."', ";
        $query .= "'".(($this->allowGuests===false)?0:1)."', ";
        $query .= "'".(($this->multiSession===false)?0:1)."', ";
        $query .= "'".DatabaseAccess::safe($this->ublogRoom)."', ";
        $query .= "'".DatabaseAccess::safe($this->maxMessagelength)."', ";
        $query .= "'".(($this->allowQuReview===false)?0:1)."', ";
        $query .= "'".(($this->allowTeacherQu===false)?0:1)."', ";
        $query .= "'".DatabaseAccess::safe($this->courseIdentifier)."', ";
        $query .= "'".DatabaseAccess::safe($this->defaultQuActiveSecs)."', ";
        $query .= "'".DatabaseAccess::safe(serialize($this->extras))."');";
        DatabaseAccess::runQuery("BEGIN;");
        $result = DatabaseAccess::runQuery($query);
        $result2 = DatabaseAccess::runQuery("SELECT LAST_INSERT_ID() AS id;");
        DatabaseAccess::runQuery("COMMIT;");
        $this->id = $result2[0]['id'];
        return $this->id;
    }

    public function update() {
        $query = "UPDATE yacrs_session ";
        $query .= "SET ownerID='".DatabaseAccess::safe($this->ownerID)."' ";
        $query .= ", title='".DatabaseAccess::safe($this->title)."' ";
        $query .= ", created='".DatabaseAccess::time2db($this->created)."' ";
        $query .= ", questions='".DatabaseAccess::safe($this->questions)."' ";
        $query .= ", currentQuestion='".DatabaseAccess::safe($this->currentQuestion)."' ";
        $query .= ", questionMode='".DatabaseAccess::safe($this->questionMode)."' ";
        $query .= ", endTime='".DatabaseAccess::time2db($this->endTime)."' ";
        $query .= ", sessionStartTime='".DatabaseAccess::time2db($this->sessionStartTime)."' ";
        $query .= ", sessionOpen='".(($this->sessionOpen===false)?0:1)."' ";
        $query .= ", sessionEndTime='".DatabaseAccess::time2db($this->sessionEndTime)."' ";
        $query .= ", visible='".(($this->visible===false)?0:1)."' ";
        $query .= ", allowGuests='".(($this->allowGuests===false)?0:1)."' ";
        $query .= ", multiSession='".(($this->multiSession===false)?0:1)."' ";
        $query .= ", ublogRoom='".DatabaseAccess::safe($this->ublogRoom)."' ";
        $query .= ", maxMessagelength='".DatabaseAccess::safe($this->maxMessagelength)."' ";
        $query .= ", allowQuReview='".(($this->allowQuReview===false)?0:1)."' ";
        $query .= ", allowTeacherQu='".(($this->allowTeacherQu===false)?0:1)."' ";
        $query .= ", courseIdentifier='".DatabaseAccess::safe($this->courseIdentifier)."' ";
        $query .= ", defaultQuActiveSecs='".DatabaseAccess::safe($this->defaultQuActiveSecs)."' ";
        $query .= ", extras='".DatabaseAccess::safe(serialize($this->extras))."' ";
        $query .= "WHERE id='".DatabaseAccess::safe($this->id)."';";
        return DatabaseAccess::runQuery($query);
    }

    public static function count($where_name=null, $equals_value=null) {
        $query = "SELECT COUNT(*) AS count FROM yacrs_session WHERE ";
        if($where_name==null)
            $query .= '1;';
        else
            $query .= "$where_name='".DatabaseAccess::safe($equals_value)."';";
        $result = DatabaseAccess::runQuery($query);
        if($result == false)
            return 0;
        else
            return $result['0']['count'];
    }


    //1:n relationship to extraTeachers
    public function getTeachersCount() {
        $query = "SELECT COUNT(*) AS count FROM extraTeachers WHERE parent_id = {$this->id};";
        $result = DatabaseAccess::runQuery($query);
        if($result == false)
            return 0;
        else
            return $result['0']['count'];
    }

    public function getTeachers($from=0, $count=-1, $sort=null) {
        $query = "SELECT * FROM yacrs_extraTeachers WHERE session_id='$this->id'";
        if(($sort !== null)&&(preg_replace('/\W/','',$sort)!== $sort))
            $query .= " ORDER BY ".$sort;
        if(($count != -1)&&(is_int($count))&&(is_int($from)))
            $query .= " LIMIT ".$count." OFFSET ".$from;
        $query .= ';';
        $result = DatabaseAccess::runQuery($query);
        if(sizeof($result)!=0)
        {
            $output = array();
            foreach($result as $r)
                $output[] = new extraTeachers($r);
            return $output;
        }
        else
            return false;
    }

    public function toXML() {
        $out = "<session>\n";
        $out .= '<id>'.htmlentities($this->id)."</id>\n";
        $out .= '<ownerID>'.htmlentities($this->ownerID)."</ownerID>\n";
        $out .= '<title>'.htmlentities($this->title)."</title>\n";
        $out .= '<created>'.htmlentities($this->created)."</created>\n";
        $out .= '<questions>'.htmlentities($this->questions)."</questions>\n";
        $out .= '<currentQuestion>'.htmlentities($this->currentQuestion)."</currentQuestion>\n";
        $out .= '<questionMode>'.htmlentities($this->questionMode)."</questionMode>\n";
        $out .= '<endtime>'.htmlentities($this->endTime)."</endtime>\n";
        $out .= '<sessionstarttime>'.htmlentities($this->sessionStartTime)."</sessionstarttime>\n";
        $out .= '<sessionOpen>'.htmlentities($this->sessionOpen)."</sessionOpen>\n";
        $out .= '<sessionendtime>'.htmlentities($this->sessionEndTime)."</sessionendtime>\n";
        $out .= '<visible>'.htmlentities($this->visible)."</visible>\n";
        $out .= '<allowGuests>'.htmlentities($this->allowGuests)."</allowGuests>\n";
        $out .= '<multiSession>'.htmlentities($this->multiSession)."</multiSession>\n";
        $out .= '<ublogRoom>'.htmlentities($this->ublogRoom)."</ublogRoom>\n";
        $out .= '<maxMessagelength>'.htmlentities($this->maxMessagelength)."</maxMessagelength>\n";
        $out .= '<allowQuReview>'.htmlentities($this->allowQuReview)."</allowQuReview>\n";
        $out .= '<allowTeacherQu>'.htmlentities($this->allowTeacherQu)."</allowTeacherQu>\n";
        $out .= '<courseIdentifier>'.htmlentities($this->courseIdentifier)."</courseIdentifier>\n";
        $out .= '<defaultQuActiveSecs>'.htmlentities($this->defaultQuActiveSecs)."</defaultQuActiveSecs>\n";
        $out .= '<extras>'.htmlentities($this->extras)."</extras>\n";
        $out .= "</session>\n";
        return $out;
    }

    public static function deleteSession($id) {
        //Get the session
        $s = DatabaseSession::retrieveSession($id);
        //Delete each question instance (including images)
        if(strlen(trim($s->questions)))
        {
            $qis = explode(',',$s->questions);
            foreach($qis as $qi)
            {
                questionInstance::deleteInstance($qi);  // also deletes responses
            }
        }
        //Delete sessionmember links
        $s->clearSessionMembers();
        //Delete any blog posts
        $s->clearSessionMessages();
        //Delete any LTI link
        $query = "DELETE FROM yacrs_ltisessionlink WHERE session_id='{$id}';";
        DatabaseAccess::runQuery($query);
        //Delete any additional teacher links
        $query = "DELETE FROM yacrs_extraTeachers WHERE session_id='{$id}';";
        DatabaseAccess::runQuery($query);
        //Delete the session.
        $query = "DELETE FROM yacrs_session WHERE id='{$id}';";
        DatabaseAccess::runQuery($query);
    }

    public function clearSessionMembers() {
        $query = "DELETE FROM yacrs_sessionMember WHERE session_id='{$this->id}';";
        DatabaseAccess::runQuery($query);
    }

    public function removeSessionMember($id) {
        $query = "DELETE FROM yacrs_sessionMember WHERE session_id='{$this->id}' AND id='{$id}';";
        DatabaseAccess::runQuery($query);
    }

    public function clearSessionMessages() {
        $query = "DELETE FROM yacrs_message_tag_link WHERE `message_id` IN (SELECT id FROM yacrs_message WHERE session_id='$this->id');";
        DatabaseAccess::runQuery($query);
        $query = "DELETE FROM yacrs_message WHERE session_id='{$this->id}';";
        DatabaseAccess::runQuery($query);
    }

    public function addQuestion($qu) {
        $qi = new questionInstance();
        $qi->theQuestion_id = $qu->id;
        $qi->inSession_id = $this->id;
        $qi->title = $qu->title;
        $qi->insert();
        $this->questions = trim($this->questions.','.$qi->id," \t\r\n,");
        $this->update();
        return $qi;
    }

    public function isStaffInSession($userid) {
        if(trim($userid)==trim($this->ownerID))
            return true;
        else
        {
            $ets = $this->getExtraTeacherIDs();
            if(in_array($userid, $ets))
                return true;
        }
        return false;
    }

    public static function retrieveAllSessions($from=0, $count=-1, $sort=null) {
        $query = "SELECT * FROM yacrs_session ";
        if($sort !== null)
            $query .= " ORDER BY ".$sort;
        if(($count != -1)&&(is_int($count))&&(is_int($from)))
            $query .= " LIMIT ".$count." OFFSET ".$from;
        $query .= ';';
        $result = DatabaseAccess::runQuery($query);
        if(sizeof($result)!=0)
        {
            $output = array();
            foreach($result as $r)
                $output[] = new DatabaseSession($r);
            return $output;
        }
        else
            return false;
    }

    public function getExtraTeacherIDs() {
        $et = array();
        $eTeachers = $this->getTeachers();
        if(is_array($eTeachers))
        {
            foreach($eTeachers as $t)
            {
                $et[] = $t->teacherID;
            }
        }
        return $et;
    }

    public function updateExtraTeachers($teachers) {
        if(!is_array($teachers))
        {
            $teachers = explode(',', $teachers);
        }
        foreach($teachers as &$t)
        {
            $t = trim($t);
        }
        $knownTeachers = $this->getTeachers();
        $ktIDs = array();
        if(is_array($knownTeachers))
        {
            foreach($knownTeachers as $kt)
            {
                if(!in_array($kt->teacherID, $teachers))
                {
                    $this->removeExtraTeacher($kt->id);
                }
                else
                {
                    $ktIDs[] = $kt->teacherID;
                }
            }
        }
        foreach($teachers as &$t)
        {
            if((strlen($t))&&(!in_array($t, $ktIDs)))
            {
                $kt = new extraTeachers();
                $kt->teacherID = $t;
                $kt->session_id = $this->id;
                $kt->insert();
            }
        }
    }

    public function removeExtraTeacher($id) {
        $query = "DELETE FROM yacrs_extraTeachers WHERE session_id='{$this->id}' AND id='{$id}';";
        DatabaseAccess::runQuery($query);
    }

    public static function teacherExtraSessions($teacherID) {
        $sessions = array();
        $ets = DatabaseExtraTeachers::retrieveExtraTeachersMatching("teacherID", $teacherID);
        if(is_array($ets)) {
            $done = array();  // This is because a bug in an earlier version of YACRS occasionaly created duplicate entries.
            foreach($ets as $s) {
                if(!in_array($s->session_id, $done)) {
                    $done[] = $s->session_id;
                    $ses = DatabaseSession::retrieveSession($s->session_id);
                    if($ses !== false) {
                        $sessions[] = $ses;
                    }
                }
            }
        }
        return $sessions;
    }

    /**
     * @return null
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getOwnerID() {
        return $this->ownerID;
    }

    /**
     * @param string $ownerID
     */
    public function setOwnerID($ownerID) {
        $this->ownerID = $ownerID;
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
     * @return string
     */
    public function getQuestions() {
        return $this->questions;
    }

    /**
     * @param string $questions
     */
    public function setQuestions($questions) {
        $this->questions = $questions;
    }

    /**
     * @return string
     */
    public function getCurrentQuestion() {
        return $this->currentQuestion;
    }

    /**
     * @param string $currentQuestion
     */
    public function setCurrentQuestion($currentQuestion) {
        $this->currentQuestion = $currentQuestion;
    }

    /**
     * @return string
     */
    public function getQuestionMode() {
        return $this->questionMode;
    }

    /**
     * @param string $questionMode
     */
    public function setQuestionMode($questionMode) {
        $this->questionMode = $questionMode;
    }

    /**
     * @return int
     */
    public function getEndTime() {
        return $this->endTime;
    }

    /**
     * @param int $endTime
     */
    public function setEndTime($endTime) {
        $this->endTime = $endTime;
    }

    /**
     * @return int
     */
    public function getSessionStartTime() {
        return $this->sessionStartTime;
    }

    /**
     * @param int $sessionStartTime
     */
    public function setSessionStartTime($sessionStartTime) {
        $this->sessionStartTime = $sessionStartTime;
    }

    /**
     * @return bool
     */
    public function isSessionOpen() {
        return $this->sessionOpen;
    }

    /**
     * @param bool $sessionOpen
     */
    public function setSessionOpen($sessionOpen) {
        $this->sessionOpen = $sessionOpen;
    }

    /**
     * @return int
     */
    public function getSessionEndTime() {
        return $this->sessionEndTime;
    }

    /**
     * @param int $sessionEndTime
     */
    public function setSessionEndTime($sessionEndTime) {
        $this->sessionEndTime = $sessionEndTime;
    }

    /**
     * @return bool
     */
    public function isVisible() {
        return $this->visible;
    }

    /**
     * @param bool $visible
     */
    public function setVisible($visible) {
        $this->visible = $visible;
    }

    /**
     * @return bool
     */
    public function isAllowGuests() {
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
    public function isMultiSession() {
        return $this->multiSession;
    }

    /**
     * @param bool $multiSession
     */
    public function setMultiSession($multiSession) {
        $this->multiSession = $multiSession;
    }

    /**
     * @return string
     */
    public function getUblogRoom() {
        return $this->ublogRoom;
    }

    /**
     * @param string $ublogRoom
     */
    public function setUblogRoom($ublogRoom) {
        $this->ublogRoom = $ublogRoom;
    }

    /**
     * @return string
     */
    public function getMaxMessagelength() {
        return $this->maxMessagelength;
    }

    /**
     * @param string $maxMessagelength
     */
    public function setMaxMessagelength($maxMessagelength) {
        $this->maxMessagelength = $maxMessagelength;
    }

    /**
     * @return bool
     */
    public function isAllowQuReview() {
        return $this->allowQuReview;
    }

    /**
     * @param bool $allowQuReview
     */
    public function setAllowQuReview($allowQuReview) {
        $this->allowQuReview = $allowQuReview;
    }

    /**
     * @return bool
     */
    public function isAllowTeacherQu() {
        return $this->allowTeacherQu;
    }

    /**
     * @param bool $allowTeacherQu
     */
    public function setAllowTeacherQu($allowTeacherQu) {
        $this->allowTeacherQu = $allowTeacherQu;
    }

    /**
     * @return string
     */
    public function getCourseIdentifier() {
        return $this->courseIdentifier;
    }

    /**
     * @param string $courseIdentifier
     */
    public function setCourseIdentifier($courseIdentifier) {
        $this->courseIdentifier = $courseIdentifier;
    }

    /**
     * @return string
     */
    public function getDefaultQuActiveSecs() {
        return $this->defaultQuActiveSecs;
    }

    /**
     * @param string $defaultQuActiveSecs
     */
    public function setDefaultQuActiveSecs($defaultQuActiveSecs) {
        $this->defaultQuActiveSecs = $defaultQuActiveSecs;
    }

    /**
     * @return bool
     */
    public function isExtras() {
        return $this->extras;
    }

    /**
     * @param bool $extras
     */
    public function setExtras($extras) {
        $this->extras = $extras;
    }
}