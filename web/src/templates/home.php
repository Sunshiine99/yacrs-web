<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $breadcrumbs Breadcrumb
 * @var $user User
 * @var $alert Alert
 * @var $sessions Session[]
 */
$this->layout("template",
    [
        "config" => $config,
        "title" => $title,
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user,
        "alert" => $alert
    ]
);
?>

<?php $this->push("end"); ?>
    <script src="<?=$this->e($config["baseUrl"])?>js/session/list.js" crossorigin="anonymous"></script>
<?php $this->stop(); ?>

<?php $this->push("preContent"); ?>
<div class="jumbotron text-center">
    <div class="container">
        <h1 class="display-3">YACRS</h1>
        <p class="lead">
            Yet Another Class Response System
        </p>
        <form method="POST" action="<?=$this->e($config["baseUrl"])?>session/join/" class="form-inline">
            <div class="input-group element-center">
                <input name="sessionID" id="sessionID" class="form-control" placeholder="Session Number"
                       type="text">
                <span class="input-group-btn">
                    <input name="submit" value="Join Session" class="btn btn-primary btn-lg" type="submit">
                </span>
            </div>
        </form>
    </div>
</div>
<?php $this->stop(); ?>

<div class="row">
    <div class="col-sm-12">
        <div class="float-left">
            <h1>My Sessions</h1>
        </div>
        <?php if($user->isSessionCreator() || $user->isAdmin()): ?>
            <div class="float-right">
                <a href="<?=$this->e($config["baseUrl"])?>session/new/" class="btn btn-primary">New Session</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?=$this->fetch("session/list", ["sessions" => $sessions, "user" => $user, "config" => $config])?>