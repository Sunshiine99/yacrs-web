$(".question-list .confirm-delete .confirm").click(function () {

    // Disable button clicked to indicate that something is happening
    $(this).attr("disabled", "disabled");

    var listGroupItem = $(this).closest(".list-group-item");

    // Find the list group
    var listGroup = listGroupItem.closest("ul.list-group");

    // Construct URL for API request
    var url = baseUrl + "api/session/" + $(this).attr("data-session-identifier") + "/question/" + $(this).attr("data-session-question-id") + "/delete/";

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

        //$($("li").get().reverse()).each(function() { /* ... */ });
        //$(listGroup.find("li.list-group-item").get().reverse()).each(function() { /* ... */ });

    });
    //location.reload();
});

$(".question-list .activate").click(function () {
    activateDeactivateQuestion(this, true);
});

$(".question-list .deactivate").click(function () {
    activateDeactivateQuestion(this, false);
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

    // Construct URL for API request
    var url = baseUrl + "api/session/" + $(that).attr("data-session-identifier") + "/question/" + $(that).attr("data-session-question-id") + "/edit/?active=";

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