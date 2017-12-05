<?php

class ApiLogin
{

    /**
     * Login API page
     */
    public static function login() {
        $config = Flight::get("config");

        $output = [];

        // Check required parameters
        $username = Api::checkParameter("username");
        $password = Api::checkParameter("password");

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Check whether login is valid
        $user = Login::checkLogin($username, $password, $config["login"]["type"], $mysqli);

        // If invalid login, output an error
        if(!$user) {
            $output["error"]["code"] = "loginInvalid";
            $output["error"]["message"] = "Invalid login details";
        }

        // Otherwise, output key and details
        else {

            // Get new api key
            $apiKey = DatabaseApiKey::newApiKey($user, $mysqli);

            $output["key"] = $apiKey;
            $output["details"]["username"] = $user->getUsername();
            $output["details"]["givenname"] = $user->getGivenName();
            $output["details"]["surname"] = $user->getSurname();
            $output["details"]["email"] = $user->getEmail();
            $output["details"]["isAdmin"] = $user->isAdmin();
            $output["details"]["isSessionCreator"] = $user->isSessionCreator();
        }


        Api::output($output);
    }

    /**
     * Logout API page
     */
    public static function logout() {

        if(isset($_SESSION["yacrs_user"])) {
            unset($_SESSION["yacrs_user"]);
        }

        if(isset($_REQUEST["key"])) {
            $key = Api::checkParameter("key");

            // Connect to database
            $databaseConnect = Flight::get("databaseConnect");
            $mysqli = $databaseConnect();

            // Logout user by making API key expire
            DatabaseApiKey::apiKeyExpire($key, $mysqli);
        }

        $output["success"] = true;
        Api::output($output);
    }
}