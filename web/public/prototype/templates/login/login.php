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

<?php $this->push("head"); ?>
    <link href="<?=$config["baseUrl"]?>/css/login.css" rel="stylesheet">
<?php $this->stop(); ?>

<form id="login" action="<?=$config["baseUrl"]?>" method="post">
    <h1>Login</h1>
    <input id="username" name="username" class="form-control" placeholder="Username" type="text">
    <input id="password" name="password" class="form-control" placeholder="Password" type="password">

    <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
    <div id="anonymous">
        <a href="<?=$config["baseUrl"]?>login/anonymous/">Anonymous Guess Access</a>
    </div>
</form>