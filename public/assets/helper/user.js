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
function select_user(base_url, element) {
    $(element).empty();
    $.ajax({
        url: base_url + "setting/user/get",
        type: 'POST',
        datatype: 'json',
        success: function (data) {
            var dt = JSON.parse(data);
            $(element).kendoComboBox({
                dataTextField: "name",
                dataValueField: "id_user",
                dataSource: dt,
                filter: "contains",
                suggest: true,
//                index: 2
            });
        }
    });
}
function select_user2(base_url, element) {
    $(element).empty();
    $.ajax({
        url: base_url + "setting/user/get",
        type: 'POST',
        datatype: 'json',
        success: function (data) {
            var dt = JSON.parse(data);
            $(element).kendoComboBox({
                dataTextField: "name",
                dataValueField: "name",
                dataSource: dt,
                filter: "contains",
                suggest: true,
//                index: 3
            });
        }
    });
}