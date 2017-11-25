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

<h1 style="text-align:center; padding:10px;">Login</h1>
<div id="loginBox">
    <div class="loginBox">
        <form method="POST" action="<?=$config["baseUrl"]?>login/" class="form-horizontal">
            <div class="form-group">
                <label for="username" class="col-sm-2 control-label">Username</label>
                <div class="col-sm-10">
                    <input name="username" id="username" class="form-control" type="text">
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-10">
                    <input name="password" id="password" class="form-control" type="password">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3 col-sm-push-2">
                    <input name="submit" value="Log in" class="btn btn-block btn-success" type="submit">
                </div>
                <div class="col-sm-3 col-sm-push-2">
                    <a href="<?=$config["baseUrl"]?>login/anonymous/" class="btn btn-link btn-block">Anonymous Guest Access</a>
                </div>
            </div>
        </form>
    </div>
</div>