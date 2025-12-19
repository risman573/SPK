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
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0D6EFD;
            --secondary-color: #6C757D;
            --success-color: #198754;
            --info-color: #0DCAF0;
            --warning-color: #FFC107;
            --danger-color: #DC3545;
            --light-color: #F8F9FA;
            --dark-color: #212529;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
            --shadow-lg: 0 8px 32px rgba(0,0,0,0.15);
            --border-radius: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%);
            line-height: 1.6;
            color: var(--dark-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Hero Section */
        .hero-section {
            background: var(--gradient-primary);
            color: white;
            padding: 80px 0 120px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.05)" points="0,1000 1000,0 1000,1000"/></svg>') no-repeat;
            background-size: cover;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
        }

        .hero-subtitle {
            font-size: clamp(1.1rem, 2vw, 1.3rem);
            font-weight: 400;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Search Card */
        .search-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            padding: 2.5rem;
            margin-top: 0;
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .search-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-floating > label {
            font-weight: 500;
            color: var(--secondary-color);
        }

        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #fafbfc;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
            background-color: white;
        }

        .btn-search {
            background: var(--gradient-primary);
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: none;
        }

        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        .btn-reset {
            background: var(--secondary-color);
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-reset:hover {
            background: #5a6268;
            color: white;
            transform: translateY(-1px);
        }

        /* Results */
        .results-container {
            margin-top: 3rem;
        }

        .results-header {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
            border-left: 4px solid var(--primary-color);
        }

        .doctor-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .doctor-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--gradient-primary);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .doctor-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .doctor-card:hover::before {
            transform: scaleY(1);
        }

        .doctor-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.75rem;
        }

        .doctor-status {
            display: inline-flex;
            align-items: center;
            background: var(--gradient-primary);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .doctor-location {
            color: var(--secondary-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .practice-info {
            background: var(--light-color);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1rem;
            border-left: 3px solid var(--info-color);
        }

        .practice-title {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .practice-item {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .practice-item:last-child {
            margin-bottom: 0;
        }

        .practice-name {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
        }

        .practice-location {
            color: var(--secondary-color);
            font-size: 0.9rem;
        }

        /* Pagination */
        .pagination {
            justify-content: center;
            margin-top: 2rem;
        }

        .page-link {
            border-radius: 8px;
            margin: 0 2px;
            border: 1px solid #dee2e6;
            color: var(--primary-color);
            padding: 0.75rem 1rem;
            font-weight: 500;
        }

        .page-link:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Loading */
        .loading {
            text-align: center;
            padding: 3rem;
        }

        .loading-spinner {
            width: 3rem;
            height: 3rem;
            border: 4px solid rgba(13, 110, 253, 0.1);
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* No Results */
        .no-results {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--secondary-color);
        }

        .no-results-icon {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }

        /* Info Section */
        .info-section {
            background: white;
            padding: 5rem 0;
            margin-top: 4rem;
        }

        .info-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 2.5rem;
            text-align: center;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
        }

        .info-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-md);
        }

        .info-icon {
            font-size: 3rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
        }

        .info-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1rem;
        }

        .info-desc {
            color: var(--secondary-color);
            line-height: 1.7;
        }

        /* Footer */
        .footer-section {
            background: var(--dark-color);
            color: white;
            padding: 2rem 0;
            text-align: center;
            margin-top: auto;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0 100px;
            }
            
            .search-card {
                margin: -60px 1rem 0;
                padding: 2rem 1.5rem;
            }
            
            .doctor-card {
                padding: 1.5rem;
            }
            
            .info-section {
                padding: 3rem 0;
            }
        }

        /* Animation Classes */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.6s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }
    </style>
    <title><?=$title?> - <?=$default['titleTab']?></title>
</head>

<body>
    <?php include APPPATH . 'Views/public/includes/navbar.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero-section" style="padding: 120px 0 120px; min-height: 400px; margin-top: -70px;">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="hero-content" data-aos="fade-up">
                        <h1 class="hero-title">Direktori Dokter THT</h1>
                        <p class="hero-subtitle">Temukan dokter spesialis THT (Telinga, Hidung, Tenggorokan) terpercaya di seluruh Indonesia</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Card -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="search-card" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="search-title">
                        <i class="bi bi-search me-2 text-primary"></i>
                        Cari Dokter THT
                    </h2>
                    
                    <form id="searchForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="search" name="search" placeholder="Masukkan nama dokter...">
                                    <label for="search"><i class="bi bi-person me-2"></i>Nama Dokter</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="status_user" name="status_user">
                                        <option value="">Semua Jenis</option>
                                        <option value="1">Konsultan & Fellowship</option>
                                        <option value="2">Spesialis</option>
                                    </select>
                                    <label for="status_user"><i class="bi bi-award me-2"></i>Status Dokter</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="provinsi" name="provinsi">
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                    <label for="provinsi"><i class="bi bi-geo-alt me-2"></i>Provinsi</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="kota" name="kota">
                                        <option value="">Pilih Kota</option>
                                    </select>
                                    <label for="kota"><i class="bi bi-building me-2"></i>Kota</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-search w-100">
                                    <i class="bi bi-search me-2"></i>
                                    Cari Dokter
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-reset w-100" id="resetForm">
                                    <i class="bi bi-arrow-clockwise me-2"></i>
                                    Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Loading -->
        <div class="loading d-none" id="loading">
            <div class="loading-spinner"></div>
            <h5>Mencari dokter...</h5>
            <p class="text-muted">Mohon tunggu sebentar</p>
        </div>
        
        <!-- Results Container -->
        <div class="results-container d-none" id="resultsContainer">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="results-header" data-aos="fade-up">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1">
                                    <i class="bi bi-list-check me-2 text-primary"></i>
                                    Hasil Pencarian
                                </h4>
                                <p class="text-muted mb-0" id="resultsCount">Ditemukan 0 dokter</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm" id="sortName">
                                    <i class="bi bi-sort-alpha-down me-1"></i>
                                    Nama
                                </button>
                                <button class="btn btn-outline-primary btn-sm" id="sortLocation">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    Lokasi
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="doctorResults"></div>
                    
                    <!-- Pagination -->
                    <nav aria-label="Pagination Navigation" class="mt-4">
                        <ul class="pagination" id="pagination"></ul>
                    </nav>
                </div>
            </div>
        </div>
        
        <!-- No Results -->
        <div class="no-results d-none" id="noResults" data-aos="fade-up">
            <div class="no-results-icon">
                <i class="bi bi-search"></i>
            </div>
            <h4>Tidak ada dokter yang ditemukan</h4>
            <p>Coba ubah kriteria pencarian Anda atau periksa ejaan kata kunci</p>
            <button class="btn btn-primary mt-3" onclick="$('#resetForm').click()">
                <i class="bi bi-arrow-clockwise me-2"></i>
                Coba Pencarian Lain
            </button>
        </div>
    </div>

    <!-- Info Section -->
    <!-- <section class="info-section">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-5 fw-bold text-dark mb-3" data-aos="fade-up">Kenapa Memilih Kami?</h2>
                    <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">Platform terpercaya untuk menemukan dokter spesialis THT terbaik di Indonesia</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="info-card h-100">
                        <i class="bi bi-shield-check info-icon"></i>
                        <h3 class="info-title">Dokter Berpengalaman</h3>
                        <p class="info-desc">Temukan dokter spesialis THT yang berpengalaman dan terpercaya untuk menangani masalah kesehatan Anda dengan profesional.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="info-card h-100">
                        <i class="bi bi-geo-alt-fill info-icon"></i>
                        <h3 class="info-title">Lokasi Strategis</h3>
                        <p class="info-desc">Cari dokter berdasarkan lokasi untuk menemukan yang paling dekat dengan tempat tinggal Anda dengan mudah dan cepat.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="info-card h-100">
                        <i class="bi bi-calendar-check info-icon"></i>
                        <h3 class="info-title">Informasi Lengkap</h3>
                        <p class="info-desc">Dapatkan informasi lengkap tentang tempat praktik, spesialisasi, dan kredensial dokter yang Anda butuhkan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Footer -->
    <footer class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p class="mb-0">&copy; <?= date('Y') ?> <?=$default['title']?>. Semua hak cipta dilindungi undang-undang.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
    // Global variables for pagination
    let currentPage = 1;
    let itemsPerPage = 6;
    let allDoctors = [];
    let filteredDoctors = [];
    
    $(document).ready(function() {
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
        
        console.log('Page loaded, initializing...');
        
        // Load provinces on page load
        loadProvinces();
        
        // Handle province change
        $('#provinsi').change(function() {
            var provinsi = $(this).val();
            console.log('Province selected:', provinsi);
            if (provinsi) {
                loadCities(provinsi);
            } else {
                $('#kota').html('<option value="">Pilih Kota</option>');
            }
        });
        
        // Handle form submission
        $('#searchForm').submit(function(e) {
            e.preventDefault();
            console.log('Form submitted');
            searchDoctors();
        });
        
        // Handle reset
        $('#resetForm').click(function() {
            console.log('Reset clicked');
            $('#searchForm')[0].reset();
            $('#kota').html('<option value="">Pilih Kota</option>');
            hideResults();
            currentPage = 1;
        });
        
        // Handle sorting
        $('#sortName').click(function() {
            sortResults('name');
        });
        
        $('#sortLocation').click(function() {
            sortResults('location');
        });
    });
    
    function loadProvinces() {
        console.log('Loading provinces...');
        $.ajax({
            url: '<?=$base_url?>api/provinsi',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log('Provinces loaded:', data);
                var options = '<option value="">Pilih Provinsi</option>';
                if (data && data.length > 0) {
                    $.each(data, function(index, item) {
                        options += '<option value="' + item.provinsi + '">' + item.provinsi + '</option>';
                    });
                }
                $('#provinsi').html(options);
            },
            error: function(xhr, status, error) {
                console.error('Error loading provinces:', error);
                showToast('Gagal memuat data provinsi', 'error');
            }
        });
    }
    
    function loadCities(provinsi) {
        console.log('Loading cities for province:', provinsi);
        $.ajax({
            url: '<?=$base_url?>api/kota',
            type: 'GET',
            data: {provinsi: provinsi},
            dataType: 'json',
            success: function(data) {
                console.log('Cities loaded:', data);
                var options = '<option value="">Pilih Kota</option>';
                if (data && data.length > 0) {
                    $.each(data, function(index, item) {
                        options += '<option value="' + item.kota + '">' + item.kota + '</option>';
                    });
                }
                $('#kota').html(options);
            },
            error: function(xhr, status, error) {
                console.error('Error loading cities:', error);
                showToast('Gagal memuat data kota', 'error');
            }
        });
    }
    
    function searchDoctors() {
        console.log('Searching doctors...');
        $('#loading').removeClass('d-none');
        hideResults();
        
        var formData = $('#searchForm').serialize();
        console.log('Form data:', formData);
        
        $.ajax({
            url: '<?=$base_url?>search',
            type: 'GET',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log('Search response:', response);
                $('#loading').addClass('d-none');
                
                // Handle both old format (direct array) and new format (with pagination)
                if (response.data) {
                    // New format with pagination
                    if (response.data.length > 0) {
                        allDoctors = response.data;
                        filteredDoctors = response.data;
                        currentPage = 1;
                        displayResults();
                    } else {
                        $('#noResults').removeClass('d-none');
                    }
                } else if (Array.isArray(response) && response.length > 0) {
                    // Old format (direct array)
                    allDoctors = response;
                    filteredDoctors = response;
                    currentPage = 1;
                    displayResults();
                } else {
                    $('#noResults').removeClass('d-none');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error searching doctors:', error);
                $('#loading').addClass('d-none');
                showToast('Terjadi kesalahan saat mencari data', 'error');
            }
        });
    }
    
    function displayResults() {
        console.log('Displaying results with pagination');
        
        // Calculate pagination
        const totalItems = filteredDoctors.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const currentItems = filteredDoctors.slice(startIndex, endIndex);
        
        // Update results count
        $('#resultsCount').text(`Ditemukan ${totalItems} dokter`);
        
        // Generate doctor cards
        let html = '';
        currentItems.forEach((doctor, index) => {
            html += generateDoctorCard(doctor, startIndex + index);
        });
        
        $('#doctorResults').html(html);
        generatePagination(totalPages);
        $('#resultsContainer').removeClass('d-none');
        
        // Animate cards
        $('.doctor-card').each(function(index) {
            $(this).css('animation-delay', (index * 0.1) + 's').addClass('fade-in-up');
        });
        
        // Smooth scroll to results
        $('html, body').animate({
            scrollTop: $('#resultsContainer').offset().top - 100
        }, 500);
    }
    
    function generateDoctorCard(doctor, index) {
        let html = `
            <div class="doctor-card" data-aos="fade-up" data-aos-delay="${(index % itemsPerPage) * 100}">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="doctor-name">${doctor.nama || 'Nama tidak tersedia'}</div>
                        ${doctor.status_user != '' ? `<div class="doctor-status"><i class="bi bi-award me-1"></i>${doctor.status_user}</div>` : ''}
                        ${doctor.kodi != '' ? `<div class="doctor-status"><i class="bi bi-award me-1"></i>${doctor.kodi}</div>` : ''}
                    </div>
                </div>`;
        
        if (doctor.kota || doctor.provinsi) {
            html += `
                <div class="doctor-location">
                    <i class="bi bi-geo-alt"></i>
                    ${doctor.kota || ''}${doctor.kota && doctor.provinsi ? ', ' : ''}${doctor.provinsi || ''}
                </div>`;
        }
        
        // Practice locations
        const practices = [];
        if (doctor.praktek_1) practices.push({nama: doctor.praktek_1, kota: doctor.kota_1, provinsi: doctor.provinsi_1});
        if (doctor.praktek_2) practices.push({nama: doctor.praktek_2, kota: doctor.kota_2, provinsi: doctor.provinsi_2});
        if (doctor.praktek_3) practices.push({nama: doctor.praktek_3, kota: doctor.kota_3, provinsi: doctor.provinsi_3});
        
        if (practices.length > 0) {
            html += `
                <div class="practice-info">
                    <div class="practice-title">
                        <i class="bi bi-building"></i>
                        Tempat Praktik
                    </div>`;
            
            practices.forEach(practice => {
                html += `
                    <div class="practice-item">
                        <div class="practice-name">${practice.nama || 'Tidak disebutkan'}</div>`;
                if (practice.kota || practice.provinsi) {
                    html += `
                        <div class="practice-location">
                            <i class="bi bi-geo-alt me-1"></i>
                            ${practice.kota || ''}${practice.kota && practice.provinsi ? ', ' : ''}${practice.provinsi || ''}
                        </div>`;
                }
                html += `</div>`;
            });
            
            html += `</div>`;
        }
        
        html += `</div>`;
        return html;
    }
    
    function generatePagination(totalPages) {
        if (totalPages <= 1) {
            $('#pagination').empty();
            return;
        }
        
        let paginationHtml = '';
        
        // Previous button
        paginationHtml += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${currentPage - 1})" aria-label="Previous">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>`;
        
        // Page numbers
        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
        
        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }
        
        // First page
        if (startPage > 1) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(1)">1</a></li>`;
            if (startPage > 2) {
                paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }
        
        // Visible pages
        for (let i = startPage; i <= endPage; i++) {
            paginationHtml += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                </li>`;
        }
        
        // Last page
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${totalPages})">${totalPages}</a></li>`;
        }
        
        // Next button
        paginationHtml += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${currentPage + 1})" aria-label="Next">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>`;
        
        $('#pagination').html(paginationHtml);
    }
    
    function changePage(page) {
        const totalPages = Math.ceil(filteredDoctors.length / itemsPerPage);
        if (page < 1 || page > totalPages) return;
        
        currentPage = page;
        displayResults();
        
        // Scroll to top of results
        $('html, body').animate({
            scrollTop: $('#resultsContainer').offset().top - 100
        }, 300);
    }
    
    function sortResults(type) {
        if (type === 'name') {
            filteredDoctors.sort((a, b) => (a.nama || '').localeCompare(b.nama || ''));
            $('#sortName').addClass('active');
            $('#sortLocation').removeClass('active');
        } else if (type === 'location') {
            filteredDoctors.sort((a, b) => {
                const locationA = (a.provinsi || '') + (a.kota || '');
                const locationB = (b.provinsi || '') + (b.kota || '');
                return locationA.localeCompare(locationB);
            });
            $('#sortLocation').addClass('active');
            $('#sortName').removeClass('active');
        }
        
        currentPage = 1;
        displayResults();
    }
    
    function hideResults() {
        $('#resultsContainer').addClass('d-none');
        $('#noResults').addClass('d-none');
    }
    
    function showToast(message, type = 'info') {
        // Simple toast implementation
        const toast = $(`
            <div class="toast align-items-center text-white bg-${type === 'error' ? 'danger' : 'primary'} border-0" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `);
        
        $('body').append(toast);
        const toastElement = new bootstrap.Toast(toast[0]);
        toastElement.show();
        
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }
    </script>
</body>

</html>