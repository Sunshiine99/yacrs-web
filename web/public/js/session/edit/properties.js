$("#defaultTimeLimitEnable").change(function () {
    $("#defaultTimeLimit").prop("disabled", !this.checked);
});

/**
 * When the "View Advanced Settings" button is clicked
 */
$("#view-advanced-settings").click(function() {

    // Show all of the advanced settings
    $(".advanced").css("display", "flex");

    // Hide the show advanced settings button
    $(this).css("display", "none");

    // Show the hide advanced settings button
    $("#hide-advanced-settings").css("display", "flex");
});

/**
 * When the "Hide Advanced Settings" button is clicked
 */
$("#hide-advanced-settings").click(function() {

    // Hide all of the advanced settings
    $(".advanced").css("display", "none");

    // Hide the hide advanced settings button
    $(this).css("display", "none");

    // Show the view advanced settings button
    $("#view-advanced-settings").css("display", "flex");
});

var choiceDefaultChanged = false;
var choiceDefaultCurrent = 1;

function userChange(that) {

    // If this is a new session,
    if(UserNew) {
        choiceDefaultChanged = true;
        $(that).attr("modified", "true");
    }
}

function userClick(that) {

    // If this is a new session,
    if(UserNew) {
        that = $(that);
        if (that.attr("modified") !== "true") {
            that.select();
        }
    }
}

function userDeleteClick() {

    // If the default input values have not been changed yet, this is a new session
    if(!choiceDefaultChanged && UserNew && choiceDefaultCurrent > 0) {
        choiceDefaultCurrent--;
    }
}

$(".input-add-more-button .input-add-more-input").click(function () {

    // If the default input values have not been changed yet and this is a new session
    if(!choiceDefaultChanged && UserNew) {

        var input = $("#add-more-choices > :last-child > input.user");

        // Get the choice index
        var name = input.attr("name").substr(11);

        var UserChoice = $("#add-more-choices > :last-child > input.user");

        // Update names and IDs of new inputs
        UserChoice.attr("id", "user-" + name);
        UserChoice.attr("name", "user-" + name);

        // Increment the current default choice
        choiceDefaultCurrent++;

        $("#add-more-choices > :last-child > button.delete").click(function() {
            userDeleteClick();
        });

        $("#add-more-choices > :last-child > input").change(function() {
            userChange(this);
        }).click(function() {
            userClick(this);
        })
    }
});

$(".input-add-more-item input.input-add-more-input").change(function() {
    userChange(this);
});

$(".input-add-more-item input.input-add-more-input").click(function() {
    userClick(this);
});

$(".input-add-more-container .input-add-more-item button.delete").click(function () {
    userDeleteClick();
});