$(".question-list .confirm-delete .confirm").click(function () {
    var listGroupItem = $(this).closest(".list-group-item");
    listGroupItem.remove();
});