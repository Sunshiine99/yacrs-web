<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $breadcrumbs Breadcrumb
 * @var $user User
 * @var $alert Alert
 */
$this->layout("template",
    [
        "config" => $config,
        "title" => "Login",
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user,
        "alert" => $alert
    ]
);
?>

<?php $this->push("head"); ?>
    <link href="<?=$this->e($config["baseUrl"])?>css/login.css" rel="stylesheet">
<?php $this->stop(); ?>

<form id="login" action="<?=$this->e($config["baseUrl"])?>login/" method="post">
    <h1>Login</h1>
    <input id="username" name="username" class="form-control" placeholder="Username" type="text">
    <input id="password" name="password" class="form-control" placeholder="Password" type="password">

    <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
    <div id="anonymous">
        <a href="<?=$this->e($config["baseUrl"])?>login/anonymous/">Anonymous Guess Access</a>
    </div>
</form>
