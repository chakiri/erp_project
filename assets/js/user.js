
$(".user-data input[type='checkbox']").change(function () {

    var href = this.value;

    if ($(this).is(":checked")){
        $.ajax({
            url : href
        })
    }

})