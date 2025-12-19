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
    
    <!-- Leaflet CSS & JS for Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    
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
        }
        .dashboard-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .sebaran-wrapper {
            display: flex;
            gap: 20px;
            justify-content: space-between;
            margin-top: 20px;
            align-items: flex-start;
        }
        .sebaran-map-box, .sebaran-filter-box {
            background: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(44,62,80,0.08);
            border: 1px solid #e8e9ea;
        }
        .sebaran-map-box {
            flex: 2;
            min-width: 400px;
        }
        .sebaran-filter-box {
            flex: 0 0 320px;
            max-width: 320px;
            min-height: fit-content;
        }
        .sebaran-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            font-size: 13px;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .sebaran-table-container {
            max-height: 400px;
            overflow-y: auto;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        .sebaran-table th, .sebaran-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }
        .sebaran-table th {
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
        .sebaran-table tbody tr {
            transition: background-color 0.2s ease;
        }
        .sebaran-table tbody tr:hover {
            background: #f8f9fa;
            transform: translateY(-1px);
        }
        .sebaran-table tbody tr:nth-child(even) {
            background: #fafbfc;
        }
        .sebaran-table tbody tr:nth-child(even):hover {
            background: #f0f2f5;
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
        .filter-select {
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
            font-size: 15px;
            background: rgba(255,255,255,0.7);
            padding: 4px 8px;
            border-radius: 4px;
            min-width: 50px;
            text-align: center;
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
        .map-container {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 2px solid #e8e9ea;
            margin-bottom: 20px;
        }
        .top-provinsi-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding: 8px;
            background: rgba(255,255,255,0.5);
            border-radius: 6px;
        }
        
        body {
            background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%);
            font-family: 'Inter', sans-serif;
        }
        
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .page-title {
            color: #2c3e50;
            margin: 2rem 0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sebaran-wrapper {
                flex-direction: column;
            }
            .sebaran-map-box, .sebaran-filter-box {
                flex: 1;
                min-width: 100%;
                max-width: 100%;
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
                        <h2 class="content-color-primary page-title">🗺️ Peta Sebaran RS</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid main-container">
        <div class="dashboard-card">
            <div class="sebaran-wrapper">
                <div class="sebaran-map-box">
                    <div class="dashboard-subtitle">📊 Peta Choropleth - Distribusi Anggota per Provinsi</div>
                    <div class="map-container">
                        <div id="map_choropleth" style="width:100%; height:380px;"></div>
                    </div>
                    
                    <div class="dashboard-subtitle">📍 Peta Titik Alamat Praktek RS</div>
                    <div class="map-container">
                        <div id="map_points" style="width:100%; height:330px;"></div>
                    </div>
                    
                    <div class="dashboard-subtitle">📋 Data Sebaran RS per Kabupaten/Kota</div>
                    <div class="sebaran-table-container">
                        <table class="sebaran-table">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 25%;">Provinsi</th>
                                    <th style="width: 30%;">Kabupaten/Kota</th>
                                    <th style="width: 20%;">Total Anggota</th>
                                    <th style="width: 15%;">Total RS</th>
                                    <!-- <th style="width: 10%;">Rasio</th> -->
                                </tr>
                            </thead>
                            <tbody id="kabupaten_table">
                                <tr>
                                    <td colspan="6" class="no-data">Memuat data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Tambahkan tabel list titik alamat -->
                    <div class="dashboard-subtitle mt-4">📋 Daftar Titik Alamat Praktek RS</div>
                    <div class="sebaran-table-container">
                        <table class="sebaran-table">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 20%;">Dokter</th>
                                    <th style="width: 10%;">Jenis</th>
                                    <th style="width: 35%;">Alamat Praktek</th>
                                    <th style="width: 25%;">Provinsi</th>
                                    <th style="width: 25%;">Kabupaten/Kota</th>
                                </tr>
                            </thead>
                            <tbody id="titik_alamat_table">
                                <tr>
                                    <td colspan="6" class="no-data">Memuat data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                
                <div class="sebaran-filter-box">
                    <div class="dashboard-subtitle">🎛️ Filter Data</div>
                    
                    <label class="filter-label">Provinsi</label>
                    <select class="filter-select" id="filter_provinsi">
                        <option value="">Semua Provinsi</option>
                    </select>
                    
                    <label class="filter-label">Kota/Kabupaten</label>
                    <select class="filter-select" id="filter_kota">
                        <option value="">Semua Kab/Kota</option>
                    </select>
                    
                    <button class="filter-btn" id="btn_apply_filter">Filter Data</button>
                    <button class="filter-btn reset" id="btn_reset_filter" style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);">Reset Filter</button>
                    
                    <!-- Stats Box -->
                    <div class="stats-box">
                        <div class="dashboard-subtitle">📊 Statistik Sebaran</div>
                        <div id="stats_content">
                            <div class="stat-item">
                                <span class="stat-label">Total Provinsi:</span>
                                <span class="stat-value" id="stat_provinsi">-</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Total Kab/Kota:</span>
                                <span class="stat-value" id="stat_kabupaten">-</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Total Anggota:</span>
                                <span class="stat-value" id="stat_anggota">-</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Total RS:</span>
                                <span class="stat-value" id="stat_rs">-</span>
                            </div>
                            <!-- <div class="stat-item">
                                <span class="stat-label">Rasio Anggota/RS:</span>
                                <span class="stat-value" id="stat_rasio">-</span>
                            </div> -->
                        </div>
                    </div>
                    
                    <div class="stats-box">
                        <div class="dashboard-subtitle">🏆 Top 5 Provinsi</div>
                        <div id="top_provinsi_content">
                            <div class="no-data">Memuat data...</div>
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
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 600,
            once: true
        });

        var mapChoropleth;
        var mapPoints;
        var currentFilters = {};

        $(document).ready(function () {
            // Initialize both maps
            initializeMaps();
            
            // Load provinsi list for filter
            loadProvinsiList();
            
            // Load initial data
            loadData();
            
            // Setup event handlers
            setupEventHandlers();
        });

        function initializeMaps() {
            // Initialize choropleth map
            mapChoropleth = L.map('map_choropleth').setView([-2.5, 118], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(mapChoropleth);
            
            // Initialize points map
            mapPoints = L.map('map_points').setView([-2.5, 118], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(mapPoints);
        }

        function setupEventHandlers() {
            // Filter provinsi change
            $('#filter_provinsi').change(function() {
                var provinsi = $(this).val();
                loadKotaList(provinsi);
            });
            
            // Apply filter
            $('#btn_apply_filter').click(function() {
                var filters = {};
                if ($('#filter_provinsi').val()) {
                    filters.provinsi = $('#filter_provinsi').val();
                }
                if ($('#filter_kota').val()) {
                    filters.kota = $('#filter_kota').val();
                }
                loadData(filters);
            });
            
            // Reset filter
            $('#btn_reset_filter').click(function() {
                $('#filter_provinsi').val('');
                $('#filter_kota').val('');
                loadKotaList('');
                loadData();
            });
        }

        function loadProvinsiList() {
            $.ajax({
                url: "<?=base_url()?>main/sebaran_rs/provinsi_list",
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    var options = '<option value="">Semua Provinsi</option>';
                    data.forEach(function(item) {
                        options += '<option value="' + item.provinsi + '">' + item.provinsi + '</option>';
                    });
                    $('#filter_provinsi').html(options);
                },
                error: function() {
                    console.log('Error loading provinsi list');
                }
            });
        }

        function loadKotaList(provinsi) {
            if (!provinsi) {
                $('#filter_kota').html('<option value="">Semua Kota/Kabupaten</option>');
                return;
            }

            $.ajax({
                url: "<?=base_url()?>main/sebaran_rs/kota_list",
                type: "GET",
                data: { provinsi: provinsi },
                dataType: "JSON",
                success: function (data) {
                    var options = '<option value="">Semua Kota/Kabupaten</option>';
                    data.forEach(function(item) {
                        options += '<option value="' + item.kota_name + '">' + item.kota_name + '</option>';
                    });
                    $('#filter_kota').html(options);
                },
                error: function() {
                    console.log('Error loading kota list');
                    $('#filter_kota').html('<option value="">Error loading kota</option>');
                }
            });
        }

        function loadData(filters = {}) {
            var url = "<?=base_url()?>main/sebaran_rs/data";
            var params = new URLSearchParams(filters).toString();
            if (params) {
                url += '?' + params;
            }

            $.ajax({
                url: url,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    console.log('Sebaran RS data received:', data);
                    
                    // Update maps
                    updateChoroplethMap(data.sebaran_provinsi);
                    updatePointsMap(data.titik_alamat);
                    
                    // Update table and stats
                    updateKabupatenTable(data.sebaran_kabupaten);
                    updateTitikAlamatTable(data.titik_alamat); // <-- Tambahkan baris ini
                    updateStatistics(data.titik_alamat, data.sebaran_provinsi);
                    updateTopProvinsi(data.sebaran_provinsi);
                    
                    // Store current filters
                    currentFilters = data.filters_applied || {};
                },
                error: function() {
                    console.log('Error loading sebaran RS data');
                    // Show dummy data as fallback
                    // loadDummyData();
                }
            });
        }

        function loadDummyData() {
            var dummyProvinsi = [
                {provinsi: "DKI Jakarta", total_anggota: 245, total_rs: 45, total_kabupaten: 5},
                {provinsi: "Jawa Barat", total_anggota: 189, total_rs: 32, total_kabupaten: 18},
                {provinsi: "Jawa Tengah", total_anggota: 167, total_rs: 28, total_kabupaten: 15}
            ];
            
            var dummyKabupaten = [
                {provinsi: "DKI Jakarta", kabupaten: "Jakarta Pusat", total_anggota: 65, total_rs: 12},
                {provinsi: "DKI Jakarta", kabupaten: "Jakarta Barat", total_anggota: 58, total_rs: 10},
                {provinsi: "DKI Jakarta", kabupaten: "Jakarta Timur", total_anggota: 52, total_rs: 9}
            ];
            
            var dummyTitik = [
                {provinsi: "DKI Jakarta", kota: "Jakarta Pusat", alamat_praktek: "RS Pusat Jakarta"},
                {provinsi: "DKI Jakarta", kota: "Jakarta Barat", alamat_praktek: "RS Barat Jakarta"}
            ];

            updateChoroplethMap(dummyProvinsi);
            updatePointsMap(dummyTitik);
            updateKabupatenTable(dummyKabupaten);
            updateStatistics(dummyProvinsi, dummyKabupaten);
            updateTopProvinsi(dummyProvinsi);
        }

        function updateChoroplethMap(provinsiData) {
            // Clear existing layers
            mapChoropleth.eachLayer(function(layer) {
                if (layer !== mapChoropleth._layers[Object.keys(mapChoropleth._layers)[0]]) {
                    mapChoropleth.removeLayer(layer);
                }
            });
            
            // Add choropleth visualization with circle markers
            var choroplethMarkers = [];
            provinsiData.forEach(function(item) {
                var lat = getProvinceLatLng(item.provinsi).lat;
                var lng = getProvinceLatLng(item.provinsi).lng;
                
                var marker = L.circleMarker([lat, lng], {
                    radius: Math.min(Math.max(item.total_anggota / 10, 8), 30),
                    fillColor: getColorByValue(item.total_anggota),
                    color: '#333',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.7
                }).addTo(mapChoropleth);
                
                marker.bindPopup('<b>' + item.provinsi + '</b><br>' +
                                'Total Anggota: ' + parseInt(item.total_anggota).toLocaleString() + '<br>' +
                                'Total RS: ' + parseInt(item.total_rs).toLocaleString() + '<br>' +
                                'Total Kab/Kota: ' + parseInt(item.total_kabupaten).toLocaleString());

                choroplethMarkers.push(marker);
            });

            // Fit map to choropleth markers if any
            if (choroplethMarkers.length > 0) {
                try {
                    var group = new L.featureGroup(choroplethMarkers);
                    mapChoropleth.fitBounds(group.getBounds().pad(0.1));
                } catch (e) {
                    console.warn('Could not fit choropleth bounds:', e);
                }
            }
        }

        function updatePointsMap(titikData) {
            // Clear existing layers except tile layer
            mapPoints.eachLayer(function(layer) {
                if (layer !== mapPoints._layers[Object.keys(mapPoints._layers)[0]]) {
                    mapPoints.removeLayer(layer);
                }
            });
            
            // Add point markers for RS locations
            var pointMarkers = [];
            if (titikData && titikData.length > 0) {
                titikData.forEach(function(item) {
                    // Use actual coordinates from data if available, otherwise fallback to calculated coordinates
                    var lat = (item.latitude && item.latitude != 0) ? parseFloat(item.latitude) : getCityLatLng(item.kota, item.provinsi).lat;
                    var lng = (item.longitude && item.longitude != 0) ? parseFloat(item.longitude) : getCityLatLng(item.kota, item.provinsi).lng;
                    
                    console.log('Adding marker for:', item.alamat_praktek, 'at', lat, lng, 'from data:', item.latitude, item.longitude);
                    
                    // Only add marker if we have valid coordinates
                    if (lat && lng && lat != 0 && lng != 0) {
                        var marker = L.marker([lat, lng]).addTo(mapPoints);
                        
                        marker.bindPopup('<b>' + item.alamat_praktek + '</b><br>' +
                                        'Kota: ' + item.kota + '<br>' +
                                        'Provinsi: ' + item.provinsi + '<br>' +
                                        'Jenis: ' + item.jenis_praktek + '<br>' +
                                        'Koordinat: ' + lat.toFixed(6) + ', ' + lng.toFixed(6));

                        pointMarkers.push(marker);
                    }
                });
            }

            // Fit points map to markers if any
            if (pointMarkers.length > 0) {
                try {
                    var groupPts = new L.featureGroup(pointMarkers);
                    mapPoints.fitBounds(groupPts.getBounds().pad(0.1));
                } catch (e) {
                    console.warn('Could not fit points bounds:', e);
                }
            }
        }

        function updateKabupatenTable(kabupatenData) {
            var tbody = '';
            if (!kabupatenData || kabupatenData.length === 0) {
                tbody = '<tr><td colspan="6" class="no-data">Tidak ada data yang sesuai dengan filter</td></tr>';
            } else {
                kabupatenData.forEach(function(item, idx) {
                    var ratio = 'N/A';
                    if (item.total_rs && item.total_rs > 0) {
                        ratio = (item.total_anggota / item.total_rs).toFixed(2);
                    }
                    tbody += `
                        <tr>
                            <td>${idx + 1}</td>
                            <td>${item.provinsi}</td>
                            <td><strong>${item.kota}</strong></td>
                            <td>${parseInt(item.total_anggota || 0).toLocaleString()}</td>
                            <td>${parseInt(item.total_rs || 0).toLocaleString()}</td>
                        </tr>
                    `;
                });
            }
            $('#kabupaten_table').html(tbody);
        }

        // Tambahkan fungsi untuk mengisi tabel titik alamat
        function updateTitikAlamatTable(titikData) {
            var tbody = '';
            if (!titikData || titikData.length === 0) {
                tbody = '<tr><td colspan="6" class="no-data">Tidak ada data titik alamat</td></tr>';
            } else {
                titikData.forEach(function(item, idx) {
                    var koordinat = (item.latitude && item.longitude) ? 
                        (parseFloat(item.latitude).toFixed(4) + ', ' + parseFloat(item.longitude).toFixed(4)) : '-';
                    tbody += `
                        <tr>
                            <td>${idx + 1}</td>
                            <td>${item.nama_dokter || '-'}</td>
                            <td>${item.jenis_praktek || '-'}</td>
                            <td>${item.alamat_praktek || '-'}</td>
                            <td>${item.provinsi || '-'}</td>
                            <td>${item.kota || '-'}</td>
                        </tr>
                    `;
                });
            }
            $('#titik_alamat_table').html(tbody);
        }

        function updateStatistics(data, sebaranData) {
            if (!data || data.length === 0) {
                $('#stat_provinsi').text('0');
                $('#stat_kabupaten').text('0');
                $('#stat_anggota').text('0');
                $('#stat_rs').text('0');
                // $('#stat_rasio').text('N/A');
                return;
            }

            var totalProvinsi = new Set(data.map(item => item.provinsi).filter(p => p && p !== "")).size;
            var totalKabupaten = new Set(data.map(item => item.kota).filter(k => k && k !== "")).size;
            // Ubah di sini: sum dari item.total_dokter
            var totalAnggota = sebaranData.reduce((sum, item) => sum + (parseInt(item.total_anggota) || 0), 0);
            var totalRs = new Set(data.map(item => item.alamat_praktek).filter(a => a && a !== "")).size;

            $('#stat_provinsi').text(totalProvinsi.toLocaleString());
            $('#stat_kabupaten').text(totalKabupaten.toLocaleString());
            $('#stat_anggota').text(totalAnggota.toLocaleString());
            $('#stat_rs').text(totalRs.toLocaleString());
            // $('#stat_rasio').text(rasio);
        }

        function updateTopProvinsi(provinsiData) {
            if (!provinsiData || provinsiData.length === 0) {
                $('#top_provinsi_content').html('<div class="no-data">Tidak ada data</div>');
                return;
            }

            // Sort by total_anggota descending and take top 5
            var sortedData = [...provinsiData].sort((a, b) => parseInt(b.total_anggota) - parseInt(a.total_anggota)).slice(0, 5);
            
            var content = '';
            sortedData.forEach(function(item, index) {
                content += `
                    <div class="top-provinsi-item">
                        <span style="font-weight: 600; color: #2c3e50;">${index + 1}. ${item.provinsi}</span>
                        <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">${parseInt(item.total_anggota).toLocaleString()}</span>
                    </div>
                `;
            });
            
            $('#top_provinsi_content').html(content);
        }

        // Helper functions for coordinates (simplified version)
        function getProvinceLatLng(provinsi) {
            var coords = {
                'Aceh': {lat: 4.6951, lng: 96.7494},
                'Bali': {lat: -8.3405, lng: 115.0920},
                'Banten': {lat: -6.4058, lng: 106.0640},
                'Bengkulu': {lat: -3.7928, lng: 102.2600},
                'DI Yogyakarta': {lat: -7.7956, lng: 110.3695},
                'DKI Jakarta': {lat: -6.2088, lng: 106.8456},
                'Gorontalo': {lat: 0.6994, lng: 122.4467},
                'Jambi': {lat: -1.4852, lng: 102.4381},
                'Jawa Barat': {lat: -6.9175, lng: 107.6191},
                'Jawa Tengah': {lat: -7.2575, lng: 110.1739},
                'Jawa Timur': {lat: -7.5360, lng: 112.2384},
                'Kalimantan Barat': {lat: -0.0333, lng: 109.3333},
                'Kalimantan Selatan': {lat: -3.3167, lng: 114.5833},
                'Kalimantan Tengah': {lat: -2.2167, lng: 113.9167},
                'Kalimantan Timur': {lat: -0.5000, lng: 117.0000},
                'Kalimantan Utara': {lat: 3.0731, lng: 116.0414},
                'Kepulauan Bangka Belitung': {lat: -2.7411, lng: 106.4406},
                'Kepulauan Riau': {lat: 3.9457, lng: 108.1429},
                'Lampung': {lat: -4.5586, lng: 105.4068},
                'Maluku': {lat: -3.2385, lng: 130.1453},
                'Maluku Utara': {lat: 1.5700, lng: 127.8088},
                'Nusa Tenggara Barat': {lat: -8.6529, lng: 117.3616},
                'Nusa Tenggara Timur': {lat: -8.6574, lng: 121.0794},
                'Nusa Tenggra Barat': {lat: -8.6529, lng: 117.3616}, // typo, duplicate NTB
                'Papua': {lat: -4.2692, lng: 138.0800},
                'Papua Barat': {lat: -1.3361, lng: 133.1747},
                'Papua Pegunungan': {lat: -4.1427, lng: 138.6196},
                'Papua Selatan': {lat: -7.6667, lng: 139.9833},
                'Papua Tengah': {lat: -3.9167, lng: 137.5833},
                'Riau': {lat: 0.2933, lng: 101.7068},
                'Sulawesi Barat': {lat: -2.8446, lng: 119.2321},
                'Sulawesi Selatan': {lat: -5.1477, lng: 119.4327},
                'Sulawesi Tengah': {lat: -1.4300, lng: 121.4456},
                'Sulawesi Tenggara': {lat: -4.1449, lng: 122.1746},
                'Sulawesi Utara': {lat: 1.4927, lng: 124.8428},
                'Sumatera Barat': {lat: -0.7893, lng: 100.6500},
                'Sumatera Selatan': {lat: -3.3194, lng: 104.9148},
                'Sumatera Utara': {lat: 3.5952, lng: 98.6722},
            };
            return coords[provinsi] || {lat: -2.5, lng: 118};
        }

        function getCityLatLng(kota, provinsi) {
            // Simplified city coordinates - in real implementation this should come from database
            return getProvinceLatLng(provinsi);
        }

        function getColorByValue(value) {
            if (value > 200) return '#8B0000';
            if (value > 150) return '#FF4500';
            if (value > 100) return '#FFD700';
            if (value > 50) return '#3c00ffff';
            return '#00ff2fff';
        }
    </script>
</body>
</html>