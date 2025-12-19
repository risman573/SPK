
function select_kelurahan(base_url, element, condition) {
    if(condition==null){
        url = "master/wilayah/kelurahan/get";
    }else{
        url = "master/wilayah/kelurahan/getBy/id_kecamatan/"+condition;
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
function select_kelurahan_kecamatan(base_url, element, condition) {
    if(condition==null){
        url = "master/wilayah/kelurahan/get";
    }else{
        url = "master/wilayah/kelurahan/getBy/id_kecamatan/"+condition;
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
                $(element).append('<option value="'+dt[i].id+'">'+dt[i].nama_kecamatan+' - '+dt[i].nama+'</option>');
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