<?php
include("autoload.php");
session_start();

$path = $_GET["path"];

if(!isset($_SESSION["history"]))
    $_SESSION["history"] = [];

// If this is the previous page, remove the last item
else {

    // If this was the page before hte last page visited
    if($_SESSION["history"][count($_SESSION["history"])-2] == $path) {

        // Remove the last item
        array_pop($_SESSION["history"]);
    }

    // Otherwise, if this isn't the same page as last time (e.g. not a refresh_
    elseif($_SESSION["history"][count($_SESSION["history"])-1] != $path) {

        // Add new item to history
        array_push($_SESSION["history"], $_GET["path"]);
    }
}

function getLastPage() {
    if(count($_SESSION["history"]) > 2) {
        return $_SESSION["history"][count($_SESSION["history"])-2];
    }
    else {
        return "";
    }
}

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

Flight::route("POST /register/", array("PageLoginNative", "registerSubmit"));
Flight::route("/register/", array("PageLoginNative", "register"));
Flight::route("POST /changepassword/", array("PageLoginNative", "changePasswordSubmit"));
Flight::route("/changepassword/", array("PageLoginNative", "changePassword"));
Flight::route("/changepassword/@username/", array("PageLoginNative", "changePassword"));

/**************************************************************
 * Sessions
 **************************************************************/

Flight::route("/session/", array("PageSession", "sessions"));

Flight::route("POST /session/@id:[0-9]*/", array("PageSession", "viewSubmit"));
Flight::route("/session/@id:[0-9]*/", array("PageSession", "view"));

Flight::route("/session/live/", array("PageSessionLive", "live"));

Flight::route("/session/@id:[0-9]*/edit/", array("PageSessionEdit", "edit"));
Flight::route("/session/@id:[0-9]*/edit/export/", array("PageSessionExport", "export"));

Flight::route("POST /session/@id:[0-9]*/edit/properties/", array("PageSessionEditProperties", "submit"));
Flight::route("/session/@id:[0-9]*/edit/properties/", array("PageSessionEditProperties", "properties"));

Flight::route("/session/@sessionID:[0-9]*/edit/question/", array("PageSessionEdit", "question"));

Flight::route("/session/@sessionID:[0-9]*/edit/question/@sessionQuestionID:[0-9]*/response/", array("PageSessionEditQuestionResponse", "response"));
Flight::route("/session/@sessionID:[0-9]*/edit/question/@sessionQuestionID:[0-9]*/response/live/", array("PageSessionEditQuestionResponse", "live"));

Flight::route("POST /session/@sessionID:[0-9]*/edit/question/@questionID:[0-9]*/", array("PageSessionEditQuestion", "editSubmit"));
Flight::route("/session/@sessionID:[0-9]*/edit/question/@questionID:[0-9]*/", array("PageSessionEditQuestion", "edit"));

Flight::route("/session/@sessionID:[0-9]*/edit/question/@questionID:[0-9]*/screenshot/", array("PageSessionEditQuestion", "screenshot"));
Flight::route("/session/@sessionID:[0-9]*/edit/question/@questionID:[0-9]*/delete/", array("PageSessionEditQuestion", "delete"));

Flight::route("POST /session/@id:[0-9]*/edit/question/new/", array("PageSessionEditQuestion", "addSubmit"));
Flight::route("/session/@id:[0-9]*/edit/question/new/", array("PageSessionEditQuestion", "add"));

Flight::route("POST /session/join/", array("PageSessionJoin", "submit"));
Flight::route("/session/join/", array("PageSessionJoin", "join"));

// Add session submit
Flight::route("POST /session/new/", array("PageSessionNew", "submit"));
Flight::route("/session/new/", array("PageSessionNew", "add"));

/**************************************************************
 * Admin
 **************************************************************/
Flight::route("/admin/", array("PageAdmin", "admin"));

/**************************************************************
 * Help
 **************************************************************/
Flight::route("/help/", array("PageHelp","help"));


/**************************************************************
 * API
 **************************************************************/

Flight::route("/services.php", array("ApiLegacy", "api"));

Flight::map("notFound", array("PageError", "error404"));

Flight::start();