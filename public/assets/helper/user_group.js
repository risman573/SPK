function select_group(base_url, element) {
    $(element).empty();
    $.ajax({
        url: base_url + "setting/user_group/get",
        type: 'POST',
        datatype: 'json',
        success: function (data) {
            var dt = JSON.parse(data);
            for(var i=0; i<dt.length;i++){
                $(element).append('<option value="'+dt[i].id_group+'">'+dt[i].group_name+'</option>');
            }
        }
    });
}

function select_group2(base_url, element) {
    $(element).empty();
    $.ajax({
        url: base_url + "setting/user_group/getGrup",
        type: 'POST',
        datatype: 'json',
        success: function (data) {
            var dt = JSON.parse(data);
            $(element).kendoComboBox({
                dataTextField: "group_name",
                dataValueField: "id_group",
                dataSource: dt,
                filter: "contains",
                suggest: true,
                index: 3
            });
        }
    });
}