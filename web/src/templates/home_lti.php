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

<ul>
    <?php if($s !== false): ?>
        <?php $ctime = strftime("%A %e %B %Y at %H:%M", $s->created); ?>
            <li>
                <p class='session-title'>
                    <a href='runsession.php?sessionID=<?=$s->id?>'><?=$s->title?></a>
                    <span class='user-badge session-id'><i class='fa fa-hashtag'></i> <?=$s->id?></span
                </p>
                <p class='session-details'> Created <?=$ctime?></p>
                <a href='editsession.php?sessionID=<?=$s->id?>'>Edit</a>
                <a href='confirmdelete.php?sessionID=<?=$s->id?>'>Delete</a>
            </li>
            <li>
                To use the teacher control app for this session login with username: <b><?=$s->id?></b> and password <b><?=substr($s->ownerID, 0, 8)?></b>
            </li>
    <?php else: ?>
        <li>No session found for this LTI link. To create a new session return to the VLE/LMS and click the link again.</li>
    <?php endif; ?>
</ul>