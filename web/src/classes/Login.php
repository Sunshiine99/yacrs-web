<?php

class Login
{

    public static function checkLogin($username, $password) {
        $uinfo = array();
        $uinfo['uname'] = $username;
        $uinfo['gn'] = "Joe";
        $uinfo['sn'] = "Bloggs";
        $uinfo['email'] = "joebloggs@exmple.com";

        if(substr($username, 0, 5) == "teach") {
            $uinfo['isAdmin'] = false;
            $uinfo['sessionCreator'] = true;
        }
        elseif(substr($username, 0, 5) == "admin") {
            $uinfo['isAdmin'] = true;
            $uinfo['sessionCreator'] = true;
        }
        else {
            $uinfo['isAdmin'] = false;
            $uinfo['sessionCreator'] = false;
        }

        if($password != "orangemonkey") {
            return false;
        }

        return $uinfo;
    }
}