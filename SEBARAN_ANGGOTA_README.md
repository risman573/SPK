# 🗺️ Peta Interaktif Sebaran Anggota THT BKL

Sistem pemetaan interaktif untuk dokter spesialis THT-BKL di Indonesia dengan berbagai jenis visualisasi dan filter yang komprehensif.

## 📋 Fitur Utama

### 1. Sebaran Dokter Spesialis THT-BKL di Indonesia (Alamat Rumah)
- **Per Provinsi**: Menampilkan distribusi dokter spesialis di setiap provinsi
- **Per Kabupaten/Kota**: Detail distribusi hingga level kabupaten/kota
- **Cabang**: Menampilkan keberadaan cabang THT (tidak semua provinsi memiliki cabang)

### 2. Sebaran Dokter Spesialis THT-BKL di RS Seluruh Indonesia (Tempat Praktek)
- **Per Provinsi**: Distribusi berdasarkan lokasi rumah sakit tempat praktek
- **Per Kabupaten/Kota**: Detail hingga level kabupaten/kota tempat praktek

### 3. Sebaran Konsultan/Fellow THT-BKL di Indonesia (Alamat Rumah)
- **Per Provinsi**: Distribusi konsultan dan fellow berdasarkan alamat rumah
- **Per Kabupaten/Kota**: Detail distribusi konsultan dan fellow
- **Cabang**: Keberadaan cabang untuk konsultan/fellow

### 4. Sebaran Konsultan/Fellow THT-BKL di RS Indonesia (Alamat Tempat Praktek)
- **Per Provinsi**: Distribusi konsultan/fellow di rumah sakit
- **Per Kabupaten/Kota**: Detail distribusi tempat praktek

### 5. Visualisasi Peta
- **Titik Warna**: Jumlah RS/Jumlah Anggota dengan kode warna berbeda
- **Filter Interaktif**: Multiple filter untuk data yang lebih spesifik
- **Popup Detail**: Informasi lengkap saat klik marker di peta

### 6. Tabel Data Sebaran
Menampilkan detail data dokter dengan kolom:
- Nama Dokter
- Alamat Rumah
- Kota
- Provinsi  
- Status (Konsultan/Fellowship/Non Konsultan Non Fellowship)
- KODI (Subspecialty)
- Alamat Praktek 1
- Alamat Praktek 2  
- Alamat Praktek 3

## 🔍 Filter yang Tersedia

### Status User
- Non Konsultan Non Fellowship
- Konsultan
- Fellowship

### KODI (Subspecialty)
- Alergi Imunologi
- Bronko Esofagologi
- Fasial Plastik & Rekonstruksi
- Laring Faring
- Neurotologi
- Onkologi Bedah Kepala Leher
- Otologi
- Rinologi
- THT Komunitas

### Lokasi
- Filter berdasarkan Provinsi
- Filter berdasarkan Kabupaten/Kota
- Filter kombinasi

## 🛠️ Implementasi Teknis

### Backend (CodeIgniter 4)

#### Model: `Sebaran_anggota_model.php`
- `getSebaranSpesialisPerProvinsi()` - Data sebaran spesialis per provinsi (alamat rumah)
- `getSebaranSpesialisPerKabupaten()` - Data sebaran spesialis per kabupaten (alamat rumah)
- `getSebaranRSPerProvinsi()` - Data sebaran RS per provinsi (tempat praktek)
- `getSebaranRSPerKabupaten()` - Data sebaran RS per kabupaten (tempat praktek)
- `getTitikAlamatRumah()` - Koordinat alamat rumah untuk peta
- `getTitikAlamatPraktek()` - Koordinat alamat praktek untuk peta
- `getDetailAnggota()` - Data detail untuk tabel
- `getProvinsiList()` - List provinsi untuk filter
- `getKotaByProvinsi()` - List kota berdasarkan provinsi
- `getStatusUserList()` - List status user untuk filter
- `getKodiList()` - List KODI untuk filter

#### Controller: `Sebaran_anggota.php`
- `index()` - Halaman utama dengan data filter
- `getSebaranSpesialisProvinsi()` - API endpoint sebaran spesialis provinsi
- `getSebaranSpesialisKabupaten()` - API endpoint sebaran spesialis kabupaten
- `getSebaranRSProvinsi()` - API endpoint sebaran RS provinsi
- `getSebaranRSKabupaten()` - API endpoint sebaran RS kabupaten
- `getTitikAlamatRumah()` - API endpoint titik alamat rumah
- `getTitikAlamatPraktek()` - API endpoint titik alamat praktek
- `getDetailAnggota()` - API endpoint detail anggota
- `getKotaByProvinsi()` - API endpoint kota berdasarkan provinsi

### Frontend

#### Teknologi
- **Leaflet.js** - Library peta interaktif
- **jQuery** - Manipulasi DOM dan AJAX
- **Bootstrap** - Styling responsif
- **OpenStreetMap** - Tiles peta

#### Fitur JavaScript
- Toggle antara jenis visualisasi (alamat rumah/praktek/agregat)
- Filter dinamis dengan dropdown yang saling terkait
- Marker popup dengan informasi detail
- Tabel data yang dapat di-scroll
- Update statistik real-time
- Reset filter functionality

## 📊 Database

### Tabel: `data_full`
Kolom utama yang digunakan:
- `nama` - Nama dokter
- `alamat`, `kota`, `provinsi` - Alamat rumah
- `lat`, `lon` - Koordinat alamat rumah
- `praktek_1`, `kota_1`, `provinsi_1`, `lat_1`, `lon_1` - Alamat praktek 1
- `praktek_2`, `kota_2`, `provinsi_2`, `lat_2`, `lon_2` - Alamat praktek 2  
- `praktek_3`, `kota_3`, `provinsi_3`, `lat_3`, `lon_3` - Alamat praktek 3
- `status_user` - Status (Konsultan/Fellowship/Non Konsultan Non Fellowship)
- `kodi` - Subspecialty
- `cabang` - Cabang THT
- `status` - Status record (< 9 untuk data aktif)

## 🚀 Penggunaan

1. **Akses Halaman**: Buka menu "Sebaran Anggota"
2. **Pilih Jenis Visualisasi**: Gunakan tombol toggle untuk memilih:
   - Alamat Rumah
   - Tempat Praktek  
   - Spesialis per Provinsi
   - RS per Provinsi
3. **Apply Filter**: Gunakan filter di panel kanan untuk mempersempit data
4. **Lihat Detail**: Klik marker di peta untuk melihat popup detail
5. **Cek Tabel**: Scroll tabel di bawah peta untuk melihat data lengkap
6. **Reset**: Gunakan tombol reset untuk mengembalikan filter ke default

## 🔧 Konfigurasi

### URL Endpoints
```
/main/sebaran_anggota/ - Halaman utama
/main/sebaran_anggota/getSebaranSpesialisProvinsi - API sebaran spesialis provinsi
/main/sebaran_anggota/getSebaranSpesialisKabupaten - API sebaran spesialis kabupaten  
/main/sebaran_anggota/getSebaranRSProvinsi - API sebaran RS provinsi
/main/sebaran_anggota/getSebaranRSKabupaten - API sebaran RS kabupaten
/main/sebaran_anggota/getTitikAlamatRumah - API titik alamat rumah
/main/sebaran_anggota/getTitikAlamatPraktek - API titik alamat praktek
/main/sebaran_anggota/getDetailAnggota - API detail anggota
/main/sebaran_anggota/getKotaByProvinsi - API kota berdasarkan provinsi
```

### CSS Classes
- `.sebaran-wrapper` - Container utama
- `.sebaran-map-box` - Container peta dan tabel
- `.sebaran-filter-box` - Container filter
- `.map-toggle-btn` - Tombol toggle jenis peta
- `.filter-btn` - Tombol filter dan reset
- `.stats-summary` - Container statistik

## 📝 Catatan Pengembangan

- Pastikan data koordinat (lat, lon) tersedia untuk visualisasi peta yang optimal
- Filter dapat dikombinasikan untuk hasil yang lebih spesifik
- Tabel data dapat menampilkan ribuan record dengan scroll
- Responsive design untuk mobile dan desktop
- Error handling untuk data kosong atau koneksi gagal

## 🔄 Update dan Maintenance

- Update data alamat dan koordinat secara berkala
- Monitoring performa query untuk dataset besar
- Backup data sebelum melakukan perubahan struktur
- Test filter combination untuk memastikan akurasi hasil
