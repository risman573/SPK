<?=view('header_view'); ?>

<div class="container-fluid bg-light-opac mb-4 main-container">
    <div class="row">
        <div class="container-fluid my-3 main-container">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="content-color-primary page-title">Kriteria Pemilihan</h2>
                    <p class="content-color-secondary page-sub-title">Kelola kriteria dan bobot untuk SAW Method</p>
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
                            <h4 class="content-color-primary mb-0">Daftar Kriteria</h4>
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
                    <input type="hidden" class="md-input" id="id_kriteria" name="id_kriteria"/>

                    <div class="row">
                        <div class="col-md-12">
                            <!-- Nama Kriteria -->
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4"><label for="nama_kriteria" class="control-label">Nama Kriteria <span class="text-danger">*</span></label></div>
                                    <div class="col-md-8">
                                        <input type="text" id="nama_kriteria" name="nama_kriteria" class="form-control" placeholder="Contoh: Processor, RAM, Storage" />
                                    </div>
                                </div>
                            </div>

                            <!-- Bobot -->
                            <div class="form-group" style="margin-top: -10px;">
                                <div class="row">
                                    <div class="col-md-4"><label for="bobot" class="control-label">Bobot (%) <span class="text-danger">*</span></label></div>
                                    <div class="col-md-8">
                                        <input type="number" id="bobot" name="bobot" class="form-control" placeholder="0-100" step="0.01" />
                                        <small class="form-text text-muted">Masukkan nilai bobot dalam persen (0-100)</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Atribut -->
                            <div class="form-group" style="margin-top: -10px;">
                                <div class="row">
                                    <div class="col-md-4"><label for="atribut" class="control-label">Tipe Atribut <span class="text-danger">*</span></label></div>
                                    <div class="col-md-8">
                                        <select id="atribut" name="atribut" data-placeholder="Pilih Tipe Atribut..." class="chosen_select form-control" tabindex="1">
                                            <option value="">-- Pilih Atribut --</option>
                                            <option value="benefit">Benefit (Semakin Besar Semakin Baik)</option>
                                            <option value="cost">Cost (Semakin Kecil Semakin Baik)</option>
                                        </select>
                                        <small class="form-text text-muted">Benefit: nilai lebih tinggi lebih baik | Cost: nilai lebih rendah lebih baik</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <!-- <div class="form-group" style="margin-top: -10px;">
                                <div class="row">
                                    <div class="col-md-4"><label for="status" class="control-label">Status <span class="text-danger">*</span></label></div>
                                    <div class="col-md-8">
                                        <select id="status" name="status" data-placeholder="Pilih Status..." class="chosen_select form-control" tabindex="1"></select>
                                    </div>
                                </div>
                            </div> -->
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
                url: "<?=$base_url?>kriteria/ajax_list",
                async: false,
                type: "POST",
                data: function(d){
                    // No additional data needed
                },
            },
            columns: [
                { title: "Nama Kriteria", data: null, render: function(data, type, row) { return data[0]; } },
                { title: "Bobot (%)", data: null, render: function(data, type, row) { return parseFloat(data[1]).toFixed(1) + ' %'; } },
                { title: "Atribut", data: null, render: function(data, type, row) { return data[2] === 'benefit' ? '<span class="badge badge-success">Benefit</span>' : '<span class="badge badge-warning">Cost</span>'; } },
                { title: "Status", data: null, render: function(data, type, row) { return data[3]; } },
                { title: "Created User", data: null, render: function(data, type, row) { return data[4]; } },
                { title: "Created Date", data: null, render: function(data, type, row) { return data[5]; } },
                { title: "Modified User", data: null, render: function(data, type, row) { return data[6]; } },
                { title: "Modified Date", data: null, render: function(data, type, row) { return data[7]; } },
                { title: "Action" },
            ],
            aoColumnDefs: [
                { "bVisible": <?=$default['showCreated']?>, "aTargets": [4, 5] },
                { "bVisible": <?=$default['showModified']?>, "aTargets": [6, 7] }
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
        $("#id_kriteria").val('');
        $("#atribut").val('benefit');
        $(".chosen_select").trigger("chosen:updated");
    }

    function simpan() {
        var url;
        if (save_method == 'add') {
            url = "<?=$base_url?>kriteria/add";
        } else {
            url = "<?=$base_url?>kriteria/update";
        }

        $.ajax({
            url: url,
            type: "POST",
            data: {
                id_kriteria: $("#id_kriteria").val(),
                nama_kriteria: $("#nama_kriteria").val(),
                bobot: $("#bobot").val(),
                atribut: $("#atribut").val(),
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
            url: "<?=$base_url?>kriteria/edit/" + id,
            type: "POST",
            dataType: "JSON",
            success: function (data) {
                $("#id_kriteria").val(data.id_kriteria);
                $("#nama_kriteria").val(data.nama_kriteria);
                $("#bobot").val(data.bobot);
                $("#atribut").val(data.atribut);
                $("#status").val(data.status);
                $(".chosen_select").trigger("chosen:updated");
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
                url: "<?=$base_url?>kriteria/delete/" + id,
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
