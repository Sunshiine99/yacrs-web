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

<h2 class="page-section">My sessions</h2>
<?=$this->fetch("sessions/list", ["sessions"=>$sessions, "config"=>$config])?>