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
