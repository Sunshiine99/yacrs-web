<?php
/**
 * @var $config array
 * @var $user User
 * @var $mysqli mysqli
 * @var $session Session
 */
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Live View</title>
        <meta name="theme-color" content="#003865">

        <link rel="stylesheet" href="<?= $this->e($config["baseUrl"]) ?>css/bootstrap-4.0.0-beta.2.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #003865;
                -webkit-app-region: drag;

                color: white;

                position: absolute;
                left: 0;
                top: 0;
                height: 100%;
                width: 100%;
                user-select: none;
            }

            .view{
                margin: 10px 10px;
            }

            img {
                height: 80px;
                float: left;
                clear: left;
            }

            #prev-question {
                float: left;
                font-size: 40px;
                padding: 10px;
                margin-left: 20px;
            }

            #question-text{
                float: left;
                font-size: 16px;
                text-align: center;
                width:350px;
                word-wrap:break-word;
                margin-left: 20px;
                padding-top: 10px;
            }

            #next-question {
                float: left;
                font-size: 40px;
                margin-left: 20px;
                padding: 10px;

            }

            .status {
                float: left;
                font-size: 28px;
                padding:10px;
                margin-left: 20px;
                margin-top: 10px;
                width: 140px;
            }

            .status, a:hover{
                text-decoration: none;
            }

            .responses{
                float: left;
                font-size: 35px;
                padding: 10px;
                margin-left: 20px;
            }

            .activity{
                float: left;
                text-align: center;
                width:70px;
                font-size: 30px;
                padding:20px 10px;
                margin-left: 20px;
            }

            #power {
                float: right;
                font-size: 40px;
                padding:10px;
                margin-left: 20px;
                clear: right;
            }

            button, a {
                color: white;
                -webkit-app-region: no-drag;
            }

            button, a:hover{
                color: #ffb949;
            }

            .display-none {
                display: none;
            }

            .not-active {
                pointer-events: none;
                cursor: default;
                color: grey;
                -webkit-app-region: drag;
            }
        </style>
    </head>
    <body>
        <div class="view">
            <img src="<?=$this->e($config["baseUrl"])?>img/uofg/logo-gu-icon.png"></img>
            <div class="qnav">
                <a id="prev-question" href="#">
                    <i class="fa fa-angle-double-left"></i>
                </a>
            </div>
            <b>
                <span id="question-text"></span>
            </b>
            <div class="qnav">
                <a id="next-question" href="#">
                    <i class="fa fa-angle-double-right"></i>
                </a>
            </div>
            <div class="status">
                <a id="activate" href="#" class="display-none">
                    <b>Activate</b>
                </a>
                <a id="deactivate" href="#" class="display-none">
                    <b>Deactivate</b>
                </a>
            </div>
            <div class="responses">
                <a id="responses" href="#">
                    <i class="fa fa-bar-chart" aria-hidden="true"></i>
                </a>
            </div>
            <div class="activity">
                <span id="users"></span>
            </div>
            <a id="power" href="#">
                <i class="fa fa-power-off"></i>
            </a>
        </div>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="<?= $this->e($config["baseUrl"]) ?>js/jquery-3.2.1.min.js"
                onload="window.$ = window.jQuery = module.exports;"></script>
        <script
        src = "<?=$this->e($config["baseUrl"])?>js/popper.min.js" ></script>
        <script src="<?= $this->e($config["baseUrl"]) ?>js/bootstrap-4.0.0-beta.2.min.js"></script>

        <script>
            var baseUrl = "<?=$this->e($config["baseUrl"])?>";
            var sessionIdentifier = <?=$session->getSessionIdentifier()?>;
        </script>
        <script src="<?= $this->e($config["baseUrl"]) ?>js/session/live.js"></script>
    </body>
</html>