<?php
$this->layout("base",
    [
        "config" => $config,
        "title" => $title,
        "description" => $description,
        "user" => $user,
        "alert" => $alert,
        "breadcrumbs"=>$breadcrumbs
    ]
);

$this->push("header"); ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <div id="branding">
                    <a href="<?=$config["baseUrl"]?>">
                        <h1 class="logo">Class Response</h1>
                    </a>
                </div>
            </div>
            <?php if($user): ?>
                <div class="col-xs-6">
                    <div id="logoutLink">
                        <div class="loginBox">
                            <?=$user->getGivenName()?> <?=$user->getSurname()?><a href="<?=$config["baseUrl"]?>logout/"><i class="fa fa-lock"></i> Log out</a>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
<?php $this->stop(); ?>

<?=$this->section("content")?>