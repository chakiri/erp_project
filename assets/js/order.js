//Modal confirm delete order
$(document).on("click", ".confirmDelete button", function ()
{
        var url = $(this).data('url');
        $('#confirmDeleteModal .removeOrderSubmit').attr("href", url);
});