<?=view('header_view'); ?>


<div class="container-fluid bg-light-opac mb-4 main-container">
    <div class="row">
        <div class="container-fluid my-3 main-container">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="content-color-primary page-title">User Account</h2>
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
        <div class="modal-content card shadow-sm border-0 mb-4 col-sm-12 col-md-10 col-lg-8 col-xl-6" style="margin-left:auto; margin-right:auto;">
        <form id="form">
            <div class="modal-header <?=$default['themeColor']?> text-white">
                <h5 class="modal-title" id="exampleModalLabel">FORM</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" class="md-input" id="id_user" name="id_user"/>
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Data</h4><hr>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4"><label for="name" class="ukcontrol-label">Name</label></div>
                                    <div class="col-md-8"><input id="name" name="name" class="form-control" placeholder=""  /></div>
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: -10px;">
                                <div class="row">
                                    <div class="col-md-4"><label class="control-label">Username</label></div>
                                    <div class="col-md-8"><input type="username" id="username" name="username" placeholder="" class="form-control" /></div>
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: -10px;">
                                <div class="row">
                                    <div class="col-md-4"><label class="control-label">Password</label></div>
                                    <div class="col-md-8">
                                        <input type="password" id="password" name="password" placeholder="" class="form-control" />
                                    <input type="checkbox" value="1" onclick="showPass();">Show Password
                                </div>
                            </div>
                            </div>
                            <div class="form-group" style="margin-top: -10px;">
                                <div class="row">
                                    <div class="col-md-4"><label class="control-label">Address</label></div>
                                    <div class="col-md-8"><textArea id="alamat" name="alamat" placeholder="" class="form-control" /></textArea></div>
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: -10px;">
                                <div class="row">
                                    <div class="col-md-4"><label class="control-label">Telp</label></div>
                                    <div class="col-md-8"><input type="text" id="telp" name="telp" placeholder="" class="form-control" /></div>
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: -10px;">
                                <div class="row">
                                    <div class="col-md-4"><label class="control-label">Status</label></div>
                                    <div class="col-md-8"><select id="status" name="status" data-placeholder="Select Status..." class="chosen_select form-control" tabindex="1" onchange=""></select></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>Access</h4><hr>
                            <div class="form-group" style="margin-top: -10px;">
                                <div class="row">
                                    <div class="col-md-4"><label class="control-label">User Group</label></div>
                                    <div class="col-md-8"><select id="user_group" name="user_group" data-placeholder="Select User Group..." class="chosen_select form-control" tabindex="1" onchange=""></select></div>
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: -10px;">
                                <div class="row">
                                    <div class="col-md-4"><label class="control-label">Level</label></div>
                                    <div class="col-md-8">
                                        <select id="level" name="level" data-placeholder="Select Level..." class="chosen_select form-control" tabindex="1" onchange="showHide()">
                                            <option value="0">Super Admin</option>
                                            <option value="1">Admin</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="cabang_group" style="margin-top: -10px;">
                                <div class="row">
                                    <div class="col-md-4"><label class="control-label">Cabang</label></div>
                                    <div class="col-md-8"><select id="cabang" name="cabang" data-placeholder="Select Cabang..." class="chosen_select form-control" tabindex="1" onchange=""></select></div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" onclick="simpan()">Submit</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">cancel</button>
            </div>
        </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?=$base_url?>assets/helper/status.js"></script>
<script type="text/javascript" src="<?=$base_url?>assets/helper/user_group.js"></script>
<script type="text/javascript" src="<?=$base_url?>assets/helper/combobox.js"></script>
<script type="text/javascript" src="<?=$base_url?>assets/helper/cabang.js"></script>

<script>
    select_status('<?=$base_url?>', '#status');
    select_group('<?=$base_url?>','#user_group');
    select_cabang('<?=$base_url?>','#cabang');

    var save_method;
    var table;
    
    
    $(document).ready(function () {        
        var level;
        var url;
        
        table = $('#dt_default').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthMenu: [<?=$default['listRowInPage']?>, <?=$default['listRowInPage']?>],
            ajax: {
                url: "<?=$base_url?>setting/user/ajax_list",
                async: false,
                type: "POST",
                data: function(user){
                        user.level = status;
                },
            },
            columns: [
              { title: "Name" },
              { title: "Username" },
              { title: "Group Name" },
              { title: "Cabang" },
              { title: "Status" },
              { title: "Created User" },
              { title: "Created Date" },
              { title: "Modified User" },
              { title: "Modified Date" },
              { title: "Action" },
            ],
            aoColumnDefs: [
                { "bVisible": <?=$default['showCreated']?>, "aTargets": [5,6] },
                { "bVisible": <?=$default['showModified']?>, "aTargets": [7,8] }
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

    function showHide() {
        var level = $("#level").val();
        if (level == 1) {
            $('#cabang_group').show();
        } else {
            $('#cabang_group').hide();
            $("#cabang").val('');
            $("#cabang").trigger("chosen:updated");
        }
    }

    function tambah() {
        save_method = 'add';
        $('#form')[0].reset();
        $("#id_user").val('');
        $("#level").val("0");
        showHide();
        document.getElementById("password").type = "password";
    }

    function simpan() {
        var url;
        if (save_method == 'add') {
            url = "<?=$base_url?>setting/user/add";
        } else {
            url = "<?=$base_url?>setting/user/update";
        }
        
        $.ajax({
            url: url,
            type: "POST",
            data: {
                id_user: $("#id_user").val(),
                name: $("#name").val(), 
                username: $("#username").val(),
                password: $("#password").val(),
                alamat: $("#alamat").val(),
                telp: $("#telp").val(),
                id_group: $("#user_group").val(),
                cabang: $("#cabang").val(),
                level: $("#level").val(),
                status: $("#status").val(),
                modified_date: '',
                modified_user: '',
                method: save_method
            },
            dataType: "JSON",
            success: function (data)
            {
                if (data.success) {
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

    function edit(id) {
        save_method = 'update';
        $('#form')[0].reset();

        $.ajax({
            url: "<?=$base_url?>setting/user/edit/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                document.getElementById("password").type = "password";
                $("#id_user").val(data.id_user);
                $("#name").val(data.name);
                $("#username").val(data.username);
                $("#password").val();
                $("#alamat").val(data.alamat);
                $("#telp").val(data.telp);
                $("#user_group").val(data.id_group);
                $("#level").val(data.level);
                $("#cabang").val(data.cabang);
                $("#status").val(data.status);
                showHide();
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

    

    function hapus(id) {
        var question = confirm("Are you sure ?");
        if (question) {
            $.ajax({
                url: "<?=$base_url?>setting/user/delete/" + id,
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
    
    function showPass() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }

    

</script>


<?=view('foother_view'); ?>
