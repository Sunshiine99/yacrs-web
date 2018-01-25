$("#defaultTimeLimitEnable").change(function () {
    $("#defaultTimeLimit").prop("disabled", !this.checked);
});

$("#advanced-settings").click(function() {
   $(".advanced").removeClass("advanced");
   $(this).remove();
});
