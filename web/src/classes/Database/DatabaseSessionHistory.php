<?php

class DatabaseSessionHistory
{

    /**
     * Insert an item into session history
     * @param User $user
     * @param Session $session
     * @param mysqli $mysqli
     * @return int|null
     */
    public static function insert($user, $session, $mysqli) {
        $userID = Database::safe____new($user->getId(), $mysqli, 11, 1);
        $sessionID = Database::safe____new($session->getSessionID(), $mysqli, 11, 1);

        // Run query to insert
        $sql = "INSERT INTO `yacrs_sessionHistory` (`userID`, `sessionID`, `time`)
                VALUES ('$userID', '$sessionID', '".time()."')";
        $result = $mysqli->query($sql);

        // If error, return null
        if(!$result) return null;

        // Return the ID of the row in session history
        return $mysqli->insert_id;
    }
}