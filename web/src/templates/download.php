<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $user User
 * @var $alert Alert
 */
$this->layout("template",
    [
        "config" => $config,
        "title" => "Download",
        "description" => $description,
        "user" => $user,
        "alert" => $alert
    ]
);
?>

<?php $this->push("preContent"); ?>
<div class="jumbotron text-center">
    <div class="container">
        <h1 class="display-3">Download YACRS</h1>
        <p class="lead">
            Latest Version: v2.0.0rc1
        </p>
    </div>
</div>
<?php $this->stop(); ?>

<div class="row">
    <div class="offset-sm-2 col-sm-8 row">
        <div class="col-sm-4">
            <!-- href="https://www.dropbox.com/sh/w2347x3h7i0h6wb/AAANRFttnqcHKXWs7uk0mc1Ka?dl=0&lst=" -->
            <div class="dropdown">
                <button id="windowsDropdown" class="btn btn-primary btn-lg width-full download" target="_blank" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-windows icon" aria-hidden="true"></i>
                    <span class="os">Windows</span>
                </button>
                <div class="dropdown-menu btn btn-light btn-lg width-full download" aria-labelledby="windowsDropdown">
                    <a class="dropdown-item" href="#">.exe</a>
                    <a class="dropdown-item" href="#">x64 .zip</a>
                    <a class="dropdown-item" href="#">x86_64 .zip</a>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <!-- href="https://www.dropbox.com/sh/1ptq1dyj5y15oxw/AAASIItlXGfDVBrO93FKOaOya?dl=0&lst=" -->
            <a class="btn btn-primary btn-lg width-full download" target="_blank" href="#">
                <i class="fa fa-apple icon" aria-hidden="true"></i>
                <span class="os">macOS</span>
            </a>
        </div>
        <div class="col-sm-4">
            <!-- href="https://www.dropbox.com/sh/1xaatvmciq0uq2s/AAD-UkL_05qdDuhAKA_XZwxia?dl=0&lst=" -->
            <div class="dropdown">
                <button id="linuxDropdown" class="btn btn-primary btn-lg width-full download" target="_blank" data-toggle="dropdown" aria-haspopup="true", aria-expanded="false">
                    <i class="fa fa-linux icon" aria-hidden="true"></i>
                    <span class="os">Linux</span>
                </button>
                <div class="dropdown-menu btn btn-light btn-lg width-full download" aria-labelledby="linuxDropdown">
                    <a class="dropdown-item" href="#">x64 .appImage</a>
                    <a class="dropdown-item" href="#">x86_64 .appImage</a>
                    <a class="dropdown-item" href="#">.snap</a>
                    <a class="dropdown-item" href="#">x64 .zip</a>
                    <a class="dropdown-item" href="#">x86_64 .zip</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .download .icon,
    .download .os,
    .download .version {
        display: block;
    }

    .download .icon {
        font-size: 50px;
    }

    .download .os {
        font-size: 25px;
    }
</style>
