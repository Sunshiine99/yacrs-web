<?php

class DatabaseUser
{
    /**
     * @param User $user
     * @param mysqli $mysqli
     * @return User
     */
    public static function loadDetails($user, $mysqli) {

        // Get the username, forename and givenname and make it database safe
        $username = Database::safe($user->getUsername(), $mysqli);
        $givenName = Database::safe($user->getGivenName(), $mysqli);
        $surname = Database::safe($user->getSurname(), $mysqli);

        // Make sure the given name and surname are not longer than 64 characters
        $givenName = strlen($givenName) > 64 ? substr($givenName, 0, 64) : $givenName;
        $surname = strlen($surname) > 64 ? substr($surname, 0, 64) : $surname;

        // If this is a guest, insert a new row
        if($user->isGuest()) {

            // Run query to INSERT new row
            $sql = "INSERT INTO `yacrs_user` (`username`, `givenName`, `surname`, `isGuest`)
                    VALUES (NULL, '$givenName', NULL, 1)";
            $result = $mysqli->query($sql);

            // If error, return null
            if(!$result) return null;

            // Get database id
            $user->setId($mysqli->insert_id);

            // Return the user
            return $user;
        }

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

            // If the given name exists in the database but not in the user object, update the user object
            if($row["givenName"] !== null && $user->getGivenName() === null) {
                $user->setGivenName($row["givenName"]);
                $givenName = Database::safe($user->getGivenName(), $mysqli);
            }

            // If the surname exists in the database but not in the user object, update the user object
            if($row["surname"] !== null && $user->getSurname() === null) {
                $user->setSurname($row["surname"]);
                $surname = Database::safe($user->getSurname(), $mysqli);
            }

            // If isSessionCreator is overridden, update user with this value
            if($row["isSessionCreatorOverride"] !== null) {
                $user->setIsSessionCreator($row["isSessionCreatorOverride"]);
            }

            // If isAdmin is overridden, update user with this value
            if($row["isAdminOverride"] !== null) {
                $user->setIsAdmin($row["isAdminOverride"]);
            }

            // Make the userid database safe
            $userId = Database::safe($user->getId(), $mysqli);

            // Run query to update given name and surname
            $sql = "UPDATE `yacrs_user`
                    SET `givenName` = '$givenName', `surname` = '$surname'
                    WHERE `yacrs_user`.`userID` = $userId;";
            $result = $mysqli->query($sql);

            // If error, return null
            if(!$result) return null;
        }

        // Otherwise, setup table with default values
        else {

            // Run query to insert username into database
            $sql = "INSERT INTO `yacrs_user` (`username`, `givenName`, `surname`)
                    VALUES ('$username', '$givenName', '$surname')";
            $result = $mysqli->query($sql);

            // If error, return null
            if(!$result) return null;

            // Get database id
            $user->setId($mysqli->insert_id);
        }

        return $user;
    }

    /**
     * @param string $username
     * @param mysqli $mysqli
     * @return User
     */
    public static function loadDetailsFromUsername($username, $mysqli) {
        $user = new User();
        $user->setUsername($username);

        // Run query to get details
        $sql = "SELECT *
                FROM `yacrs_user`
                WHERE `yacrs_user`.`username` = '$username'";
        $result = $mysqli->query($sql);

        // If error, return null
        if(!$result) return null;

        // If user details existed in the database
        if($result->num_rows == 1) {

            // Load the database row
            $row = $result->fetch_assoc();

            // Load user details from row
            $user->fromArray($row);

            print_r($row);
            echo "<br>";
            echo "<br>";
        }

        else {
            return null;
        }

        return self::loadDetails($user, $mysqli);
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