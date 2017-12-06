<?php
$config = [];

$config["version"]                       = "2.0.0indev";
$config["title"]                         = "YACRS";
$config["baseUrl"]                       = isset($_SERVER['HTTPS']) ? "https" : "http" . "://" . $_SERVER['HTTP_HOST'] . "/";
$config["login"]["type"]                 = "any";


// Get database details from docker
$config["database"]["host"]              = getenv("MYSQL_HOST");
$config["database"]["username"]          = getenv("MYSQL_USER");
$config["database"]["password"]          = getenv("MYSQL_PASSWORD");
$config["database"]["name"]              = getenv("MYSQL_DATABASE");

// Basic LDAP details
$config["ldap"]["host"]                  = "130.209.13.173";
$config["ldap"]["context"]               = "o=Gla";

// Details used for LDAP bind
//$config["ldap"]["bind"]["user"] = "";
//$config["ldap"]["bind"]["pass"] = "";

// LDAP fields and values that result in sessionCreator (teacher) status
$config["ldap"]["sessionCreatorRules"]   = array();
$config["ldap"]["sessionCreatorRules"][] = array("field" => "dn", "contains" => "ou=staff");
$config["ldap"]["sessionCreatorRules"][] = array("field" => "homezipcode", "match" => "PGR");
$config["ldap"]["sessionCreatorRules"][] = array("field" => "uid", "regex" => "/^[a-z]{2,3}[0-9]+[a-z]$/");
//$config["ldap"]["sessionCreatorRules"][] = array('field'=>'mail', 'regex'=>'/[a-zA-Z]+\.[a-zA-Z]+.*?@glasgow\.ac\.uk/');