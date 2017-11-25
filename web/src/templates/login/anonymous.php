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
<h2 class="page-section">Anonymous Guest Login</h2>
<div id="loginBox">
    <div class="loginBox">
        <form method="POST" action="<?=$config["baseUrl"]?>login/anonymous/" class="form-horizontal">
            <div class="form-group">
                <label for="nickname" class="col-sm-4 control-label">Nickname (Optional)</label>
                <div class="col-sm-8">
                    <input name="nickname" id="nickname" class="form-control" type="text">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-push-4">
                    <input name="submit" value="Log in" class="btn btn-block btn-success" type="submit">
                </div>
            </div>
        </form>
    </div>
</div>