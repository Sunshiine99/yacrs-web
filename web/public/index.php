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

// Default page description
$data["description"] = "YACRS (Yet Another Class Response System) is a classroom interaction system that allows students
                        to use their own devices to respond to questions during class";
$data["config"] = $config;
Flight::set("data", $data);

/**
 * Define a function that can connect to the database. This can be assessed by calling Flight::get("databaseConnect")
 */
Flight::set("databaseConnect",
    function() use ($config) {

        // Attempt to connect to the database
        try {
            $mysqli = new mysqli($config["database"]["host"], $config["database"]["username"], $config["database"]["password"], $config["database"]["name"]);
        }

        catch (Exception $e) {
            error_log($e->getMessage());
            PageError::error500();

            PageError::generic(
                "Database Connection Error",
                null,
                500,
                false);

            exit;
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

Flight::route("/session/", array("PageSession", "sessions"));

Flight::route("POST /session/@id:[0-9]*/", array("PageSession", "viewSubmit"));
Flight::route("/session/@id:[0-9]*/", array("PageSession", "view"));

Flight::route("/session/@id:[0-9]*/run/", array("PageSessionRun", "run"));
Flight::route("/session/@id:[0-9]*/run/class/", array("PageSessionRun", "classMode"));
Flight::route("/session/@id:[0-9]*/run/export/", array("PageSessionExport", "export"));


Flight::route("/session/@sessionID:[0-9]*/run/question/@sessionQuestionID:[0-9]*/response/", array("PageSessionRunQuestionResponse", "response"));

Flight::route("POST /session/@sessionID:[0-9]*/run/question/@questionID:[0-9]*/", array("PageSessionRunQuestion", "editSubmit"));
Flight::route("/session/@sessionID:[0-9]*/run/question/@questionID:[0-9]*/", array("PageSessionRunQuestion", "edit"));

Flight::route("/session/@sessionID:[0-9]*/run/question/@questionID:[0-9]*/delete/", array("PageSessionRunQuestion", "delete"));

Flight::route("POST /session/@id:[0-9]*/run/question/new/", array("PageSessionRunQuestion", "addSubmit"));
Flight::route("/session/@id:[0-9]*/run/question/new/", array("PageSessionRunQuestion", "add"));

Flight::route("POST /session/join/", array("PageSessionJoin", "submit"));
Flight::route("/session/join/", array("PageSessionJoin", "join"));

Flight::route("POST /session/@id:[0-9]*/edit/", array("PageSessionEdit", "submit"));
Flight::route("/session/@id:[0-9]*/edit/", array("PageSessionEdit", "edit"));

// Add session submit
Flight::route("POST /session/new/", array("PageSessionNew", "submit"));
Flight::route("/session/new/", array("PageSessionNew", "add"));

/**************************************************************
 * API
 **************************************************************/

Flight::route("/services.php", array("ApiLegacy", "api"));

Flight::map("notFound", array("PageError", "error404"));

Flight::start();