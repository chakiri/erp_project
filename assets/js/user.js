//Active desactive user
$(".user-data input[type='checkbox']").change(function () {
    var href = this.value;

    $.ajax({
        type: 'POST',
        url : href
    })

});

//Change role user
$(document).on("click", ".roleEdit button", function () {
    var url = $(this).data('url');
    var userId = $(this).data('id');

    $("#roleModal .roleSubmit").click(function () {

        var valueOption = $("#roleModal select").val();

        $.ajax({
            type: 'POST',
            url : url,
            data: {
                id : userId,
                option : valueOption
            },
            success : function () {
                $("#roleModal").modal("toggle");
                location.reload();
                //$("#table-data").load(window.location.href + " #table-data" );

            },
            fail : function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });
});
