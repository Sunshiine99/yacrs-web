var electron = null;
var remote = null;

var sessionQuestionID = null;   // The current question being displayed
var active = false;             // Whether the currently display question is active
var interval = false;           // Interval used to update users etc when question is active

/**********************************************************************************************************
 * Start
 *********************************************************************************************************/

/**
 * Function to run when the page is ready to be run
 */
function ready() {

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
}

// Attempt to do electron stuff, if any of it fails assume that the user is using a web browser
try {
    electron = require('electron');
    remote = electron.remote;
    let {ipcRenderer} = electron;

    // When start is sent over IPC, run the ready function
    ipcRenderer.on("start", ready);
}

    // Otherwise, assuming this is a web browser.
catch(e) {

    alert(e);

    // When the document is ready, run the ready function
    $(document).ready(ready);
}

/**********************************************************************************************************
 * jQuery Events (click, ready etc)
 *********************************************************************************************************/

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

$("#responses").click(function() {
    if(isDesktopApp()) {
        let {ipcRenderer} = electron;
        ipcRenderer.send("showResponses", [sessionIdentifier, sessionQuestionID]);
    }
});

$("#power").click(function() {
    if(isDesktopApp()) {
        var window = remote.getCurrentWindow();
        window.close();
    }
    else {
        window.location = baseUrl + "session/" + sessionIdentifier + "/edit/";
    }
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
            noQuestions();
        }
    });
}

function noQuestions() {
    $("#question-text").text("No Questions Found");
    $("#deactivate").addClass("display-none");
    $("#activate").addClass("display-none");
    $("#next-question").addClass("not-active");
    $("#prev-question").addClass("not-active");
    $("#responses").addClass("not-active");
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

        var users, questionText;

        var deactivate = $("#deactivate");
        var activate = $("#activate");
        var nextQuestion = $("#next-question");
        var prevQuestion = $("#prev-question");

        // If this questions is active
        if (data["active"] === true) {

            active = true;

            users = data["users"]["answered"] + "/" + data["users"]["total"];

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

            users = "";

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
        $("#users").text(users);
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
    return !!electron;
}