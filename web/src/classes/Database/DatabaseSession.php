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

        if(!$result)
            return null;

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

        // Run database query to insert session ID into session identifiers
        $sql = "INSERT INTO `yacrs_sessionIdentifier` (`sessionID`)
                VALUES ('$sessionID') ";
        $result = $mysqli->query($sql);

        if(!$result)
            return null;

        // Get the session identifier
        $sessionIdentifier = Database::safe($mysqli->insert_id, $mysqli);


        return $sessionIdentifier;
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

        // If error with result
        if(!$result) return null;

        // Delete all existing Additional Users
        // TODO: DON'T DO THIS. CHECK EXISTING AND UPDATE IF NEEDED.
        $sql = "DELETE FROM `yacrs_sessionsAdditionalUsers`
                WHERE `yacrs_sessionsAdditionalUsers`.`sessionID` = $sessionID;";
        $result = $mysqli->query($sql);

        // If error with result
        if(!$result) return null;

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
     * @param int $sessionIdentifier
     * @param mysqli $mysqli
     * @return Session
     */
    public static function loadSession($sessionIdentifier, $mysqli) {

        // Make variables safe for database use
        $sessionIdentifier = Database::safe($sessionIdentifier, $mysqli);

        //Get the sessionID
        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        if(!$sessionID) return null;

        $sql = "SELECT
                    s.*
                FROM
                    `yacrs_sessions` as s
                WHERE s.`sessionID` = '$sessionID'";
        $result = $mysqli->query($sql);

        // If error with result
        if(!$result) return null;

        // Load row from database
        $row = $result->fetch_assoc();

        // Get the owner username from the database
        $row["owner"] = DatabaseUser::getUsername($row["ownerID"], $mysqli);

        // Create a new session with the loaded attributes
        $session = new Session($row);
        $session->setSessionIdentifier($sessionIdentifier);

        // SQL query to get additional users
        $sql = "SELECT username
                FROM
                  `yacrs_sessionsAdditionalUsers` as sau,
                  `yacrs_user` as u
                WHERE sau.`sessionID` = $sessionID
                  AND sau.`userID` = u.`userID`";
        $result = $mysqli->query($sql);

        // If error with result
        if(!$result) return null;

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

        // SQL query to load all sessions that the user can edit
        $sql = "SELECT *
                FROM (
                    (
                        SELECT
                            si.`sessionIdentifier`,
                            s.*,
                            1 as canEdit
                        FROM
                            `yacrs_sessions` as s,
                            `yacrs_sessionIdentifier` as si
                        WHERE s.`ownerID` = $userId
                          AND s.`sessionID` = si.`sessionID`
                    )
                    UNION
                    (
                        SELECT
                            si.`sessionIdentifier`,
                            s.*,
                            1 as canEdit
                        FROM
                            `yacrs_sessions` AS s,
                            `yacrs_sessionsAdditionalUsers` as sau,
                            `yacrs_sessionIdentifier` as si
                        WHERE s.`sessionID` = sau.`sessionID`
                          AND sau.`userID` = $userId
                          AND s.`sessionID` = si.`sessionID`
                    )
                ) as sessions
                ORDER BY lastUpdate DESC";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        $sessions = [];

        // TODO
        $username = DatabaseUser::getUsername($userId, $mysqli);

        // Loop for every row and add additional user
        while($row = $result->fetch_assoc()) {

            // Create a new session using the database row
            $session = new Session($row);
            $session->setOwner($username);

            // Make the session ID safe for SQL query
            $sessionID = Database::safe($session->getSessionID(), $mysqli);

            // SQL query to get additional users
            $sql = "SELECT username
                    FROM
                      `yacrs_sessionsAdditionalUsers` as sau,
                      `yacrs_user` as u
                    WHERE sau.`sessionID` = $sessionID
                      AND sau.`userID` = u.`userID`";
            $result2 = $mysqli->query($sql);

            // If query was successful
            if($result2) {

                // Loop for every row and add additional user
                while($row2 = $result2->fetch_assoc()) {
                    $session->addAdditionalUser($row2["username"]);
                }
            }

            // Add session to array of sessions
            array_push($sessions, $session);
        }

        return $sessions;
    }

    /**
     * @param mysqli $mysqli
     */
    public static function loadAllSessions($mysqli) {

    }

    /**
     * Delete a session
     * @param int $sessionIdentifier
     * @param mysqli $mysqli
     * @return bool
     */
    public static function delete($sessionIdentifier, $mysqli) {

        // Make variables safe for database use
        $sessionIdentifier = Database::safe($sessionIdentifier, $mysqli);

        //Get the sessionID
        $sql = "SELECT `sessionID`
                FROM `yacrs_sessionIdentifier`
                WHERE `sessionIdentifier` = $sessionIdentifier";
        $result = $mysqli->query($sql);
        $row = $result->fetch_assoc();
        $sessionID = $row["sessionID"];

        //Delete from sessionQuestions
        $sql = "SELECT `questionID`
                FROM `yacrs_sessionQuestions`
                WHERE `sessionID` = $sessionID";
        $result2 = $mysqli->query($sql);

        // If query was successful
        if($result2) {
            // Loop for every row
            while($row2 = $result2->fetch_assoc()) {
                $questionID = $row2["questionID"];
                DatabaseSessionQuestion::delete($questionID, $mysqli);
                //Delete from questions
                $sql = "DELETE FROM `yacrs_questions`
                        WHERE `questionID` = $questionID";
                $result = $mysqli->query($sql);
            }
        }


        //Delete from sessionIdentifier
        $sql = "DELETE FROM `yacrs_sessionIdentifier`
                WHERE `yacrs_sessionIdentifier`.`sessionIdentifier` = $sessionIdentifier";
        $result = $mysqli->query($sql);

        //Delete from sessions
        $sql = "DELETE FROM `yacrs_sessions`
                WHERE `sessionID` = $sessionID";
        $result = $mysqli->query($sql);


        return $result ? true : false;
    }

    /**
     * Load sessions that the user can edit and they have joined
     * @param int $userID
     * @param mysqli $mysqli
     * @return Session[]|null
     */
    public static function loadUserHistoryAndEditableSessions($userID, $mysqli) {
        $userID = Database::safe($userID, $mysqli);

        // Run query to get session IDs
        $sql = "SELECT `sessionID`, `sessionIdentifier`, MAX(`time`) as time
                    FROM (
                    (
                        SELECT
                            si.`sessionIdentifier`,
                            s.`sessionID`,
                            s.`lastUpdate` as time
                        FROM
                            `yacrs_sessions` as s,
                            `yacrs_sessionIdentifier` as si
                        WHERE s.`ownerID` = $userID
                          AND s.`sessionID` = si.`sessionID`
                    )
                    UNION
                    (
                        SELECT
                            si.`sessionIdentifier`,
                            s.`sessionID`,
                            s.`lastUpdate` as time
                        FROM
                            `yacrs_sessionsAdditionalUsers` as sau,
                            `yacrs_sessions` as s,
                            `yacrs_sessionIdentifier` as si
                        WHERE sau.`sessionID` = s.`sessionID`
                          AND sau.`userID` = $userID
                          AND s.`sessionID` = si.`sessionID`
                    )
                    UNION
                    (
                        SELECT
                            si.`sessionIdentifier`,
                            sh.`sessionID`,
                            MAX(sh.`time`) as time
                        FROM
                          `yacrs_sessionHistory` as sh,
                          `yacrs_sessions` as s,
                          `yacrs_sessionIdentifier` as si
                        WHERE sh.`userID` = $userID
                          AND sh.`sessionID` = s.`sessionID`
                          /* Only get sessions that should be shown in the session list */
                          AND s.`onSessionList` = 1
                          AND s.`sessionID` = si.`sessionID`
                        GROUP BY `sessionID`, `sessionIdentifier`
                    )
                ) as sessions
                GROUP BY `sessionID`, `sessionIdentifier`
                ORDER BY `time` DESC";
        $result = $mysqli->query($sql);

        // If error, return null
        if(!$result) return null;

        $output = [];

        // Loop for every row in the database result
        while($row = $result->fetch_assoc()) {

            // Load session object from session ID
            $session = DatabaseSession::loadSession($row["sessionID"], $mysqli);

            if(!$session) continue;

            $session->setSessionIdentifier($row["sessionIdentifier"]);

            // If success, Add to output array
            if($session) {
                $output[] = $session;
            }
        }

        return $output;
    }

    public static function loadUserActiveSessions($userID, $mysqli){

        $userID = Database::safe($userID, $mysqli);

        $sql = "SELECT si.`sessionIdentifier`
                FROM `yacrs_sessions` as s,
                    `yacrs_sessionIdentifier` as si,
                    `yacrs_user` as u,
                    `yacrs_sessionQuestions` as q
                WHERE s.`ownerID` = $userID
                AND s.`sessionID` = si.`sessionID`
                AND u.`userID` = $userID
                AND q.`sessionID` = s.`sessionID`
                AND q.`active` = 1";
        $result = $mysqli->query($sql);

        // If error, return null
        if(!$result) return null;

        $output = [];

        // Loop for every row in the database result
        while($row = $result->fetch_assoc()) {
            array_push($output, $row);
        }

        return $output;
    }
}