<?php

interface LoginType
{

    /**
     * Checks login username and password
     * @param $username
     * @param $password
     * @param array $config
     * @return User|null
     */
    public static function checkLogin($username, $password, $config);
}