<?=view('header_view'); ?>

<div class="container-fluid bg-light-opac mb-4 main-container">
    <div class="row">
        <div class="container-fluid my-3 main-container">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="content-color-primary page-title">Perhitungan Nilai</h2>
                    <p class="content-color-secondary page-sub-title">Kelola normalisasi nilai dengan bobot kriteria</p>
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
                            <h4 class="content-color-primary mb-0">Daftar Perhitungan Nilai</h4>
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
                    <input type="hidden" class="md-input" id="id_normalisasi" name="id_normalisasi"/>

                    <div class="row">
                        <div class="col-md-12">
                            <!-- Alternatif -->
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4"><label for="id_alternatif" class="control-label">Alternatif <span class="text-danger">*</span></label></div>
                                    <div class="col-md-8">
                                        <select id="id_alternatif" name="id_alternatif" data-placeholder="Pilih Alternatif..." class="chosen_select form-control" tabindex="1"></select>
                                    </div>
                                </div>
                            </div>

                            <!-- Kriteria -->
                            <div class="form-group" style="margin-top: -10px;">
                                <div class="row">
                                    <div class="col-md-4"><label for="id_kriteria" class="control-label">Kriteria <span class="text-danger">*</span></label></div>
                                    <div class="col-md-8">
                                        <select id="id_kriteria" name="id_kriteria" data-placeholder="Pilih Kriteria..." class="chosen_select form-control" tabindex="1"></select>
                                    </div>
                                </div>
                            </div>

                            <!-- Nilai Perhitungan -->
                            <div class="form-group" style="margin-top: -10px;">
                                <div class="row">
                                    <div class="col-md-4"><label for="nilai_normalisasi" class="control-label">Nilai Perhitungan <span class="text-danger">*</span></label></div>
                                    <div class="col-md-8">
                                        <input type="number" id="nilai_normalisasi" name="nilai_normalisasi" class="form-control" placeholder="0.0 - 1.0" step="0.0001" min="0" max="1" />
                                        <small class="form-text text-muted">Nilai harus antara 0 dan 1 (hasil perhitungan dengan bobot)</small>
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

        // Load alternatif dropdown
        $.ajax({
            url: "<?=$base_url?>alternatif/get",
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                $.each(data, function(key, val) {
                    $('#id_alternatif').append('<option value="' + val.id_alternatif + '">' + val.nama_alternatif + '</option>');
                });
                $('#id_alternatif').trigger('chosen:updated');
            }
        });

        // Load kriteria dropdown
        $.ajax({
            url: "<?=$base_url?>kriteria/get",
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                $.each(data, function(key, val) {
                    $('#id_kriteria').append('<option value="' + val.id_kriteria + '">' + val.nama_kriteria + '</option>');
                });
                $('#id_kriteria').trigger('chosen:updated');
            }
        });

        // Initialize DataTable
        table = $('#dt_default').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthMenu: [<?=$default['listRowInPage']?>, <?=$default['listRowInPage']?>],
            ajax: {
                url: "<?=$base_url?>perhitungan/ajax_list",
                async: false,
                type: "POST",
                data: function(d){
                    // No additional data needed
                },
            },
            columns: [
                { title: "Alternatif" },
                { title: "Kriteria" },
                { title: "Normalisasi" },
                { title: "Status" },
                { title: "Created User" },
                { title: "Created Date" },
                { title: "Modified User" },
                { title: "Modified Date" },
                { title: "Action" }
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
        $("#id_normalisasi").val('');
        $(".chosen_select").trigger("chosen:updated");
    }

    function simpan() {
        var url;
        if (save_method == 'add') {
            url = "<?=$base_url?>perhitungan/add";
        } else {
            url = "<?=$base_url?>perhitungan/update";
        }

        $.ajax({
            url: url,
            type: "POST",
            data: {
                id_normalisasi: $("#id_normalisasi").val(),
                id_alternatif: $("#id_alternatif").val(),
                id_kriteria: $("#id_kriteria").val(),
                nilai_normalisasi: $("#nilai_normalisasi").val(),
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
            url: "<?=$base_url?>perhitungan/edit/" + id,
            type: "POST",
            dataType: "JSON",
            success: function (data) {
                $("#id_normalisasi").val(data.id_normalisasi);
                $("#id_alternatif").val(data.id_alternatif);
                $("#id_kriteria").val(data.id_kriteria);
                $("#nilai_normalisasi").val(data.nilai_normalisasi);
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
                url: "<?=$base_url?>perhitungan/delete/" + id,
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
