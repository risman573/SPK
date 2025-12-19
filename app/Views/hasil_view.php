<?=view('header_view'); ?>

<div class="container-fluid bg-light-opac mb-4 main-container">
    <div class="row">
        <div class="container-fluid my-3 main-container">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="content-color-primary page-title">Hasil Keputusan</h2>
                    <p class="content-color-secondary page-sub-title">Kelola hasil ranking dan preference value</p>
                </div>
                <div class="col-auto">
                    <?php if ($tambah == "1") {?>
                    <button class="btn btn-rounded <?=$default['themeColor']?> text-white btn-icon text-uppercase pr-3" data-toggle="modal" data-target="#modal_form" onclick="tambah()">
                        <i class="material-icons">add</i>
                        <span class="text-hide-xs">New Hasil</span>
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
                            <h4 class="content-color-primary mb-0">Daftar Hasil Keputusan</h4>
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
    <div class="modal-dialog" role="document" style="max-width: 2000px;">
        <div class="modal-content card shadow-sm border-0 mb-4 col-sm-12 col-md-10 col-lg-8 col-xl-6" style="margin-left:auto; margin-right:auto;">
            <form id="form">
                <div class="modal-header <?=$default['themeColor']?> text-white">
                    <h5 class="modal-title" id="formModalLabel">Form Hasil Keputusan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="md-input" id="id_hasil" name="id_hasil"/>

                    <div class="row">
                        <div class="col-md-12">
                            <h4>Data Hasil</h4><hr>

                            <!-- Alternatif -->
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4"><label for="id_alternatif" class="control-label">Alternatif <span class="text-danger">*</span></label></div>
                                    <div class="col-md-8">
                                        <select id="id_alternatif" name="id_alternatif" data-placeholder="Pilih Alternatif..." class="chosen_select form-control" tabindex="1"></select>
                                    </div>
                                </div>
                            </div>

                            <!-- Nilai Preferensi -->
                            <div class="form-group" style="margin-top: -10px;">
                                <div class="row">
                                    <div class="col-md-4"><label for="nilai_preferensi" class="control-label">Nilai Preferensi <span class="text-danger">*</span></label></div>
                                    <div class="col-md-8">
                                        <input type="number" id="nilai_preferensi" name="nilai_preferensi" class="form-control" placeholder="0.0 - 1.0" step="0.0001" min="0" max="1" />
                                        <small class="form-text text-muted">Nilai preferensi hasil dari penjumlahan normalisasi × bobot (0-1)</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Ranking -->
                            <div class="form-group" style="margin-top: -10px;">
                                <div class="row">
                                    <div class="col-md-4"><label for="ranking" class="control-label">Ranking <span class="text-danger">*</span></label></div>
                                    <div class="col-md-8">
                                        <input type="number" id="ranking" name="ranking" class="form-control" placeholder="1, 2, 3, ..." step="1" min="1" />
                                        <small class="form-text text-muted">Urutan ranking dari tertinggi ke terendah</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="form-group" style="margin-top: -10px;">
                                <div class="row">
                                    <div class="col-md-4"><label for="status" class="control-label">Status <span class="text-danger">*</span></label></div>
                                    <div class="col-md-8">
                                        <select id="status" name="status" data-placeholder="Pilih Status..." class="chosen_select form-control" tabindex="1"></select>
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
            url: "<?=$base_url?>spk/alternatif/get",
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                $.each(data, function(key, val) {
                    $('#id_alternatif').append('<option value="' + val.id_alternatif + '">' + val.nama_alternatif + '</option>');
                });
                $('#id_alternatif').trigger('chosen:updated');
            }
        });

        // Initialize DataTable
        table = $('#dt_default').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthMenu: [<?=$default['listRowInPage']?>, <?=$default['listRowInPage']?>],
            ajax: {
                url: "<?=$base_url?>spk/hasil/ajax_list",
                async: false,
                type: "POST",
                data: function(d){
                    // No additional data needed
                },
            },
            columns: [
                { title: "Ranking", data: null, render: function(data, type, row) { return data[4]; } },
                { title: "Alternatif", data: null, render: function(data, type, row) { return data[2]; } },
                { title: "Nilai Preferensi", data: null, render: function(data, type, row) { return parseFloat(data[3]).toFixed(4); } },
                { title: "Status", data: null, render: function(data, type, row) { return data[5]; } },
                { title: "Created User", data: null, render: function(data, type, row) { return data[6]; } },
                { title: "Created Date", data: null, render: function(data, type, row) { return data[7]; } },
                { title: "Modified User", data: null, render: function(data, type, row) { return data[8]; } },
                { title: "Modified Date", data: null, render: function(data, type, row) { return data[9]; } },
                { title: "Action", data: null, orderable: false, render: function(data, type, row) {
                    var btn = '';
                    if ("<?=$ubah?>" == "1") btn += '<button class="btn btn-sm btn-info" onclick="edit(\'' + data[0] + '\')"><i class="material-icons">edit</i></button> ';
                    if ("<?=$hapus?>" == "1") btn += '<button class="btn btn-sm btn-danger" onclick="hapus(\'' + data[0] + '\')"><i class="material-icons">delete</i></button>';
                    return btn;
                } }
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
            order: [[0, 'asc']]
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
        $("#id_hasil").val('');
        $(".chosen_select").trigger("chosen:updated");
    }

    function simpan() {
        var url;
        if (save_method == 'add') {
            url = "<?=$base_url?>spk/hasil/add";
        } else {
            url = "<?=$base_url?>spk/hasil/update";
        }

        $.ajax({
            url: url,
            type: "POST",
            data: {
                id_hasil: $("#id_hasil").val(),
                id_alternatif: $("#id_alternatif").val(),
                nilai_preferensi: $("#nilai_preferensi").val(),
                ranking: $("#ranking").val(),
                status: $("#status").val(),
            },
            dataType: "JSON",
            success: function (data) {
                if (data.status === 'success') {
                    reload_table();
                    $.toast({
                        heading: 'Success',
                        text: data.msg,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3000
                    });
                    $('#modal_form').modal('hide');
                } else {
                    $.toast({
                        heading: 'Error',
                        text: data.msg,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3000
                    });
                }
                $.unblockUI();
            },
            beforeSend: function(){
                $.blockUI();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $.unblockUI();
                console.log('Error:', errorThrown);
                $.toast({
                    heading: 'Error',
                    text: 'Terjadi kesalahan. Silahkan coba lagi.',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 3000
                });
            }
        });
    }

    function edit(id_hasil) {
        $.ajax({
            url: "<?=$base_url?>spk/hasil/edit",
            type: "POST",
            data: { id_hasil: id_hasil },
            dataType: "JSON",
            success: function (data) {
                save_method = 'update';
                $('#form')[0].reset();
                $("#id_hasil").val(data.id_hasil);
                $("#id_alternatif").val(data.id_alternatif);
                $("#nilai_preferensi").val(data.nilai_preferensi);
                $("#ranking").val(data.ranking);
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

    function hapus(id_hasil) {
        if (confirm('Apakah anda yakin ingin menghapus data ini?')) {
            $.ajax({
                url: "<?=$base_url?>spk/hasil/delete",
                type: "POST",
                data: { id_hasil: id_hasil },
                dataType: "JSON",
                success: function (data) {
                    if (data.status === 'success') {
                        reload_table();
                        $.toast({
                            heading: 'Success',
                            text: data.msg,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 3000
                        });
                    } else {
                        $.toast({
                            heading: 'Error',
                            text: data.msg,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 3000
                        });
                    }
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
    }
</script>
