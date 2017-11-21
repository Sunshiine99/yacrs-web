<?php

class Login
{

    public static function checkLogin($username, $password, $type="ldap") {
        $login = LoginTypeFactory::create($type);
        return $login::checkLogin($username, $password);
    }
}