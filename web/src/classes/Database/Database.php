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