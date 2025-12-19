function select_imam(base_url, element) {
    $(element).empty();
    $.ajax({
        url: base_url + "hadist/imam/get",
        type: 'POST',
        async: false,
        datatype: 'json',
        success: function (data) {
            var dt = JSON.parse(data);
            for(var i=0; i<dt.length;i++){
                $(element).append('<option value="'+dt[i].identifier+'">'+dt[i].name+' </option>');
            }
            $.unblockUI();
        },
        beforeSend: function(){
            $.blockUI();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            $.unblockUI();
            console.log(errorThrown);
        }
    });
}