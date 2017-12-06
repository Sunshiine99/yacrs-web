<?php
$this->layout("base",
    [
        "config" => $config,
        "title" => $title,
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user,
        "logo" => "img/uofg/logo-retina.png",
        "footer" => "partials/footer-uofg"
    ]
);
?>
<?php $this->push("head"); ?>
    <link href="<?=$config["baseUrl"]?>css/style-uofg.css" rel="stylesheet">
    <link rel="icon" href="<?=$config["baseUrl"]?>img/uofg/favicon.ico">
<?php $this->stop(); ?>

<?=$this->section("content")?>