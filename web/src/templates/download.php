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
<?php $this->push("head"); ?>
    <link rel="stylesheet" type="text/css" href="<?=$this->e($config["baseUrl"])?>css/download.css" />
<?php $this->end(); ?>
<?php $this->push("preContent"); ?>
<div class="jumbotron text-center">
    <div class="container">
        <h1 class="display-3">Download YACRS</h1>
        <p class="lead">
            Latest Version: v2.0.0
        </p>
        <div class="row">
            <div class="col-md-0 col-lg-2"></div>
            <div class="col-md-12 col-lg-8 row">
                <div class="col-sm-4">
                    <a class="btn btn-primary btn-lg width-full download" target="_blank" href="https://www.dropbox.com/sh/w2347x3h7i0h6wb/AAANRFttnqcHKXWs7uk0mc1Ka?dl=0&lst=">
                        <i class="fa fa-windows icon" aria-hidden="true"></i>
                        <span class="os">Windows</span>
                    </a>
                </div>
                <div class="col-sm-4">
                    <a class="btn btn-primary btn-lg width-full download" target="_blank" href="https://www.dropbox.com/sh/1ptq1dyj5y15oxw/AAASIItlXGfDVBrO93FKOaOya?dl=0&lst=">
                        <i class="fa fa-apple icon" aria-hidden="true"></i>
                        <span class="os">macOS</span>
                    </a>
                </div>
                <div class="col-sm-4">
                    <a class="btn btn-primary btn-lg width-full download" target="_blank" href="https://www.dropbox.com/sh/1xaatvmciq0uq2s/AAD-UkL_05qdDuhAKA_XZwxia?dl=0&lst=">
                        <i class="fa fa-linux icon" aria-hidden="true"></i>
                        <span class="os">Linux</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->stop(); ?>

<div class="container">
    <h1>Installation Instructions</h1>
    <ul class="nav nav-tabs" data-target="sections">
        <li class="nav-item" id="nav-responses" data-target="section-windows">
            <a class="nav-link active" href="javascript:void(0);">Windows</a>
        </li>
        <li class="nav-item" id="nav-bar-chart" data-target="section-mac">
            <a class="nav-link" href="javascript:void(0);">macOS</a>
        </li>
        <li class="nav-item" id="nav-pie-chart" data-target="section-linux">
            <a class="nav-link" href="javascript:void(0);">Linux</a>
        </li>
    </ul>
</div>
<div class="sections" id="sections">
    <div id="section-windows" class="section container">
        <h2>Setup</h2>
        <ol>
            <li>
                Click the Windows operating system option above.
            </li>
            <li>
                Download the setup .exe file.
            </li>
            <li>
                <div>
                    Double click the downloaded .exe file to start the installation.
                </div>
                <a href="#" data-toggle="collapse" data-target="#windows-protected-pc-1">
                    (If you receive an error stating "Windows protected your PC" click here)
                </a>
                <div id="windows-protected-pc-1" class="windows-protected-pc collapse">
                    <ol>
                        <li>
                            <div>
                                Click "more info" and then click "run anyway"
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="<?=$this->e($config["baseUrl"])?>img/download/windows-protected-pc-1.jpg"/>
                                </div>
                                <div class="col-md-6">
                                    <img src="<?=$this->e($config["baseUrl"])?>img/download/windows-protected-pc-2.jpg"/>
                                </div>
                            </div>
                        </li>
                    </ol>
                </div>
            </li>
            <li>
                Search "YACRS Control" in the start menu or double click the "YACRS Control" icon on the desktop to
                start the application.
            </li>
            <li>
                Uninstall the application by:
                <ol>
                    <li>Open the "Control Panel".</li>
                    <li>Select "Uninstall a program"</li>
                    <li>Select YACRS Control</li>
                    <li>Click "Uninstall" and follow any on screen prompts</li>
                </ol>
            </li>
        </ol>
        <h2>Unpacked Application</h2>
        <p>
            This option allows you to run the application without installing.
        </p>
        <ol>
            <li>
                Click the Windows operating system option above.
            </li>
            <li>
                Download the unpacked .zip file for your platform. 32bit (x86) or 64bit (x64).
            </li>
            <li>
                Extract the .zip archive by right clicking it and clicking "Extract All"
            </li>
            <li>
                <div>
                    Open the newly created directory and run the application by double clicking "yacrs-control.exe"
                </div>
                <a href="#" data-toggle="collapse" data-target="#windows-protected-pc-2">
                    (If you receive an error stating "Windows protected your PC" click here)
                </a>
                <div id="windows-protected-pc-2" class="windows-protected-pc collapse">
                    <ol>
                        <li>
                            <div>
                                Click "more info" and then click "run anyway"
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="<?=$this->e($config["baseUrl"])?>img/download/windows-protected-pc-1.jpg"/>
                                </div>
                                <div class="col-md-6">
                                    <img src="<?=$this->e($config["baseUrl"])?>img/download/windows-protected-pc-2.jpg"/>
                                </div>
                            </div>
                        </li>
                    </ol>
                </div>
            </li>
            <li>
                To uninstall you can simply remove the .zip archive and the newly created directory.
            </li>
        </ol>
    </div>
    <div id="section-mac" class="section container display-none">
        <h2>Mac Installation</h2>
        <ol>
            <li>
                Click the Mac operating system option above.
            </li>
            <li>
                Click the file "YACRS Control-2.0.0.dmg"
            </li>
            <li>
                Click the "Download" button and then choose "Direct Download".
            </li>
            <li>
                Click on the downloaded file, a window will open showing the YACRS application file and your Applications folder. 
            </li>
			<li>
				Simply drag the file into your Applications folder.
			</li>
			<li>
				You can now run the application by navigating to your Applications folder and clicking "YACRS Control"
			</li>
            <li>
                To uninstall you can simply remove the file from your Applications folder.
            </li>
        </ol>
    </div>
    <div id="section-linux" class="section container display-none">
        <p>
            These instructions assume you are familiar with terminal commands.
        </p>
        <h2>App Image</h2>
        <ol>
            <li>
                Click the Linux operating system option above.
            </li>
            <li>
                Download the .AppImage file for your platform. 32bit (x86) or 64bit (x64).
            </li>
            <li>
                To run the downloaded file give it executable permissions:<br>
                <span class="code indent">$ chmod +x yacrs-control-2.0.0-x64.AppImage</span>
            </li>
            <li>
                Now the .AppImage file can be run by double clicking it or running the following command:<br>
                <span class="code indent">$ ./yacrs-control-2.0.0-x64.AppImage</span>
            </li>
            <li>
                To uninstall you can simply remove the .AppImage file.
            </li>
        </ol>
        <h2>Unpacked Application</h2>
        <ol>
            <li>
                Click the Linux operating system option above.
            </li>
            <li>
                Download the unpacked .zip file for your platform. 32bit (x86) or 64bit (x64).
            </li>
            <li>
                Extract the .zip archive using your file manager or by using the following command:<br>
                <span class="code indent">$ unzip yacrs-control-2.0.0-x64-unpacked-linux.zip</span>
            </li>
            <li>
                Enter the newly created directory and run the application by running:<br>
                <span class="code indent">$ ./yacrs-control</span>
            </li>
            <li>
                To uninstall you can simply remove the .zip archive and the newly created directory.
            </li>
        </ol>
        <h2>Snap</h2>
        <ol>
            <li>
                Click the Linux operating system option above.
            </li>
            <li>
                Download the .AppImage file for your platform. Currently only 64bit (x64) available.
            </li>
            <li>
                To install the application run the following command (<span class="code">--dangerous</span> required as
                we are installing a local snap.)<br>
                <span class="code indent">$ snap install yacrs-control-2.0.0-x64.snap --dangerous</span><br>
            </li>
            <li>
                Run YACRS by searching your application launcher for "YACRS Control" or by running the following
                command:<br>
                <span class="code indent">$ yacrs-control</span>
            </li>
            <li>
                To uninstall run the following command:<br>
                <span class="code indent">$ snap remove yacrs-control</span>
            </li>
        </ol>
    </div>
</div>