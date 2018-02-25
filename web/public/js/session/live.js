var electron = null;
var remote = null;

var questions = [];
var active = false;
var sessionIdentifier = null;
var sessionQuestionID = null;
var questionNumber = null;
var loadQuestionsInterval = null;
var loadUsersInterval = null;

function setDefaults() {

    // If the load questions interval has started, stop it
    if(loadQuestionsInterval) {
        clearInterval(loadQuestionsInterval);
    }

    // If the load users interval has started, stop it
    if(loadUsersInterval) {
        clearInterval(loadUsersInterval);
    }

    questions = [];
    sessionIdentifier = null;
    sessionQuestionID = null;
    questionNumber = null;
    loadQuestionsInterval = null;
    loadUsersInterval = null;
}

/**********************************************************************************************************
 * Start and KeyPress
 *********************************************************************************************************/

// Attempt to do electron stuff, if any of it fails assume that the user is using a web browser
try {
    electron = require('electron');
    remote = electron.remote;
    let {ipcRenderer} = electron;

    // When start is sent over IPC, run the ready function
    ipcRenderer.on("start", function (e, args) {
        ready(args);
    });
}

// Otherwise, assuming this is a web browser.
catch(e) {}

/**
 * Function to run when the page is ready to be run
 */
function ready(si) {

    setDefaults();

    sessionIdentifier = si;

    // Start repeatedly loading all questions
    startLoadAllQuestionsInterval();
}

/**
 * Starts repeatedly loading all questions
 */
function startLoadAllQuestionsInterval() {

    // Load all questons
    loadQuestions(function() {

        // If this question is active and the user loader interval hasn't started
        if(active && !loadUsersInterval) {
            startLoadUsersInterval();
        }
    });

    // Start the load questions interval
    loadQuestionsInterval = setInterval(function() {
        loadQuestions();
    }, 1000);
}

/**
 * Starts repeatedly loading number of active users
 */
function startLoadUsersInterval() {

    // If the load user interval has not been started
    if(!loadUsersInterval) {

        // Load users
        loadUsers();

        // Start the load users interval
        loadUsersInterval = setInterval(function() {
            loadUsers();
        }, 1000);
    }
}

/**
 * Stop repeatedly loading number of active users
 */
function stopLoadUsersInterval() {
    clearInterval(loadUsersInterval);
    $("#users").text("");
    $(".button-container.users").addClass("display-none");
    $(".button-container.new-question").removeClass("display-none");
    loadUsersInterval = null;
}

/**
 * Load active users
 */
function loadUsers() {

    // Construct the URL for the api communication
    var url = baseUrl + "api/session/" + sessionIdentifier + "/question/" + sessionQuestionID + "/users/";

    // Make an api request
    $.getJSON(url, function (data) {
        if(active) {
            $("#users").text(data["answered"] + "/" + data["total"]);
            $(".button-container.users").removeClass("display-none");
            $(".button-container.new-question").addClass("display-none");
        }
    });
}

/**
 * When a key is pressed
 */
$(document).keydown(function(e) {

    // Only process key presses if questions exist
    if(questions.length > 0) {

        switch(e.key) {

            // Left Key: Previous Question
            case "ArrowLeft":
                if(!active) {
                    nextQuestion([].concat(questions).reverse());
                }
                break;

            // Right Key: Next Question
            case "ArrowRight": // right
                if(!active) {
                    nextQuestion(questions);
                }
                break;

            // A Key: Activate/Deactivate Question
            case "a":
            case "A":

                // If question is active, deactivate
                if(active) {
                    deactivate();
                }

                // Otherwise, activate
                else {
                    activate();
                }

                break;

            // R Key: View Responses
            case "r":
            case "R":
                responses();
                break;

            // Escape Key: Exit live view
            case "Escape":
                exit();
                break;

            // Exit this handler for other keys
            default: return;
        }

        // prevent the default action (scroll / move caret)
        e.preventDefault();
    }
});

/**********************************************************************************************************
 * API
 *********************************************************************************************************/

/**
 * Load all questions
 * @param callback
 */
function loadQuestions(callback) {

    // Construct the URL for the api communication
    var url = baseUrl + "api/session/" + sessionIdentifier + "/live/";

    // Make an api request
    $.getJSON(url, function (data) {

        // If there are questions
        if(data["questions"]) {

            $("#activate").removeClass("not-active");
            $("#deactivate").removeClass("not-active");
            $("#responses").removeClass("not-active");
            $("#new-question").removeClass("not-active");

            // TODO: check error
            questions = data["questions"].reverse();

            if(data["activeSessionQuestionID"]) {
                sessionQuestionID = data["activeSessionQuestionID"];
            }

            else if(data["questions"].length > 0 && sessionQuestionID === null) {
                sessionQuestionID = data["questions"][0]["sessionQuestionID"];
            }

            displayQuestion(callback);
        }

        // Otherwise, display an error
        else {
            $("#question-text").text("No Questions Available");
            $("#prev-question").addClass("not-active");
            $("#next-question").addClass("not-active");
            $("#activate").addClass("not-active");
            $("#deactivate").addClass("not-active");
            $("#responses").addClass("not-active");
        }
    });
}

/**********************************************************************************************************
 *
 *********************************************************************************************************/

/**
 * Display current question
 * @param callback
 */
function displayQuestion(callback) {
    var question;

    questionNumber = 1;

    // Loop for every question
    questions.some(function (q) {

        // If this is the current question
        if(q["sessionQuestionID"] === sessionQuestionID) {
            question = q;
            return true;
        }

        questionNumber++;
    });

    // If this question is active, setup UI
    if(question && question["active"]) {
        activateDone(callback);
    }

    // Otherwise, setup UI for deactivated question
    else {
        deactivateDone(callback);
    }

    var nextQuestion = $("#next-question");
    var prevQuestion = $("#prev-question");

    // If first question, hide previous button
    if(questionNumber === 1 || active) {
        prevQuestion.addClass("not-active");
    }
    else if(!active) {
        prevQuestion.removeClass("not-active");
    }

    // If last question, hide next button
    if(questionNumber === questions.length || active) {
        nextQuestion.addClass("not-active");
    }
    else if(!active) {
        nextQuestion.removeClass("not-active");
    }

    // Update UI
    $("#question-text").text(questionNumber + ". " + question["question"]);
}

/**********************************************************************************************************
 * Question Navigation
 *********************************************************************************************************/

function nextQuestion(qs) {
    var found = false;
    qs.forEach(function(q) {
        if(q["sessionQuestionID"] === sessionQuestionID) {
            found = true;
        }
        else if(found) {
            sessionQuestionID = q["sessionQuestionID"];
            found = false;
        }
    });
    displayQuestion(function() {
        if(active) {
            startLoadUsersInterval();
        }
        else {
            stopLoadUsersInterval();
        }
    });
}

$("#prev-question").click(function () {
    nextQuestion([].concat(questions).reverse());
});

$("#next-question").click(function () {
    nextQuestion(questions);
});

/**********************************************************************************************************
 * Activate / Deactivate
 *********************************************************************************************************/

$("#activate").click(function () {
    activate();
});

$("#deactivate").click(function () {
    deactivate();
});

function activate() {
    clearInterval(loadQuestionsInterval);
    $("#activate").addClass("not-active");

    activateQuestion(sessionQuestionID, false, function() {
        activateDone(function() {
            startLoadUsersInterval();
        });
    });
}

function activateDone(callback) {
    var activate = $("#activate");
    var deactivate = $("#deactivate");
    $("#next-question").addClass("not-active");
    $("#prev-question").addClass("not-active");
    $("#new-question-container").addClass("display-none");
    $("#question-type-container").addClass("display-none");

    activate.addClass("display-none");
    deactivate.removeClass("display-none");

    activate.removeClass("not-active");
    active = true;

    if(callback) {
        callback();
    }
}

function deactivate() {
    $("#deactivate").addClass("not-active");

    activateQuestion(sessionQuestionID, true, function() {
        deactivateDone(function() {
            stopLoadUsersInterval();
            startLoadAllQuestionsInterval();
        });
    });
}

function deactivateDone(callback) {
    var activate = $("#activate");
    var deactivate = $("#deactivate");
    //$("#next-question").removeClass("not-active");
    //$("#prev-question").removeClass("not-active");

    deactivate.addClass("display-none");
    activate.removeClass("display-none");
    deactivate.removeClass("not-active");
    active = false;

    if(callback) {
        callback();
    }
}

/**
 * Change the activation status of the current question
 * @param sqid
 * @param deactivate True if deactivating, false if activating
 * @param callback
 */
function activateQuestion(sqid, deactivate = false, callback) {
    var activate = !deactivate;

    // If there is a session question ID
    if (sqid) {

        // Construct the URL for the api communication
        var url = baseUrl + "api/session/" + sessionIdentifier + "/question/" + sqid + "/edit/?active=" + activate.toString();

        // Make an api request
        $.getJSON(url, function (data) {

            // If success
            if (data["active"] === activate) {

                if(callback) {
                    callback();
                }
            }

            // Otherwise, error
            else {
                console.log("Error activating/deactivating question");
            }
        });
    }
}

/**********************************************************************************************************
 * New Question
 *********************************************************************************************************/

$("#new-question").click(newQuestion);
$("#new-question-submit").click(newQuestionSubmit);

function newQuestion() {
    $(".view").addClass("expanded");
    $(".button-container.new-question").addClass("display-none");
    $(".button-container.question-type").removeClass("display-none");
}

function newQuestionSubmit() {

    // Add a new active question
    addGenericQuestionFromCode($("#question-type").val(), sessionIdentifier, function(data) {

        sessionQuestionID = data["sessionQuestionID"];
        displayQuestion(function() {

            // Exit expanded mode
            $(".view").removeClass("expanded");
            $(".button-container.new-question").removeClass("display-none");
            $(".button-container.question-type").addClass("display-none");
        });
    });
}

/**********************************************************************************************************
 * Results / Quit
 *********************************************************************************************************/

$("#responses").click(responses);
$("#power").click(exit);

/**
 * View responses
 */
function responses() {

    // If this is running as a desktop app
    if(isDesktopApp()) {

        // Send a new IPC message to create a new window for responses
        let {ipcRenderer} = electron;
        ipcRenderer.send("showResponses", [sessionIdentifier, sessionQuestionID]);
    }

    // Otherwise, forward the user to the responses page
    else {
        var url = baseUrl + "session/" + sessionIdentifier + "/edit/question/" + sessionQuestionID + "/response/live/";
        window.open(url, null, "height=720,width=800,status=no,toolbar=no,menubar=no,location=no");
    }
}

/**
 * Exit the live view
 */
function exit() {

    // If this is running as a desktop app
    if(isDesktopApp()) {

        // Close the window
        var remoteWindow = remote.getCurrentWindow();
        remoteWindow.close();
    }

    // Otherwise, forward the user to the 'edit session' page
    else {
        if(sessionIdentifier) {
            window.location = baseUrl + "session/" + sessionIdentifier + "/edit/";
        }
        else {
            window.location = baseUrl;
        }
    }
}

/**********************************************************************************************************
 * DEBUG
 *********************************************************************************************************/

$("#debug-session-join").click(function() {
    var sessionIdentifier = $("#debug-session-identifier").val();
    ready(sessionIdentifier);
});

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