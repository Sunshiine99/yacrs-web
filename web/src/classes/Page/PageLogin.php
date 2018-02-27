<?php

class PageLogin
{

    public static function login() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");

        //$data["breadcrumbs"] = $breadcrumbs;
        echo $templates->render("login/login", $data);
    }

    public static function loginSubmit() {
        $config = Flight::get("config");

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Check this login
        $user = Login::checkLogin(
            $_POST["username"],
            $_POST["password"],
            $config,
            $mysqli);

        if($user === null) {
            PageError::error500("Error loading user");
            die();
        }

        // If invalid login
        if(!$user) {

            $alert = new Alert();
            $alert->setType("danger");
            $alert->setDismissable(true);
            $alert->setTitle("Error");
            $alert->setMessage("Invalid username or password");
            Alert::displayAlertSession($alert);

            // Forward to login
            header("Location: " . $config["baseUrl"] . "login/");
            die();
        }

        // Forward the user home
        header("Location: " . $config["baseUrl"]);
        die();
    }

    public static function anonymous() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Login", $config["baseUrl"] . "login/");
        $breadcrumbs->addItem("Anonymous");

        $data["breadcrumbs"] = $breadcrumbs;
        echo $templates->render("login/anonymous", $data);
    }

    public static function anonymousSubmit() {
        $config = Flight::get("config");

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Create an anonymous user
        Login::anonymousUserCreate($_POST["nickname"], $mysqli);

        // Forward the user home
        header("Location: " . $config["baseUrl"]);
        die();
    }

    public static function register() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // If not using native logins or registration has been disabled, display 404
        if($config["login"]["type"] !== "native" || !$config["login"]["register"]) {
            PageError::error404();
            die();
        }

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Login", $config["baseUrl"] . "login/");
        $breadcrumbs->addItem("Register");

        $data["breadcrumbs"] = $breadcrumbs;
        echo $templates->render("login/register", $data);
    }

    public static function registerSubmit() {
        $config = Flight::get("config");

        // If not using native logins or registration has been disabled, display 404
        if($config["login"]["type"] !== "native" || !$config["login"]["register"]) {
            PageError::error404();
            die();
        }

        // If passwords don't match
        if($_POST["password"] !== $_POST["verifyPassword"]) {

            $alert = new Alert();
            $alert->setType("danger");
            $alert->setDismissable(true);
            $alert->setTitle("Error");
            $alert->setMessage("Passwords did not match");
            Alert::displayAlertSession($alert);

            // Forward to register
            header("Location: " . $config["baseUrl"] . "register/");
            die();
        }

        // If username or password was not entered
        if(!$_POST["username"] || !$_POST["password"]) {

            $alert = new Alert();
            $alert->setType("danger");
            $alert->setDismissable(true);
            $alert->setTitle("Error");
            $alert->setMessage("Invalid username or password");
            Alert::displayAlertSession($alert);

            // Forward to register
            header("Location: " . $config["baseUrl"] . "register/");
            die();
        }

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        $register = DatabaseLogin::register($_POST["username"], $_POST["password"], $_POST["givenName"], $_POST["surname"], $_POST["email"], $mysqli);

        // If username already exists
        if($register === 100) {

            $alert = new Alert();
            $alert->setType("danger");
            $alert->setDismissable(true);
            $alert->setTitle("Error");
            $alert->setMessage("Username already exists");
            Alert::displayAlertSession($alert);

            // Forward to register
            header("Location: " . $config["baseUrl"] . "register/");
            die();
        }

        // If registration failed for another reason
        if(!$register) {

            $alert = new Alert();
            $alert->setType("danger");
            $alert->setDismissable(true);
            $alert->setTitle("Error");
            $alert->setMessage("Registration failed");
            Alert::displayAlertSession($alert);

            // Forward to register
            header("Location: " . $config["baseUrl"] . "register/");
            die();
        }

        // Otherwise, successful! So forward to login page
        $alert = new Alert();
        $alert->setType("success");
        $alert->setDismissable(true);
        $alert->setTitle("Success!");
        $alert->setMessage("You have been successfully registered");
        Alert::displayAlertSession($alert);
        header("Location: " . $config["baseUrl"] . "login/");
        die();
    }
}