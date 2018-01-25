$(".confirm-delete .confirm").click(function () {

    var listGroupItem = $(this).closest(".list-group-item");
    var sessionList = listGroupItem.closest(".session-list");

    listGroupItem.remove();
});