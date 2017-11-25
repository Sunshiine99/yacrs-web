<?php

require_once('config.php');

// Create new instance of plates templating system
$templates = new League\Plates\Engine($CFG['templates']);

// Data for template
$data = [
    "CFG" => $CFG,
    "title" => $CFG['sitetitle'],
    "description" => $CFG['sitetitle'],
    "breadcrumbs" => new Breadcrumb(),
];

die($templates->render("template", $data));