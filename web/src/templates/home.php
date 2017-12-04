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
    <h2 class="page-section">
        Join a session
    </h2>
    <form method="POST" action="<?= $config["baseUrl"] ?>sessions/join/" class="form-inline">
        <div class="input-group">
            <input name="sessionID" id="sessionID" class="form-control" type="text" placeholder="Session ID">
            <span class="input-group-btn">
                <input name="submit" value="Join Session" class="btn btn-success" type="submit">
            </span>
        </div>
    </form>
    <br>
    <div id="box">
        <a class="btn btn-primary" href="<?= $config["baseUrl"] ?>sessions/new/">
            <i class="fa fa-plus-circle"></i> New Session
        </a>
    </div>
</div>


<h2 class="page-section">My sessions</h2>
<?= $this->fetch("sessions/list", ["sessions" => $sessions, "config" => $config]) ?>

<?php // If user can create sessions, show create new session button ?>
<?php if ($user->isSessionCreator()): ?>

<?php endif; ?>

<h2 class="page-section">My settings</h2>

<?php // If user is an admin, show a link to admin page ?>
<?php if ($user->isAdmin()): ?>
    <a href="<?= $config["baseUrl"] ?>admin/" class="btn btn-danger">
        <i class="fa fa-wrench"></i> YACRS administration
    </a>
<?php endif; ?>

