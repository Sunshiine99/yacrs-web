<?php
include("autoload.php");

session_start();

// Enable error logging
Flight::set('flight.log_errors', true);

Flight::set("config", $config);
Flight::set("templates", new League\Plates\Engine(dirname(__FILE__)."/../src/templates/"));
Flight::set("data", ["config" => $config]);
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

Flight::route("/", array("PageHome", "home"));

Flight::route("POST /login/", array("PageLogin", "loginSubmit"));
Flight::route("/login/", array("PageLogin", "login"));
Flight::route("POST /login/anonymous/", array("PageLogin", "anonymousSubmit"));
Flight::route("/login/anonymous/", array("PageLogin", "anonymous"));
Flight::route("/logout/", array("PageLogout", "logout"));

/**************************************************************
 * Sessions
 **************************************************************/

Flight::route("/sessions/", array("PageSessions", "sessions"));

Flight::route("/sessions/@id:[0-9]*/", array("PageSessions", "view"));

Flight::route("POST /sessions/@id:[0-9]*/run/", array("PageSessionsRun", "runSubmit"));
Flight::route("/sessions/@id:[0-9]*/run/", array("PageSessionsRun", "run"));

Flight::route("POST /sessions/@sessionID:[0-9]*/run/questions/@questionID:[0-9]*/", array("PageSessionsRunQuestions", "editSubmit"));
Flight::route("/sessions/@sessionID:[0-9]*/run/questions/@questionID:[0-9]*/", array("PageSessionsRunQuestions", "edit"));

Flight::route("POST /sessions/@id:[0-9]*/run/questions/new/", array("PageSessionsRunQuestions", "addSubmit"));
Flight::route("/sessions/@id:[0-9]*/run/questions/new/", array("PageSessionsRunQuestions", "add"));

Flight::route("POST /sessions/join/", array("PageSessionsJoin", "submit"));
Flight::route("/sessions/join/", array("PageSessionsJoin", "join"));

Flight::route("POST /sessions/@id:[0-9]*/edit/", array("PageSessionsEdit", "submit"));
Flight::route("/sessions/@id:[0-9]*/edit/", array("PageSessionsEdit", "edit"));

// Add session submit
Flight::route("POST /sessions/new/", array("PageSessionsNew", "submit"));
Flight::route("/sessions/new/", array("PageSessionsNew", "add"));

Flight::start();
