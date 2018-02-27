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

$("#add-question-submit").click(function() {

    var addQuestionSelect = $("#add-question-select");

    // If a custom question is selected
    if(addQuestionSelect.val() === "custom") {

        // Forward the user to the custom question page
        window.location = addQuestionSelect.attr("data-custom-href");
    }

    // Otherwise, actually add a question
    else {

        var sessionIdentifier = $("meta[name=sessionIdentifier]").attr("content").toString();

        // Add the selected generic question, when done refresh
        addGenericQuestionFromCode(addQuestionSelect.val(), sessionIdentifier, function() {
            location.reload();
        });
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


/*******************************************************************************************************************
 * Make questions reorderable
 *******************************************************************************************************************/

var dragSrcEl = null;

function sessionDragStart(e) {
    // Target (this) element is the source node.
    dragSrcEl = this;

    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.outerHTML);

    this.classList.add('dragElem');
}
function sessionDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault(); // Necessary. Allows us to drop.
    }
    this.classList.add('over');

    e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

    return false;
}

function sessionDragEnter(e) {
    // this / e.target is the current hover target.
}

function sessionDragLeave(e) {
    this.classList.remove('over');  // this / e.target is previous target element.
}

function sessionDrop(e) {
    // this/e.target is current target element.

    if (e.stopPropagation) {
        e.stopPropagation(); // Stops some browsers from redirecting.
    }

    // Don't do anything if dropping the same column we're dragging.
    if (dragSrcEl != this) {
        // Set the source column's HTML to the HTML of the column we dropped on.
        //alert(this.outerHTML);
        //dragSrcEl.innerHTML = this.innerHTML;
        //this.innerHTML = e.dataTransfer.getData('text/html');
        this.parentNode.removeChild(dragSrcEl);
        var dropHTML = e.dataTransfer.getData('text/html');
        this.insertAdjacentHTML('beforebegin',dropHTML);
        var dropElem = this.previousSibling;
        addDnDHandlers(dropElem);

    }
    this.classList.remove('over');
    return false;
}

function sessionDragEnd(e) {
    // this/e.target is the source node.
    this.classList.remove('over');

    /*[].forEach.call(cols, function (col) {
      col.classList.remove('over');
    });*/
}

function addDnDHandlers(elem) {
    elem.addEventListener('dragstart', sessionDragStart, false);
    elem.addEventListener('dragenter', sessionDragEnter, false);
    elem.addEventListener('dragover', sessionDragOver, false);
    elem.addEventListener('dragleave', sessionDragLeave, false);
    elem.addEventListener('drop', sessionDrop, false);
    elem.addEventListener('dragend', sessionDragEnd, false);

}

var cols = document.querySelectorAll('.question-list .question-item');
[].forEach.call(cols, addDnDHandlers);