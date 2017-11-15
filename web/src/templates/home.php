<?php
$this->layout("template",
    [
        "CFG" => $CFG,
        "title" => $title,
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "uinfo" => $uinfo,
    ]
);
?>

<div id="box">
    <h2 class="page-section">Join a session</h2>
    <form method="POST" action="vote.php" class="form-horizontal">
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
<?php if($uinfo['sessionCreator']):?>
    <div class="row">
        <div class="col-sm-8 col-sm-push-4">
            <a class="btn btn-primary" href="editsession.php">
                <i class="fa fa-plus-circle"></i> Create a new clicker session
            </a>
        </div>
    </div>
<?php endif; ?>

<?php // If user can create sessions, show the user's sessions ?>
<?php if($uinfo['sessionCreator']):?>
    <h2 class="page-section">My sessions (staff)</h2>

    <?php // If user has no sessions, tell them that ?>
    <?php if(sizeof($staffSessions) == 0): ?>
        <p>No sessions found</p>

    <?php // Otherwise, list the sessions ?>
    <?php else: ?>
        <ul class="session-list">

            <?php foreach($staffSessions as $s): ?>
                <?php $ctime = strftime("%A %e %B %Y at %H:%M", $s->created); ?>
                <li>
                    <p class='session-title'>
                        <a href='runsession.php?sessionID=<?=$s->id?>'><?=$s->title?></a>
                        <span class='user-badge session-id'>
                            <i class='fa fa-hashtag'></i> <?=$s->id?>
                        </span>
                    </p>
                    <p class='session-details'> Created <?=$ctime?></p>
                    <span class='feature-links'>
                        <a href='editsession.php?sessionID=<?=$s->id?>'>
                            <i class='fa fa-pencil'></i> Edit
                        </a>
                        <a href='confirmdelete.php?sessionID=<?=$s->id?>'>
                            <i class='fa fa-trash-o'></i> Delete
                        </a>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<?php endif; ?>

<h2 class="page-section">My sessions</h2>

<?php // If user has no sessions, tell them that ?>
<?php if(sizeof($sessions) == 0): ?>
    <p>No sessions found</p>

<?php // Otherwise, list the sessions ?>
<?php else: ?>
    <ul>
        <?php foreach($sessions as $s): ?>
            <li><a href='vote.php?sessionID=<?=$s->id?>'><?=$s->title?></a>
                <?php // If this sessions can be reviewed ?>
                <?php if((isset($s->extras['allowFullReview']))&&($s->extras['allowFullReview'])): ?>
                     (<a href='review.php?sessionID=<?=$s->id?>'>Review previous answers</a>)
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>

<h2 class="page-section">My settings</h2>

<?php // If sms is setup
    if((isset($CFG['smsnumber']))&&(strlen($CFG['smsnumber']))) {

        // Add SMS details if so
        $code = substr(md5($CFG['cookiehash'].$user->username),0,4);
        if(strlen($user->phone))
            echo "<p>Current phone for SMS: {$user->phone}</p>";

        echo "<p>To associate a phone with your username text \"link {$user->username} $code\" (without quotes) to {$CFG['smsnumber']}.</p>";
    }
?>

<?php // If user is an admin, show a link to admin page ?>
<?php if($uinfo['isAdmin']):?>
    <a href="admin.php" class="btn btn-danger">
        <i class="fa fa-wrench"></i> YACRS administration
    </a>
<?php endif; ?>
