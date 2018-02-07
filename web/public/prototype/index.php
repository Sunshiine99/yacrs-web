<?php
require_once("autoload.php");

// Set variables for access in page classes
Flight::set("templates", new League\Plates\Engine(dirname(__FILE__)."/templates/"));
Flight::set("config", $config);


Flight::route("/", array("Demo", "home"));
Flight::route("/login/", array("Demo", "login"));
Flight::route("/session/1/", array("Demo", "sessionView"));
Flight::route("/session/1/edit/", array("Demo", "sessionRun"));
Flight::route("/session/new/", array("Demo", "sessionNew"));

Flight::start();