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
    <link rel="icon" href="<?=$this->e($config["baseUrl"])?>img/uofg/favicon.ico">
<?php $this->stop(); ?>

<?=$this->section("content")?>