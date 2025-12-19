function select_provinsi(base_url, element) {
    $(element).empty();
    $.ajax({
        url: base_url + "api/provinsi",
        type: 'GET',
        async: false,
        datatype: 'json',
        success: function (data) {
            // var dt = JSON.parse(data);
            var dt = data;
            for(var i=0; i<dt.length;i++){
                $(element).append('<option value="'+dt[i].provinsi+'">'+dt[i].provinsi+'</option>');
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