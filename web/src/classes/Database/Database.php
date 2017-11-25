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
}