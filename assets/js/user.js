
$(".user-data input[type='checkbox']").change(function () {
    var href = this.value;

    $.ajax({
        url : href
    })

})