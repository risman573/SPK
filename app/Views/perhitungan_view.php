<?=view('header_view'); ?>

<div class="container-fluid bg-light-opac mb-4 main-container">
    <div class="row">
        <div class="container-fluid my-3 main-container">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="content-color-primary page-title">Perhitungan Normalisasi</h2>
                </div>
                <div class="col-auto">
                    <button class="btn btn-rounded <?=$default['themeColor']?> text-white btn-icon text-uppercase pr-3" onclick="hitungNormalisasi()">
                        <span class="text-hide-xs">Hitung Normalisasi</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel 4: Hasil Normalisasi (Matrix) -->
<div class="container-fluid mt-4 main-container">
    <div class="row">
        <div class="col-sm-12">
            <div class="card mb-4 shadow">
                <div class="card-header <?=$default['themeColor']?>>
                    <h4 class="mb-0">📋 Hasil Normalisasi</h4>
                    <small>Matrix normalisasi berdasarkan kriteria benefit dan cost</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0" id="tbl_normalisasi">
                            <thead class="thead-light">
                                <tr class="text-center">
                                    <th rowspan="2" class="align-middle" style="min-width: 200px; background-color: #f8f9fc;">
                                        <strong>Merk Laptop</strong>
                                    </th>
                                    <th colspan="7" style="background-color: #e7f3ff;">
                                        <strong>Kriteria Normalisasi</strong>
                                    </th>
                                </tr>
                                <tr class="text-center" style="background-color: #e7f3ff;">
                                    <th style="min-width: 80px;"><strong>Brand</strong></th>
                                    <th style="min-width: 80px;"><strong>Harga</strong></th>
                                    <th style="min-width: 80px;"><strong>RAM</strong></th>
                                    <th style="min-width: 100px;"><strong>Tipe<br>Hardisk</strong></th>
                                    <th style="min-width: 100px;"><strong>Kapasitas<br>Hardisk</strong></th>
                                    <th style="min-width: 100px;"><strong>Kelengkapan<br>Port</strong></th>
                                    <th style="min-width: 80px;"><strong>Garansi</strong></th>
                                </tr>
                            </thead>
                            <tbody id="tbody_normalisasi">
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="material-icons" style="font-size: 64px; color: #ccc;">analytics</i>
                                        <p class="text-muted mt-3">Belum ada data normalisasi</p>
                                        <p class="text-muted">Klik tombol <strong>"Hitung Normalisasi"</strong> untuk memulai perhitungan</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <i class="material-icons vm" style="font-size: 16px;">info_outline</i>
                        <strong>Keterangan:</strong> Nilai berkisar antara 0.00 - 1.00 | Benefit: r<sub>ij</sub> = X<sub>ij</sub> / Max(X<sub>ij</sub>) | Cost: r<sub>ij</sub> = Min(X<sub>ij</sub>) / X<sub>ij</sub>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<?=view('footer_view'); ?>

<style>
    #tbl_normalisasi thead th {
        position: sticky;
        top: 0;
        z-index: 1;
    }

    #tbl_normalisasi tbody tr:hover {
        background-color: #f5f5f5;
    }

    #tbl_normalisasi td {
        vertical-align: middle;
    }

    .table-responsive {
        max-height: 600px;
        overflow-y: auto;
    }
</style>

<script type="text/javascript">
    $(document).ready(function () {
        loadNormalisasi();
    });

    function loadNormalisasi() {
        $.ajax({
            url: "<?=$base_url?>perhitungan/get_normalisasi",
            type: "POST",
            dataType: "JSON",
            success: function (data) {

                if (data.length === 0) {
                    $('#tbody_normalisasi').html(`
                        <tr>
                            <td colspan="20" class="text-center py-5">
                                <p class="text-muted">Belum ada data normalisasi</p>
                            </td>
                        </tr>
                    `);
                    return;
                }

                let alternatifMap = {};
                let kriteriaList = [];

                // Mapping data
                data.forEach(row => {
                    if (!alternatifMap[row.nama_alternatif]) {
                        alternatifMap[row.nama_alternatif] = {};
                    }

                    alternatifMap[row.nama_alternatif][row.nama_kriteria] = row.nilai_normalisasi;

                    if (!kriteriaList.includes(row.nama_kriteria)) {
                        kriteriaList.push(row.nama_kriteria);
                    }
                });

                // Build header (optional kalau mau dinamis penuh)
                let html = '';

                Object.keys(alternatifMap).forEach(alternatif => {
                    html += `<tr>`;
                    html += `<td><strong>${alternatif}</strong></td>`;

                    kriteriaList.forEach(k => {
                        let nilai = alternatifMap[alternatif][k] ?? 0;
                        html += `<td class="text-center">${parseFloat(nilai).toFixed(2)}</td>`;
                    });

                    html += `</tr>`;
                });

                $('#tbody_normalisasi').html(html);
            }
        });
    }

    function hitungNormalisasi() {
        if (!confirm('Hitung ulang normalisasi? Data lama akan dihapus.')) {
            return;
        }

        $.ajax({
            url: "<?=$base_url?>perhitungan/calculate",
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.success) {
                    toaster('Success', 'Normalisasi berhasil dihitung! Total: ' + data.total + ' data', 'success');
                    loadNormalisasi();
                } else {
                    toaster('Error', data.message, 'error');
                }
                $.unblockUI();
            },
            beforeSend: function() {
                $.blockUI({
                    message: '<h5><i class="fa fa-spinner fa-spin"></i> Menghitung normalisasi...</h5>'
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $.unblockUI();
                toaster('Error', 'Terjadi kesalahan: ' + errorThrown, 'error');
            }
        });
    }
</script>