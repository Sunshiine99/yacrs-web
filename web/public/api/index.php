<?php
require_once("../../src/autoload.php");

//Flight::route("/", array("PageHome", "home"));
Flight::route("/login", array("ApiLogin", "login"));
Flight::route("/logout", array("ApiLogin", "logout"));
Flight::route("/session/", array("ApiSessions", "listSessions"));

Flight::map('error', array("ApiError", "handler"));
Flight::map('notFound', array("ApiError", "notFound"));

Flight::start();