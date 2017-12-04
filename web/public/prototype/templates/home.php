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

<?php $this->push("end"); ?>
    <script src="<?=$config["baseUrl"]?>js/session/list.js" crossorigin="anonymous"></script>
<?php $this->stop(); ?>

<?php $this->push("preContent"); ?>
<div class="jumbotron text-center">
    <div class="container">
        <h1 class="display-3">YACRS</h1>
        <p class="lead">
            Yet Another Class Response System
        </p>
        <form method="POST" action="<?=$config["baseUrl"]?>session/1/" class="form-inline">
            <div class="input-group element-center">
                <input name="sessionID" id="sessionID" class="form-control" placeholder="Session Number"
                       type="text">
                <span class="input-group-btn">
                    <input name="submit" value="Join Session" class="btn btn-success btn-lg" type="submit">
                </span>
            </div>
        </form>
    </div>
</div>
<?php $this->stop(); ?>

<div style="display:block;">
    <div class="float-left">
        <h1>My Sessions</h1>
    </div>
    <div class="float-right">
        <a href="<?=$config["baseUrl"]?>session/new/" class="btn btn-primary">New Session</a>
    </div>
</div>
<p id="no-sessions" class="lead">
    No sessions found
</p>
<div>
    <ul class="list-group session-list">
        <?php
        for($i=1; $i<6; $i++):
            $time = time();
            $time = rand($time-60*60*24*31*12*2, $time);
            $created = date("d/m/Y H:i", $time);
            ?>
            <li class="list-group-item session-item">
                <div class="pull-left">
                    <span class="session-title">
                        <a href="#">Test Session <?=$i?></a>
                    </span>
                    <span class="session-number">
                        <i class="fa fa-hashtag"></i><?=$i?>
                    </span>
                    <span class="session-date text-muted">
                        Created <?=$created?>
                    </span>
                </div>
                <div class="actions-confirm-delete">
                    <div class="btn-group pull-right actions" aria-label="Actions">
                        <button data-href="<?=$config["baseUrl"]?>session/1/run/" type="button" class="btn btn-light btn-light-border" onclick="onclickHref(this)">
                            <i class="fa fa-play"></i> Run
                        </button>
                        <button data-href="<?=$config["baseUrl"]?>session/new/" type="button" class="btn btn-light btn-light-border" onclick="onclickHref(this)">
                            <i class="fa fa-pencil"></i> Edit
                        </button>
                        <button type="button" class="btn btn-light btn-light-border delete">
                            <i class="fa fa-trash-o"></i> Delete
                        </button>
                    </div>
                    <div class="btn-group pull-right confirm-delete" aria-label="Confirm Delete">
                        <button type="button" class="btn btn-danger btn-danger-border confirm">
                            <i class="fa fa-check"></i> Confirm
                        </button>
                        <button type="button" class="btn btn-light btn-light-border cancel">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                    </div>
                </div>
            </li>
        <?php endfor; ?>
    </ul>
</div>