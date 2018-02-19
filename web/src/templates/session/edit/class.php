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

            color: white;

            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
        }

        .view{
            margin: 10px 10px;
        }

        img {
            height: 80px;
            float: left;
        }

        #prev-question {
            float: left;
            font-size: 40px;
            padding: 10px;
        }

        #question-text{
            float: left;
            font-size: 16px;
            padding: 30px;
        }

        #next-question {
            float: left;
            font-size: 40px;
            padding: 10px;
        }

        .status {
            float: left;
            font-size: 28px;
            padding:20px 10px;
        }

        .responses{
            float: left;
            font-size: 35px;
            padding: 10px
        }

        .activity{
            float: left;
            font-size: 30px;
            padding:20px 10px;
        }

        #power {
            float: right;
            font-size: 40px;
            padding:10px;
        }

        button, a {
            color: white;
            -webkit-app-region: no-drag;
        }

        .display-none {
            display: none;
        }

        .not-active {
            pointer-events: none;
            cursor: default;
        }
    </style>
</head>
<body>

<div class="view">
    <img src="<?= $this->e($config["baseUrl"]) ?>img/uofg/logo-gu-icon.png"></img>

    <div class="qnav">
        <a id="prev-question" href="#">
            <i class="fa fa-angle-double-left"></i></a>
    </div>

    <b><span id="question-text"></span></b>

    <div class="qnav">
        <a id="next-question" href="#">
            <i class="fa fa-angle-double-right"></i></a>
    </div>

    <div class="status">
        <a id="activate" href="#" class="display-none"><b>Activate</b></a>
        <a id="deactivate" href="#" class="display-none"><b>Deactivate</b></a>
    </div>

    <div class="responses">
    <i class="fa fa-bar-chart" aria-hidden="true"></i>
    </div>

    <div class="activity">
        <span id="users-answered">#</span>/<span id="users-total">#</span>
    </div>

    <a id="power" href="#" onclick="exitLiveView()">
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
    var remote = null;
    var sessionQuestionID = null;   // The current question being displayed
    var active = false;             // Whether the currently display question is active
    var interval = false;           // Interval used to update users etc when question is active

    /**********************************************************************************************************
     * jQuery Events (click, ready etc)
     *********************************************************************************************************/

    /**
     * When the document is ready
     */
    $(document).ready(function () {

        // Gets the active question
        getActiveQuestion(sessionIdentifier, function (sessionQuestionID) {

            // If there is an active question, display it
            if (sessionQuestionID !== null) {
                displayQuestion(sessionQuestionID);
            }

            // Otherwise, display the first session question
            else {
                displayFirstQuestion();
            }
        });
    });

    $("#prev-question").click(function () {

        // Only if no question active
        if (!active) {

            // Get all the questions
            getAllQuestions(sessionIdentifier, function (sqids) {

                // Reverese the session questions IDs for prev to work
                sqids = sqids.reverse();

                var foundCurrent = false;
                var nextSessionQuestionID = null;

                // Loop for each question
                sqids.some(function (value) {

                    // If this is the current displayed question
                    if (sessionQuestionID === value) {

                        // Set found flag to true (to be used next loop
                        foundCurrent = true;
                    }

                    // If the last item was the current displayed question
                    else if (foundCurrent) {

                        // Set the next session question ID
                        nextSessionQuestionID = value;

                        // Exit loop early
                        return true;
                    }
                });

                // If a next question was found, display it!
                if (nextSessionQuestionID !== null) {
                    displayQuestion(nextSessionQuestionID);
                }

                // Otherwise, no more questions :(
                else {
                    alert("No More Questions");
                }
            });
        }
    });

    // TODO
    $("#next-question").click(function () {

        // Only if no question active
        if (!active) {

            // Get all the questions
            getAllQuestions(sessionIdentifier, function (sqids) {

                var foundCurrent = false;
                var nextSessionQuestionID = null;
                var i = 0;

                // Loop for each question
                sqids.some(function (value) {

                    // If this is the current displayed question
                    if (sessionQuestionID === value) {

                        // Set found flag to true (to be used next loop
                        foundCurrent = true;
                    }

                    // If the last item was the current displayed question
                    else if (foundCurrent) {

                        // Set the next session question ID
                        nextSessionQuestionID = value;

                        // Exit loop early
                        return true;
                    }

                    i++;
                });

                // If a next question was found, display it!
                if (nextSessionQuestionID !== null) {
                    displayQuestion(nextSessionQuestionID);
                }

                // Otherwise, no more questions :(
                else {
                    alert("No More Questions");
                }
            });
        }
    });

    /**
     * Change the activation status of the current question
     * @param deactivate True if deactivating, false if activating
     */
    function activateQuestion(deactivate = false) {

        // If there is a session question ID
        if (sessionQuestionID) {

            // Construct the URL for the api communication
            var url = baseUrl + "api/session/" + sessionIdentifier + "/question/" + sessionQuestionID + "/edit/?active=" + deactivate.toString();

            // Make an api request
            $.getJSON(url, function (data) {

                // If success
                if (data["active"] === deactivate) {
                    displayQuestion(sessionQuestionID);
                }

                // Otherwise, error
                else {
                    console.log("Error activating/deactivating question");
                }
            });
        }
    }


    $("#activate").click(function () {
        activateQuestion(true);
    });

    $("#deactivate").click(function () {
        activateQuestion(false);
    });

    /**********************************************************************************************************
     * Functions containing logical steps
     *********************************************************************************************************/

    /**
     * Display the first availiable question
     */
    function displayFirstQuestion() {

        // Get all questions
        getAllQuestions(sessionIdentifier, function (sessionQuestionIDs) {

            // If there is a first question
            if (sessionQuestionIDs.length > 0) {
                displayQuestion(sessionQuestionIDs[0]);
            }

            // Otherwise, no questions!
            else {
                alert("No Questions!");
            }
        });
    }

    /**
     * Display a given question
     * @param sqid The Session Question ID
     */
    function displayQuestion(sqid) {

        // Construct the URL for the api communication
        var url = baseUrl + "api/session/" + sessionIdentifier + "/live/" + sqid + "/";

        // Make an api request
        $.getJSON(url, function (data) {

            var usersAnswered, usersTotal, questionText;

            var deactivate = $("#deactivate");
            var activate = $("#activate");
            var nextQuestion = $("#next-question");
            var prevQuestion = $("#prev-question");

            // If this questions is active
            if (data["active"] === true) {

                active = true;

                usersAnswered = data["users"]["answered"];
                usersTotal = data["users"]["total"];

                // Display only the deactivate button
                deactivate.removeClass("display-none");
                activate.addClass("display-none");

                // Disable the next/prev question buttons
                nextQuestion.addClass("not-active");
                prevQuestion.addClass("not-active");

                // If an interval is not looping, start looping
                if (!interval) {
                    loopDisplayQuestion();
                }
            }

            // Otherwise, if this question is inactive
            else {

                active = false;

                usersAnswered = "#";
                usersTotal = "#";

                // Display only the activate button
                activate.removeClass("display-none");
                deactivate.addClass("display-none");

                // Enable the next/prev question buttons
                nextQuestion.removeClass("not-active");
                prevQuestion.removeClass("not-active");

                // If an interval is looping, stop looping
                if (!interval) {
                    clearInterval(interval);
                }
            }

            // Get the question next
            questionText = data["question"];

            // Update UI
            $("#users-answered").text(usersAnswered);
            $("#users-total").text(usersTotal);
            $("#question-text").text(questionText + " --- " + sqid);

            sessionQuestionID = sqid;
        });
    }

    function loopDisplayQuestion() {
        interval = setInterval(function () {
            displayQuestion(sessionQuestionID);
        }, 200);
    }

    /**********************************************************************************************************
     * API Functions
     *********************************************************************************************************/

    /**
     * Gets all questions and sends them to the callback as its only parameter
     * @param sessionIdentifier The Session Identifier
     * @param callback The callback function which excepts one parameter
     */
    function getAllQuestions(sessionIdentifier, callback) {

        // Construct the URL for the api communication
        var url = baseUrl + "api/session/" + sessionIdentifier + "/question/all/";

        // Make an api request
        $.getJSON(url, function (data) {

            // If this has returned an array of numbers
            if (!data.some(isNaN)) {
                callback(data);
            }

            else {
                console.log("Error getting all questions")
            }
        });
    }

    /**
     * Gets the active question and sends it to the callback as its only parameter. Sends null if no active
     * question
     * @param sessionIdentifier The Session Identifier
     * @param callback The callback function which excepts one parameter
     */
    function getActiveQuestion(sessionIdentifier, callback) {

        // Construct the URL for the api communication
        var url = baseUrl + "api/session/" + sessionIdentifier + "/question/active/";

        // Make an api request
        $.getJSON(url, function (data) {

            // If no items in response, no question active
            if (data.length === 0) {
                callback(null);
            }

            // If items exist and they are all numbers
            else if (data.length > 0 && !data.some(isNaN)) {

                // Send the first to the callback
                callback(data[0]);
            }

            // Otherwise, error
            else {
                console.log("Error getting active questions")
            }
        });
    }

    /**********************************************************************************************************
     * Generic Utility Functions
     *********************************************************************************************************/

    /**
     * Returns if the app is being run within the electron app. Produces accurate results only after setRemote()
     * has been called.
     */
    function isDesktopApp() {
        return !!remote;
    }

    /**
     * Attempts to store the remote electron variable. If not running in electron, stores null.
     */
    function setRemote() {
        try {
            remote = require('electron').remote;
        }
        catch (e) {
            remote = null;
        }
    }
</script>
</body>
</html>