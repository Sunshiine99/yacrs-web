<?php

class Database
{

    /**
     * Make string safe for use in database
     * @param $string
     * @param mysqli $mysqli
     * @return mixed
     */
    public static function safe($string, $mysqli) {
        return $mysqli->real_escape_string($string);
    }

    /**
     * Make string safe for use in database
     * @param $string
     * @param mysqli $mysqli
     * @param int $length
     * @param int $type
     * @return mixed
     */
    public static function safe____new($string, $mysqli, $length=64, $type=0) {
        switch($type) {

            // STRING
            case 0;
                $string = substr($string, $length);
                break;

            // INTEGER
            case 1;
                $string = substr($string, 0, $length);
                break;
        }

        return $mysqli->real_escape_string($string);
    }

    /**
     * TODO: REMOVE
     * @param mysqli $mysqli
     * @param int|null $line
     * @param string|null $file
     */
    public static function checkError($mysqli, $line=null, $file=null) {
        if($mysqli->error) {

            // Construct and log error message
            $error = "Database error";
            if($file)
                $error .= " in " . basename($file);
            if($line)
                $error .= " on line " . $line;
            $error .= ": " . $mysqli->error;
            error_log($error);

            // Display a 500
            PageError::error500();
            die();
        }
    }
}