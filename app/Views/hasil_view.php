<?=view('header_view'); ?>

<div class="container-fluid bg-light-opac mb-4 main-container">
    <div class="row">
        <div class="container-fluid my-3 main-container">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="content-color-primary page-title">🏆 Hasil Perangkingan Laptop</h2>
                </div>
                <div class="col-auto">
                    <button class="btn btn-rounded <?=$default['themeColor']?> text-white btn-icon text-uppercase pr-3" onclick="hitungRanking()">
                        <span class="text-hide-xs">Hitung Ranking</span>
                    <button class="btn btn-rounded btn-info text-white btn-icon text-uppercase pr-3 ml-2" onclick="exportPDF()">
                        <i class="material-icons">print</i>
                        <span class="text-hide-xs">Export PDF</span>
                    </button>
                    <button class="btn btn-rounded btn-secondary text-white btn-icon text-uppercase pr-3 ml-2" onclick="window.location.href='<?=$base_url?>perhitungan'">
                        <i class="material-icons">arrow_back</i>
                        <span class="text-hide-xs">Kembali</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Card Pemenang -->
<div class="container-fluid mt-4 main-container" id="winner_card" style="display: none;">
    <div class="row">
        <div class="col-sm-12">
            <div class="card border-success mb-4">
                <div class="card-body text-center bg-light">
                    <h2 class="text-success mb-3">🥇 LAPTOP TERBAIK</h2>
                    <h3 id="winner_name" class="mb-2"></h3>
                    <h4 class="text-muted">Nilai Preferensi: <span id="winner_score" class="text-success"></span></h4>
                    <p class="mt-3 text-muted">Laptop dengan nilai preferensi tertinggi berdasarkan metode SAW</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel 5 Perangkingan -->
<div class="container-fluid mt-4 main-container">
    <div class="row">
        <div class="col-sm-12">
            <div class="card mb-4">
                <div class="card-header <?=$default['themeColor']?>>
                    <h4 class="mb-0">📊 Tabel Perangkingan</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Merk Laptop</th>
                                    <th>Vi</th>
                                    <th>Rangking</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_ranking">
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-5">
                                        <i class="material-icons" style="font-size: 48px;">info</i>
                                        <p class="mt-2">Klik tombol <strong>"Hitung Ranking"</strong> untuk melihat hasil perangkingan</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Info Keterangan -->
<div class="container-fluid mt-4 main-container">
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info">
                <h5><i class="material-icons vm">info</i> Keterangan:</h5>
                <ul class="mb-0">
                    <li><strong>Vi</strong> = Nilai Preferensi (Total bobot × normalisasi untuk semua kriteria)</li>
                    <li><strong>Rangking</strong> = Urutan dari nilai Vi tertinggi ke terendah</li>
                    <li>Laptop dengan <strong>ranking 1</strong> adalah rekomendasi terbaik berdasarkan kriteria yang telah ditentukan</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?=view('footer_view'); ?>

<style>
    /* Highlight untuk ranking 1-3 */
    .rank-1 {
        background-color: #d4edda !important;
        font-weight: bold;
    }
    .rank-2 {
        background-color: #d1ecf1 !important;
    }
    .rank-3 {
        background-color: #fff3cd !important;
    }

    /* Badge ranking */
    .badge-ranking {
        font-size: 1.2em;
        padding: 8px 15px;
        border-radius: 20px;
    }
</style>

<script>
    $(document).ready(function() {
        loadRanking();
    });

    function loadRanking() {
        $.ajax({
            url: "<?=$base_url?>hasil/get_ranking",
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                var html = '';

                if (data.length > 0) {
                    // Tampilkan pemenang
                    $('#winner_name').text(data[0].merk_laptop);
                    $('#winner_score').text(parseFloat(data[0].vi).toFixed(2));
                    $('#winner_card').fadeIn();

                    // Render tabel
                    $.each(data, function(index, row) {
                        var rowClass = '';
                        var badgeClass = 'badge-secondary';

                        if (row.ranking == 1) {
                            rowClass = 'rank-1';
                            badgeClass = 'badge-success';
                        } else if (row.ranking == 2) {
                            rowClass = 'rank-2';
                            badgeClass = 'badge-info';
                        } else if (row.ranking == 3) {
                            rowClass = 'rank-3';
                            badgeClass = 'badge-warning';
                        }

                        html += '<tr class="' + rowClass + '">';
                        html += '<td class="text-left"><strong>' + row.merk_laptop + '</strong></td>';
                        html += '<td><strong>' + parseFloat(row.vi).toFixed(2) + '</strong></td>';
                        html += '<td><span class="badge badge-ranking ' + badgeClass + '">' + row.ranking + '</span></td>';
                        html += '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="3" class="text-center text-muted py-5">';
                    html += '<i class="material-icons" style="font-size: 48px;">warning</i>';
                    html += '<p class="mt-2">Belum ada data ranking.<br>Pastikan sudah menghitung normalisasi terlebih dahulu.</p>';
                    html += '</td></tr>';
                }

                $('#tbody_ranking').html(html);
            },
            error: function() {
                $('#tbody_ranking').html('<tr><td colspan="3" class="text-center text-danger">Gagal memuat data</td></tr>');
            }
        });
    }

    function hitungRanking() {
        if (!confirm('Hitung ulang ranking? Data lama akan dihapus.')) {
            return;
        }

        $.ajax({
            url: "<?=$base_url?>hasil/calculate",
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.success) {
                    toaster('Success', '✅ ' + data.message + '<br>Total laptop: ' + data.total, 'success');
                    loadRanking();
                } else {
                    toaster('Error', '❌ ' + data.message, 'error');
                }
                $.unblockUI();
            },
            beforeSend: function() {
                $.blockUI({
                    message: '<h5><i class="fa fa-spinner fa-spin"></i> Menghitung ranking...<br><small>Mohon tunggu</small></h5>'
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $.unblockUI();
                toaster('Error', 'Terjadi kesalahan: ' + errorThrown, 'error');
            }
        });
    }

    function exportPDF() {
        toaster('Info', 'Fitur export PDF akan segera tersedia', 'info');
        // TODO: Implementasi export PDF
    }
</script>