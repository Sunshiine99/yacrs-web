<?php

class DatabaseSessionIdentifier
{

    /**
     * Loads the session ID from a session identifier
     * @param int $sessionIdentifier
     * @param mysqli $mysqli
     * @return int|null;
     */
    public static function loadSessionID($sessionIdentifier, $mysqli) {
        $sessionIdentifier = Database::safe($sessionIdentifier, $mysqli);

        $sql = "SELECT *
                FROM `yacrs_sessionIdentifier` as si
                WHERE si.`sessionIdentifier` = $sessionIdentifier";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        $row = $result->fetch_assoc();

        return intval($row["sessionID"]);
    }
}