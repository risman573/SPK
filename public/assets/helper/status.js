
    
    function select_status(base_url, element){
        $(element).empty();
        $.ajax({
            url: base_url + "setting/combo/getCombo/combo/status",
            type: 'POST',
            datatype: 'json',
            success: function (data) {
                var dt = JSON.parse(data);
                for(var i=0; i<dt.length;i++){
                    $(element).append('<option value="'+dt[i].kode+'">'+dt[i].nama+'</option>');
                }
            }
        });
    }