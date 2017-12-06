<?php
$this->layout("base",
    [
        "config" => $config,
        "title" => $title,
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user,
        "alert" => $alert,
        "logo" => "img/logo.png"
    ]
);
?>
<?=$this->section("content")?>