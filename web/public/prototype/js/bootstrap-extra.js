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

        // Add new item using the first
        dataInputContainer.append(dataInputContainerFirstChild[0].outerHTML);

        // Clear input values in this new item
        dataInputContainer.find(":last-child .input-add-more-input").attr("value", "");

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

    // If there is only one child left, only hide it
    if($(that).closest(".input-add-more-container").children().length === 1) {
        $(that).closest(".input-add-more-item").css("visibility", "hidden");
    }

    // Otherwise, remove the item
    else {
        $(that).closest(".input-add-more-item").remove();
    }
}

initAddMoreDelete($(".input-add-more-container .input-add-more-input.delete"));

/********************************************************************
 * Confirm Delete
 ********************************************************************/

$(".actions-confirm-delete .actions .delete").click(function () {
    var actions = $(this).closest(".actions");
    var actionsConfirmDelete = $(this).closest(".actions-confirm-delete");
    var confirmDelete = actionsConfirmDelete.find(".confirm-delete");
    actions.css("display", "none");
    confirmDelete.css("display", "inline-flex");
});

$(".confirm-delete .cancel").click(function () {
    var actionsConfirmDelete = $(this).closest(".actions-confirm-delete");
    var actions = actionsConfirmDelete.find(".actions");
    var confirmDelete = actionsConfirmDelete.find(".confirm-delete");
    confirmDelete.css("display", "none");
    actions.css("display", "inline-flex");
});