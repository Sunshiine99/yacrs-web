<?php

class LoginTypeLdapCsq extends LoginTypeLdap
{

    /**
     * Checks login username and password
     * @param $username
     * @param $password
     * @param array $config
     * @return User|null
     */
    public static function checkLogin($username, $password, $config = []) {

        // Perform LDAP login
        $user = parent::checkLogin($username, $password, $config);

        // If login was valid
        if($user) {

            // Force all CSQ members to have admin and teacher permissions
            $username = strtolower($username);
            switch($username) {
                case "2262645c";    // Chase
                case "2205747i";    // Hristo
                case "2141683m";    // Michael
                case "2198207s";    // David
                case "2036909a";    // Nora
                    $user->setIsSessionCreator(true);
                    $user->setIsAdmin(true);
                    break;
            }
        }

        return $user;
    }
}