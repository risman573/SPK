<!doctype html>
<html lang="id">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="<?=$base_url?><?=$default['logo']?>">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?=$base_url?>assets/vendor/bootstrap-4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=$base_url?>assets/vendor/materializeicon/material-icons.css">
    
    <!-- Custom CSS -->
    <style>
        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
        }
        
        .header-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .back-link {
            color: white;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 20px;
            display: inline-block;
        }
        
        .back-link:hover {
            color: #f8f9fa;
            text-decoration: none;
        }
        
        .doctor-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .doctor-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .doctor-name {
            font-size: 1.4rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        
        .doctor-status {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .doctor-location {
            color: #666;
            margin-bottom: 8px;
        }
        
        .practice-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
        }
        
        .practice-title {
            font-weight: 600;
            color: #555;
            margin-bottom: 10px;
        }
        
        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .login-link {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .login-link:hover {
            background: rgba(255,255,255,0.3);
            color: white;
            text-decoration: none;
        }
    </style>
    
    <title><?=$title?> - <?=$default['titleTab']?></title>
</head>

<body>
    <!-- Header Section -->
    <section class="header-section">
        <a href="<?=$login_url?>" class="login-link">
            <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">login</i>
            Login Admin
        </a>
        
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <a href="<?=$base_url?>" class="back-link">
                        <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">arrow_back</i>
                        Kembali ke Pencarian
                    </a>
                    <h1 class="header-title"><?=$title?></h1>
                    <p>Ditemukan <?= count($doctors) ?> dokter</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Results Section -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php if (empty($doctors)): ?>
                    <div class="no-results">
                        <i class="material-icons" style="font-size: 4rem; color: #ccc; margin-bottom: 20px;">search_off</i>
                        <h4>Tidak ada dokter yang ditemukan</h4>
                        <p>Coba ubah kriteria pencarian Anda</p>
                        <a href="<?=$base_url?>" class="btn btn-primary mt-3">Coba Lagi</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($doctors as $doctor): ?>
                        <div class="doctor-card">
                            <div class="doctor-name"><?= htmlspecialchars($doctor->nama) ?></div>
                            <div class="doctor-status"><?= htmlspecialchars($doctor->status_user ?: 'Dokter') ?></div>
                            
                            <?php if (!empty($doctor->kota) || !empty($doctor->provinsi)): ?>
                                <div class="doctor-location">
                                    <i class="material-icons" style="vertical-align: middle; margin-right: 5px; font-size: 18px;">location_on</i>
                                    <?= htmlspecialchars($doctor->kota) ?>
                                    <?php if (!empty($doctor->kota) && !empty($doctor->provinsi)): ?>, <?php endif; ?>
                                    <?= htmlspecialchars($doctor->provinsi) ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php 
                            $practices = array();
                            if (!empty($doctor->praktek_1)) $practices[] = array('nama' => $doctor->praktek_1, 'kota' => $doctor->kota_1, 'provinsi' => $doctor->provinsi_1);
                            if (!empty($doctor->praktek_2)) $practices[] = array('nama' => $doctor->praktek_2, 'kota' => $doctor->kota_2, 'provinsi' => $doctor->provinsi_2);
                            if (!empty($doctor->praktek_3)) $practices[] = array('nama' => $doctor->praktek_3, 'kota' => $doctor->kota_3, 'provinsi' => $doctor->provinsi_3);
                            ?>
                            
                            <?php if (!empty($practices)): ?>
                                <div class="practice-info">
                                    <div class="practice-title">Tempat Praktik:</div>
                                    <?php foreach ($practices as $practice): ?>
                                        <div class="mb-2">
                                            <strong><?= htmlspecialchars($practice['nama']) ?></strong>
                                            <?php if (!empty($practice['kota']) || !empty($practice['provinsi'])): ?>
                                                <br><small class="text-muted">
                                                    <?= htmlspecialchars($practice['kota']) ?>
                                                    <?php if (!empty($practice['kota']) && !empty($practice['provinsi'])): ?>, <?php endif; ?>
                                                    <?= htmlspecialchars($practice['provinsi']) ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer style="background: #333; color: white; padding: 40px 0; margin-top: 50px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p>&copy; <?= date('Y') ?> <?=$default['title']?>. Semua hak cipta dilindungi.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="<?=$base_url?>assets/js/jquery-3.2.1.min.js"></script>
    <script src="<?=$base_url?>assets/vendor/bootstrap-4.1.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>