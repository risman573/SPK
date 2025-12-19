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
        .filter-btn.reset {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
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
        .map-controls {
            margin-bottom: 15px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .map-toggle-btn {
            padding: 8px 16px;
            border-radius: 6px;
            border: 2px solid #3498db;
            background: #fff;
            color: #3498db;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        .map-toggle-btn.active {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
        }
        .map-toggle-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
        }
        .loading {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
            font-style: italic;
        }
        
        /* Custom styles for provinsi markers */
        .custom-marker-provinsi {
            border: none !important;
            background: transparent !important;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 8px;
        }

        .leaflet-popup-content {
            margin: 8px 12px;
            line-height: 1.4;
        }
        
        .custom-popup-provinsi .leaflet-popup-content-wrapper {
            background: #fff;
            border: 2px solid #3498db;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .custom-popup-provinsi .leaflet-popup-tip {
            border-top-color: #3498db;
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

    <div class="container-fluid bg-light-opac main-container">
        <div class="row">
            <div class="container-fluid my-3 main-container">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="content-color-primary page-title">🗺️ Pemetaan Cakupan Layanan Spesialis THT BKL di Indonesia</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid main-container">
        <div class="dashboard-card">
            <div class="sebaran-wrapper">
                <div class="sebaran-map-box">
                    <!-- Map Controls -->
                    <div class="map-controls">
                        <!-- <button class="map-toggle-btn active" data-type="alamat_rumah">Alamat Rumah</button> -->
                        <button class="map-toggle-btn active" data-type="alamat_praktek">Tempat Praktek</button>
                        <button class="map-toggle-btn" data-type="spesialis_provinsi">Spesialis per Provinsi</button>
                        <!-- <button class="map-toggle-btn" data-type="rs_provinsi">RS per Provinsi</button> -->
                    </div>
                    
                    <!-- Map Container -->
                    <div class="dashboard-subtitle">📍 Peta Interaktif Sebaran Anggota</div>
                    <div class="map-container">
                        <div id="map" style="width:100%; height:450px;"></div>
                    </div>
                    
                    <!-- Tabel Data Sebaran -->
                    <div class="dashboard-subtitle">📋 Tabel Data Sebaran Anggota</div>
                    <div class="sebaran-table-container">
                        <table class="sebaran-table" id="detail_table">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Nama</th>
                                    <th style="width: 10%;">Kota</th>
                                    <th style="width: 12%;">Provinsi</th>
                                    <th style="width: 8%;">Status</th>
                                    <th style="width: 10%;">KODI</th>
                                    <th style="width: 15%;">Praktek 1</th>
                                    <th style="width: 15%;">Praktek 2</th>
                                    <th style="width: 15%;">Praktek 3</th>
                                </tr>
                            </thead>
                            <tbody id="anggota_table">
                                <tr><td colspan="9" class="no-data">Memuat data...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="sebaran-filter-box">
                    <div class="dashboard-subtitle">🎛️ Filter Data</div>
                    
                    <label class="filter-label">Status User</label>
                    <select class="filter-select" id="filter_status_user">
                        <option value="">Semua Status</option>
                        <option value="Konsultan">Konsultan</option>
                        <option value="Fellowship">Fellowship</option>
                        <option value="Non Konsultan Non Fellowship">Non Konsultan Non Fellowship</option>
                    </select>
                    
                    <label class="filter-label">Provinsi</label>
                    <select class="filter-select" id="filter_provinsi">
                        <option value="">Semua Provinsi</option>
                    </select>
                    
                    <label class="filter-label">Kota/Kabupaten</label>
                    <select class="filter-select" id="filter_kota">
                        <option value="">Semua Kab/Kota</option>
                    </select>
                    
                    <label class="filter-label">Fellow/Subspesialis</label>
                    <select class="filter-select" id="filter_kodi">
                        <option value="">Semua</option>
                    </select>
                    
                    <button class="filter-btn" id="btn_apply_filter">Filter Data</button>
                    <button class="filter-btn reset" id="btn_reset_filter">Reset Filter</button>
                    
                    <!-- Stats Box -->
                    <div class="stats-box">
                        <div class="dashboard-subtitle">📊 Statistik Sebaran</div>
                        <div id="stats_content">
                            <div class="stat-item">
                                <span class="stat-label">Total Anggota:</span>
                                <span class="stat-value" id="total_anggota">-</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Konsultan:</span>
                                <span class="stat-value" id="total_konsultan">-</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Fellowship:</span>
                                <span class="stat-value" id="total_fellowship">-</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Non Konsultan:</span>
                                <span class="stat-value" id="total_non_konsultan">-</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Total Provinsi:</span>
                                <span class="stat-value" id="total_provinsi">-</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Total Kab/Kota:</span>
                                <span class="stat-value" id="total_kota">-</span>
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
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 600,
            once: true
        });

        var map;
        var currentMarkers = [];
        var currentDataType = 'alamat_praktek';

        // Koordinat provinsi Indonesia
        var provinsiCoordinates = {
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
          'Sumatera Utara': {lat: 3.5952, lng: 98.6722}
        };

        $(document).ready(function () {
            // Inisialisasi peta Leaflet
            initializeMap();
            
            // Load data awal
            loadMapData();
            loadDetailData();
            loadProvinsiOptions();
            loadKodiOptions();
            
            // Event handlers
            setupEventHandlers();
        });

        function initializeMap() {
            map = L.map('map').setView([-2.5, 118], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
        }

        function setupEventHandlers() {
            // Map type toggle
            $('.map-toggle-btn').click(function() {
                $('.map-toggle-btn').removeClass('active');
                $(this).addClass('active');
                currentDataType = $(this).data('type');
                console.log('Map type changed to:', currentDataType);
                loadMapData();
                loadDetailData();
            });
            
            // Filter provinsi change
            $('#filter_provinsi').change(function() {
                var provinsi = $(this).val();
                loadKotaByProvinsi(provinsi);
            });
            
            // Apply filter
            $('#btn_apply_filter').click(function() {
                loadMapData();
                loadDetailData();
            });
            
            // Reset filter
            $('#btn_reset_filter').click(function() {
                resetFilters();
                loadMapData();
                loadDetailData();
            });
        }

        function loadProvinsiOptions() {
            $.get('<?= base_url() ?>api/provinsi', function(data) {
                var options = '<option value="">Semua Provinsi</option>';
                data.forEach(function(item) {
                    options += '<option value="' + item.provinsi + '">' + item.provinsi + '</option>';
                });
                $('#filter_provinsi').html(options);
            }).fail(function() {
                console.error('Error loading provinsi options');
            });
        }

        function loadKodiOptions() {
            $.get('<?= base_url() ?>public/api/kodi', function(data) {
                var options = '<option value="">Semua KODI</option>';
                data.forEach(function(item) {
                    options += '<option value="' + item.kodi + '">' + item.kodi + '</option>';
                });
                $('#filter_kodi').html(options);
            }).fail(function() {
                console.error('Error loading kodi options');
            });
        }

        function loadKotaByProvinsi(provinsi) {
            if (!provinsi) {
                $('#filter_kota').html('<option value="">Semua Kab/Kota</option>');
                return;
            }
            
            $.get('<?= base_url() ?>api/kota?provinsi=' + encodeURIComponent(provinsi), function(data) {
                var options = '<option value="">Semua Kab/Kota</option>';
                data.forEach(function(item) {
                    options += '<option value="' + item.kota + '">' + item.kota + '</option>';
                });
                $('#filter_kota').html(options);
            }).fail(function() {
                console.error('Error loading kota options');
            });
        }

        function getFilterData() {
            var endpoint = '';
            switch(currentDataType) {
                case 'alamat_rumah':
                    endpoint = 'alamat_rumah';
                    break;
                case 'alamat_praktek':
                    endpoint = 'alamat_praktek';
                    break;
                case 'spesialis_provinsi':
                    endpoint = 'spesialis_provinsi';
                    break;
                case 'rs_provinsi':
                    endpoint = 'rs_provinsi';
                    break;
            }
            return {
                status_user: $('#filter_status_user').val(),
                provinsi: $('#filter_provinsi').val(),
                kota: $('#filter_kota').val(),
                kodi: $('#filter_kodi').val(),
                tipe: endpoint
            };
        }

        function loadMapData() {
            clearMarkers();
            
            // Show loading in table
            $('#anggota_table').html('<tr><td colspan="9" class="no-data">Memuat data peta...</td></tr>');
            
            var filters = getFilterData();
            var endpoint = '';
            
            switch(currentDataType) {
                case 'alamat_rumah':
                    endpoint = 'getTitikAlamatRumah';
                    break;
                case 'alamat_praktek':
                    endpoint = 'getTitikAlamatPraktek';
                    break;
                case 'spesialis_provinsi':
                    endpoint = 'getSebaranSpesialisProvinsi';
                    break;
                case 'rs_provinsi':
                    endpoint = 'getSebaranRSProvinsi';
                    break;
            }
            
            console.log('Calling endpoint:', endpoint, 'with filters:', filters);
            
            $.post('<?= base_url() ?>public/api/sebaran-anggota/' + endpoint, filters, function(response) {
                try {
                    var data = typeof response === 'string' ? JSON.parse(response) : response;
                    
                    console.log('API Response for', currentDataType, ':', data);
                    
                    if (data.status === 'success') {
                        console.log('Data received:', data.data);
                        if (currentDataType === 'alamat_rumah' || currentDataType === 'alamat_praktek') {
                            displayPointMarkers(data.data);
                        } else if (currentDataType === 'spesialis_provinsi' || currentDataType === 'rs_provinsi') {
                            displayProvinsiMarkers(data.data);
                        } else {
                            displayAggregateData(data.data);
                        }
                    } else {
                        console.error('API returned error:', data);
                        $('#anggota_table').html('<tr><td colspan="9" class="no-data">Gagal memuat data peta</td></tr>');
                    }
                } catch (e) {
                    console.error('Error parsing map data:', e);
                    $('#anggota_table').html('<tr><td colspan="9" class="no-data">Error dalam memproses data</td></tr>');
                }
            }).fail(function() {
                $('#anggota_table').html('<tr><td colspan="9" class="no-data">Gagal menghubungi server</td></tr>');
            });
        }

        function loadDetailData() {
            var filters = getFilterData();
            
            $.post('<?= base_url() ?>public/api/sebaran-anggota/getDetailAnggota', filters, function(response) {
                try {
                    var data = typeof response === 'string' ? JSON.parse(response) : response;
                    
                    if (data.status === 'success') {
                        updateDetailTable(data.data);
                        updateStats(data.data);
                    }
                } catch (e) {
                    console.error('Error parsing detail data:', e);
                }
            }).fail(function() {
                console.error('Error loading detail data');
            });
        }

        function clearMarkers() {
            currentMarkers.forEach(function(marker) {
                map.removeLayer(marker);
            });
            currentMarkers = [];
        }

        function displayPointMarkers(data) {
            if (!data || data.length === 0) {
                $('#anggota_table').html('<tr><td colspan="9" class="no-data">Tidak ada data</td></tr>');
                return;
            }

            data.forEach(function(item) {
                if (item.lat && item.lon && item.lat !== '0' && item.lon !== '0') {
                    // Custom icon based on status
                    var iconColor = '#3498db'; // default
                    if (item.status_user === 'Konsultan') iconColor = '#e74c3c';
                    else if (item.status_user === 'Fellowship') iconColor = '#f39c12';
                    else if (item.status_user === 'Non Konsultan Non Fellowship') iconColor = '#95a5a6';
                    
                    var customIcon = L.divIcon({
                        className: 'custom-marker',
                        html: '<div style="' +
                            'background: ' + iconColor + '; ' +
                            'width: 20px; ' +
                            'height: 20px; ' +
                            'border-radius: 50%; ' +
                            'border: 3px solid white; ' +
                            'box-shadow: 0 2px 5px rgba(0,0,0,0.3);' +
                        '"></div>',
                        iconSize: [20, 20],
                        iconAnchor: [10, 10]
                    });
                    
                    var marker = L.marker([parseFloat(item.lat), parseFloat(item.lon)], {icon: customIcon})
                        .addTo(map)
                        .bindPopup(
                            '<div style="min-width: 250px; font-family: Arial, sans-serif;">' +
                                '<div style="border-bottom: 2px solid #3498db; padding-bottom: 8px; margin-bottom: 12px;">' +
                                    '<h5 style="margin: 0; color: #2c3e50; font-weight: 600;">' + item.nama + '</h5>' +
                                '</div>' +
                                '<div style="margin-bottom: 8px;">' +
                                    '<strong style="color: #34495e;">Status:</strong> ' +
                                    '<span style="padding: 2px 6px; border-radius: 3px; font-size: 11px; font-weight: 600; background: ' + getStatusColor(item.status_user) + '">' + (item.status_user || '-') + '</span>' +
                                '</div>' +
                                (item.kodi ? '<div style="margin-bottom: 8px;"><strong style="color: #34495e;">KODI:</strong> <span style="color: #7f8c8d;">' + item.kodi + '</span></div>' : '') +
                                '<div style="margin-bottom: 8px;">' +
                                    '<strong style="color: #34495e;">Kota:</strong> ' +
                                    '<span style="color: #7f8c8d;">' + (item.kota || '-') + '</span>' +
                                '</div>' +
                                '<div style="margin-bottom: 8px;">' +
                                    '<strong style="color: #34495e;">Provinsi:</strong> ' +
                                    '<span style="color: #7f8c8d;">' + (item.provinsi || '-') + '</span>' +
                                '</div>' +
                                (item.cabang ? '<div style="margin-bottom: 8px;"><strong style="color: #34495e;">Cabang:</strong> <span style="color: #7f8c8d;">' + item.cabang + '</span></div>' : '') +
                                '<div style="padding-top: 8px; border-top: 1px solid #ecf0f1; font-size: 12px; color: #95a5a6;">' +
                                    '<strong>Jenis:</strong> ' + (item.jenis_alamat === 'alamat_rumah' ? 'Alamat Rumah' : 'Alamat Praktek') +
                                '</div>' +
                            '</div>'
                        );
                    
                    currentMarkers.push(marker);
                }
            });

            if (currentMarkers.length > 0) {
                var group = new L.featureGroup(currentMarkers);
                map.fitBounds(group.getBounds().pad(0.1));
            }
        }

        function getStatusColor(status) {
            switch(status) {
                case 'Konsultan': return '#e74c3c';
                case 'Fellowship': return '#f39c12';
                case 'Non Konsultan Non Fellowship': return '#95a5a6';
                default: return '#3498db';
            }
        }

        function displayProvinsiMarkers(data) {
            console.log('Data received for provinsi markers:', data);
            
            if (!data || data.length === 0) {
                console.log('No data available for provinsi markers');
                $('#anggota_table').html('<tr><td colspan="8" class="no-data">Tidak ada data provinsi yang ditemukan</td></tr>');
                return;
            }
            
            // Group data by provinsi
            var provinsiData = {};
            data.forEach(function(item) {
                console.log('Processing item:', item);
                
                var provinsiName = item.provinsi || item.nama_provinsi || 'Unknown';
                
                if (!provinsiData[provinsiName]) {
                    provinsiData[provinsiName] = {
                        provinsi: provinsiName,
                        total: 0,
                        kodi_details: {},
                        total_anggota: 0,
                        total_rs: 0
                    };
                }
                
                var itemTotal = parseInt(item.total || item.jumlah || item.count || 1);
                provinsiData[provinsiName].total += itemTotal;
                
                // For RS per provinsi data
                if (currentDataType === 'rs_provinsi') {
                    var totalAnggota = parseInt(item.total_anggota || 0);
                    var totalRs = parseInt(item.total_rs || 0);
                    
                    provinsiData[provinsiName].total_anggota += totalAnggota;
                    provinsiData[provinsiName].total_rs += totalRs;
                } else {
                    // For spesialis per provinsi - process KODI fields
                    var kodiFields = [
                        'alergi_imunologi', 'bronko_esofagologi', 'fasial_plastik_rekonstruksi',
                        'laring_faring', 'neurotologi', 'onkologi_bedah_kepala_leher',
                        'otologi', 'rinologi', 'tht_komunitas'
                    ];
                    
                    kodiFields.forEach(function(kodiField) {
                        var kodiValue = parseInt(item[kodiField] || 0);
                        if (kodiValue > 0) {
                            if (!provinsiData[provinsiName].kodi_details[kodiField]) {
                                provinsiData[provinsiName].kodi_details[kodiField] = 0;
                            }
                            provinsiData[provinsiName].kodi_details[kodiField] += kodiValue;
                        }
                    });
                    
                    // Also check for legacy 'kodi' field for backward compatibility
                    var kodiName = item.kodi || item.subspecialty || item.spesialis;
                    if (kodiName && kodiName !== 'Tidak Diketahui') {
                        if (!provinsiData[provinsiName].kodi_details[kodiName]) {
                            provinsiData[provinsiName].kodi_details[kodiName] = 0;
                        }
                        provinsiData[provinsiName].kodi_details[kodiName] += itemTotal;
                    }
                }
            });
            
            console.log('Grouped provinsi data:', provinsiData);
            
            // Create markers for each provinsi
            var markersCreated = 0;
            Object.keys(provinsiData).forEach(function(provinsiName) {
                var prov = provinsiData[provinsiName];
                var coordinates = provinsiCoordinates[provinsiName];
                
                console.log('Processing provinsi:', provinsiName, 'coordinates:', coordinates, 'total:', prov.total);
                
                if (coordinates && prov.total > 0) {
                    // Create small dot marker (like Choropleth map style)
                    var iconSize = 12; // Fixed small size like choropleth dots
                    var iconColor = getProvinsiColor(prov.total);
                    
                    var customIcon = L.divIcon({
                        className: 'custom-marker-provinsi-dot',
                        html: `<div style="
                            background: red; 
                            width: ${iconSize}px; 
                            height: ${iconSize}px; 
                            border-radius: 50%; 
                            border: 2px solid white; 
                            box-shadow: 0 2px 6px rgba(0,0,0,0.4);
                            cursor: pointer;
                        "></div>`,
                        iconSize: [iconSize, iconSize],
                        iconAnchor: [iconSize/2, iconSize/2]
                    });
                    
                    // Build popup content with KODI details
                    // Build popup content - different for RS vs Spesialis
                    var popupContent = 
                        '<div style="min-width: 320px; max-width: 400px; font-family: Arial, sans-serif;">' +
                            '<div style="border-bottom: 3px solid #3498db; padding-bottom: 10px; margin-bottom: 15px; text-align: center;">' +
                                '<h4 style="margin: 0; color: #2c3e50; font-weight: 700; font-size: 18px;">🏥 ' + provinsiName + '</h4>' +
                            '</div>';
                    
                    if (currentDataType === 'rs_provinsi') {
                        // For RS per provinsi - show total_anggota and total_rs
                        var totalAnggota = prov.total_anggota || prov.total || 0;
                        var totalRs = prov.total_rs || 0;
                        
                        popupContent += `
                            <div style="margin-bottom: 15px;">
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                    <div style="background: linear-gradient(135deg, #e3f2fd, #bbdefb); border-radius: 8px; padding: 12px; text-align: center; border-left: 4px solid #2196f3;">
                                        <div style="color: #1976d2; font-size: 12px; font-weight: 600; margin-bottom: 4px;">👥 TOTAL ANGGOTA</div>
                                        <div style="font-size: 20px; font-weight: bold, color: #0d47a1;">${prov.total_anggota}</div>
                                    </div>
                                    <div style="background: linear-gradient(135deg, #f3e5f5, #e1bee7); border-radius: 8px; padding: 12px; text-align: center; border-left: 4px solid #9c27b0;">
                                        <div style="color: #7b1fa2; font-size: 12px; font-weight: 600; margin-bottom: 4px;">🏥 TOTAL RS</div>
                                        <div style="font-size: 20px; font-weight: bold; color: #4a148c;">${prov.total_rs}</div>
                                    </div>
                                </div>
                            </div>
                            <div style="background: linear-gradient(135deg, #fce4ec, #f8bbd9); border-radius: 8px; padding: 12px; margin-bottom: 10px;">
                                <div style="text-align: center;">
                                    <div style="color: #ad1457; font-size: 12px; font-weight: 600; margin-bottom: 6px;">📊 RASIO ANGGOTA PER RS</div>
                                    <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                        <span style="background: #9c27b0; color: white; padding: 4px 10px; border-radius: 4px; font-size: 14px; font-weight: bold;">${prov.total_rs > 0 ? (prov.total_anggota / prov.total_rs).toFixed(1) : '0'}</span>
                                    </div>
                                </div>
                            </div>`;
                    } else {
                        // For spesialis per provinsi - show KODI details
                        popupContent += `
                        <div style="margin-bottom: 8px;">
                            <strong style="color: #34495e;">Detail per KODI:</strong>
                        </div>
                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #ecf0f1; border-radius: 6px; padding: 8px;">`;
                    
                        // Daftar lengkap KODI dengan mapping dari API key ke display name
                        var kodiMapping = {
                        'alergi_imunologi': 'Alergi Imunologi',
                        'bronko_esofagologi': 'Bronko Esofagologi', 
                        'fasial_plastik_rekonstruksi': 'Fasial Plastik & Rekonstruksi',
                        'laring_faring': 'Laring Faring',
                        'neurotologi': 'Neurotologi',
                        'onkologi_bedah_kepala_leher': 'Onkologi Bedah Kepala Leher',
                        'otologi': 'Otologi',
                        'rinologi': 'Rinologi',
                        'tht_komunitas': 'THT Komunitas'
                        };
                        
                        // Calculate totals for all KODI (including 0s)
                        var kodiTotals = {};
                        Object.keys(kodiMapping).forEach(function(apiKey) {
                        var displayName = kodiMapping[apiKey];
                        kodiTotals[displayName] = prov.kodi_details[apiKey] || 0;
                        });
                        
                        // Add any additional KODI that might not be in the standard mapping
                        Object.keys(prov.kodi_details).forEach(function(kodiKey) {
                        var displayName = kodiMapping[kodiKey] || kodiKey; // Use mapping or original key
                        if (!kodiTotals.hasOwnProperty(displayName)) {
                            kodiTotals[displayName] = prov.kodi_details[kodiKey];
                        }
                        });
                        
                        // Sort by total descending, then alphabetically
                        var kodiEntries = Object.entries(kodiTotals).sort((a, b) => {
                        if (b[1] !== a[1]) return b[1] - a[1]; // Sort by total first
                        return a[0].localeCompare(b[0]); // Then alphabetically
                        });
                        
                        if (kodiEntries.length > 0) {
                        kodiEntries.forEach(function([kodi, kodiTotal]) {
                            var percentage = prov.total > 0 ? ((kodiTotal / prov.total) * 100).toFixed(1) : '0.0';
                            var bgColor = kodiTotal > 0 ? 'linear-gradient(135deg, #f8f9fa, #e9ecef)' : 'linear-gradient(135deg, #fafafa, #f0f0f0)';
                            var borderColor = kodiTotal > 0 ? '#3498db' : '#bdc3c7';
                            var textColor = kodiTotal > 0 ? '#2c3e50' : '#7f8c8d';
                            var badgeColor = kodiTotal > 0 ? '#3498db' : '#95a5a6';
                            
                            popupContent += `
                            <div style="padding: 8px 10px; margin: 4px 0; background: ${bgColor}; border-left: 4px solid ${borderColor}; border-radius: 4px; transition: all 0.2s;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="flex: 1;">
                                    <strong style="color: ${textColor}; font-size: 13px;">${kodi}</strong>
                                    ${kodiTotal > 0 ? '' : '<div style="font-size: 10px; color: #95a5a6; font-style: italic;">Tidak ada data</div>'}
                                </div>
                                <div style="text-align: right;">
                                    <span style="background: ${badgeColor}; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px; font-weight: bold;">${kodiTotal}</span>
                                </div>
                                </div>
                            </div>
                            `;
                        });
                        } else {
                        popupContent += `
                            <div style="text-align: center; padding: 20px; color: #95a5a6; font-style: italic;">
                            Data KODI tidak tersedia
                            </div>
                        `;
                        }
                        
                        popupContent += `
                            </div>
                            <div style="padding-top: 12px; border-top: 2px solid #ecf0f1; font-size: 11px; color: #95a5a6; text-align: center; margin-top: 10px;">
                            <i>💡 Klik marker untuk melihat detail lengkap</i>
                            </div>
                        </div>
                        `;
                        
                    } // Close else block for spesialis data
                    
                    var marker = L.marker([coordinates.lat, coordinates.lng], {icon: customIcon})
                        .addTo(map)
                        .bindPopup(popupContent, {
                            maxWidth: 400,
                            className: 'custom-popup-provinsi'
                        });
                    
                    currentMarkers.push(marker);
                    markersCreated++;
                    
                    console.log('Created marker for:', provinsiName, 'at coordinates:', coordinates);
                } else {
                    console.log('Skipped provinsi:', provinsiName, 'coordinates found:', !!coordinates, 'total:', prov.total);
                }
            });
            
            console.log('Total markers created:', markersCreated);
            
            // Fit map to show all markers if any were created
            if (markersCreated > 0 && currentMarkers.length > 0) {
                var group = new L.featureGroup(currentMarkers);
                map.fitBounds(group.getBounds().pad(0.1));
            }
        }

        function getProvinsiColor(total) {
            if (total >= 50) return '#e74c3c';      // Red for high
            else if (total >= 20) return '#f39c12'; // Orange for medium-high
            else if (total >= 10) return '#3498db'; // Blue for medium
            else if (total >= 5) return '#2ecc71';  // Green for low-medium
            else return '#95a5a6';                  // Gray for very low
        }

        function displayAggregateData(data) {
            // Update table with aggregate data for provinsi
            var tbody = '';
            if (!data || data.length === 0) {
                tbody = '<tr><td colspan="8" class="no-data">Tidak ada data yang ditemukan</td></tr>';
            } else {
                // Group data by provinsi for table display
                var provinsiData = {};
                data.forEach(function(item) {
                    if (!provinsiData[item.provinsi]) {
                        provinsiData[item.provinsi] = {
                            provinsi: item.provinsi,
                            total: 0,
                            kodi_list: []
                        };
                    }
                    provinsiData[item.provinsi].total += parseInt(item.total || 0);
                    if (item.kodi && provinsiData[item.provinsi].kodi_list.indexOf(item.kodi) === -1) {
                        provinsiData[item.provinsi].kodi_list.push(item.kodi);
                    }
                });
                
                Object.keys(provinsiData).forEach(function(provinsiName) {
                    var prov = provinsiData[provinsiName];
                    tbody += '<tr>' +
                        '<td><strong>📍 ' + prov.provinsi + '</strong></td>' +
                        '<td>-</td>' +
                        '<td>-</td>' +
                        '<td><span style="padding: 2px 6px; border-radius: 3px; font-size: 11px; font-weight: 600; background: rgba(52, 152, 219, 0.1); color: #3498db;">' + prov.total + '</span></td>' +
                        '<td colspan="4">' + (prov.kodi_list.join(', ') || '-') + '</td>' +
                        '</tr>';
                });
            }
            $('#anggota_table').html(tbody);
        }

        function updateDetailTable(data) {
            var tbody = '';
            if (!data || data.length === 0) {
                tbody = '<tr><td colspan="9" class="no-data">Tidak ada data yang ditemukan</td></tr>';
            } else {
                data.forEach(function(item, index) {
                    tbody += '<tr>' +
                        '<td>' + (item.nama || '-') + '</td>' +
                        '<td>' + (item.kota || '-') + '</td>' +
                        '<td>' + (item.provinsi || '-') + '</td>' +
                        '<td>' + (item.status_user || '-') + '</td>' +
                        '<td>' + (item.kodi || '-') + '</td>' +
                        '<td>' + (item.praktek_1 || '-') + '</td>' +
                        '<td>' + (item.praktek_2 || '-') + '</td>' +
                        '<td>' + (item.praktek_3 || '-') + '</td>' +
                        '</tr>';
                });
            }
            $('#anggota_table').html(tbody);
        }

        function updateStats(data) {
            if (!data || data.length === 0) {
                $('#total_anggota').text('0');
                $('#total_konsultan').text('0');
                $('#total_fellowship').text('0');
                $('#total_non_konsultan').text('0');
                $('#total_provinsi').text('0');
                $('#total_kota').text('0');
                return;
            }

            var konsultan = data.filter(item => item.status_user === 'Konsultan').length;
            var fellowship = data.filter(item => item.status_user === 'Fellowship').length;
            var nonKonsultan = data.filter(item => item.status_user === 'Non Konsultan Non Fellowship').length;
            var provinsi = new Set(data.map(item => item.provinsi).filter(p => p && p !== "")).size;
            var kota = new Set(data.map(item => item.kota).filter(k => k && k !== "")).size;


            $('#total_anggota').text(data.length.toLocaleString());
            $('#total_konsultan').text(konsultan.toLocaleString());
            $('#total_fellowship').text(fellowship.toLocaleString());
            $('#total_non_konsultan').text(nonKonsultan.toLocaleString());
            $('#total_provinsi').text(provinsi.toLocaleString());
            $('#total_kota').text(kota.toLocaleString());
        }

        function resetFilters() {
            $('#filter_status_user').val('');
            $('#filter_provinsi').val('');
            $('#filter_kota').val('');
            $('#filter_kodi').val('');
            loadKotaByProvinsi('');
        }
    </script>
</body>
</html>