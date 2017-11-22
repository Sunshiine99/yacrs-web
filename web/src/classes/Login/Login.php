<?php

class Login
{

    public static function checkLogin($username, $password, $type="ldap") {
        $login = LoginTypeFactory::create($type);

        $uinfo = $login::checkLogin($username, $password);


        if(!$uinfo)
            return false;

        // Load the user details from the database
        $databaseUser = DatabaseUser::retrieveByUsername($username);

        // If database has user, override session creator and admin privileges
        if($databaseUser) {
            $uinfo["isAdmin"] = $databaseUser->isAdmin();
            $uinfo["sessionCreator"] = $databaseUser->isSessionCreator();
        }

        return $uinfo;
    }
}