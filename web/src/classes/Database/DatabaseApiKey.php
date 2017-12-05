<?php

class DatabaseApiKey
{

    /**
     * Generate new API key
     * @return string
     */
    private static function generateApiKey() {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }

    /**
     * Creates new API key
     * @param string $username
     * @param mysqli $mysqli
     * @return string|null
     */
    public static function newApiKey($username, $mysqli) {
        $i = 0;

        // Make username database safe
        $username = Database::safe($username, $mysqli);

        // Generate new api key
        $key = self::generateApiKey();

        // While the API key has been used
        while(self::checkApiKey($key, $mysqli)) {

            // Generate new api key
            $key = self::generateApiKey();
        }

        // Key creation time
        $created = time();

        // Run SQL Query
        $sql = "INSERT INTO `yacrs_apiKey` (`key`, `created`, `username`)
                VALUES ('$key', $created, '$username');";
        $result = $mysqli->query($sql);

        return ($result ? $key : null);
    }


    /**
     * Checks api key
     * @param string $key
     * @param mysqli $mysqli
     * @return bool
     */
    public static function checkApiKey($key="", $mysqli) {

        // Escape database key
        $key = Database::safe($key, $mysqli);

        // Run database query
        $sql = "SELECT `key`, `created`
                FROM `yacrs_apiKey`
                WHERE `yacrs_apiKey`.`key` = '$key'";
        $result = $mysqli->query($sql);

        // If query did not return a result, i.e. the key does not exist
        if($result->num_rows == 0)
            return false;

        $row = $result->fetch_assoc();

        // If key has expired, return false
        if($row["created"] <= 0)
            return false;

        return true;
    }

    /**
     * @param string $key
     * @param mysqli $mysqli
     * @return bool
     */
    public static function apiKeyExpire($key, $mysqli) {

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Escape database key
        $key = Database::safe($key, $mysqli);

        // Run database query
        $sql = "UPDATE `yacrs_apiKey`
                SET `created`=0
                WHERE `yacrs_apiKey`.`key`='$key'";
        $result = $mysqli->query($sql);

        return $result ? true : false;
    }
}
