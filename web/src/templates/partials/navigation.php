<?php
/**
 * @var $config array
 * @var $logo string
 * @var $user User
 */
?>
<nav class="navbar navbar-expand-md navbar-dark fixed-top">
    <a class="navbar-brand" href="<?=$config["baseUrl"]?>">
        <?php if($logo): ?>
            <img src="<?=$this->e($config["baseUrl"])?><?=$this->e($logo)?>">
        <?php else: ?>
        <div class="navbar-brand-text">
            YACRS
        </div>
        <?php endif; ?>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse"
            aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbar-collapse">
        <?php if($user): ?>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="<?=$this->e($config["baseUrl"])?>">Home <span class="sr-only">(current)</span></a>
                </li>
                <?php if($user->isSessionCreator() || $user->isAdmin()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href=#" id="dropdown-sessions"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sessions</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown-sessions">
                            <a class="dropdown-item" href="<?=$this->e($config["baseUrl"])?>session/">
                                Manage Sessions
                            </a>
                            <a class="dropdown-item" href="<?=$this->e($config["baseUrl"])?>session/new/">
                                New Session
                            </a>
                        </div>
                    </li>
                <?php endif; ?>
                <?php if($user->isAdmin()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=$this->e($config["baseUrl"])?>admin/">Admin</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item active">
                    <a class="nav-link" href="<?=$this->e($config["baseUrl"])?>download/">Download</a>
                </li>
                <li class="nav-item">
                    <a class ="nav-link" href="<?=$this->e($config["baseUrl"])?>help/">Help</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="navbar-text d-none d-md-block d-lg-block d-xl-block">
                        <?=$this->e($user->getFullName())?>
                    </span>
                </li>
                <?php if($config["login"]["type"] === "native" && $config["login"]["register"]): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=$this->e($config["baseUrl"])?>changepassword/">
                            Change Password
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="<?=$this->e($config["baseUrl"])?>logout/" class="btn btn-light">
                        <i class="fa fa-lock"></i>
                        Logout
                    </a>
                </li>
            </ul>
        <?php endif ?>
    </div>
</nav>
