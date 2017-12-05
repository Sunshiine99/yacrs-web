<?php

$config["title"] = "YACRS";

$config["baseUrl"] = getenv("BASE_URL");

$config["login"]["type"] = "any";

// Get database details from docker
$config["database"]["host"]     = getenv("MYSQL_HOST");
$config["database"]["username"] = getenv("MYSQL_USER");
$config["database"]["password"] = getenv("MYSQL_PASSWORD");
$config["database"]["name"]     = getenv("MYSQL_DATABASE");
