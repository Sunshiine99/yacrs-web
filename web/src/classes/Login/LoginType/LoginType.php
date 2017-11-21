<?php

interface LoginType
{

    /**
     * Checks login details. Returns userinfo array if success.
     * @param $username
     * @param $password
     * @return array|bool
     */
    public static function checkLogin($username, $password);
}