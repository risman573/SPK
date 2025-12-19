
function select_kota(base_url, element, condition) {
    if(condition==null){
        url = "api/kota";
    }else{
        url = "api/kota?provinsi="+condition;
    }
    $(element).empty();
    $.ajax({
        url: base_url + url,
        type: 'GET',
        async: false,
        datatype: 'json',
        success: function (data) {
            // var dt = JSON.parse(data);
            var dt = data;
            for(var i=0; i<dt.length;i++){
                $(element).append('<option value="'+dt[i].kota+'">'+dt[i].kota+'</option>');
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