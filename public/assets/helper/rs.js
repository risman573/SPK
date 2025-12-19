/**function combobox_user(element, base_url) {
    $(element).combobox({
        url: base_url + 'index.php/master/user/get',
        method: 'post',
        mode: 'remote',
        valueField: 'id_user',
        textField: 'nama_user',
        //editable: false,
        onShowPanel: function () {
            $(this).combobox('reload');
        }
    });
}**/
function select_rs(base_url, element) {
    $(element).empty();
    $.ajax({
        url: base_url + "main/sebaran_rs/rs_list",
        type: 'POST',
        async: false,
        datatype: 'json',
        success: function (data) {
            var dt = JSON.parse(data);
            for(var i=0; i<dt.length;i++){
                $(element).append('<option value="'+dt[i].nama+'">'+dt[i].nama+' </option>');
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