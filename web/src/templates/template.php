<?php
$this->layout("base",
    [
        "CFG" => $CFG,
        "title" => $title,
        "description" => $description,
        "uinfo" => $uinfo,
    ]
);

$this->push("header"); ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <div id="branding">
                    <h1 class="logo">Class Response</h1>
                </div>
            </div>
            <?php if($uinfo): ?>
            <div class="col-xs-6">
                <div id="logoutLink">
                    <div class="loginBox">
                        USER NAME <a href="/logout/"><i class="fa fa-lock"></i> Log out</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
<?php $this->stop(); ?>

<?php $this->push("preContent"); ?>
    <?=$this->fetch("partials/breadcrumb", ["breadcrumbs"=>$breadcrumbs])?>
<?php $this->stop(); ?>

<?=$this->section("content")?>