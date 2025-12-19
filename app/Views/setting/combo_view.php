<?=view('header_view'); ?>


<div class="container-fluid bg-light-opac mb-4 main-container">
    <div class="row">
        <div class="container-fluid my-3 main-container">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="content-color-primary page-title">Combo Setting</h2>
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
                    <input type="hidden" class="md-input" id="id" name="id"/>
                    <div class="form-group">
                       <div class="row">
                          <div class="col-md-4">
                             <label class="control-label">Code</label>
                          </div>
                          <div class="col-md-8">
                             <input type="text" id="kode" name="kode" placeholder="" class="form-control" />
                          </div>
                       </div>
                    </div>
                    <div class="form-group">
                       <div class="row">
                          <div class="col-md-4">
                             <label class="control-label">Name</label>
                          </div>
                          <div class="col-md-8">
                             <input type="text" id="nama" name="nama" placeholder="" class="form-control" />
                          </div>
                       </div>
                    </div>
                    <div class="form-group">
                       <div class="row">
                          <div class="col-md-4">
                             <label class="control-label">Sort</label>
                          </div>
                          <div class="col-md-8">
                             <input type="text" id="sort" name="sort" placeholder="" class="form-control" />
                          </div>
                       </div>
                    </div>
                    <div class="form-group">
                       <div class="row">
                          <div class="col-md-4">
                             <label class="control-label">Type</label>
                          </div>
                          <div class="col-md-8">
                                <select id="type" name="type" data-placeholder="Choose a Type..." class="chosen_select form-control" tabindex="1"></select>
                          </div>
                       </div>
                    </div>
                    <div class="form-group">
                       <div class="row">
                          <div class="col-md-4">
                             <label class="control-label">Flag</label>
                          </div>
                          <div class="col-md-8">
                                <select id="flag" name="flag" data-placeholder="Choose a Type..." class="chosen_select form-control" tabindex="1"></select>
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
<script>
    select_status('<?=$base_url?>', '#status');
    select_type('<?=$base_url?>', '#type');
    select_flag('<?=$base_url?>', '#flag');

    var save_method;
    var table;
    var foto = 'no_photo.png';
    
    
    $(document).ready(function () {        
        table = $('#dt_default').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthMenu: [<?=$default['listRowInPage']?>, <?=$default['listRowInPage']?>],
            ajax: {
                url:"<?=$base_url?>setting/combo/ajax_list",
                type: "POST"
            },
            columns: [
                { title: "Code" },
                { title: "Name" },
                { title: "Type" },
                { title: "Flag" },
                { title: "Sort" },
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
    
    function reload_table() {
        table.ajax.reload(null, false);
    }

    $('#modal_form').on('hide.uk.modal', function (e) {
        reload_table();
    });
    
    function select_type(base_url, element){
        $(element).empty();
        $.ajax({
            url: base_url + "setting/combo/getCombo/combo/combo",
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
    
    function select_flag(base_url, element){
        $(element).empty();
        $.ajax({
            url: base_url + "setting/combo/getCombo/combo/flag",
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

    function tambah() {
        save_method = 'add';
        $('#form')[0].reset();
        $("#id").val('');
//        foto = 'no_photo.png';
//        $('#foto_img').attr('src', '<?=$base_url?>files/image/user/no_photo.png');
    }
    

    function simpan() {
        var url;
        if (save_method == 'add') {
            url = "<?=$base_url?>setting/combo/add";
        } else {
            url = "<?=$base_url?>setting/combo/update";
        }
        
        $.ajax({
            url: url,
            type: "POST",
            data: {
                id: $("#id").val(),
                kode: $("#kode").val(), 
                nama: $("#nama").val(), 
                sort: $("#sort").val(), 
                type: $("#type").val(),
                flag: $("#flag").val(),
                status: $("#status").val(),
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
            url: "<?=$base_url?>setting/combo/edit/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $("#id").val(data.id);
                $("#kode").val(data.kode);
                $("#nama").val(data.nama);
                $("#sort").val(data.sort);
                $("#flag").val(data.flag);
                $("#type").val(data.type);
                $("#status").val(data.status);
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
                url: "<?=$base_url?>setting/combo/delete/" + id,
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

//    function clearFileInput(id) {
//        $('#' + id).html($('#' + id).html());
//    }
//	
//    function hapusGambar(gambar) {
//        $success = false;
//        if (gambar !== 'no_image.png') {
//            $.ajax({
//                url: "<?=$base_url?>setting/combo/delete_file/" + gambar,
//                type: "POST",
//                dataType: "JSON",
//                success: function (data) {
//                    var result = JSON.parse(data);
//                    if (result.success) {
//                        $success = true;
//                    } else {
//                        $success = false;
//                    }
//                },
//                cache: false,
//                contentType: false,
//                processData: false
//            });
//        }
//        return $success;
//    }
//    
//    function PreviewImage(input,id) {
//        if (input.files && input.files[0]) {
//            var reader = new FileReader();
////            console.log(id);
//            reader.onload = function (e) {
//                $('#'+id+'_img').attr('src', e.target.result);
//            };
//
//            reader.readAsDataURL(input.files[0]);
//        }
//    }

    

</script>


<?=view('foother_view'); ?>
