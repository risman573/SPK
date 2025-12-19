function select_surah(base_url, element) {
    $(element).empty();
    $.ajax({
        url: base_url + "quran/surah/get",
        type: 'POST',
        async: false,
        datatype: 'json',
        success: function (data) {
            var dt = JSON.parse(data);
            for(var i=0; i<dt.length;i++){
                $(element).append('<option value="'+dt[i].nomor+'">'+dt[i].nomor+' '+dt[i].nama_latin+' ('+dt[i].nama+')</option>');
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