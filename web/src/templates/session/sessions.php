<?php
$this->layout("template",
    [
        "config" => $config,
        "title" => $title,
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user
    ]
);
?>

<h1 class="page-section">My sessions</h1>
<?=$this->fetch("session/list", ["sessions"=>$sessions, "config"=>$config])?>