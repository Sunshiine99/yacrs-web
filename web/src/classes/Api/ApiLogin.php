<?php

class ApiLogin
{

    /**
     * Login API page
     */
    public static function login() {
        $output = [];

        // Check required parameters
        $username = Api::checkParameter("username");
        $password = Api::checkParameter("password");

        // Attempt to login, get user details if success false if not
        $uinfo = Login::checkLogin($username, $password);

        // If invalid login, output an error
        if(!$uinfo) {
            $output["error"]["code"] = "login_invalid";
            $output["error"]["message"] = "Invalid login details";
        }

        // Otherwise, output key and details
        else {

            // Get new api key
            $apiKey = DatabaseApiKey::newApiKey($uinfo["uname"]);

            $output["key"] = $apiKey;
            $output["details"]["username"] = $uinfo["uname"];
            $output["details"]["givenname"] = $uinfo["gn"];
            $output["details"]["surname"] = $uinfo["sn"];
            $output["details"]["email"] = $uinfo["email"];
            $output["details"]["isAdmin"] = $uinfo["isAdmin"];
            $output["details"]["sessionCreator"] = $uinfo["sessionCreator"];
        }


        Api::output($output);
    }

    /**
     * Logout API page
     */
    public static function logout() {

        // Check required parameters
        $key = Api::checkParameter("key");

        // Logout user by making API key expire
        DatabaseApiKey::apiKeyExpire($key);

        $output["success"] = true;
        Api::output($output);
    }
}