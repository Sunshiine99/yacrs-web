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
     * @param $username
     * @return string
     */
    public static function newApiKey($username) {

        // Escape database username
        $username = DatabaseAccess::safe($username);

        $i = 0;

        // Generate new api key
        $key = self::generateApiKey();

        // While the API key has been used
        while(self::checkApiKey($key)) {

            // Generate new api key
            $key = md5($i);
        }

        // Key creation time
        $created = time();

        // Run SQL Query
        $sql = "INSERT INTO `yacrs_apiKey` (`key`, `created`, `username`)
                VALUES ('$key', $created, '$username');";
        DatabaseAccess::runQuery($sql);

        return $key;
    }

    /**
     * Checks api key
     * @param string $key
     * @return null|string Username or null if failure
     */
    public static function checkApiKey($key="") {

        // Escape database key
        $key = DatabaseAccess::safe($key);

        // Run database query
        $sql = "SELECT `key`, `username`, `created`
                FROM `yacrs_apiKey`
                WHERE `yacrs_apiKey`.`key` = '$key'";
        $rows = DatabaseAccess::runQuery($sql);

        // If query did not return a result, i.e. the key does not exist
        if(count($rows) == 0)
            return null;

        // If key has expired, return false
        if($rows[0]["created"] <= 0)
            return null;

        return $rows[0]["username"];
    }

    public static function apiKeyExpire($key) {

        // Escape database key
        $key = DatabaseAccess::safe($key);

        // Run database query
        $sql = "UPDATE `yacrs_apiKey`
                SET `created`=0
                WHERE `yacrs_apiKey`.`key`='$key'";
        $rows = DatabaseAccess::runQuery($sql);

        return true;
    }
}