<?php
$config = [];

$config["version"]                       = "2.0.0indev";
$config["title"]                         = "YACRS";
$config["baseUrl"]                       = isset($_SERVER['HTTPS']) ? "https" : "http" . "://" . $_SERVER['HTTP_HOST'] . "/";
$config["login"]["type"]                 = "some";

// Whether user's can register new accounts (Only for native login system)
$config["login"]["register"]             = true;

$config["datetime"]["date"]["short"]     = "d/m/y";
$config["datetime"]["date"]["long"]      = "d F Y \\a\\t";
$config["datetime"]["time"]["short"]     = "H:i";
$config["datetime"]["time"]["long"]      = "H:i";
$config["datetime"]["datetime"]["short"] = $config["datetime"]["date"]["short"] . " " . $config["datetime"]["time"]["short"];
$config["datetime"]["datetime"]["long"]  = $config["datetime"]["date"]["long"] . " " . $config["datetime"]["time"]["long"];

// Get database details from docker
$config["database"]["host"]              = getenv("MYSQL_HOST");
$config["database"]["username"]          = getenv("MYSQL_USER");
$config["database"]["password"]          = getenv("MYSQL_PASSWORD");
$config["database"]["name"]              = getenv("MYSQL_DATABASE");

// Basic LDAP details
$config["ldap"]["host"]                  = "130.209.13.173";
$config["ldap"]["context"]               = "o=Gla";

// Manual username password combos. Used for initial setting up of admin users.
$config["user"]["users"]["admin"]        = "password";

// Users who should always be admin
$config["user"]["admin"][0]               = "admin";
$config["user"]["admin"][1]               = "2198207s";

// Details used for LDAP bind
//$config["ldap"]["bind"]["user"] = "";
//$config["ldap"]["bind"]["pass"] = "";

// LDAP fields and values that result in sessionCreator (teacher) status
$config["ldap"]["sessionCreatorRules"]   = array();
$config["ldap"]["sessionCreatorRules"][] = array("field" => "dn", "contains" => "ou=staff");
$config["ldap"]["sessionCreatorRules"][] = array("field" => "homezipcode", "match" => "PGR");
$config["ldap"]["sessionCreatorRules"][] = array("field" => "uid", "regex" => "/^[a-z]{2,3}[0-9]+[a-z]$/");
//$config["ldap"]["sessionCreatorRules"][] = array('field'=>'mail', 'regex'=>'/[a-zA-Z]+\.[a-zA-Z]+.*?@glasgow\.ac\.uk/');