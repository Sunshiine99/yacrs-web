<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $breadcrumbs Breadcrumb
 * @var $user User
 * @var $alert Alert
 * @var $sessions Session[]
 * @var $users User[]
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
    <script src="<?=$this->e($config["baseUrl"])?>js/admin/admin.js" crossorigin="anonymous"></script>
<?php $this->stop(); ?>

<div class="page-header">
    <h1>Admin</h1>
</div>

<ul class="nav nav-tabs" data-target="sections">
    <li class="nav-item" id="nav-sessions" data-target="section-sessions">
        <a class="nav-link active" href="#">Sessions</a>
    </li>
    <li class="nav-item" id="nav-users" data-target="section-users">
        <a class="nav-link" href="#">Users</a>
    </li>
</ul>
<div id="sections" class="sections">
    <div id="section-sessions" class="section">
        <?=$this->fetch("session/list", ["sessions" => $sessions, "user" => $user, "config" => $config])?>
    </div>
    <div id="section-users" class="section display-none">
        <ul class="list-group users-list">
            <?php // If user has sessions, display them ?>
            <?php if(sizeof($users) > 0): ?>
                <?php foreach($users as $u): ?>
                    <li class="list-group-item session-item" data-user-id="<?=$u->getId()?>">
                        <div class="pull-left">
                            <?=$u->getFullName()?><br>
                            <span class="text-muted">
                                @<?=$u->getUsername()?>
                            </span>
                        </div>
                        <div class="actions-confirm-delete width-xs-full">
                            <div class="btn-group pull-right actions width-xs-full" aria-label="Actions">
                                <?php if($u->isAdmin()): ?>
                                    <button type="button" class="btn btn-light btn-light-border width-xs-full admin-remove">
                                        Remove Admin
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-light btn-light-border width-xs-full admin-add">
                                        Make Admin
                                    </button>
                                <?php endif; ?>
                                <?php if($u->isSessionCreator()): ?>
                                    <button type="button" class="btn btn-light btn-light-border width-xs-full session-creator-remove">
                                        Remove Session Creator
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-light btn-light-border width-xs-full session-creator-add">
                                        Make Session Creator
                                    </button>
                                <?php endif; ?>
                                <button type="button" class="btn btn-light btn-light-border delete width-xs-full">
                                    <i class="fa fa-trash-o"></i> Delete
                                </button>
                            </div>
                            <div class="btn-group pull-right confirm-delete width-xs-full" aria-label="Confirm Delete">
                                <button type="button" class="btn btn-danger btn-danger-border confirm width-xs-full delete-user">
                                    <i class="fa fa-check"></i> Confirm
                                </button>
                                <button type="button" class="btn btn-light btn-light-border cancel width-xs-full">
                                    <i class="fa fa-times"></i> Cancel
                                </button>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>