$(".confirm-delete .confirm").click(function () {

    var listGroupItem = $(this).closest(".list-group-item");
    var sessionList = listGroupItem.closest(".session-list");

    // Make an api request
    var url = baseUrl + "api/session/" + $(this).attr("data-session-id") + "/delete/";
    $.getJSON(url, function(data) {

        // If delete was successful, delete html element
        if(data["success"] === "true") {
            listGroupItem.remove();
        }

        else {
            alert("Unknown Error");
        }

    });
});