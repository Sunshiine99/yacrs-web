<?php

class DatabaseExtraTeachers
{
    // TODO: Make these private
    var $id; //primary key
    var $session_id; //foreign key
    var $teacherID;

    public function __construct($asArray=null) {
        $this->id = null; //primary key
        $this->session_id = null; // foreign key, needs dealt with.
        $this->teacherID = "";
        if($asArray!==null)
            $this->fromArray($asArray);
    }

    public function fromArray($asArray) {
        $this->id = $asArray['id'];
        $this->session_id = $asArray['session_id']; // foreign key, check code
        $this->teacherID = $asArray['teacherID'];
    }

    public static function retrieveExtraTeachers($id) {
        $query = "SELECT * FROM yacrs_extraTeachers WHERE id='".DatabaseAccess::safe($id)."';";
        $result = DatabaseAccess::runQuery($query);
        if(sizeof($result)!=0)
        {
            return new extraTeachers($result[0]);
        }
        else
            return false;
    }

    public static function retrieveExtraTeachersMatching($field, $value, $from=0, $count=-1, $sort=null) {
        if(preg_replace('/\W/','',$field)!== $field)
            return false; // not a permitted field name;
        $query = "SELECT * FROM yacrs_extraTeachers WHERE $field='".DatabaseAccess::safe($value)."'";
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

    public function insert() {
        $query = "INSERT INTO yacrs_extraTeachers(session_id, teacherID) VALUES(";
        if($this->session_id!==null)
            $query .= "'".DatabaseAccess::safe($this->session_id)."', ";
        else
            $query .= "null, ";
        $query .= "'".DatabaseAccess::safe($this->teacherID)."');";
        DatabaseAccess::runQuery("BEGIN;");
        $result = DatabaseAccess::runQuery($query);
        $result2 = DatabaseAccess::runQuery("SELECT LAST_INSERT_ID() AS id;");
        DatabaseAccess::runQuery("COMMIT;");
        $this->id = $result2[0]['id'];
        return $this->id;
    }

    public function update() {
        $query = "UPDATE yacrs_extraTeachers ";
        $query .= "SET session_id='".DatabaseAccess::safe($this->session_id)."' ";
        $query .= ", teacherID='".DatabaseAccess::safe($this->teacherID)."' ";
        $query .= "WHERE id='".DatabaseAccess::safe($this->id)."';";
        return DatabaseAccess::runQuery($query);
    }

    public static function count($where_name=null, $equals_value=null) {
        $query = "SELECT COUNT(*) AS count FROM yacrs_extraTeachers WHERE ";
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

    public function toXML() {
        $out = "<extraTeachers>\n";
        $out .= '<id>'.htmlentities($this->id)."</id>\n";
        $out .= '<session>'.htmlentities($this->session)."</session>\n";
        $out .= '<teacherID>'.htmlentities($this->teacherID)."</teacherID>\n";
        $out .= "</extraTeachers>\n";
        return $out;
    }
}