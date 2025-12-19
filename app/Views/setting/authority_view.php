
<div class="page-box">
    <div class="datatables-example-heading">
        <div class="row">
            <div class="col-md-6">
                <h3>Authority</h3>
            </div>

            <?php if ($addable == "1") {?>
            <div class="col-md-6 right" align="right">
                <a class="btn btn-info btn-lg btn-rounded" data-toggle="modal" data-target="#modal_form" onclick="tambah()" href="#" >
                    New
                </a>
            </div>
            <?php } ?>
        </div><hr>
    </div>
    <table id="dt_default" class="table display table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>User Group</th>
                <th>Menu</th>
                <th>Addable</th>
                <th>Editable</th>
                <th>Deleteable</th>
                <th>Status</th>                    
                <th>Modified Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<!--<div class="uk-modal" id="modal_form">-->
    <div class="modal-dialog" style="width: 40%">
    <!--<div class="modal-content">-->
        
        <div class="page-box">
           <div class="form-example">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3> Add New</h3>
              <div class="form-wrap label-left form-layout-page">
                 <form id="form">
                    <input type="hidden" class="md-input" id="id_auth" name="id_auth"/>
                    <div class="form-group">
                        <div class="row">
                            <div class="uk-grid">
                                <div class="col-md-4">
                                    <label for="kode" class="ukcontrol-label">Group Name</label>
                                </div>
                                <div class="col-md-8">
                                    <input id="group_name" name="group_name" placeholder="Select Group" class="" style="width: 100%;" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                       <div class="row">
                          <div class="col-md-4">
                             <label class="control-label">Menu</label>
                          </div>
                          <div class="col-md-8">
                             <input id="menu" name="menu" placeholder="Select Menu" class="form-control" style="width: 100%;" />
                          </div>
                       </div>
                    </div>
                    <div class="form-group">
                       <div class="row">
                          <div class="col-md-4">
                             <label class="control-label">Access</label>
                          </div>
                          <div class="col-md-8">
                             <input id="addable" name="addable" class="toggle toggle-modern" type="checkbox" /> Add
                             <input id="updateable" name="updateable" class="toggle toggle-modern" type="checkbox" /> Update
                             <input id="deleteable" name="deleteable" class="toggle toggle-modern" type="checkbox" /> Delete
                          </div>
                       </div>
                    </div>
                    <div class="form-group">
                       <div class="row">
                          <div class="col-md-4">
                             <label class="control-label">Status</label>
                          </div>
                          <div class="col-md-8">
                              <input type="" id="status" name="status" placeholder="Enter Status" class="select2" width="100px" />
                          </div>
                       </div>
                    </div>
                    <div class="form-group">
                       <div class="row">
                          <div class="col-md-12">
                             <div class="form-layout-submit">
                                <button type="button" class="btn btn-info" onclick="simpan()">Submit</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">cancel</button>
                             </div>
                          </div>
                       </div>
                    </div>
                 </form>
              </div>
           </div>
        </div>
    <!--</div>-->
    </div>
</div>


<script type="text/javascript" src="{base_url}asset/js/helper/status.js"></script>
<script type="text/javascript" src="{base_url}asset/js/helper/menu.js"></script>
<script type="text/javascript" src="{base_url}asset/js/helper/user_group.js"></script>

<script>       
//    select_status('#status');
    select_status('{base_url}', '#status');
    select_menu('{base_url}','#menu');
    select_group('{base_url}','#group_name');
    
    var save_method;
    var table;
    
    
    $(document).ready(function () { 
        table = $('#dt_default').DataTable({
            pagingType: "numbers",
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url:"{base_url}setting/authority/ajax_list/{id_auth}",
                type: "POST"
            },
            columnDefs: [
                {
                    targets: [-1],
                    orderable: true
                },
                {width: "50px", targets: 0}
            ],
            aoColumns: [
                {"sClass": "center"},
                {"sClass": "center"},
                {"sClass": "center"},
                {"sClass": "center"},
                {"sClass": "center"},
                {"sClass": "center"},
                {"sClass": "center"},
                {"sClass": "center"}
            ]
        });               
    });
    

    function reload_table() {
        table.ajax.reload(null, false);
    }

    $('#modal_form').on('hide.uk.modal', function (e) {
        reload_table();
    });

    function tambah() {
        save_method = 'add';
        $('#form')[0].reset();
//        $("#addable").attr("checked",false);
//        $("#updateable").attr("checked",false);
//        $("#deleteable").attr("checked",false);
        $("#id_auth").val('');
    }

    function simpan() {
        var url;
        if (save_method == 'add') {
            url = "{base_url}setting/authority/add";
        } else {
            url = "{base_url}setting/authority/update";
        }
        
        var addable = 0;
        if($("#addable").is(":checked")){
            addable = 1;
        }
        
        var updateable = 0;
        if($("#updateable").is(":checked")){
            updateable = 1;
        }
        
        var deleteable= 0;
        if($("#deleteable").is(":checked")){
            deleteable = 1;
        }
        $.ajax({
            url: url,
            type: "POST",
            data: {
                id_auth: $("#id_auth").val(),
                id_group: $("#group_name").val(),                
                id_menu: $("#menu").val(),                
                addable: addable,
                updateable: updateable,
                deleteable: deleteable,
                status: $("#status").val(),
                modified_date: '',
                method: save_method
            },
            dataType: "JSON",
            success: function (data)
            {
                if (data.success) {
                    reload_table();
                    swal("Data Saved", "", "success");
                    $('#modal_form').modal('hide');
//                    ("#modal_form").hide();
                } else {
                    swal("Failed", "Please Chek Your Data ", "error");
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                console.log(errorThrown);
            }
        });
    }

    function edit(id) {
        save_method = 'update';
        $('#form')[0].reset();

        $.ajax({
            url: "{base_url}setting/authority/edit/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $("#id_auth").val(data.id_auth);                
                $("#group_name").data("kendoComboBox").value(data.id_group);                
                $("#menu").data("kendoComboBox").value(data.id_menu);
                if(data.addable == '1'){
                   $("#addable").attr("checked",true); 
                }else{
                   $("#addable").attr("checked",false); 
                }
                if(data.updateable == '1'){
                   $("#updateable").attr("checked",true); 
                }else{
                   $("#updateable").attr("checked",false); 
                }
                if(data.deleteable == '1'){
                   $("#deleteable").attr("checked",true); 
                }else{
                   $("#deleteable").attr("checked",false); 
                }
                $("#status").data("kendoComboBox").value(data.status);
                $('#modal_form').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                console.log(errorThrown);
            }
        });
    }

    

    function hapus(id) {
         swal({
            title: "Are you sure!",
            type: "error",
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes!",
            showCancelButton: true,
        }, function () {
           
            $.ajax({
                url: "{base_url}setting/authority/delete/" + id,
                type: "POST",
                dataType: "JSON",
                success: function (data)
                {
                    if (data.success) {
                        reload_table();
                        swal("Deleted", "", "success");
                    } else {
                        swal("Failed", " ", "error");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    console.log(errorThrown);
                }
            });
        });
    }

    function clearFileInput(id) {
        $('#' + id).html($('#' + id).html());
    }

    

</script>
