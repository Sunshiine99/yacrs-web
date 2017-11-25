<?php

class DatabaseUser
{
    /**
     * @param User $user
     * @param mysqli $mysqli
     * @return User
     */
    public static function loadDetails($user, $mysqli) {

        // Get the username and make it database safe
        $username = Database::safe($user->getUsername(), $mysqli);

        // Run query to get details
        $sql = "SELECT *
                FROM `yacrs_user`
                WHERE `yacrs_user`.`username` = '$username'";
        $result = $mysqli->query($sql);

        // If user details existed in the database
        if($result->num_rows == 1) {

            // Load the database row
            $row = $result->fetch_assoc();

            // Get database id
            $user->setId($row["userID"]);

            // If isSessionCreator is overridden, update user with this value
            if($row["isSessionCreatorOverride"] !== null) {
                $user->setIsSessionCreator($row["isSessionCreatorOverride"]);
            }

            // If isAdmin is overridden, update user with this value
            if($row["isAdminOverride"] !== null) {
                $user->setIsAdmin($row["isAdminOverride"]);
            }
        }

        // Otherwise, setup table with default values
        else {
            $sql = "INSERT INTO `yacrs_user` (`username`)
                    VALUES ('$username')";
            $mysqli->query($sql);

            // Get database id
            $user->setId($mysqli->insert_id);
        }

        return $user;
    }

    /**
     * @param string $username
     * @param mysqli $mysqli
     * @return bool
     */
    public static function checkUserExists($username, $mysqli) {

        // Get the username and make it database safe
        $username = Database::safe($username, $mysqli);

        // Run query to get details
        $sql = "SELECT *
                FROM `yacrs_user`
                WHERE `yacrs_user`.`username` = '$username'";
        $result = $mysqli->query($sql);

        // If user details existed in the database
        if($result->num_rows == 1) {
            return true;
        }

        return false;
    }

    public static function getUserId($username, $mysqli) {

        // Get the username and make it database safe
        $username = Database::safe($username, $mysqli);

        // Run query to get details
        $sql = "SELECT *
                FROM `yacrs_user`
                WHERE `yacrs_user`.`username` = '$username'";
        $result = $mysqli->query($sql);

        // If user details existed in the database
        if($result->num_rows == 1) {

            // Load the database row and return the ID
            $row = $result->fetch_assoc();
            return $row["userID"];
        }

        return false;
    }

    public static function getUsername($userID, $mysqli) {

        // Get the username and make it database safe
        $userID = Database::safe($userID, $mysqli);

        // Run query to get details
        $sql = "SELECT *
                FROM `yacrs_user`
                WHERE `yacrs_user`.`userID` = '$userID'";
        $result = $mysqli->query($sql);

        // If user details existed in the database
        if($result->num_rows == 1) {

            // Load the database row and return the ID
            $row = $result->fetch_assoc();
            return $row["username"];
        }

        return false;
    }
}