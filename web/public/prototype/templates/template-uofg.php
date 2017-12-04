<?php
$this->layout("base",
    [
        "config" => $config,
        "title" => $title,
        "description" => $description,
        "user" => $user,
        "logo" => "img/uofg/logo.png",
        "footer" => "partials/footer-uofg"
    ]
);
?>
<?php $this->push("head"); ?>
    <link href="<?=$config["baseUrl"]?>css/style-uofg.css" rel="stylesheet">
    <link rel="icon" href="<?=$config["baseUrl"]?>img/uofg/favicon.ico">
<?php $this->stop(); ?>

<?=$this->section("content")?>