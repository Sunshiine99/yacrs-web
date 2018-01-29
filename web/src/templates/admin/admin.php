<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $breadcrumbs Breadcrumb
 * @var $user User
 * @var $alert Alert
 * @var $sessions Session[]
 */
$this->layout("template",
    [
        "config" => $config,
        "title" => $title,
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user,
        "alert" => $alert
    ]
);
?>

<?php $this->push("end"); ?>
    <script src="<?=$this->e($config["baseUrl"])?>js/session/list.js" crossorigin="anonymous"></script>
<?php $this->stop(); ?>

<?=$this->fetch("session/list", ["sessions" => $sessions, "user" => $user, "config" => $config])?>