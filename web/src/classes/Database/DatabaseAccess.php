<?php

class DatabaseAccess
{

    /** @var null|mysqli */
    private static $dblink=null;

    /**
     * Connect to the database
     */
    public static function connect() {
        global $DBCFG;
        //# Move this to config soon!
        $host=$DBCFG['host']; // Host name
        $username=$DBCFG['username']; // Mysql username
        $password=$DBCFG['password']; // Mysql password
        $db_name=$DBCFG['db_name']; // Database name
        self::$dblink = mysqli_connect("$host", "$username", "$password", $db_name)or die("cannot connect");
    }

    /**
     * Run database query
     * @param string $query SQL Query string
     * @return array|bool
     */
    public static function runQuery($query="") {

        // If not connected to database, connect to it
        if(self::$dblink==null)
            DatabaseAccess::connect();

        // Run database query
        $result = mysqli_query(self::$dblink, $query);

        // If error running query, output error
        if(!$result) {
            $message  = 'Invalid query: ' . mysqli_error(self::$dblink) . "\n";
            $message .= 'Whole query: ' . $query;
            die($message);
        }

        // If successful query that doesn't output a result
        if($result===true)
            $output = true;

        // Otherwise this query outputted a result
        else {

            // Put result rows into array
            $output = array();
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $output[] = $row;
            }
        }

        return $output;
    }

    public static function close()
    {
        if(self::$dblink!=null)
            mysqli_close(self::$dblink);
        self::$dblink = null;
    }

    public static function safe($in)
    {
        if (self::$dblink==NULL)
            DatabaseAccess::connect();

        return self::$dblink->real_escape_string($in);
    }

    public static function db2date($in)
    {
        list($y,$m,$d) = explode("-",$in);
        return mktime(0,0,0,$m,$d,$y);
    }

    public static function date2db($in)
    {
        return strftime("%Y-%m-%d", $in);
    }

    public static function db2time($in)
    {
        if(strlen($in)==0)
            return 0;
        list($dt, $ti) = explode(" ",$in);
        list($y,$m,$d) = explode("-",$dt);
        list($hh,$mm,$ss) = explode(":",$ti);
        return mktime($hh,$mm,$ss,$m,$d,$y);
    }

    public static function time2db($in)
    {
        return strftime("%Y-%m-%d %H:%M:%S", $in);
    }
}