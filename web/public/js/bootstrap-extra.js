function onclickHref(that) {
    document.location = $(that).attr("data-href");
}

/********************************************************************
 * Input Add More
 ********************************************************************/

$(".input-add-more-button .input-add-more-input").click(function () {

    // Find the add more button
    var inputAddMoreButton = $(this).closest(".input-add-more-button");

    // Load the container using the data attribute
    var dataInputContainer = $("#" + inputAddMoreButton.attr("data-input-container-id"));

    var dataInputContainerFirstChild = dataInputContainer.find("> :first-child");

    if(dataInputContainerFirstChild.css("visibility") === "hidden") {
        dataInputContainerFirstChild.css("visibility", "visible")
    }

    else {

        // Get previous last child
        var input = dataInputContainer.find(":last-child .input-add-more-input");

        // Get position of last "-" + 1
        var pos = input.attr("name").lastIndexOf("-") + 1;

        var nextNamePrefix = input.attr("name").substr(0, pos);
        var nextNameNum = parseInt(input.attr("name").substr(pos)) + 1;
        var nextName = nextNamePrefix + nextNameNum;

        // Add new item using the first
        dataInputContainer.append(dataInputContainerFirstChild[0].outerHTML);

        // Get new last child
        input = dataInputContainer.find(":last-child .input-add-more-input");

        // Clear input values in this new item
        input.attr("value", "");
        input.attr("name", nextName);
        input.attr("id", nextName);

        initAddMoreDelete(dataInputContainer.find(":last-child .input-add-more-input.delete"));
    }
});

function initAddMoreDelete(deleteButton) {
    deleteButton.bind( "click", function() {
        addMoreDelete(this);
    });

    deleteButton.mouseover(function() {
        $(this).removeClass("btn-light");
        $(this).addClass("btn-danger btn-danger-border");
    });

    deleteButton.mouseout(function() {
        $(this).removeClass("btn-danger btn-danger-border");
        $(this).addClass("btn-light");
    });
}

function addMoreDelete(that) {

    var inputAddMoreContainer = $(that).closest(".input-add-more-container");

    // If this isn't the minimum number of inputs
    if(inputAddMoreContainer.children().length > parseInt(inputAddMoreContainer.attr("data-minimum-count"))) {

        // If there is only one child left, only hide it
        if(inputAddMoreContainer.children().length === 1) {
            $(that).closest(".input-add-more-item").css("visibility", "hidden");
        }

        // Otherwise, remove the item
        else {
            $(that).closest(".input-add-more-item").remove();
        }
    }
}

initAddMoreDelete($(".input-add-more-container .input-add-more-input.delete"));

/********************************************************************
 * Confirm Delete
 ********************************************************************/

$(".actions-confirm-delete .actions .delete").click(function() {
    var actions = $(this).closest(".actions");
    var actionsConfirmDelete = $(this).closest(".actions-confirm-delete");
    var confirmDelete = actionsConfirmDelete.find(".confirm-delete");
    actions.css("display", "none");
    confirmDelete.css("display", "inline-flex");
});

$(".confirm-delete .cancel").click(function() {
    var actionsConfirmDelete = $(this).closest(".actions-confirm-delete");
    var actions = actionsConfirmDelete.find(".actions");
    var confirmDelete = actionsConfirmDelete.find(".confirm-delete");
    confirmDelete.css("display", "none");
    actions.css("display", "inline-flex");
});

/********************************************************************
 * Click Href, Click Post
 ********************************************************************/

//var redirect = 'http://www.website.com/page?id=23231';
//$.redirectPost(redirect, {x: 'example', y: 'abc'});

/**
 * Extend jQuery to have function redirectPost which redirects the user with post data
 */
/*
*/
$.extend({
    redirectPost: function(location, args) {
        var form = '';
        $.each( args, function( key, value ) {
            form += '<input type="hidden" name="'+key+'" value="'+value+'">';
        });
        $('<form action="'+location+'" method="POST">'+form+'</form>').appendTo('body').submit();
    }

});