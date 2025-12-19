# 🖥️ SPK - Sistem Pendukung Keputusan Pemilihan Laptop Terbaik

Sistem Pendukung Keputusan (SPK) berbasis web menggunakan metode **SAW (Simple Additive Weighting)** untuk membantu pengguna memilih laptop terbaik berdasarkan kriteria yang telah ditentukan.

## 📋 Daftar Isi

- [Tentang Proyek](#tentang-proyek)
- [Fitur](#fitur)
- [Teknologi](#teknologi)
- [Instalasi](#instalasi)
- [Penggunaan](#penggunaan)
- [Struktur Proyek](#struktur-proyek)
- [Kriteria Penilaian](#kriteria-penilaian)

## Tentang Proyek

SPK Pemilihan Laptop Terbaik adalah aplikasi web yang dirancang untuk membantu mengambil keputusan dalam memilih laptop yang sesuai dengan kebutuhan. Sistem ini menggunakan metode SAW yang mengalikan bobot kriteria dengan nilai ternormalisasi dari setiap alternatif.

## Fitur

- ✅ **Dashboard Interaktif** - Tampilan data kriteria, alternatif, dan hasil penilaian
- ✅ **5 Kriteria Penilaian** - Processor, RAM, Storage, Battery, dan Harga
- ✅ **5 Laptop Alternatif** - MacBook Pro, Dell XPS, ASUS ROG, HP Pavilion, Lenovo ThinkPad
- ✅ **Perhitungan SAW Otomatis** - Normalisasi dan pembobotan nilai
- ✅ **Visualisasi Ranking** - Tampilan skor dan progress bar
- ✅ **UI Modern** - Responsive design dengan gradient dan animasi

## Teknologi

- **Backend**: CodeIgniter 4.3.5
- **Frontend**: Bootstrap 4, jQuery, Custom CSS
- **Database**: MySQL
- **PHP Version**: 8.2.29
- **Charts Library**: AmCharts 4 (opsional)

## Instalasi

### Prasyarat
- PHP 8.2+
- Composer
- MySQL 5.7+
- Git

### Langkah-langkah

1. **Clone Repository**
   ```bash
   git clone https://github.com/risman573/spk.git
   cd spk
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   ```bash
   cp env .env
   ```
   Edit file `.env` dan sesuaikan konfigurasi database:
   ```
   database.default.hostname = localhost
   database.default.database = spk
   database.default.username = root
   database.default.password =
   ```

4. **Jalankan Server**
   ```bash
   php -S 127.0.0.1:8080
   ```

5. **Akses Aplikasi**
   - Buka browser: `http://127.0.0.1:8080`

## Penggunaan

### Dashboard Utama
Halaman dashboard menampilkan:
1. **Kriteria Penilaian** - 5 kriteria dengan bobot (30%, 25%, 20%, 15%, 10%)
2. **Data Alternatif** - Tabel laptop dengan spesifikasi
3. **Hasil Ranking** - Top 3 dan tabel lengkap dengan skor SAW

### Navigasi
- **Dashboard** - Halaman utama menampilkan hasil SPK
- **Login** - Akses admin untuk pengelolaan data (opsional)

## Struktur Proyek

```
spk/
├── app/
│   ├── Controllers/       # Controller aplikasi
│   ├── Models/           # Model database
│   ├── Views/            # Template view (dashboard, header, footer)
│   ├── Config/           # Konfigurasi aplikasi
│   ├── Filters/          # Filter dan middleware
│   └── Helpers/          # Helper functions
├── public/
│   ├── assets/
│   │   ├── css/          # File CSS
│   │   ├── js/           # File JavaScript
│   │   ├── img/          # Gambar
│   │   ├── helper/       # Helper JavaScript
│   │   └── vendor/       # Library pihak ketiga
│   ├── index.php         # Entry point aplikasi
│   └── favicon.ico
├── system/               # Core CodeIgniter
├── vendor/               # Composer dependencies
├── writable/             # Folder writable (cache, logs, session)
├── composer.json         # Composer configuration
├── .env                  # Environment configuration
└── README.md             # File ini
```

## Kriteria Penilaian

| Kriteria | Bobot | Tipe | Keterangan |
|----------|-------|------|-----------|
| Processor | 30% | Benefit | Semakin tinggi semakin baik |
| RAM | 25% | Benefit | Semakin banyak semakin baik |
| Storage | 20% | Benefit | Semakin besar semakin baik |
| Battery | 15% | Benefit | Semakin tahan lama semakin baik |
| Harga | 10% | Cost | Semakin murah semakin baik |

## Laptop Alternatif

1. **MacBook Pro 14"** - M3 Pro, 18GB RAM, 512GB Storage, 16h Battery, Rp25 Juta
2. **Dell XPS 13** - Intel i7-1360P, 16GB RAM, 512GB Storage, 13h Battery, Rp18 Juta
3. **ASUS ROG Gaming** - Intel i9-13900H, 32GB RAM, 1024GB Storage, 8h Battery, Rp30 Juta
4. **HP Pavilion 15** - AMD Ryzen 5, 8GB RAM, 256GB Storage, 10h Battery, Rp10 Juta
5. **Lenovo ThinkPad** - Intel i5-1340P, 16GB RAM, 512GB Storage, 14h Battery, Rp15 Juta

## Lisensi

Proyek ini dilisensikan di bawah [License](LICENSE) file.

## Kontributor

- **Risman** - Developer

## Support

Untuk pertanyaan atau masalah, silakan buka issue di repository ini.

---

**Dibuat dengan ❤️ menggunakan CodeIgniter 4**
