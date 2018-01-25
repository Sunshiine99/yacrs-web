<?php
$this->layout("base",
    [
        "config" => $config,
        "title" => $title,
        "description" => $description,
        "user" => $user,
        "logo" => "img/logo.png"
    ]
);
?>
<?=$this->section("content")?>