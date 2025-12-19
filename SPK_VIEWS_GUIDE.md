# SPK Views Documentation

Dokumentasi lengkap untuk semua views yang telah dibuat untuk sistem SPK Pemilihan Laptop.

## 📋 Views yang Telah Dibuat

### 1. **Kriteria View** - `app/Views/spk/kriteria_view.php`
**Fungsi:** Mengelola kriteria pemilihan laptop dengan bobot dan tipe atribut

**Features:**
- ✅ DataTables dengan server-side processing
- ✅ Form modal untuk tambah/edit kriteria
- ✅ Validasi form (nama, bobot, atribut)
- ✅ Pilihan atribut: Benefit / Cost
- ✅ Dropdown Status (from combo table)
- ✅ CRUD operations: Add, Edit, Delete
- ✅ Audit trail columns: Created User, Created Date, Modified User, Modified Date

**Form Fields:**
- Nama Kriteria (text)
- Bobot (%) - number 0-100
- Tipe Atribut - select (benefit/cost)
- Status - select (from combo)

**URL:** `/spk/kriteria`

---

### 2. **Alternatif View** - `app/Views/spk/alternatif_view.php`
**Fungsi:** Mengelola laptop alternatives yang akan dievaluasi

**Features:**
- ✅ DataTables dengan server-side processing
- ✅ Form modal untuk tambah/edit alternatif
- ✅ Minimal form (hanya nama alternatif & status)
- ✅ CRUD operations: Add, Edit, Delete
- ✅ Audit trail columns

**Form Fields:**
- Nama Alternatif (text) - Contoh: MacBook Pro, Dell XPS, ASUS ROG
- Status - select (from combo)

**URL:** `/spk/alternatif`

---

### 3. **Nilai View** - `app/Views/spk/nilai_view.php`
**Fungsi:** Kelola decision matrix - nilai alternatif untuk setiap kriteria

**Features:**
- ✅ DataTables dengan server-side processing
- ✅ Form modal untuk tambah/edit nilai
- ✅ Dual dropdown: Alternatif + Kriteria
- ✅ Dynamic dropdown loading via AJAX
- ✅ Validasi nilai (numeric, > 0)
- ✅ CRUD operations: Add, Edit, Delete
- ✅ Audit trail columns

**Form Fields:**
- Alternatif - dropdown (loaded from `spk/alternatif/get`)
- Kriteria - dropdown (loaded from `spk/kriteria/get`)
- Nilai - number input
- Status - select (from combo)

**Special:**
- Dropdown diisi via AJAX saat page load
- Kolom display: Alternatif, Kriteria, Nilai (2 desimal), Status, Created/Modified User, Action

**URL:** `/spk/nilai`

---

### 4. **Perhitungan View** - `app/Views/spk/normalisasi_view.php`
**Fungsi:** Kelola normalisasi nilai (standardisasi dengan bobot kriteria)

**Features:**
- ✅ DataTables dengan server-side processing
- ✅ Form modal untuk tambah/edit normalisasi
- ✅ Dual dropdown: Alternatif + Kriteria
- ✅ Dynamic dropdown loading via AJAX
- ✅ Validasi nilai normalisasi (0-1)
- ✅ CRUD operations: Add, Edit, Delete
- ✅ Audit trail columns

**Form Fields:**
- Alternatif - dropdown (loaded from `spk/alternatif/get`)
- Kriteria - dropdown (loaded from `spk/kriteria/get`)
- Nilai Perhitungan - number input (0-1, 4 desimal)
- Status - select (from combo)

**Special:**
- Dropdown diisi via AJAX saat page load
- Kolom display: Alternatif, Kriteria, Nilai Perhitungan (4 desimal), Status, Created/Modified User, Action

**URL:** `/spk/normalisasi`

---

### 5. **Hasil View** - `app/Views/spk/hasil_view.php`
**Fungsi:** Kelola hasil ranking - preference value dan ranking final

**Features:**
- ✅ DataTables dengan server-side processing
- ✅ Form modal untuk tambah/edit hasil
- ✅ Single dropdown: Alternatif
- ✅ Dynamic dropdown loading via AJAX
- ✅ Validasi preference value (0-1) dan ranking
- ✅ CRUD operations: Add, Edit, Delete
- ✅ Audit trail columns
- ✅ Default sort: ranking ASC (otomatis urut dari 1, 2, 3...)

**Form Fields:**
- Alternatif - dropdown (loaded from `spk/alternatif/get`)
- Nilai Preferensi - number input (0-1, 4 desimal)
- Ranking - number input (1, 2, 3, ...)
- Status - select (from combo)

**Special:**
- Dropdown diisi via AJAX saat page load
- Kolom display: Ranking, Alternatif, Nilai Preferensi (4 desimal), Status, Created/Modified User, Action
- Default sort by Ranking ASC

**URL:** `/spk/hasil`

---

## 🎯 Fitur Umum Semua Views

### Form Modal Features:
- ✅ Modal dialog dengan responsive sizing
- ✅ Header dengan warna theme dari config
- ✅ Hidden field untuk primary key
- ✅ Save & Cancel buttons
- ✅ Form reset saat tambah data baru

### DataTables Features:
- ✅ Server-side processing
- ✅ Responsive design
- ✅ Custom row rendering dengan badges
- ✅ Action buttons (Edit, Delete) dengan permission checks
- ✅ Column visibility toggle (Created/Modified info)
- ✅ Search & Pagination
- ✅ Configurable rows per page

### AJAX Operations:
- ✅ jQuery BlockUI untuk loading state
- ✅ Toast notifications (success/error)
- ✅ Confirmation dialog untuk delete
- ✅ Error handling dengan console logging
- ✅ Automatic table reload setelah operasi

### Authorization:
- ✅ Session validation di setiap page
- ✅ Permission check untuk Edit (ubah)
- ✅ Permission check untuk Delete (hapus)
- ✅ Add (tambah) permission di button New

---

## 🔌 Dependencies

### CSS/JS Libraries:
- Bootstrap 4
- DataTables 1.10.18
- Chosen select plugin
- jQuery Toast Plugin
- jQuery BlockUI
- Material Icons

### Helper Scripts:
- `status.js` - Load status dropdown

### Controller Methods Required:
- `index()` - Show view
- `ajax_list()` - Load DataTables data
- `add()` - Handle POST for insert
- `update()` - Handle POST for update
- `edit()` - Get record for edit
- `delete()` - Delete record
- `get()` - Get data for dropdown (untuk Nilai, Perhitungan, Hasil)

---

## 🚀 Implementation Checklist

- ✅ Kriteria view created
- ✅ Alternatif view created
- ✅ Nilai view created
- ✅ Perhitungan view created
- ✅ Hasil view created
- ✅ `get()` method added to Kriteria controller
- ✅ `get()` method added to Alternatif controller
- ⏳ Routes setup (di app/Config/Routes.php)
- ⏳ Database tables created
- ⏳ Menu items added
- ⏳ Authority permissions setup
- ⏳ Test CRUD operations

---

## 📝 Notes

### Column Rendering:
- Benefit/Cost badge dengan badge-success/badge-warning
- Number formatting dengan toFixed()
- Kombinasi untuk display FK relations

### Dropdown Loading:
- Nilai, Perhitungan, Hasil menggunakan dynamic dropdown loading
- AJAX request ke `/spk/alternatif/get` dan `/spk/kriteria/get`
- Filter: hanya status = 1 (active)

### Validation:
- Server-side validation di controller (form_validation)
- Client-side hints di form (small text)
- Error messages dari server ditampilkan di toast

---

## 🔐 Security Features

- ✅ Session validation sebelum akses page
- ✅ Session validation di setiap AJAX call
- ✅ Permission check di button render
- ✅ CSRF protection (CodeIgniter built-in)
- ✅ XSS protection via echo escape (CodeIgniter)

---

## 📊 Data Flow

```
Kriteria View (Master)
  ↓
Alternatif View (Master)
  ↓
Nilai View (Input raw scores)
  ↓
Perhitungan View (Standardize with weights)
  ↓
Hasil View (Final ranking)
  ↓
Dashboard (Display results)
```

---

## ✅ Next Steps

1. **Setup Routes** di `app/Config/Routes.php`:
```php
$routes->group('spk', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->resource('kriteria');
    $routes->resource('alternatif');
    $routes->resource('nilai');
    $routes->resource('normalisasi');
    $routes->resource('hasil');
});
```

2. **Create Directory** untuk views (jika belum ada):
```bash
mkdir -p app/Views/spk
```

3. **Execute Database Migration**:
```bash
mysql -u user -p database < spk.txt
mysql -u user -p database < spk_menu_insert.sql
```

4. **Setup Authority** - Assign menus ke user groups

5. **Test CRUD** - Verify all operations work correctly

