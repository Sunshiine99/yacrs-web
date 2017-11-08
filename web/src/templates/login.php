<?php
$this->layout("template",
    [
        "CFG" => $CFG,
        "title" => $title,
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
    ]
);
?>

<h1 style="text-align:center; padding:10px;">Login</h1>
<div id="loginBox">
    <div class="loginBox">
        <form method="POST" action="<?=$CFG["baseUrl"]?>" class="form-horizontal">
            <div class="form-group">
                <label for="uname" class="col-sm-4 control-label">Username</label>
                <div class="col-sm-8">
                    <input name="uname" id="uname" class="form-control" type="text">
                </div>
            </div>
            <div class="form-group">
                <label for="pwd" class="col-sm-4 control-label">Password</label>
                <div class="col-sm-8">
                    <input name="pwd" id="pwd" class="form-control" type="password">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-push-4">
                    <input name="submit" value="Log in" class="btn btn-block btn-success" type="submit">
                </div>
                <div class="col-sm-4 col-sm-push-4">
                    <a href="join.php" class="btn btn-link btn-block">Anonymous Guest Access</a>
                </div>
            </div>
        </form>
    </div>
</div>