<?php

class ApiLegacyLogin
{

    /**
     * @param string $username
     * @param string $password
     * @param array $config
     * @param mysqli $mysqli
     */
    public static function login($username, $password, $config, $mysqli) {
        $errors = [];
        $data = [];

        // Log the user in
        $user = Login::checkLogin($username, $password, $config["login"]["type"], $mysqli);

        // If incorrect login, output error
        if(!$user) {
            $errors[] = "Incorrect login";
            ApiLegacy::sendResponse("login", $errors, [], $config);
            die();
        }

        ApiLegacy::sendResponse("login", $errors, $data, $config);
    }
}