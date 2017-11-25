<?php // If user has no sessions, tell them that ?>
<?php if(sizeof($sessions) == 0): ?>
    <p>No sessions found</p>

    <?php // Otherwise, list the sessions ?>
<?php else: ?>
    <ul class="session-list">
        <?php foreach($sessions as $s): ?>
            <?php //$s = new Session(); ?>
            <?php $ctime = strftime("%A %e %B %Y at %H:%M", $s->getCreated()); ?>
            <li>
                <p class='session-title'>
                    <a href='<?=$config["baseUrl"]?>sessions/<?=$s->getSessionID()?>/'><?=$s->getTitle()?></a>
                    <span class='user-badge session-id'>
                            <i class='fa fa-hashtag'></i> <?=$s->getSessionID()?>
                        </span>
                </p>
                <p class='session-details'> Created <?=$ctime?></p>
                <span class='feature-links'>
                        <a href='<?=$config["baseUrl"]?>sessions/<?=$s->getSessionID()?>/run/'>
                            <i class='fa fa-play'></i> Run
                        </a>
                        <a href='<?=$config["baseUrl"]?>sessions/<?=$s->getSessionID()?>/edit/'>
                            <i class='fa fa-pencil'></i> Edit
                        </a>
                        <a href='<?=$config["baseUrl"]?>sessions/<?=$s->getSessionID()?>/delete/'>
                            <i class='fa fa-trash-o'></i> Delete
                        </a>
                    </span>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>