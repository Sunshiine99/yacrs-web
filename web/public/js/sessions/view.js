$("#answer-update").click(function () {
    $(".answer[type=radio]").removeAttr("disabled");
    $(".answer-submit").removeClass("display-none");
    $(this).remove();
});