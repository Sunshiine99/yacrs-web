<?php

$config["title"] = "YACRS";

$config["baseUrl"] = "http://127.0.0.1:4000/";

$config["login"]["type"] = "any";

// Get database details from docker
$config["database"]["host"]     = getenv("MYSQL_HOST");
$config["database"]["username"] = getenv("MYSQL_USER");
$config["database"]["password"] = getenv("MYSQL_PASSWORD");
$config["database"]["name"]     = getenv("MYSQL_DATABASE");