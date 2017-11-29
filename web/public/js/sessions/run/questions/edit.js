mcqInit();

$("#questionType").change(function() {

    var questionType = $(this).val();
    switch(questionType) {
        case "mcq":
            mcqInit();
            break;
        case "text":
            textInit();
            break;
        case "textlong":
            textInit(true);
            break;
    }
});

/*******************************************************************************************
 * Multiple Choice Questions
 *******************************************************************************************/

function mcqInit() {
    $("#editQuestion").html(mcqEditTemplate);

    for (i = 0; i < 4; i++) {
        var id = "mcq-choice-"+i;

        // Add the input group
        $("#mcq-choices").append(mcqInputGroup(id));

        var choice = $("#"+id);

        // If the first, don't round bottom
        if(i === 0) {
            choice.addClass("not-rounded-bottom");
        }

        // If the last, don't round both
        else if(i === 4-1) {
            choice.addClass("not-rounded-top");
        }

        // Otherwise, don't round top or bottom
        else {
            choice.addClass("not-rounded-both");
        }
    }

    $("#mcq-add-option").click(function() {
        var choices = $('#mcq-choices');
        var id;

        // If there are no children
        if(choices.children().length === 0) {
            id = "mcq-choice-0";
        }

        // Otherwise get the last child and get the new id
        else {

            // Get the last choice
            var lastChoice = choices.children().last();

            // Get the string index
            var index = lastChoice.attr("id").lastIndexOf("-")+1;

            // Construct a new ID
            id = "mcq-choice-" + (parseInt(lastChoice.attr("id").substring(index))+1);

            // Change rounding class of previous last choice
            lastChoice.removeClass("not-rounded-top");
            lastChoice.addClass("not-rounded-both");
        }

        // Add new option
        choices.append(mcqInputGroup(id));

        // Change rounding class of new choice
        var choice = $("#"+id);
        choice.addClass("not-rounded-top");

        var deleteButton = $("#" + id + " .delete");
        initDeleteChoiceButton(deleteButton);
    });

    function initDeleteChoiceButton(deleteButton) {
        deleteButton.bind( "click", function() {
            msqChoiceDelete(this);
        });

        deleteButton.mouseover(function() {
            $(this).removeClass("btn-default");
            $(this).addClass("btn-danger");
        });

        deleteButton.mouseout(function() {
            $(this).removeClass("btn-danger");
            $(this).addClass("btn-default");
        });
    }

    var deleteButton = $(".mcq-choice .delete");
    initDeleteChoiceButton(deleteButton);
}

function mcqInputGroup(id) {
    return '<div id="'+id+'"class="input-group mcq-choice">\
                <input name="'+id+'" type="text" class="form-control" tabindex="1">\
                <span class="input-group-btn">\
                <button class="delete btn btn-default" type="button" tabindex="2">\
                    <i class="fa fa-trash-o" aria-hidden="true"></i>\
                </button>\
                </span>\
            </div>'
}

function msqChoiceDelete(that) {
    var choices = $('#mcq-choices');

    if(choices.children().length > 1) {
        $(that).closest(".mcq-choice").remove();
    }
    else {
        alert("You cannot delete all choices.");
    }
}

/*******************************************************************************************
 * Text
 *******************************************************************************************/

function textInit(long) {
    $("#editQuestion").html(textEditTemplate);
    if(long) {
        $("input#questionType").attr("value", "textlong");
    }
}