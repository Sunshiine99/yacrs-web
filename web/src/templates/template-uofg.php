<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $breadcrumbs Breadcrumb
 * @var $user User
 * @var $alert Alert
 * @var $noHeaderFooter bool
 */
$this->layout("base",
    [
        "config" => $config,
        "title" => $title,
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user,
        "alert" => $alert,
        "logo" => "img/uofg/logo-retina.png",
        "footer" => "partials/footer-uofg",
        "noHeaderFooter" => $noHeaderFooter
    ]
);
?>
<?php $this->push("head"); ?>
    <link href="<?=$this->e($config["baseUrl"])?>css/style-uofg.css" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="16x16" href="<?=$this->e($config["baseUrl"])?>img/uofg/icon-16x16.png">
    <link rel="apple-touch-icon" sizes="32x32" href="<?=$this->e($config["baseUrl"])?>img/uofg/icon-16x16.png">
    <link rel="apple-touch-icon" sizes="64x64" href="<?=$this->e($config["baseUrl"])?>img/uofg/icon-16x16.png">
    <link rel="apple-touch-icon" sizes="128x128" href="<?=$this->e($config["baseUrl"])?>img/uofg/icon-128x128.png">
    <link rel="apple-touch-icon" sizes="256x256" href="<?=$this->e($config["baseUrl"])?>img/uofg/icon-256x256.png">
    <link rel="apple-touch-icon" sizes="512x512" href="<?=$this->e($config["baseUrl"])?>img/uofg/icon-512x512.png">

    <link rel="icon" href="<?=$this->e($config["baseUrl"])?>img/uofg/favicon.ico">
<?php $this->stop(); ?>

<?=$this->section("content")?>