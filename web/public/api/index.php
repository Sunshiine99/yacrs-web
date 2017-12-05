<?php
require_once("../autoload.php");
;
Flight::set("data", []);
Flight::set("config", $config);

Flight::set("databaseConnect",
    function() use ($config) {

        // Attempt to connect to the database
        $mysqli = @mysqli_connect($config["database"]["host"], $config["database"]["username"], $config["database"]["password"], $config["database"]["name"]);

        // If error connecting to database, display error 500
        if (!$mysqli) {
            $templates = Flight::get("templates");
            $data = Flight::get("data");
            PageError::error500();
            die();
        }
        return $mysqli;
    }
);

//Flight::route("/", array("PageHome", "home"));
Flight::route("/login", array("ApiLogin", "login"));
Flight::route("/logout", array("ApiLogin", "logout"));
Flight::route("/session/", array("ApiSession", "listSessions"));
Flight::route("/session/@id:[0-9-]*/", array("ApiSession", "details"));


Flight::map('error', array("ApiError", "handler"));
Flight::map('notFound', array("ApiError", "notFound"));

Flight::start();