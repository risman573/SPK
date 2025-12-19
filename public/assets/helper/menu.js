function select_menu(base_url, element) {
    $(element).empty();
    $.ajax({
        url: base_url + "setting/menu/get",
        type: 'POST',
        datatype: 'json',
        success: function (data) {
            var dt = JSON.parse(data);
            for(var i=0; i<dt.length;i++){
                $(element).append('<option value="'+dt[i].id_menu+'">'+dt[i].menu_name+'</option>');
            }
        }
    });
    
//    $("#menu").kendoDropDownList({
//                dataTextField: "menu_name",
//                dataValueField: "id_menu",
//                height:400,
//                dataSource: {
//                    type: "odata",
//                    transport: {
//                        read: "{base_url}setting/menu/get",
//                    },
//                    group : {field: "parent_name"}
//                    transport : {
//                        read:{
//                            url : "{base_url}setting/menu/get",
//                            dataType : 'jsonp'
//                        }
//                    },
//                    group : {field : "parent_name"}
//                }
//                ,
//                filter: "contains",
//                suggest: true,
//                index: 3
//        });
}