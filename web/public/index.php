<?php
include("autoload.php");
session_start();

// Enable error logging
Flight::set('flight.log_errors', true);

Flight::set("config", $config);
Flight::set("templates", new League\Plates\Engine(dirname(__FILE__)."/../src/templates/"));


// If an alert is in the session
if(isset($_SESSION["yacrs_alert"])) {

    // If the session has expired, remove the alert from the session
    if((isset($_SESSION["yacrs_alert"]["expire"]) ? $_SESSION["yacrs_alert"]["expire"] : 0) < time()) {
        unset($_SESSION["yacrs_alert"]);
    }

    // Otherwise, add the alert to the page data
    else {
        $data["alert"] = new Alert($_SESSION["yacrs_alert"]["alert"]);
        unset($_SESSION["yacrs_alert"]);
    }
}

$data["config"] = $config;
Flight::set("data", $data);

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

Flight::route("/session/", array("PageSessions", "sessions"));

Flight::route("POST /session/@id:[0-9]*/", array("PageSessions", "viewSubmit"));
Flight::route("/session/@id:[0-9]*/", array("PageSessions", "view"));

Flight::route("POST /session/@id:[0-9]*/run/", array("PageSessionsRun", "runSubmit"));
Flight::route("/session/@id:[0-9]*/run/", array("PageSessionsRun", "run"));

Flight::route("POST /session/@sessionID:[0-9]*/run/question/@questionID:[0-9]*/", array("PageSessionsRunQuestions", "editSubmit"));
Flight::route("/session/@sessionID:[0-9]*/run/question/@questionID:[0-9]*/", array("PageSessionsRunQuestions", "edit"));

Flight::route("POST /session/@id:[0-9]*/run/question/new/", array("PageSessionsRunQuestions", "addSubmit"));
Flight::route("/session/@id:[0-9]*/run/question/new/", array("PageSessionsRunQuestions", "add"));

Flight::route("POST /session/join/", array("PageSessionsJoin", "submit"));
Flight::route("/session/join/", array("PageSessionsJoin", "join"));

Flight::route("POST /session/@id:[0-9]*/edit/", array("PageSessionsEdit", "submit"));
Flight::route("/session/@id:[0-9]*/edit/", array("PageSessionsEdit", "edit"));

// Add session submit
Flight::route("POST /session/new/", array("PageSessionsNew", "submit"));
Flight::route("/session/new/", array("PageSessionsNew", "add"));

/**************************************************************
 * API
 **************************************************************/

Flight::route("/services.php", array("ApiLegacy", "api"));


Flight::start();
