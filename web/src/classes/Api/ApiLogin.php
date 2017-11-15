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
            $output["key"] = "2d1c5aedf340d670d9e05739f2e87e377e661bb7c8db19b287b465959161efd4";
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
        $output["error"]["code"] = "notImplemented";
        $output["error"]["message"] = "Not Implemented";
        Api::output($output);
    }
}