<?php
$this->layout("template",
    [
        "config" => $config,
        "title" => $title,
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
    ]
);
?>

<?php $this->push("head"); ?>
    <link href="<?=$config["baseUrl"]?>/css/login.css" rel="stylesheet">
<?php $this->stop(); ?>

<form id="login" action="<?=$config["baseUrl"]?>login/anonymous/" method="post">
    <h1>Anonymous Guest Login</h1>
    <input name="nickname" id="nickname" class="form-control" placeholder="Nickname (Optional)" type="text">

    <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
</form>