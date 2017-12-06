<?php

class LoginTypeAny implements LoginType
{

    /**
     * Checks login details. Returns user
     * @param $username
     * @param $password
     * @return User|bool
     */
    public static function checkLogin($username, $password) {
        $user = new User();
        $user->setUsername($username);
        $user->setGivenName("Joe");
        $user->setSurname("Bloggs");
        $user->setEmail("joebloggs@example.com");

        if(substr($username, 0, 5) == "teach") {
            $user->setIsSessionCreator(true);
        }
        elseif(substr($username, 0, 5) == "admin") {
            $user->setIsAdmin(true);
            $user->setIsSessionCreator(true);
        }

        return $user;
    }
}