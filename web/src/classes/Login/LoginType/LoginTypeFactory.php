<?php

class LoginTypeFactory
{
    /**
     * Returns a new instance of a login type object from a given type.
     * @param $type string Type of login
     * @return LoginType
     * @throws Exception 'LoginTypeFactory_ClassNotFoundException': Given type does not translate to login object
     */
    public static function create($type)
    {
        switch ($type) {
            case "ldap":
                return new LoginTypeLdap();
                break;
            case "any":
                return new LoginTypeAny();
                break;
            default:
                throw new Exception("LoginTypeFactory_ClassNotFoundException");
        }
    }
}