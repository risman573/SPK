
function select_kecamatan(base_url, element, condition) {
    if(condition==null){
        url = "master/wilayah/kecamatan/get";
    }else{
        url = "master/wilayah/kecamatan/getBy/id_kota/"+condition;
    }
    $(element).empty();
    $.ajax({
        url: base_url + url,
        type: 'POST',
        async: false,
        datatype: 'json',
        success: function (data) {
            var dt = JSON.parse(data);
            for(var i=0; i<dt.length;i++){
                $(element).append('<option value="'+dt[i].id+'">'+dt[i].nama+'</option>');
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
function select_kecamatan_kota(base_url, element, condition) {
    if(condition==null){
        url = "master/wilayah/kecamatan/get";
    }else{
        url = "master/wilayah/kecamatan/getBy/id_kota/"+condition;
    }
    $(element).empty();
    $.ajax({
        url: base_url + url,
        type: 'POST',
        async: false,
        datatype: 'json',
        success: function (data) {
            var dt = JSON.parse(data);
            for(var i=0; i<dt.length;i++){
                $(element).append('<option value="'+dt[i].id+'">'+dt[i].nama_kota+' - '+dt[i].nama+'</option>');
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