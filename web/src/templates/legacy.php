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
            <div class="col-xs-6">
                <?= $logoutLink ?>
            </div>
        </div>
    </div>
<?php
$this->stop();

$this->push("preContent");
echo $breadcrumb;
echo $sideLinks;
$this->stop();

echo $headings;
echo $loginBox;
echo $coursesBlock;
echo $sideInfo;
echo $mainBody;