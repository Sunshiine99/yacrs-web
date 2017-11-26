<?php

class DatabaseSession
{

    /**
     * Add new session to database
     * @param Session $session Session as Session object
     * @param mysqli $mysqli Database connection
     * @return int Session ID
     */
    public static function insert($session, $mysqli) {

        // Make variables safe for database use
        $ownerID                = Database::safe($session->getOwner(), $mysqli);
        $title                  = Database::safe($session->getTitle(), $mysqli);
        $courseID               = Database::safe($session->getCourseID(), $mysqli);
        $allowGuests            = Database::safe($session->getAllowGuests(), $mysqli);
        $onSessionList          = Database::safe($session->getOnSessionList(), $mysqli);
        $questionControlMode    = Database::safe($session->getQuestionControlMode(), $mysqli);
        $defaultTimeLimit       = Database::safe($session->getDefaultTimeLimit(), $mysqli);
        $allowModifyAnswer      = Database::safe($session->getAllowModifyAnswer(), $mysqli);
        $allowQuestionReview    = Database::safe($session->getAllowQuestionReview(), $mysqli);
        $classDiscussionEnabled = Database::safe($session->getClassDiscussionEnabled(), $mysqli);

        // Convert boolean values to string for use in query
        $allowGuests            = $allowGuests              ? "1" : "0";
        $onSessionList          = $onSessionList            ? "1" : "0";
        $allowModifyAnswer      = $allowModifyAnswer        ? "1" : "0";
        $allowQuestionReview    = $allowQuestionReview      ? "1" : "0";
        $classDiscussionEnabled = $classDiscussionEnabled   ? "1" : "0";

        // Run query to insert into yacrs_sessions table
        $sql = "INSERT INTO `yacrs_sessions` (
                    `ownerID`,
                    `title`,
                    `courseID`,
                    `allowGuests`,
                    `onSessionList`,
                    `questionControlMode`,
                    `defaultTimeLimit`,
                    `allowModifyAnswer`,
                    `allowQuestionReview`,
                    `classDiscussionEnabled`,
                    `created`,
                    `lastUpdate`
                )
                VALUES (
                    '$ownerID',
                    '$title',
                    '$courseID',
                    '$allowGuests',
                    '$onSessionList',
                    '$questionControlMode',
                    '$defaultTimeLimit',
                    '$allowModifyAnswer',
                    '$allowQuestionReview',
                    '$classDiscussionEnabled',
                    ".time().",
                    ".time().")";
        $result = $mysqli->query($sql);

        // Get the session ID
        $sessionID = Database::safe($mysqli->insert_id, $mysqli);

        // Foreach additional user
        foreach($session->getAdditionalUsers() as $additionalUser) {

            // Get additional user ID
            $additionalUserId = Database::safe(DatabaseUser::getUserId($additionalUser, $mysqli), $mysqli);

            // If user ID was found
            if($additionalUserId) {

                // Add query to insert rhe additional user
                $sql = "INSERT INTO `yacrs_sessionsAdditionalUsers` (`sessionID`, `userID`)
                        VALUES ('$sessionID', '$additionalUserId'); ";
                $result = $mysqli->query($sql);
            }
        }

        return $sessionID;
    }

    /**
     * Update session in database
     * @param Session $session Session as Session object
     * @param mysqli $mysqli Database connection
     * @return bool Success?
     */
    public static function update($session, $mysqli) {
        // Make variables safe for database use
        $sessionID              = Database::safe($session->getSessionID(), $mysqli);
        $ownerID                = Database::safe($session->getOwner(), $mysqli);
        $title                  = Database::safe($session->getTitle(), $mysqli);
        $courseID               = Database::safe($session->getCourseID(), $mysqli);
        $allowGuests            = Database::safe($session->getAllowGuests(), $mysqli);
        $onSessionList          = Database::safe($session->getOnSessionList(), $mysqli);
        $questionControlMode    = Database::safe($session->getQuestionControlMode(), $mysqli);
        $defaultTimeLimit       = Database::safe($session->getDefaultTimeLimit(), $mysqli);
        $allowModifyAnswer      = Database::safe($session->getAllowModifyAnswer(), $mysqli);
        $allowQuestionReview    = Database::safe($session->getAllowQuestionReview(), $mysqli);
        $classDiscussionEnabled = Database::safe($session->getClassDiscussionEnabled(), $mysqli);

        // Convert boolean values to string for use in query
        $allowGuests            = $allowGuests              ? "1" : "0";
        $onSessionList          = $onSessionList            ? "1" : "0";
        $allowModifyAnswer      = $allowModifyAnswer        ? "1" : "0";
        $allowQuestionReview    = $allowQuestionReview      ? "1" : "0";
        $classDiscussionEnabled = $classDiscussionEnabled   ? "1" : "0";

        // Run query to update table
        $sql = "UPDATE `yacrs_sessions`
                SET
                  `title`                  = '$title',
                  `courseID`               = '$courseID',
                  `allowGuests`            = '$allowGuests',
                  `onSessionList`          = '$onSessionList',
                  `questionControlMode`    = '$questionControlMode',
                  `defaultTimeLimit`       = '$defaultTimeLimit',
                  `allowModifyAnswer`      = '$allowModifyAnswer',
                  `allowQuestionReview`    = '$allowQuestionReview',
                  `classDiscussionEnabled` = '$classDiscussionEnabled',
                  `lastUpdate`             = ".time()."
                WHERE `sessionID` = '$sessionID'";
        $result = $mysqli->query($sql);

        // Delete all existing Additional Users
        // TODO: DON'T DO THIS. CHECK EXISTING AND UPDATE IF NEEDED.
        $sql = "DELETE FROM `yacrs_sessionsAdditionalUsers`
                WHERE `yacrs_sessionsAdditionalUsers`.`sessionID` = $sessionID;";
        $result = $mysqli->query($sql);

        // Foreach additional user
        foreach($session->getAdditionalUsers() as $additionalUser) {

            // Get additional user ID
            $additionalUserId = Database::safe(DatabaseUser::getUserId($additionalUser, $mysqli), $mysqli);

            // If user ID was found
            if($additionalUserId) {

                // Add query to insert rhe additional user
                $sql = "INSERT INTO `yacrs_sessionsAdditionalUsers` (`sessionID`, `userID`)
                        VALUES ('$sessionID', '$additionalUserId'); ";
                $result = $mysqli->query($sql);
            }
        }

        return true;
    }

    /**
     * @param $sessionID
     * @param mysqli $mysqli
     * @return Session
     */
    public static function loadSession($sessionID, $mysqli) {

        // Make variables safe for database use
        $sessionID = Database::safe($sessionID, $mysqli);

        $sql = "SELECT *
                FROM `yacrs_sessions`
                WHERE `yacrs_sessions`.`sessionID` = '$sessionID'";
        $result = $mysqli->query($sql);

        // If error with result
        if($result->num_rows != 1) {
            return null;
        }

        // Load row from database
        $row = $result->fetch_assoc();

        // Get the owner username from the database
        $row["owner"] = DatabaseUser::getUsername($row["ownerID"], $mysqli);

        // Create a new session with the loaded attributes
        $session = new Session($row);

        // SQL query to get additional users
        $sql = "SELECT username
                FROM
                  `yacrs_sessionsAdditionalUsers` as sau,
                  `yacrs_user` as u
                WHERE sau.`sessionID` = $sessionID
                  AND sau.`userID` = u.`userID`";
        $result = $mysqli->query($sql);

        // Loop for every row and add additional user
        while($row = $result->fetch_assoc()) {
            $session->addAdditionalUser($row["username"]);
        }

        return $session;
    }

    /**
     * @param $userId
     * @param mysqli $mysqli
     * @return array
     */
    public static function loadUserSessions($userId, $mysqli) {

        // Make variables safe for database use
        $userId = Database::safe($userId, $mysqli);

        $sql = "SELECT *
                FROM (
                    (
                        SELECT
                            s.*,
                            1 as canEdit
                        FROM `yacrs_sessions` as s
                        WHERE s.`ownerID` = $userId
                    )
                    UNION
                    (
                        SELECT
                            s.*,
                            1 as canEdit
                        FROM
                        `yacrs_sessions` AS s,
                        `yacrs_sessionsAdditionalUsers` as sau
                        WHERE s.`sessionID` = sau.`sessionID`
                        AND sau.`userID` = $userId
                    )
                ) as sessions
                ORDER BY lastUpdate DESC";
        $result = $mysqli->query($sql);

        $sessions = [];

        // Loop for every row and add additional user
        while($row = $result->fetch_assoc()) {
            array_push($sessions, new Session($row));
        }

        return $sessions;
    }
}