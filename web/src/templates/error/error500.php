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

<div class="error-container">
    <h1>Error 500: Internal Server Error</h1>
    <p class="lead">
        The server ran into a problem whilst carrying out your request.
    </p>
    <p class="lead">
        You can either <a href="javascript:history.back()">go back</a> and try again or return to the <a href="<?=$this->e($config["baseUrl"])?>">home page</a>
    </p>
</div>