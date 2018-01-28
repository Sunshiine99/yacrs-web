$(".question-list .confirm-delete .confirm").click(function () {

    // Disable button clicked to indicate that something is happening
    $(this).attr("disabled", "disabled");

    var listGroupItem = $(this).closest(".list-group-item");

    // Find the list group
    var listGroup = listGroupItem.closest("ul.list-group");

    var sessionIdentifier = $("meta[name=sessionIdentifier]").attr("content").toString();

    // Construct URL for API request
    var url = baseUrl + "api/session/" + sessionIdentifier + "/question/" + $(this).attr("data-session-question-id") + "/delete/";

    // Store this for access when this is no longer "this"
    var that = this;

    // Make an api request
    $.getJSON(url, function(data) {

        // If delete was successful, delete html element
        if(data["success"] === true) {
            listGroupItem.remove();
        }

        else {
            alerter({
                title: "Error",
                message: "Could not delete session question for an unknown reason",
                type: "danger",
                dismissable: true
            });
        }
    })
    .always(function() {

        // Enable button once request is complete
        $(that).removeAttr("disabled");

        // Reset confirm delete buttons
        $(that).closest(".confirm-delete").css("display", "none");
        var actionsConfirmDelete = $(that).closest(".actions-confirm-delete");
        actionsConfirmDelete.find(".actions").css("display", "inline-flex");

        // For each question
        var i = 1;
        $(listGroup.find("li.list-group-item").get().reverse()).each(function(index) {

            // Update the question number
            $(this).find("div.question-number").text(i + ".");
            i++;
        });
    });
});

$(".question-list .activate").click(function () {
    activateDeactivateQuestion(this, true);
});

$(".question-list .deactivate").click(function () {
    activateDeactivateQuestion(this, false);
});

// TODO: Use a better solution
$("#activate-all").click(function () {
    $(".question-list .activate").click();
});

// TODO: Use a better solution
$("#deactivate-all").click(function () {
    $(".question-list .deactivate").click();
});

function addGenericQuestion(url, type) {

    // Make an api request
    $.getJSON(url, function(data) {

        // If delete was successful, delete html element
        if(data["type"] === type) {
            location.reload();
        }

        else {
            alerter({
                title: "Error",
                message: "Could not add question for an unknown reason",
                type: "danger",
                dismissable: true
            });
        }
    });
}

/**
 * Add a new generic choices (MCQ/MRQ) question
 * @param numChoices The number of choices
 * @param type mcq or mrq
 */
function addGenericChoicesQuestion(numChoices, type) {
    if(numChoices > 26 | numChoices <= 0)
        return;

    var sessionIdentifier = $("meta[name=sessionIdentifier]").attr("content").toString();

    // Construct URL for API request
    var url = baseUrl + "api/session/" + sessionIdentifier + "/question/new/" + type + "/";

    // Add URL parameters
    url += "?question=Generic " + type.toUpperCase() + " Question A-" + String.fromCharCode(65 + numChoices - 1) + "&";

    // Add generic choice (I.e. A, B, C, D)
    for (var i = 0; i < numChoices; i++) {

        // Add choice to URL as parameter
        url += "choice-" + i + "=" + String.fromCharCode(65 + i);

        // If not the last choice, add "&" ready for next choice
        if(i < numChoices-1) {
            url += "&";
        }
    }

    addGenericQuestion(url, type);
}

/**
 * Add a new generic true/false question
 * @param dontKnow Whether don't know is also an option
 */
function addGenericTrueFalseQuestion(dontKnow) {
    dontKnow = dontKnow===true;

    var sessionIdentifier = $("meta[name=sessionIdentifier]").attr("content").toString();

    // Construct URL for API request
    var url = baseUrl + "api/session/" + sessionIdentifier + "/question/new/mcq/";

    // Add URL parameters
    url += "?question=Generic True/False";
    if(dontKnow)
        url += "/Don't Know";
    url += " Question";

    // Add true/false
    url += "&choice-0=True";
    url += "&choice-1=False";

    if(dontKnow)
        url += "&choice-2=Don't Know";

    addGenericQuestion(url, "mcq");
}


/**
 * Add a new generic text question
 * @param long If a long text question
 */
function addGenericTextQuestion(long) {
    long = long===true;

    var sessionIdentifier = $("meta[name=sessionIdentifier]").attr("content").toString();

    // Get the question type from whether it is a long text question
    var type = long ? "textlong" : "text";
    var typeView = long ? "Long Text" : "Text";

    // Construct URL for API request
    var url = baseUrl + "api/session/" + sessionIdentifier + "/question/new/" + type + "/";

    // Add URL parameters
    url += "?question=Generic " + typeView + " Question";

    addGenericQuestion(url, type);
}

$("#add-question-submit").click(function() {

    var addQuestionSelect = $("#add-question-select");

    // If a custom question is selected
    if(addQuestionSelect.val() === "custom") {

        // Forward the user to the custom question page
        window.location = addQuestionSelect.attr("data-custom-href");
    }

    // Otherwise, actually add a question
    else {

        switch(addQuestionSelect.val()) {
            case "mcq_d":
                addGenericChoicesQuestion(4, "mcq");
                break;
            case "mcq_e":
                addGenericChoicesQuestion(5, "mcq");
                break;
            case "mcq_f":
                addGenericChoicesQuestion(6, "mcq");
                break;
            case "mcq_g":
                addGenericChoicesQuestion(7, "mcq");
                break;
            case "mcq_h":
                addGenericChoicesQuestion(8, "mcq");
                break;
            case "mrq_d":
                addGenericChoicesQuestion(4, "mrq");
                break;
            case "mrq_e":
                addGenericChoicesQuestion(5, "mrq");
                break;
            case "mrq_f":
                addGenericChoicesQuestion(6, "mrq");
                break;
            case "mrq_g":
                addGenericChoicesQuestion(7, "mrq");
                break;
            case "mrq_h":
                addGenericChoicesQuestion(8, "mrq");
                break;
            case "text":
                addGenericTextQuestion(false);
                break;
            case "textlong":
                addGenericTextQuestion(true);
                break;
            case "truefalse":
                addGenericTrueFalseQuestion(false);
                break;
            case "truefalsedk":
                addGenericTrueFalseQuestion(true);
                break;
        }
    }
});

/**
 * Activate or Deactivate a question
 * @param that
 * @param activate True if activate, False if deactivate
 */
function activateDeactivateQuestion(that, activate) {
    var listGroupItem = $(that).closest(".list-group-item");
    var questionList = listGroupItem.closest(".question-list");
    var questionControlMode = parseInt(questionList.attr("data-question-control-mode"));

    var sessionIdentifier = $("meta[name=sessionIdentifier]").attr("content").toString();

    // Construct URL for API request
    var url = baseUrl + "api/session/" + sessionIdentifier + "/question/" + $(that).attr("data-session-question-id") + "/edit/?active=";

    $(that).attr("disabled", "disabled");

    // If activate, send activate to url
    if(activate)
        url += "true";

    // If deactivate, send deactivate to url
    else
        url += "false";

    // Make an api request
    $.getJSON(url, function(data) {

        // If delete was successful, delete html element
        if(data["active"] === activate) {

            if(activate) {

                // If this is a teacher led session
                if (questionControlMode === 0) {
                    questionList.find("li.question-item.active-question").removeClass("active-question");
                }

                listGroupItem.addClass("active-question");
            }

            else {
                listGroupItem.removeClass("active-question");
            }
        }

        else {
            alerter({
                title: "Error",
                message: "Could not activate/deactivate session question for an unknown reason",
                type: "danger",
                dismissable: true
            });
        }
    })
    .always(function() {

        // Enable button once request is complete
        $(that).removeAttr("disabled");
    });
}