<ul class="list-group session-list">
    <li class="no-sessions">
        No Sessions Found
    </li>
    <?php // If user has sessions, display them ?>
    <?php if(sizeof($sessions) > 0): ?>
        <?php foreach($sessions as $s): ?>
            <?php $created = strftime("%A %e %B %Y at %H:%M", $s->getCreated()); ?>
            <li class="list-group-item session-item">
                <div class="pull-left">
                    <span class="session-title">
                        <a href="<?=$config["baseUrl"]?>session/">
                            <?=$s->getTitle()?>
                        </a>
                    </span>
                    <span class="session-number">
                        <i class="fa fa-hashtag"></i><?=$s->getSessionID()?>
                    </span>
                    <span class="session-date text-muted">
                        Createdxx <?=$created?>
                    </span>
                </div>
                <div class="actions-confirm-delete">
                    <div class="btn-group pull-right actions" aria-label="Actions">
                        <button data-href="<?=$config["baseUrl"]?>session/<?=$s->getSessionID()?>/run/" type="button" class="btn btn-light btn-light-border" onclick="onclickHref(this)">
                            <i class="fa fa-play"></i> Run
                        </button>
                        <button data-href="<?=$config["baseUrl"]?>session/<?=$s->getSessionID()?>/edit/" type="button" class="btn btn-light btn-light-border" onclick="onclickHref(this)">
                            <i class="fa fa-pencil"></i> Edit
                        </button>
                        <button type="button" class="btn btn-light btn-light-border delete">
                            <i class="fa fa-trash-o"></i> Delete
                        </button>
                    </div>
                    <div class="btn-group pull-right confirm-delete" aria-label="Confirm Delete">
                        <button type="button" class="btn btn-danger btn-danger-border confirm" data-session-id="<?=$s->getSessionID()?>">
                            <i class="fa fa-check"></i> Confirm
                        </button>
                        <button type="button" class="btn btn-light btn-light-border cancel">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>