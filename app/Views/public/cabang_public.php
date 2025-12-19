<!doctype html>
<html lang="id">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?=$base_url?><?=$default['logo']?>">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <title><?=$title?> - <?=$default['titleTab']?></title>
    
    <!-- Custom CSS - Match Admin Style -->
    <style>
        .dashboard-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(44,62,80,0.08);
            padding: 24px 18px;
            margin-bottom: 18px;
            width: 100%;
            overflow: visible;
        }
        .dashboard-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .cabang-wrapper {
            display: flex;
            gap: 24px;
            justify-content: space-between;
            margin-top: 20px;
            align-items: flex-start;
            flex-wrap: nowrap;
            width: 100%;
        }
        .cabang-chart-box, .cabang-filter-box {
            background: #ffffff;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border: 1px solid #e8e9ea;
        }
        .cabang-chart-box {
            flex: 1;
            min-width: 0;
            order: 1;
        }
        .cabang-filter-box {
            flex: 0 0 340px;
            max-width: 340px;
            min-height: fit-content;
            width: 340px;
            order: 2;
        }
        .dashboard-subtitle {
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 600;
            padding-bottom: 8px;
            border-bottom: 2px solid #3498db;
            position: relative;
        }
        .dashboard-subtitle::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 30px;
            height: 2px;
            background: #e74c3c;
        }
        .filter-label {
            font-size: 13px;
            margin-bottom: 8px;
            color: #34495e;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .filter-input, .filter-select {
            width: 100%;
            padding: 10px 12px;
            border-radius: 6px;
            border: 2px solid #ddd;
            margin-bottom: 15px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            background: #fff;
        }
        .filter-select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        .filter-btn {
            width: 100%;
            padding: 12px 0;
            border-radius: 6px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .chart-container {
            width: 100%;
            margin-bottom: 30px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border: 2px solid #f1f2f6;
            background: #fff;
        }
        .table-container {
            width: 100%;
            margin-top: 30px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border: 2px solid #f1f2f6;
            background: #fff;
            max-height: 450px;
            overflow-y: auto;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .data-table th, .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }
        .data-table th {
            font-weight: 600;
            color: #2c3e50;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 10;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .data-table tbody tr {
            transition: background-color 0.2s ease;
        }
        .data-table tbody tr:hover {
            background: #f8f9fa;
            transform: translateY(-1px);
        }
        .data-table tbody tr:nth-child(even) {
            background: #fafbfc;
        }
        .data-table tbody tr:nth-child(even):hover {
            background: #f0f2f5;
        }
        .no-data {
            text-align: center;
            padding: 30px 20px;
            color: #7f8c8d;
            font-style: italic;
            font-size: 14px;
            background: #f8f9fa;
            border-radius: 6px;
            margin: 10px 0;
        }
        .stats-box {
            margin-top: 20px;
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 10px;
            border: 1px solid #e0e6ed;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255,255,255,0.3);
            font-size: 14px;
        }
        .stat-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .stat-label {
            color: #34495e;
            font-weight: 500;
        }
        .stat-value {
            font-weight: 700;
            color: #2c3e50;
            font-size: 16px;
            background: rgba(255,255,255,0.8);
            padding: 6px 12px;
            border-radius: 6px;
            min-width: 60px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        body {
            background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%);
            font-family: 'Inter', sans-serif;
        }
        
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .page-title {
            color: #2c3e50;
            margin: 2rem 0;
            font-weight: 600;
            font-size: 28px;
        }

        /* Media Query untuk responsive */
        @media (max-width: 1200px) {
            .cabang-wrapper {
                flex-direction: column;
                gap: 20px;
            }
            .cabang-chart-box {
                width: 100%;
                min-width: auto;
                order: 1;
            }
            .cabang-filter-box {
                width: 100%;
                flex: none;
                order: 2;
                max-width: none;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 0 15px;
            }
            .page-title {
                font-size: 24px;
                margin: 1.5rem 0;
            }
            .dashboard-card {
                padding: 16px;
            }
            .cabang-chart-box, .cabang-filter-box {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <?php include APPPATH . 'Views/public/includes/navbar.php'; ?>

    <div class="container-fluid bg-light-opac mb-4 main-container">
        <div class="row">
            <div class="container-fluid my-3 main-container">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="content-color-primary page-title">📊 Analisis Cabang</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid main-container">
        <div class="dashboard-card">
            <div class="cabang-wrapper">
                <div class="cabang-chart-box">
                    <div class="dashboard-subtitle">📊 Jumlah Anggota per Cabang</div>
                    <div class="chart-container">
                        <div id="chart_cabang" style="width:100%; height:500px; margin-top: 20px;"></div>
                    </div>
                    
                    <div class="dashboard-subtitle" style="margin-top: 30px;">📋 Data List Anggota per Cabang</div>
                    <div class="table-container">
                        <table class="data-table" id="data_table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Cabang</th>
                                    <th>Provinsi</th>
                                    <th>Kota</th>
                                    <th style="width: 120px;">Jumlah Anggota</th>
                                    <th style="width: 100px;">Jumlah RS</th>
                                    <th style="width: 120px;">Rasio Anggota/RS</th>
                                </tr>
                            </thead>
                            <tbody id="data_table_body">
                                <tr>
                                    <td colspan="7" class="no-data">Memuat data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="cabang-filter-box">
                    <div class="dashboard-subtitle">🎛️ Filter Data</div>
                    
                    <label class="filter-label">Cabang</label>
                    <select class="filter-select" id="filter_cabang">
                        <option value="">Pilih Cabang</option>
                    </select>
                    
                    <label class="filter-label">Provinsi</label>
                    <select class="filter-select" id="filter_provinsi">
                        <option value="">Pilih Provinsi</option>
                    </select>
                    
                    <label class="filter-label">Kota/Kabupaten</label>
                    <select class="filter-select" id="filter_kota">
                        <option value="">Pilih Kota/Kabupaten</option>
                    </select>
                    
                    <button class="filter-btn" id="btn_filter">Filter Data</button>
                    <button class="filter-btn" id="btn_reset" style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);">Reset Filter</button>
                    
                    <div class="stats-box">
                        <div class="dashboard-subtitle">📊 Statistik Cabang</div>
                        <div id="stats_content">
                            <div class="stat-item">
                                <span class="stat-label">Total Cabang:</span>
                                <span class="stat-value" id="stat_cabang">-</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Total Anggota:</span>
                                <span class="stat-value" id="stat_anggota">-</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Total RS:</span>
                                <span class="stat-value" id="stat_rs">-</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Rasio Anggota/RS:</span>
                                <span class="stat-value" id="stat_rasio">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- amCharts for bar & line chart -->
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 600,
            once: true
        });

        $(document).ready(function () {
            var chartCabang;
            var currentFilters = {};

            // Load provinsi list
            loadCabangList();
            loadProvinsiList();
            
            // Load initial data
            loadData();

            function loadProvinsiList() {
                $.ajax({
                    url: "<?= base_url() ?>public/api/provinsi",
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        console.log('Provinsi data received:', data);
                        let provinsiSelect = $("#filter_provinsi");
                        provinsiSelect.empty();
                        provinsiSelect.append('<option value="">Semua Provinsi</option>');
                        
                        // Handle both array and object response
                        var provinsiData = data.data || data;
                        if (Array.isArray(provinsiData)) {
                            provinsiData.forEach(function(item) {
                                provinsiSelect.append('<option value="' + item.provinsi + '">' + item.provinsi + '</option>');
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("Error loading provinsi list:", error);
                        console.log("Response:", xhr.responseText);
                        // Fallback with dummy data
                        let provinsiSelect = $("#filter_provinsi");
                        provinsiSelect.empty();
                        provinsiSelect.append('<option value="">Semua Provinsi</option>');
                        provinsiSelect.append('<option value="DKI Jakarta">DKI Jakarta</option>');
                        provinsiSelect.append('<option value="Jawa Barat">Jawa Barat</option>');
                        provinsiSelect.append('<option value="Jawa Tengah">Jawa Tengah</option>');
                    }
                });
            }            
            function loadKotaList(provinsi) {
                if (!provinsi) {
                    $('#filter_kota').html('<option value="">Pilih Kota/Kabupaten</option>');
                    $('#filter_cabang').html('<option value="">Pilih Cabang</option>');
                    return;
                }

                $.ajax({
                    url: "<?= base_url() ?>public/api/kota",
                    type: "GET",
                    data: { provinsi: provinsi },
                    dataType: "JSON",
                    success: function (data) {
                        console.log('Kota data received:', data);
                        var kotaData = data.data || data;
                        var options = '<option value="">Semua Kota/Kabupaten</option>';
                        
                        if (Array.isArray(kotaData)) {
                            kotaData.forEach(function(item) {
                                options += '<option value="' + item.kota + '">' + item.kota + '</option>';
                            });
                        }
                        $('#filter_kota').html(options);
                        
                        // Load cabang list for the selected province
                        // loadCabangList(provinsi, '');
                    },
                    error: function(xhr, status, error) {
                        console.log('Error loading kota list:', error);
                        console.log("Response:", xhr.responseText);
                        $('#filter_kota').html('<option value="">Error loading kota</option>');
                    }
                });
            }

            function loadCabangList() {
                // if (!provinsi) {
                //     $('#filter_cabang').html('<option value="">Pilih Cabang</option>');
                //     return;
                // }

                // var params = { provinsi: provinsi };
                // if (kota) {
                //     params.kota = kota;
                // }

                $.ajax({
                    url: "<?= base_url() ?>public/api/cabang",
                    type: "GET",
                    // data: params,
                    dataType: "JSON",
                    success: function (data) {
                        console.log('Cabang data received:', data);
                        var cabangData = data.data || data;
                        var options = '<option value="">Semua Cabang</option>';
                        
                        if (Array.isArray(cabangData)) {
                            cabangData.forEach(function(item) {
                                options += '<option value="' + item.cabang + '">' + item.cabang + '</option>';
                            });
                        }
                        $('#filter_cabang').html(options);
                    },
                    error: function(xhr, status, error) {
                        console.log('Error loading cabang list:', error);
                        console.log("Response:", xhr.responseText);
                        $('#filter_cabang').html('<option value="">Error loading cabang</option>');
                    }
                });
            }

            function loadData(filters = {}) {
                var url = "<?= base_url() ?>public/api/cabang-data";
                var params = new URLSearchParams(filters).toString();
                if (params) {
                    url += '?' + params;
                }

                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "JSON",
                    success: function (response) {
                        console.log('Cabang data received:', response);
                        
                        // Handle the new response format from public endpoint
                        var data = response.data || response;
                        
                        updateBarChart(data);
                        updateDataTable(data);
                        updateStatistics(data);
                        
                        // Store current filters
                        currentFilters = filters;
                    },
                    error: function() {
                        console.log('Error loading cabang data');
                        // Show dummy data as fallback
                        loadDummyData();
                    }
                });
            }

            function loadDummyData() {
                var cabangData = [
                    {cabang: "Jakarta Pusat", provinsi: "DKI Jakarta", kota: "Jakarta Pusat", jumlah: 42, jumlah_rs: 12},
                    {cabang: "Jakarta Selatan", provinsi: "DKI Jakarta", kota: "Jakarta Selatan", jumlah: 35, jumlah_rs: 10},
                    {cabang: "Bandung", provinsi: "Jawa Barat", kota: "Bandung", jumlah: 22, jumlah_rs: 6}
                ];

                updateBarChart(cabangData);
                updateDataTable(cabangData);
                updateStatistics(cabangData);
            }

            function updateBarChart(cabangData) {
                console.log('updateBarChart received data:', cabangData);
                
                if (chartCabang) {
                    chartCabang.dispose();
                }

                // Grouped bar chart jumlah anggota dan RS per cabang
                am4core.useTheme(am4themes_animated);
                chartCabang = am4core.create("chart_cabang", am4charts.XYChart);
                chartCabang.data = cabangData;
                
                // Configure category axis
                var categoryAxis = chartCabang.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "cabang";
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.renderer.minGridDistance = 30;
                categoryAxis.renderer.labels.template.rotation = -45;
                categoryAxis.renderer.labels.template.horizontalCenter = "right";
                categoryAxis.renderer.labels.template.verticalCenter = "middle";
                categoryAxis.renderer.labels.template.fontSize = 12;
                categoryAxis.renderer.labels.template.fill = am4core.color("#2c3e50");
                
                // Configure value axis
                var valueAxis = chartCabang.yAxes.push(new am4charts.ValueAxis());
                valueAxis.renderer.labels.template.fontSize = 12;
                valueAxis.renderer.labels.template.fill = am4core.color("#2c3e50");
                
                // Series untuk Anggota
                var seriesAnggota = chartCabang.series.push(new am4charts.ColumnSeries());
                seriesAnggota.dataFields.valueY = "jumlah";
                seriesAnggota.dataFields.categoryX = "cabang";
                seriesAnggota.name = "Anggota";
                seriesAnggota.columns.template.tooltipText = "{name}: [bold]{valueY}[/]";
                seriesAnggota.columns.template.fill = am4core.color("#667eea");
                seriesAnggota.columns.template.stroke = am4core.color("#667eea");
                seriesAnggota.columns.template.strokeWidth = 0;
                seriesAnggota.columns.template.column.cornerRadiusTopLeft = 3;
                seriesAnggota.columns.template.column.cornerRadiusTopRight = 3;
                
                // Series untuk RS
                var seriesRS = chartCabang.series.push(new am4charts.ColumnSeries());
                seriesRS.dataFields.valueY = "jumlah_rs";
                seriesRS.dataFields.categoryX = "cabang";
                seriesRS.name = "Rumah Sakit";
                seriesRS.columns.template.tooltipText = "{name}: [bold]{valueY}[/]";
                seriesRS.columns.template.fill = am4core.color("#FF9800");
                seriesRS.columns.template.stroke = am4core.color("#FF9800");
                seriesRS.columns.template.strokeWidth = 0;
                seriesRS.columns.template.column.cornerRadiusTopLeft = 3;
                seriesRS.columns.template.column.cornerRadiusTopRight = 3;
                
                // Add legend with custom styling
                chartCabang.legend = new am4charts.Legend();
                chartCabang.legend.fontSize = 13;
                chartCabang.legend.fontWeight = "500";
                chartCabang.legend.labels.template.fill = am4core.color("#2c3e50");
                
                // Chart title
                var title = chartCabang.titles.create();
                title.text = "Distribusi Anggota dan Rumah Sakit per Cabang";
                title.fontSize = 16;
                title.fontWeight = "600";
                title.fill = am4core.color("#2c3e50");
                title.marginBottom = 20;
            }

            function updateDataTable(cabangData) {
                console.log('updateDataTable received data:', cabangData);
                
                var tbody = $('#data_table_body');
                tbody.empty();
                
                if (!cabangData || cabangData.length === 0) {
                    tbody.append('<tr><td colspan="7" class="no-data">Tidak ada data tersedia</td></tr>');
                    return;
                }
                
                cabangData.forEach(function(item, index) {
                    var ratio = 'N/A';
                    if (item.jumlah_rs && item.jumlah_rs > 0) {
                        ratio = (item.jumlah / item.jumlah_rs).toFixed(2);
                    }
                    
                    var row = '<tr>' +
                        '<td>' + (index + 1) + '</td>' +
                        '<td><strong>' + (item.cabang || '-') + '</strong></td>' +
                        '<td>' + (item.provinsi || '-') + '</td>' +
                        '<td>' + (item.kota || '-') + '</td>' +
                        '<td>' + (item.jumlah || 0).toLocaleString() + '</td>' +
                        '<td>' + (item.jumlah_rs || 0).toLocaleString() + '</td>' +
                        '<td>' + ratio + '</td>' +
                        '</tr>';
                    tbody.append(row);
                });
            }

            function updateStatistics(cabangData) {
                var totalCabang = cabangData.length;
                var totalAnggota = cabangData.reduce((sum, item) => sum + parseInt(item.jumlah || 0), 0);
                var totalRS = cabangData.reduce((sum, item) => sum + parseInt(item.jumlah_rs || 0), 0);
                var rasio = totalRS > 0 ? (totalAnggota / totalRS).toFixed(2) : 'N/A';
                
                $('#stat_cabang').text(totalCabang.toLocaleString());
                $('#stat_anggota').text(totalAnggota.toLocaleString());
                $('#stat_rs').text(totalRS.toLocaleString());
                $('#stat_rasio').text(rasio);
            }

            // Event handlers
            $('#filter_provinsi').change(function() {
                var provinsi = $(this).val();
                loadKotaList(provinsi);
            });

            $('#filter_kota').change(function() {
                var provinsi = $('#filter_provinsi').val();
                var kota = $(this).val();
                // if (provinsi) {
                //     loadCabangList(provinsi, kota);
                // }
            });

            $('#btn_filter').click(function() {
                var filters = {};
                
                var provinsi = $('#filter_provinsi').val();
                var kota = $('#filter_kota').val();
                var cabang = $('#filter_cabang').val();
                
                if (provinsi) filters.provinsi = provinsi;
                if (kota) filters.kota = kota;
                if (cabang) filters.cabang = cabang;
                
                loadData(filters);
            });

            // Reset filters
            $('#btn_reset').click(function() {
                $('#filter_provinsi').val('');
                $('#filter_kota').html('<option value="">Pilih Kota/Kabupaten</option>');
                $('#filter_cabang').html('<option value="">Pilih Cabang</option>');
                loadData();
            });
        });
    </script>
</body>
</html>