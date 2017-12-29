<?php
/**
 * @var $config array
 * @var $user User
 * @var $sessions Session[]
 */
?>
<ul class="list-group session-list">
    <li class="no-sessions">
        No Sessions Found
    </li>
    <?php // If user has sessions, display them ?>
    <?php if(sizeof($sessions) > 0): ?>
        <?php foreach($sessions as $s): ?>
            <?php $created = date($config["datetime"]["datetime"]["long"], $s->getCreated()); ?>
            <li class="list-group-item session-item">
                <div class="pull-left">
                    <span class="session-title">
                        <a href="<?=$this->e($config["baseUrl"])?>session/<?=$this->e($s->getSessionID())?>/">
                            <?=$this->e($s->getTitle())?>
                        </a>
                    </span>
                    <span class="session-number">
                        <i class="fa fa-hashtag"></i><?=$this->e($s->getSessionID())?>
                    </span>
                    <span class="session-date text-muted">
                        Created <?=$this->e($created)?>
                    </span>
                </div>
                <?php
                // If the user can edit this session, view edit controls
                if($s->checkIfUserCanEdit($user)):
                ?>

                    <div class="actions-confirm-delete width-xs-full">
                        <div class="btn-group pull-right actions width-xs-full" aria-label="Actions">
                            <button data-href="<?=$this->e($config["baseUrl"])?>session/<?=$this->e($s->getSessionID())?>/run/" type="button" class="btn btn-light btn-light-border width-xs-full" onclick="onclickHref(this)">
                                <i class="fa fa-play"></i> Run
                            </button>
                            <button data-href="<?=$this->e($config["baseUrl"])?>session/<?=$this->e($s->getSessionID())?>/edit/" type="button" class="btn btn-light btn-light-border width-xs-full" onclick="onclickHref(this)">
                                <i class="fa fa-pencil"></i> Edit
                            </button>
                            <?php if($s->getOwner() === $user->getUsername()): ?>
                                <button type="button" class="btn btn-light btn-light-border delete width-xs-full">
                                    <i class="fa fa-trash-o"></i> Delete
                                </button>
                            <?php endif; ?>
                        </div>
                        <?php if($s->getOwner() === $user->getUsername()): ?>
                            <div class="btn-group pull-right confirm-delete width-xs-full" aria-label="Confirm Delete">
                                <button type="button" class="btn btn-danger btn-danger-border confirm width-xs-full" data-session-id="<?=$this->e($s->getSessionID())?>">
                                    <i class="fa fa-check"></i> Confirm
                                </button>
                                <button type="button" class="btn btn-light btn-light-border cancel width-xs-full">
                                    <i class="fa fa-times"></i> Cancel
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>

                <?php // Otherwise, view controls for normal user?>
                <?php else: ?>
                    <div class="actions-confirm-delete">
                        <div class="btn-group pull-right actions" aria-label="Actions">
                            <button data-href="<?=$this->e($config["baseUrl"])?>session/<?=$this->e($s->getSessionID())?>/" type="button" class="btn btn-light btn-light-border" onclick="onclickHref(this)">
                                <i class="fa fa-plus"></i> Join
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>