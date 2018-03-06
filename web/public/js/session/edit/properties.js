$("#defaultTimeLimitEnable").change(function () {
    $("#defaultTimeLimit").prop("disabled", !this.checked);
});

/**
 * When the "View Advanced Settings" button is clicked
 */
$("#view-advanced-settings").click(function() {

    // Show all of the advanced settings
    $(".advanced").css("display", "flex");

    // Hide all of the basic settings
    //$(".basic").css("display", "none");

    // Hide the show advanced settings button
    $(this).css("display", "none");

    // Show the hide advanced settings button
    $("#hide-advanced-settings").css("display", "flex");
});

/**
 * When the "Hide Advanced Settings" button is clicked
 */
$("#hide-advanced-settings").click(function() {

    //Show all basic settings
    //$(".basic").css("display", "flex");

    // Hide all of the advanced settings
    $(".advanced").css("display", "none");

    // Hide the advanced settings button
    $(this).css("display", "none");

    // Show the view advanced settings button
    $("#view-advanced-settings").css("display", "flex");
});

var choiceDefaultChanged = false;
var choiceDefaultCurrent = 1;
var deletedSecond = false;

function userChange(that) {

    // If this is a new session,
    if(SessionNew) {
        choiceDefaultChanged = true;
        $(that).attr("modified", "true");
    }
}

function userClick(that) {

    // If this is a new session,
    if(SessionNew) {
        that = $(that);
        if (that.attr("modified") !== "true") {
            that.select();
        }
    }
}

function userDeleteClick() {
    
    // If the default input values have not been changed yet, this is a new session
    if(!choiceDefaultChanged && SessionNew && choiceDefaultCurrent > 0) {
        choiceDefaultCurrent--;
    }
    else if($('.input-add-more-item').get().length == 1 && deletedSecond){
        $('.input-add-more-item').css('visibility', 'hidden');
    }
    if($('.input-add-more-item #user-1').length == 0){
        deletedSecond = true;
    }
}

$(".input-add-more-button .input-add-more-input").click(function () {

    if($('.input-add-more-item').get().length == 1){
        $('.input-add-more-item').css('visibility', 'visible');
    }

    // If the default input values have not been changed yet and this is a new session
    if(!choiceDefaultChanged && SessionNew) {

        var input = $("#add-more-choices > :last-child > input.user");

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