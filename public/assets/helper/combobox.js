
    
    function combobox(base_url, element, flag){
        $(element).empty();
        $.ajax({
            url: base_url + "setting/combo/getCombo/combo/"+flag,
            type: 'POST',
            datatype: 'json',
            async: false,
            success: function (data) {
                var dt = JSON.parse(data);
                // $(element).append('<option value="">-</option>');
                for(var i=0; i<dt.length;i++){
                    $(element).append('<option value="'+dt[i].kode+'">'+dt[i].nama+'</option>');
                }
            },
            beforeSend: function(){                
            },
        });
    }