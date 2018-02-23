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
                margin-left: 10px;
                margin-top: 10px;
                width: 140px;
                text-align: center;
            }

            .status, a:hover{
                text-decoration: none;
            }

            .responses{
                float: left;
                font-size: 35px;
                padding: 10px;
                margin-left: 20px;
                width: 70px;
                text-align: center;
            }

            .activity{
                float: left;
                text-align: center;
                width:115px;
                font-size: 30px;
                padding:20px 10px;
                margin-left: 0px;
            }

            #power {
                float: left;
                font-size: 40px;
                padding:10px;
                margin-left: 20px;
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

            .question-text-container-outer {
                width: 300px;
                height: 80px;
                position: relative;
                float: left;
            }

            .question-text-container-inner {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                display: table;
            }

            .question-text-container-inner p {
                display: table-cell;
                vertical-align: middle;
                text-align: center;
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
            <div class="question-text-container-outer">
                <div class="question-text-container-inner">
                    <p id="question-text"></p>
                </div>
            </div>
            <div class="qnav">
                <a id="next-question" href="#">
                    <i class="fa fa-angle-double-right"></i>
                </a>
            </div>
            <div class="status">
                <a id="activate" href="#">
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