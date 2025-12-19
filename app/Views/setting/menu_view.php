<?=view('header_view'); ?>


<div class="container-fluid bg-light-opac mb-4 main-container">
    <div class="row">
        <div class="container-fluid my-3 main-container">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="content-color-primary page-title">Menu / Modul</h2>
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <form id="form">
            <div class="modal-header <?=$default['themeColor']?> text-white">
                <h5 class="modal-title" id="exampleModalLabel">FORM</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                        <input type="hidden" class="md-input" id="id_menu" name="id_menu"/>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="kode" class="ukcontrol-label">Menu Name</label>
                                </div>
                                <div class="col-md-8">
                                    <input id="menu_name" name="menu_name" class="form-control" placeholder="" class="" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-4">
                                 <label class="control-label">Type</label>
                              </div>
                              <div class="col-md-8">
                                  <select id="type" name="type" data-placeholder="Choose a Type..." class="chosen_select form-control" tabindex="1" onchange="showHide()">
                                        <option value="0">Parent</option>
                                        <option value="1">Child</option>
                                        <option value="2">Sub Child</option>
                                    </select>
                              </div>
                           </div>
                        </div>
                        <div class="form-group" id="parentHide">
                           <div class="row">
                              <div class="col-md-4">
                                 <label class="control-label">Parent</label>
                              </div>
                              <div class="col-md-8">
                                    <select id="parent" name="parent" data-placeholder="Choose a Type..." class="chosen_select form-control" tabindex="1"></select>
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="kode" class="ukcontrol-label">URL</label>
                                </div>
                                <div class="col-md-8">
                                    <input id="url" name="url" class="form-control" placeholder="" class="" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-4">
                                 <label class="control-label">Status</label>
                              </div>
                              <div class="col-md-8">
                                    <select id="status" name="status" data-placeholder="Choose a Type..." class="chosen_select form-control" tabindex="1"></select>
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="kode" class="ukcontrol-label">Sort</label>
                                </div>
                                <div class="col-md-8">
                                    <input id="sort" name="sort" class="form-control" placeholder="" class="" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="kode" class="ukcontrol-label">Icon</label>
                                </div>
                                <div class="col-md-8">
                                    <input id="icon" name="icon" class="form-control" placeholder="" class="" />
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



<script type="text/javascript" src="<?=$base_url?>assets/helper/menu.js"></script>
<script type="text/javascript" src="<?=$base_url?>assets/helper/status.js"></script>

<script>
    select_menu('<?=$base_url?>', '#parent');
    select_status('<?=$base_url?>', '#status');
//    select_menu_type('#type');

    var save_method;
    var table;


    $(document).ready(function () {
        table = $('#dt_default').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthMenu: [<?=$default['listRowInPage']?>, <?=$default['listRowInPage']?>],
            ajax: {
                url:"<?=$base_url?>setting/menu/ajax_list/",
                type: "POST"
            },
            columns: [
              { title: "Menu Name" },
              { title: "Parent Name" },
              { title: "URL" },
              { title: "Sort" },
              { title: "Icon" },
              { title: "Status" },
              { title: "Created User" },
              { title: "Created Date" },
              { title: "Modified User" },
              { title: "Modified Date" },
              { title: "Action" },
            ],
            aoColumnDefs: [
                { "bVisible": <?=$default['showCreated']?>, "aTargets": [6,7] },
                { "bVisible": <?=$default['showModified']?>, "aTargets": [8,9] }
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

    function showHide(){
        var item = $("#type").val();
        if(item == 0 || item == ''){
            $('#parentHide').css("display","none");
            $("#parent").val('');
        }else{
            $('#parentHide').css("display","block");
        }
    }

    function reload_table() {
        table.ajax.reload(null, false);
    }

    $('#modal_form').on('hide.uk.modal', function (e) {
        reload_table();
    });

    function tambah() {
        save_method = 'add';
        $('#form')[0].reset();
        $("#id_menu").val('');
        showHide();
    }

    function simpan() {
        var url;
        if (save_method == 'add') {
            url = "<?=$base_url?>setting/menu/add";
        } else {
            url = "<?=$base_url?>setting/menu/update";
        }

        $.ajax({
            url: url,
            type: "POST",
            data: {
                id_menu: $("#id_menu").val(),
                menu_name: $("#menu_name").val(),
                parent: $("#parent").val(),
                url: $("#url").val(),
                type: $("#type").val(),
                status: $("#status").val(),
                sort: $("#sort").val(),
                icon: $("#icon").val(),
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
        $('#modal_form').modal('show');

        $.ajax({
            url: "<?=$base_url?>setting/menu/edit/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $("#id_menu").val(data.id_menu);
                $("#menu_name").val(data.menu_name);
                $("#type").val(data.type);
                $("#parent").val(data.parent);
                $('#url').val(data.url);
                $('#status').val(data.status);
                $('#sort').val(data.sort);
                $('#icon').val(data.icon);
                showHide();
//                $('#modal_form').modal();
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
                url: "<?=$base_url?>setting/menu/delete/" + id,
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



</script>


<?=view('footer_view'); ?>