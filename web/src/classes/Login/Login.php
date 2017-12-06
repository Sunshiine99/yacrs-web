<?php

class Login
{

    /**
     * Checks if user is currently logged in
     * @return bool|User False if not. User object if so.
     */
    public static function checkUserLoggedIn() {

        // If user is not stored in the session
        if(!isset($_SESSION["yacrs_user"])) {
            return false;
        }

        // Return the user from the session
        return new User($_SESSION["yacrs_user"]);
    }

    /**
     * @param string $username
     * @param string $password
     * @param array $config
     * @param mysqli $mysqli
     * @return User|bool
     */
    public static function checkLogin($username, $password, $config, $mysqli) {

        $type = $config["login"]["type"];

        // Load given login type class
        $login = LoginTypeFactory::create($type);

        // Check login details with given login type
        $user = $login::checkLogin($username, $password, $config);

        // If invalid user details, return false
        if(!$user)
            return false;

        // Load additional details from the database
        $user = DatabaseUser::loadDetails($user, $mysqli);

        // Store user details in the session
        $_SESSION["yacrs_user"] = $user->toArray();

        return $user;
    }

    public static function anonymousUserCreate($nickname, $mysqli) {

        // Random anonymous username
        $username = "anonymous-" . bin2hex(openssl_random_pseudo_bytes(32));

        // While username already exist, generate a new random username
        while(DatabaseUser::checkUserExists($username, $mysqli)) {
            $username = "anonymous-" . bin2hex(openssl_random_pseudo_bytes(32));
        }

        // If no nickname, just use name Guest
        if(!$nickname) {
            $givenName = "";
            $surname = "Guest";
        }

        // If no nickname, just use nickname followed by "(Guest)"
        else {
            $givenName = $nickname;
            $surname = "(Guest)";
        }

        // Create a new user
        $user = new User();
        $user->setUsername($username);
        $user->setGivenName($givenName);
        $user->setSurname("$surname");

        // Load additional details from the database
        $user = DatabaseUser::loadDetails($user, $mysqli);

        // Store user details in the session
        $_SESSION["yacrs_user"] = $user->toArray();

        return $user;
    }
}