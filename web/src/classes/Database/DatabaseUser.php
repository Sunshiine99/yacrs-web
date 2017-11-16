<?php

class DatabaseUser
{
    /**
     * Primary Key
     * @var int|null
     */
    private $id;

    /** @var string */
    private $username;

    /** @var string */
    private $name;

    /** @var string */
    private $email;

    /** @var string */
    private $nickname;

    /** @var string */
    private $phone;

    /** @var bool */
    private $sessionCreator;

    /** @var bool */
    private $isAdmin;

    /** @var string */
    private $teacherPrefs;

    /**
     * DatabaseUser constructor.
     * @param null $asArray
     */
    public function __construct($asArray=null) {
        $this->id = null; //primary key
        $this->username = "";
        $this->name = "";
        $this->email = "";
        $this->nickname = "";
        $this->phone = "";
        $this->sessionCreator = false;
        $this->isAdmin = false;
        $this->teacherPrefs = false;
        if($asArray!==null)
            $this->fromArray($asArray);
    }

    /**
     * Load class attributes from array
     * @param $asArray
     */
    private function fromArray($asArray) {
        $this->id = $asArray['id'];
        $this->username = $asArray['username'];
        $this->name = $asArray['name'];
        $this->email = $asArray['email'];
        $this->nickname = $asArray['nickname'];
        $this->phone = $asArray['phone'];
        $this->sessionCreator = ($asArray['sessionCreator']==0)?false:true;
        $this->isAdmin = ($asArray['isAdmin']==0)?false:true;
        $this->teacherPrefs = unserialize($asArray['teacherPrefs']);
    }

    public static function retrieve_userInfo($id) {
        $query = "SELECT * FROM yacrs_userInfo WHERE id='".DatabaseAccess::safe($id)."';";
        $result = DatabaseAccess::runQuery($query);
        if(sizeof($result)!=0)
        {
            return new userInfo($result[0]);
        }
        else
            return false;
    }


    public static function retrieve_by_username($username) {
        $query = "SELECT * FROM yacrs_userInfo WHERE username='".DatabaseAccess::safe($username)."';";
        $result = DatabaseAccess::runQuery($query);
        if(sizeof($result)!=0)
        {
            return new userInfo($result[0]);
        }
        else
            return false;
    }

    public static function retrieve_userInfo_matching($field, $value, $from=0, $count=-1, $sort=null) {
        if(preg_replace('/\W/','',$field)!== $field)
            return false; // not a permitted field name;
        $query = "SELECT * FROM yacrs_userInfo WHERE $field='".DatabaseAccess::safe($value)."'";
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
                $output[] = new userInfo($r);
            return $output;
        }
        else
            return false;
    }

    public function insert() {
        //#Any required insert methods for foreign keys need to be called here.
        $query = "INSERT INTO yacrs_userInfo(username, name, email, nickname, phone, sessionCreator, isAdmin, teacherPrefs) VALUES(";
        $query .= "'".DatabaseAccess::safe($this->username)."', ";
        $query .= "'".DatabaseAccess::safe($this->name)."', ";
        $query .= "'".DatabaseAccess::safe($this->email)."', ";
        $query .= "'".DatabaseAccess::safe($this->nickname)."', ";
        $query .= "'".DatabaseAccess::safe($this->phone)."', ";
        $query .= "'".(($this->sessionCreator===false)?0:1)."', ";
        $query .= "'".(($this->isAdmin===false)?0:1)."', ";
        $query .= "'".DatabaseAccess::safe(serialize($this->teacherPrefs))."');";
        DatabaseAccess::runQuery("BEGIN;");
        $result = DatabaseAccess::runQuery($query);
        $result2 = DatabaseAccess::runQuery("SELECT LAST_INSERT_ID() AS id;");
        DatabaseAccess::runQuery("COMMIT;");
        $this->id = $result2[0]['id'];
        return $this->id;
    }

    public function update() {
        $query = "UPDATE yacrs_userInfo ";
        $query .= "SET username='".DatabaseAccess::safe($this->username)."' ";
        $query .= ", name='".DatabaseAccess::safe($this->name)."' ";
        $query .= ", email='".DatabaseAccess::safe($this->email)."' ";
        $query .= ", nickname='".DatabaseAccess::safe($this->nickname)."' ";
        $query .= ", phone='".DatabaseAccess::safe($this->phone)."' ";
        $query .= ", sessionCreator='".(($this->sessionCreator===false)?0:1)."' ";
        $query .= ", isAdmin='".(($this->isAdmin===false)?0:1)."' ";
        $query .= ", teacherPrefs='".DatabaseAccess::safe(serialize($this->teacherPrefs))."' ";
        $query .= "WHERE id='".DatabaseAccess::safe($this->id)."';";
        return DatabaseAccess::runQuery($query);
    }

    public static function count($where_name=null, $equals_value=null) {
        $query = "SELECT COUNT(*) AS count FROM yacrs_userInfo WHERE ";
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

    private function toXML() {
        $out = "<userInfo>\n";
        $out .= '<id>'.htmlentities($this->id)."</id>\n";
        $out .= '<username>'.htmlentities($this->username)."</username>\n";
        $out .= '<name>'.htmlentities($this->name)."</name>\n";
        $out .= '<email>'.htmlentities($this->email)."</email>\n";
        $out .= '<nickname>'.htmlentities($this->nickname)."</nickname>\n";
        $out .= '<phone>'.htmlentities($this->phone)."</phone>\n";
        $out .= '<sessionCreator>'.htmlentities($this->sessionCreator)."</sessionCreator>\n";
        $out .= '<isAdmin>'.htmlentities($this->isAdmin)."</isAdmin>\n";
        $out .= '<teacherPrefs>'.htmlentities($this->teacherPrefs)."</teacherPrefs>\n";
        $out .= "</userInfo>\n";
        return $out;
    }
    //[[USERCODE_userInfo]] Put code for custom class members in this block.

    public static function retrieveByMobileNo($mobileNo) {
        $query = "SELECT * FROM yacrs_userInfo WHERE phone='".DatabaseAccess::safe($mobileNo)."';";
        $result = DatabaseAccess::runQuery($query);
        if(sizeof($result)==1)    // If duplicated just return none for now...
        {
            $output = new userInfo($result[0]);
            return $output;
        }
        else
            return false;
    }

    public static function retrieve_all_userInfo($from=0, $count=-1, $sort=null) {
        $query = "SELECT * FROM yacrs_userInfo ";
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
                $output[] = new userInfo($r);
            return $output;
        }
        else
            return false;
    }

    public static function search_userInfo($searchTerm, $from=0, $count=-1) {
        $query = "SELECT * FROM yacrs_userInfo";
        $query .= " WHERE username LIKE '%".DatabaseAccess::safe($searchTerm)."%'";
        $query .= " OR name LIKE '%".DatabaseAccess::safe($searchTerm)."%'";
        $query .= " ORDER BY name ASC";
        if(($count != -1)&&(is_int($count))&&(is_int($from)))
            $query .= " LIMIT ".$count." OFFSET ".$from;
        $query .= ';';
        $result = DatabaseAccess::runQuery($query);
        if(sizeof($result)!=0)
        {
            $output = array();
            foreach($result as $r)
                $output[] = new userInfo($r);
            return $output;
        }
        else
            return false;
    }

    /**
     * @return int|null
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getNickname() {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     */
    public function setNickname($nickname) {
        $this->nickname = $nickname;
    }

    /**
     * @return string
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone) {
        $this->phone = $phone;
    }

    /**
     * @return bool
     */
    public function isSessionCreator() {
        return $this->sessionCreator;
    }

    /**
     * @param bool $sessionCreator
     */
    public function setSessionCreator($sessionCreator) {
        $this->sessionCreator = $sessionCreator;
    }

    /**
     * @return bool
     */
    public function isAdmin() {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     */
    public function setIsAdmin($isAdmin) {
        $this->isAdmin = $isAdmin;
    }

    /**
     * @return string
     */
    public function getTeacherPrefs() {
        return $this->teacherPrefs;
    }

    /**
     * @param string $teacherPrefs
     */
    public function setTeacherPrefs($teacherPrefs) {
        $this->teacherPrefs = $teacherPrefs;
    }
}