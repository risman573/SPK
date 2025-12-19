<?=view('header_view'); ?>


<style>
#list_menu {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#list_menu td, #list_menu th {
  padding: 8px;
}

#list_menu tr:nth-child(even){background-color: #f2f2f2;}

#list_menu tr:hover {background-color: #ddd;}

#list_menu th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}
</style>


<div class="container-fluid bg-light-opac mb-4 main-container">
    <div class="row">
        <div class="container-fluid my-3 main-container">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="content-color-primary page-title">User Previlege</h2>
                    <p class="content-color-secondary page-sub-title"> </p>
                </div>
                <div class="col-auto">
                        <?php if ($tambah == "1") {?>
                        <button class="btn btn-rounded <?=$default['themeColor']?> text-white btn-icon text-uppercase pr-3" data-toggle="modal" data-target="#modal_form" onclick="tambah()">
                            <i class="material-icons">add</i> 
                            <span class="text-hide-xs">New</span>
                        </button>
                        <?php }?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-4 main-container">
    <div class="row">
        <div class="col-sm-12">
            <div class="card mb-4 fullscreen">
                <div class="card-header">
                    <div class="media">
                        <div class="media-body">
                            <h4 class="content-color-primary mb-0"> </h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="dt_default" class="table table-striped table-bordered" width="100%">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 2000px;">
        <div class="modal-content" style="width:80%; margin-left:auto; margin-right:auto;">
            <div class="modal-header <?=$default['themeColor']?> text-white">
                <h5 class="modal-title" id="exampleModalLabel">FORM</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <form id="form">
                    <input type="hidden" class="md-input" id="id_group" name="id_group"/>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="kode" class="ukcontrol-label">Group Name</label>
                            </div>
                            <div class="col-md-8">
                                <input id="group_name" name="group_name" class="form-control" placeholder="Enter Menu Name" class="" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                       <div class="row">
                          <div class="col-md-4">
                             <label class="control-label">Status</label>
                          </div>
                            <div class="col-md-8">
                                <select id="status" name="status" data-placeholder="Choose a Country..." class="chosen_select form-control" tabindex="1"></select>
                            </div>
                       </div>
                    </div>        
                    </form>

                        
                    <h3 class="heading_b uk-margin-bottom">List Menu</h3><hr>
                    <div style="height: 400px;overflow-x:auto;">
                        <form id="form_auth" name="form_auth" enctype="multipart/form-data">
                            <table id="list_menu" class="stripe" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th align="center">Menu Name</th>
                                        <th align="center">Addable</th>
                                        <th align="center">Updateable</th>
                                        <th align="center">Deleteable</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>  
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" onclick="simpan()">Submit</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">cancel</button>
            </div>
        </div>
    </div>
</div>








<script type="text/javascript" src="<?=$base_url?>assets/helper/status.js"></script>

<script>
    select_status('<?=$base_url?>', '#status');

    var save_method;
    var table;
    
    
    $(document).ready(function () {        
        table = $('#dt_default').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthMenu: [<?=$default['listRowInPage']?>, <?=$default['listRowInPage']?>],
            ajax: {
                url:"<?=$base_url?>setting/user_group/ajax_list",
                type: "POST"
            },
            columns: [
              { title: "Group Name" },
              { title: "Status" },
              { title: "Created User" },
              { title: "Created Date" },
              { title: "Modified User" },
              { title: "Modified Date" },
              { title: "Action" },
            ],
            aoColumnDefs: [
                { "bVisible": <?=$default['showCreated']?>, "aTargets": [2,3] },
                { "bVisible": <?=$default['showModified']?>, "aTargets": [4,5] }
            ],
            columnDefs: [
                {
                    targets: [-1],
                    orderable: false
                },
//                {width: "150px", targets: -1}
            ],
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
        $("#id_group").val('');
        $('#list_menu tbody').html('');
        getListMenu('');
    }

    function simpan() {
        var url;
        var id_group;
        
        if (save_method == 'add') {
            url = "<?=$base_url?>setting/user_group/add";
        } else {
            url = "<?=$base_url?>setting/user_group/update";
        }
        
        $.ajax({
            url: url,
            type: "POST",
            //async : false,
            data: {
                id_group: $("#id_group").val(),
                group_name: $("#group_name").val(), 
                status: $("#status").val(),
                modified_date: '',
                modified_user: '',
                method: save_method
            },
            dataType: "JSON",
            success: function (data)
            {
                if (data.success) {
                    if(save_method == 'add'){
                        id_group = data.id;
                    }else{
                        id_group = $("#id_group").val();
                    }
                    simpan_auth(id_group);
                    reload_table();
                    toaster('Success', '', 'success');
                    $('#modal_form').modal('hide');
                } else {
                     toaster('Failed', data.msg, 'error');
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
    
    function simpan_auth(id_group){
        //var url;       
        //var data = new FormData($('form#form_auth')[0]);
        var data = $('form#form_auth').serialize();
        $.ajax({
            url : '<?=$base_url?>setting/authority/add_auth/'+id_group,
            type : 'POST',
            data : data,
            async : false,
            success : function(r){
                console.log(r);
            },
            error : function(r){
                console.log(r);
            }
        });
    }

    function edit(id) {
        save_method = 'update';
        $('#form')[0].reset();

        $.ajax({
            url: "<?=$base_url?>setting/user_group/edit/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $("#id_group").val(data.id_group);
                $("#group_name").val(data.group_name);
                $("#status").val(data.status);
                $('#list_menu tbody').html('');
                getListMenu(data.id_group);
                $('#modal_form').modal('show');
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

    function getListMenu(id){
        var url;
        if(id==''){
            url = '<?=$base_url?>setting/authority/get_list';
        }else{
            url = '<?=$base_url?>setting/authority/get_list/'+id;
        }
        $.ajax({
            url : url,
            type: 'POST',
            async: false,
            success : function(r){
                //if(r!=''){
                $('#list_menu tbody').html(r);
                //}
            },
            error: function (r){
                console.log(r);
            }
        });
    }

    function hapus(id) {
        var question = confirm("Are you sure ?");
        if (question) {
            $.ajax({
                url: "<?=$base_url?>setting/user_group/delete/" + id,
                type: "POST",
                dataType: "JSON",
                success: function (data)
                {
                    if (data.success) {
                        reload_table();
                        toaster('Success', 'Deleted', 'success');
                    } else {
                         toaster('Failed', data.msg, 'error');
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
    }

    function clearFileInput(id) {
        $('#' + id).html($('#' + id).html());
    }
	
    function setcek(id){
		console.log(id);
        $('#text'+id).val(document.getElementById(id).checked == true ? 1 : 0);
    }

    

</script>


<?=view('foother_view'); ?>