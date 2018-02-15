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

        <link rel="stylesheet" href="<?=$this->e($config["baseUrl"])?>css/bootstrap-4.0.0-beta.2.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

        <script>
            function exitLiveView(sessionIdentifier) {
                const electron = require('electron');
                let {ipcRenderer} = electron;
                ipcRenderer.send("exitLiveView");
            }
        </script>
        <style>
            body {
                background-color: #003865;
                -webkit-app-region: drag;

                font-size: 28px;
                color: white;

                position: absolute;
                left: 0;
                top: 0;
                height: 100%;
                width: 100%;

            }
            img{
                height: 50px;
            }

            button, a {
                color: white;
                font-size: 25px;
                -webkit-app-region: no-drag;
            }

        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-1">
                    <img src="<?=$this->e($config["baseUrl"])?>img/uofg/logo-gu-icon.png"></img>
                </div>
                <div class="col-md-1">
                    <span id="users-answered">#</span>/<span id="users-total">#</span>
                </div>
                <div class="col-md-2">
                    <b>Activate</b>
                </div>
                <div class="col-md-1">
                    <a id="next-question" href="#">
                        <i class="fa fa-angle-double-right"></i>
                    </a>
                </div>
                <div class="col-md-3">
                    <b><span id="question-text"></span></b>
                </div>
                <div class="col-md-1">
                    <a href="#" onclick="exitLiveView()">
                        <i class="fa fa-power-off"></i>
                    </a>
                </div>
                <!--        <div class="col-md-1">-->
                <!--            <i class="fa fa-pie-chart" aria-hidden="true"></i>-->
                <!--        </div>-->
                <!--        <div class="col-md-1">-->
                <!--            <i class="fa fa-bar-chart" aria-hidden="true"></i>-->
                <!--        </div>-->
            </div>
        </div>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="<?=$this->e($config["baseUrl"])?>js/jquery-3.2.1.min.js" onload="window.$ = window.jQuery = module.exports;"></script>
        <script src="<?=$this->e($config["baseUrl"])?>js/popper.min.js"></script>
        <script src="<?=$this->e($config["baseUrl"])?>js/bootstrap-4.0.0-beta.2.min.js"></script>

        <script>
            var baseUrl = "<?=$this->e($config["baseUrl"])?>";
            var sessionIdentifier = <?=$session->getSessionIdentifier()?>;

            /*
            var baseUrl = "<?=$this->e($config["baseUrl"])?>";
            var sessionIdentifier = <?=$session->getSessionIdentifier()?>;
            var sessionQuestionID = null;
            var nextSessionQuestionID = null;
            var remote;

            // When the document is ready
            $(document).ready(function() {

                try {
                    remote = require('electron').remote;
                }
                catch(e) {
                    remote = null;
                }

                ready();
            });

            function isVisible() {
                return !remote || remote.getCurrentWindow().isVisible();
            }

            /**
             * When the document is ready
             *//*
            function ready() {

                var interval = setInterval(function() {

                    // If this is running in a web browser or the electron app is shown
                    if(isVisible()) {

                        // Start communicating with the API
                        clearInterval(interval);
                        loopUpdateDisplay();
                    }

                }, 1000);  // TODO: An event instead of a loop?
            }


            function updateDisplay() {

                // Construct the URL for the api communication
                var url = baseUrl + "api/session/" + sessionIdentifier + "/live/";

                // Make an api request
                $.getJSON(url, function(data) {

                    // If there is a question active
                    if(data["active"] === true) {

                        // Load variables from response
                        var usersAnswered = data["users"]["answered"];
                        var usersTotal = data["users"]["total"];
                        var questionText = data["question"];
                        sessionQuestionID = data["sessionQuestionID"];
                        nextSessionQuestionID = data["nextSessionQuestionID"];

                        // Update UI
                        $("#users-answered").text(usersAnswered);
                        $("#users-total").text(usersTotal);
                        $("#question-text").text(questionText);
                    }

                    // Otherwise, question no question active
                    else {
                        $("#users-answered").text("#");
                        $("#users-total").text("#");
                        $("#question-text").text("No Active Question");
                    }
                });
            }

            /**
             * Loop for API Updates
             *//*
            function loopUpdateDisplay() {

                updateDisplay();

                // Loop every second
                var interval = setInterval(function() {

                    // If window is visible
                    if(isVisible()) {
                        updateDisplay();
                    }

                    // Otherwise, clear interval
                    else {
                        clearInterval(interval);
                        ready();
                    }
                }, 100);
            }

            /**
             * When the next question button is clicked
             *//*
            $("#next-question").click(function() {

                // Construct the URL for the api communication
                var url = baseUrl + "api/session/" + sessionIdentifier + "/question/" + nextSessionQuestionID + "/edit/?active=true";

                var oldNextSessionQuestionID = nextSessionQuestionID;

                // Make an api request
                $.getJSON(url, function(data) {

                    // If session question ID changed correctly
                    if(data["sessionQuestionID"] !== oldNextSessionQuestionID) {
                        alert("Unknown Error");
                    }
                });
            });
            */
        </script>
    </body>
</html>