<?=view('header_view'); ?>

<div class="container-fluid bg-light-opac mb-4 main-container">
    <div class="row">
        <div class="container-fluid my-3 main-container">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="content-color-primary page-title">Alternatif Pemilihan</h2>
                    <p class="content-color-secondary page-sub-title">Kelola laptop alternatives untuk evaluasi</p>
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
                            <h4 class="content-color-primary mb-0">Daftar Alternatif</h4>
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

<!-- Modal Form -->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
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
                    <input type="hidden" class="md-input" id="id_alternatif" name="id_alternatif"/>

                    <div class="row">
                        <div class="col-md-12">
                            <!-- Nama Alternatif -->
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4"><label for="nama_alternatif" class="control-label">Nama Alternatif <span class="text-danger">*</span></label></div>
                                    <div class="col-md-8">
                                        <input type="text" id="nama_alternatif" name="nama_alternatif" class="form-control" placeholder="Contoh: MacBook Pro, Dell XPS" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn <?=$default['themeColor']?> text-white" onclick="simpan()">
                        <i class="material-icons vm">save</i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?=view('footer_view'); ?>

<script type="text/javascript" src="<?=$base_url?>assets/helper/status.js"></script>

<script type="text/javascript">
    var save_method = '';

    $(document).ready(function () {
        // Load status dropdown
        select_status('<?=$base_url?>', '#status');

        // Initialize DataTable
        table = $('#dt_default').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthMenu: [<?=$default['listRowInPage']?>, <?=$default['listRowInPage']?>],
            ajax: {
                url: "<?=$base_url?>alternatif/ajax_list",
                async: false,
                type: "POST",
                data: function(d){
                    // No additional data needed
                },
            },
            columns: [
                { title: "Nama Alternatif", data: null, render: function(data, type, row) { return data[0]; } },
                { title: "Status", data: null, render: function(data, type, row) { return data[1]; } },
                { title: "Created User", data: null, render: function(data, type, row) { return data[2]; } },
                { title: "Created Date", data: null, render: function(data, type, row) { return data[3]; } },
                { title: "Modified User", data: null, render: function(data, type, row) { return data[4]; } },
                { title: "Modified Date", data: null, render: function(data, type, row) { return data[5]; } },
                { title: "Action" },
            ],
            aoColumnDefs: [
                { "bVisible": <?=$default['showCreated']?>, "aTargets": [2, 3] },
                { "bVisible": <?=$default['showModified']?>, "aTargets": [4, 5] }
            ],
            columnDefs: [
                {
                    targets: [-1],
                    orderable: false
                }
            ],
        });
    });

    function reload_table() {
        table.ajax.reload(null, false);
    }

    $('#modal_form').on('hide.bs.modal', function (e) {
        reload_table();
    });

    function tambah() {
        save_method = 'add';
        $('#form')[0].reset();
        $("#id_alternatif").val('');
    }

    function simpan() {
        var url;
        if (save_method == 'add') {
            url = "<?=$base_url?>alternatif/add";
        } else {
            url = "<?=$base_url?>alternatif/update";
        }

        $.ajax({
            url: url,
            type: "POST",
            data: {
                id_alternatif: $("#id_alternatif").val(),
                nama_alternatif: $("#nama_alternatif").val(),
                status: $("#status").val(),
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
            url: "<?=$base_url?>alternatif/edit/" + id,
            type: "POST",
            dataType: "JSON",
            success: function (data) {
                $("#id_alternatif").val(data.id_alternatif);
                $("#nama_alternatif").val(data.nama_alternatif);
                $("#status").val(data.status);
                $('#modal_form').modal('show');
                $.unblockUI();
            },
            beforeSend: function(){
                $.blockUI();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $.unblockUI();
                console.log(errorThrown);
            }
        });
    }

    function hapus(id) {
        var question = confirm('Apakah anda yakin ingin menghapus data ini?');
        if (question) {
            $.ajax({
                url: "<?=$base_url?>alternatif/delete/" + id,
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
</script>
