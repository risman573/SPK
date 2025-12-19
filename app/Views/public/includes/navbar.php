<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3); z-index: 9999;">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="<?= base_url('/') ?>" style="font-size: 1.5rem;">
            <img src="<?=$base_url?><?=$default['logo']?>" alt="" class="logo-image" style="width: 30px;"> &nbsp; 
            <span style="color: #fff;"><?=$default['title']?></span>
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" style="box-shadow: none;">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item mx-1">
                    <a class="nav-link text-white fw-semibold <?= (current_url() == base_url('')) ? 'active' : '' ?>" href="<?= base_url('') ?>" style="padding: 10px 20px; border-radius: 25px; transition: all 0.3s ease;">
                        <i class="bi bi-people me-1"></i> Cari Dokter
                    </a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link text-white fw-semibold <?= (strpos(current_url(), 'sebaran-anggota') !== false) ? 'active' : '' ?>" href="<?= base_url('/public/sebaran-anggota') ?>" style="padding: 10px 20px; border-radius: 25px; transition: all 0.3s ease;">
                        <i class="bi bi-people me-1"></i> Sebaran Anggota
                    </a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link text-white fw-semibold <?= (strpos(current_url(), 'sebaran-rs') !== false) ? 'active' : '' ?>" href="<?= base_url('/public/sebaran-rs') ?>" style="padding: 10px 20px; border-radius: 25px; transition: all 0.3s ease;">
                        <i class="bi bi-hospital me-1"></i> Sebaran RS
                    </a>
                </li>
                <!-- <li class="nav-item mx-1">
                    <a class="nav-link text-white fw-semibold <?= (strpos(current_url(), 'cabang') !== false) ? 'active' : '' ?>" href="<?= base_url('/public/cabang') ?>" style="padding: 10px 20px; border-radius: 25px; transition: all 0.3s ease;">
                        <i class="bi bi-building me-1"></i> Cabang
                    </a>
                </li> -->
                <li class="nav-item mx-1">
                    <a class="nav-link text-white fw-semibold" href="<?= base_url('/login') ?>" style="padding: 10px 20px; border-radius: 25px; transition: all 0.3s ease; background: rgba(255,255,255,0.1);">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
.navbar {
    z-index: 9999 !important;
    min-height: 70px;
}

.navbar-brand {
    font-size: 1.5rem !important;
    font-weight: 700 !important;
}

.navbar-nav .nav-link {
    color: rgba(255, 255, 255, 0.95) !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    margin: 0 3px;
    padding: 10px 20px !important;
    border-radius: 25px !important;
    transition: all 0.3s ease !important;
    text-decoration: none !important;
    position: relative;
    overflow: hidden;
}

.navbar-nav .nav-link:hover {
    background: rgba(255, 255, 255, 0.15) !important;
    color: white !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.2);
}

.navbar-nav .nav-link.active {
    background: rgba(255, 255, 255, 0.2) !important;
    color: white !important;
    font-weight: 700 !important;
    box-shadow: 0 2px 8px rgba(255, 255, 255, 0.3);
}

.navbar-nav .nav-link i {
    margin-right: 6px;
    font-size: 16px;
}

body {
    padding-top: 85px !important; /* Account for fixed navbar */
}

.navbar-toggler {
    border: 2px solid rgba(255, 255, 255, 0.3) !important;
    padding: 6px 10px !important;
}

.navbar-toggler:focus {
    box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25) !important;
}

@media (max-width: 991px) {
    .navbar-nav {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 15px;
        padding: 15px;
        margin-top: 10px;
    }
    
    .navbar-nav .nav-link {
        margin: 3px 0 !important;
        text-align: center;
        display: block;
    }
    
    .navbar-collapse {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        margin-top: 10px;
        padding-top: 10px;
    }
}

@media (max-width: 576px) {
    .navbar-brand {
        font-size: 1.3rem !important;
    }
    
    .navbar-nav .nav-link {
        font-size: 13px !important;
        padding: 8px 16px !important;
    }
}
</style>