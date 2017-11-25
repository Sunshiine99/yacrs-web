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

<div id="box">
    <h2 class="page-section">Join a session</h2>
    <form method="POST" action="<?=$config["baseUrl"]?>sessions/join/" class="form-horizontal">
        <div class="form-group">
            <label for="sessionID" class="col-sm-4 control-label">Session number</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <input name="sessionID" id="sessionID" class="form-control" type="text">
                    <span class="input-group-btn">
                        <input name="submit" value="Join Session" class="btn btn-success" type="submit">
                    </span>
                </div>
            </div>
        </div>
    </form>
</div>

<?php // If user can create sessions, show create new session button ?>
<?php if($user->isSessionCreator()):?>
    <div class="row">
        <div class="col-sm-8 col-sm-push-4">
            <a class="btn btn-primary" href="<?=$config["baseUrl"]?>sessions/new/">
                <i class="fa fa-plus-circle"></i> Create a new clicker session
            </a>
        </div>
    </div>
<?php endif; ?>

<h2 class="page-section">My sessions</h2>
<?=$this->fetch("sessions/list", ["sessions"=>$sessions, "config"=>$config])?>

<h2 class="page-section">My settings</h2>

<?php // If user is an admin, show a link to admin page ?>
<?php if($user->isAdmin()):?>
    <a href="<?=$config["baseUrl"]?>admin/" class="btn btn-danger">
        <i class="fa fa-wrench"></i> YACRS administration
    </a>
<?php endif; ?>

