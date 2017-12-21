$("#questionType").change(function() {
    var question = $(".question");

    // Switch on question type
    switch($(this).val()) {
        case "mcq":
            question.css("display", "none");
            $("#question-mcq").css("display", "flex");
            break;
        case "text":
        case "textlong":
            question.css("display", "none");
            $("#question-text").css("display", "flex");
            break
    }
});