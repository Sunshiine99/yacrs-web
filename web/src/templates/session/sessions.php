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
        "title" => "Sessions",
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user
    ]
);
?>

<h1 class="page-section">My Sessions</h1>
<?=$this->fetch("session/list", ["sessions"=>$sessions, "user" => $user, "config"=>$config])?>