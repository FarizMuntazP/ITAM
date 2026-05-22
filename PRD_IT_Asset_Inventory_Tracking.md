# 📋 Product Requirements Document (PRD)
## Sistem Tracking Aset Inventory IT — Multi Store

---

> **Versi:** 1.0.0
> **Tanggal:** 2025
> **Status:** Draft
> **Dibuat untuk:** Antigravity IDE Project

---

## Daftar Isi

1. [Overview Produk](#1-overview-produk)
2. [Tujuan & Sasaran](#2-tujuan--sasaran)
3. [Scope Proyek](#3-scope-proyek)
4. [Stack Teknologi](#4-stack-teknologi)
5. [Desain & Tema UI](#5-desain--tema-ui)
6. [Arsitektur Sistem](#6-arsitektur-sistem)
7. [Database Schema](#7-database-schema)
8. [Fitur & Requirement Detail](#8-fitur--requirement-detail)
9. [Alur Pengguna (User Flow)](#9-alur-pengguna-user-flow)
10. [Spesifikasi API / Route](#10-spesifikasi-api--route)
11. [Keamanan](#11-keamanan)
12. [Milestone & Timeline](#12-milestone--timeline)
13. [Kriteria Penerimaan (Acceptance Criteria)](#13-kriteria-penerimaan-acceptance-criteria)
14. [Risiko & Mitigasi](#14-risiko--mitigasi)
15. [Referensi & Catatan Tambahan](#15-referensi--catatan-tambahan)

---

## 1. Overview Produk

**Nama Produk:** ITAM (IT Asset Management) — Internal Store Tracker

**Deskripsi Singkat:**
Aplikasi web berbasis Laravel untuk mengelola, melacak, dan memantau aset inventory IT di seluruh jaringan store (±60 store, dapat berkembang). Sistem ini memungkinkan admin untuk menginput, memperbarui, menelusuri, dan mengekspor data aset secara efisien, dilengkapi fitur QR Code per aset, upload foto, kalkulasi umur aset, serta import/export Excel massal.

**Target Pengguna:**
- Admin IT Pusat (superadmin)
- Admin IT per Store / Cabang

**Platform:** Web Application (Desktop-first, responsive)

---

## 2. Tujuan & Sasaran

| No | Tujuan | Indikator Keberhasilan |
|----|--------|------------------------|
| 1 | Sentralisasi data aset IT seluruh store | Semua aset terdokumentasi dalam satu sistem |
| 2 | Mempercepat proses input & pencarian aset | Waktu input < 2 menit per aset |
| 3 | Mengurangi kehilangan aset tidak tercatat | Zero untracked asset setelah implementasi |
| 4 | Memudahkan audit aset berkala | Generate laporan kapan saja |
| 5 | Skalabilitas untuk pertumbuhan store | Sistem mampu menampung >200 store tanpa rekonfigurasi |

---

## 3. Scope Proyek

### ✅ In Scope
- Modul autentikasi (Login/Logout)
- CRUD aset inventory IT
- Auto-generate ID Aset
- Kalkulasi & tampilan umur aset
- Upload & manajemen foto aset
- Generate QR Code per aset (berisi data lengkap aset)
- Filter & Sort pada tabel aset
- Import massal via Excel (template disediakan)
- Export data ke Excel
- Manajemen Store/Cabang
- Manajemen Kategori Aset

### ❌ Out of Scope (fase ini)
- Notifikasi otomatis (email/WhatsApp) masa garansi habis
- Mobile application (Android/iOS)
- Integrasi ERP/SAP
- Fitur multi-role selain admin (user read-only menjadi fase 2)
- Laporan analitik lanjutan / dashboard grafik (fase 2)

---

## 4. Stack Teknologi

| Komponen | Teknologi | Versi Rekomendasi |
|----------|-----------|-------------------|
| Backend Framework | Laravel | 11.x |
| Frontend Styling | Tailwind CSS | 3.x |
| Templating Engine | Blade (Laravel built-in) | — |
| Database | MySQL (via phpMyAdmin / XAMPP) | 8.x |
| Local Server | XAMPP | Terbaru |
| PHP | PHP | 8.2+ |
| QR Code Generator | `simplesoftwareio/simple-qrcode` | ^4.2 |
| Excel Import/Export | `maatwebsite/excel` (Laravel Excel) | ^3.1 |
| Image Upload | Laravel Storage (local disk) | — |
| Authentication | Laravel Breeze / Session Auth | — |
| JS Build Tool | Vite (bawaan Laravel 11) | — |
| Node.js (untuk Vite) | Node.js | 18+ |

### Composer Packages yang Dibutuhkan:
```
simplesoftwareio/simple-qrcode
maatwebsite/excel
intervention/image (opsional, untuk resize foto)
```

---

## 5. Desain & Tema UI

### Palet Warna

| Elemen | Warna | Hex |
|--------|-------|-----|
| Primary / Aksen Utama | Kuning Brand | `#fecb00` |
| Background Utama | Hitam / Dark | `#111111` |
| Background Card/Panel | Dark Grey | `#1e1e1e` |
| Text Utama | Putih | `#ffffff` |
| Text Sekunder | Abu-abu terang | `#a0a0a0` |
| Border / Divider | Abu-abu gelap | `#2e2e2e` |
| Hover State | Kuning redup | `#d4a900` |
| Success | Hijau | `#22c55e` |
| Danger / Delete | Merah | `#ef4444` |
| Warning | Oranye | `#f97316` |

### Prinsip Desain
- Dark mode sebagai tema utama (hitam dominan)
- Aksen kuning `#fecb00` pada tombol CTA, header, badge status, dan ikon aktif
- Font: **Inter** atau **Poppins** (Google Fonts)
- Sidebar navigasi vertikal, sticky
- Tabel dengan row hover effect kuning transparan
- Komponen: card, badge, modal, toast notification
- Ikon: Heroicons atau Lucide Icons (SVG inline via Blade)

### Layout Utama
```
┌─────────────────────────────────────────────────┐
│  HEADER (Logo + Nama User + Logout)  [#fecb00]  │
├──────────┬──────────────────────────────────────┤
│          │  BREADCRUMB                          │
│ SIDEBAR  │─────────────────────────────────────│
│          │                                      │
│ - Dashboard       CONTENT AREA                  │
│ - Aset            (Main Page)                   │
│ - Store                                         │
│ - Kategori │                                    │
│ - Import   │                                    │
│ - Export   │                                    │
│            │                                    │
└──────────┴──────────────────────────────────────┘
```

---

## 6. Arsitektur Sistem

### Struktur Folder Laravel (Relevan)
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── AssetController.php
│   │   ├── StoreController.php
│   │   ├── CategoryController.php
│   │   └── ExcelController.php
│   └── Middleware/
│       └── AdminAuth.php
├── Models/
│   ├── Asset.php
│   ├── Store.php
│   └── Category.php
├── Imports/
│   └── AssetsImport.php
├── Exports/
│   └── AssetsExport.php
resources/
├── views/
│   ├── auth/
│   │   └── login.blade.php
│   ├── layouts/
│   │   └── app.blade.php
│   ├── dashboard/
│   │   └── index.blade.php
│   ├── assets/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   ├── stores/
│   └── categories/
routes/
├── web.php
public/
├── storage/ (symlink)
storage/
└── app/public/
    ├── assets/photos/
    └── qrcodes/
```

---

## 7. Database Schema

### Tabel: `users`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT UNSIGNED AI PK | |
| name | VARCHAR(100) | Nama admin |
| username | VARCHAR(50) UNIQUE | Username login |
| password | VARCHAR(255) | Bcrypt hash |
| role | ENUM('superadmin','admin') | Default: admin |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

> **Seed awal:** username: `admin`, password: `admin` (akan di-hash bcrypt)

---

### Tabel: `stores`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT UNSIGNED AI PK | |
| store_code | VARCHAR(20) UNIQUE | Kode store (mis. STR-001) |
| store_name | VARCHAR(100) | Nama store |
| location | VARCHAR(200) | Alamat / kota |
| region | VARCHAR(100) NULL | Wilayah / region |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

---

### Tabel: `categories`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT UNSIGNED AI PK | |
| category_code | VARCHAR(10) UNIQUE | Kode kategori (mis. NTB, PRN, MON) |
| category_name | VARCHAR(100) | Nama kategori |
| description | TEXT NULL | |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

> **Contoh kategori:** Notebook (NTB), Printer (PRN), Monitor (MON), UPS (UPS), Server (SRV), Switch (SWT), CCTV (CTV), dll.

---

### Tabel: `assets` ⭐ (Tabel Utama)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT UNSIGNED AI PK | |
| asset_id | VARCHAR(30) UNIQUE | **Auto-generated** (mis. `ITAM-NTB-0001`) |
| asset_name | VARCHAR(150) | Nama/deskripsi aset |
| category_id | BIGINT FK → categories.id | |
| store_id | BIGINT FK → stores.id | |
| brand | VARCHAR(100) NULL | Merek |
| model | VARCHAR(100) NULL | Model / tipe |
| serial_number | VARCHAR(100) NULL UNIQUE | Nomor seri |
| specs | TEXT NULL | Spesifikasi teknis |
| condition | ENUM('good','fair','poor','damaged') | Kondisi aset |
| status | ENUM('active','inactive','maintenance','disposed') | Status aset |
| purchase_date | DATE NULL | Tanggal pembelian |
| warranty_until | DATE NULL | Garansi hingga |
| purchase_price | DECIMAL(15,2) NULL | Harga beli |
| location_detail | VARCHAR(200) NULL | Lokasi detail di store |
| notes | TEXT NULL | Catatan tambahan |
| photo | VARCHAR(255) NULL | Path foto aset |
| qr_code_path | VARCHAR(255) NULL | Path file QR Code |
| added_at | TIMESTAMP | **Waktu ditambahkan** (untuk hitung umur) |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

---

### Format Auto-Generate `asset_id`
```
Format : [PREFIX]-[KODE_KATEGORI]-[NOMOR_URUT_4_DIGIT]
Contoh : ITAM-NTB-0001
         ITAM-PRN-0023
         ITAM-MON-0005

Logika :
1. Ambil kode kategori (mis. NTB dari kategori Notebook)
2. Hitung jumlah aset dengan kategori yang sama → +1
3. Format nomor urut dengan zero-padding 4 digit
4. Gabungkan: ITAM-{KODE}-{NOMOR}
```

---

## 8. Fitur & Requirement Detail

---

### 8.1 Autentikasi (Login)

**Deskripsi:** Halaman login sederhana sebelum mengakses sistem.

**Requirement:**
- Form login dengan field: Username & Password
- Kredensial default: `admin` / `admin`
- Validasi input (required, max length)
- Session-based authentication (Laravel `Auth`)
- Redirect ke dashboard setelah login berhasil
- Tombol logout di header
- Proteksi seluruh halaman dengan middleware auth

**UI Notes:**
- Halaman login full-screen, centered card
- Background hitam, card dengan border kuning `#fecb00`
- Logo / nama aplikasi di atas form
- Tombol login warna kuning `#fecb00` dengan teks hitam

---

### 8.2 Auto-Generate Asset ID

**Deskripsi:** Saat aset baru disimpan, sistem otomatis membuat ID unik.

**Requirement:**
- ID di-generate di backend (Controller), bukan diinput manual
- Format: `ITAM-{KODE_KATEGORI}-{NOMOR_URUT_4DIGIT}`
- Nomor urut berdasarkan total aset per kategori (tidak reset)
- Asset ID ditampilkan di form setelah kategori dipilih (preview, read-only) via AJAX / Alpine.js
- Asset ID tersimpan di kolom `asset_id` (UNIQUE)

**Logika Generate (PHP):**
```php
// Pseudocode
$categoryCode = $category->category_code; // mis. "NTB"
$count = Asset::where('category_id', $categoryId)->count() + 1;
$assetId = 'ITAM-' . $categoryCode . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
// Hasil: ITAM-NTB-0042
```

---

### 8.3 Kalkulasi Umur Aset

**Deskripsi:** Sistem menghitung dan menampilkan berapa lama suatu aset sudah ada sejak ditambahkan ke sistem.

**Requirement:**
- Referensi waktu: kolom `added_at` (diisi otomatis saat aset pertama kali dibuat)
- Format tampilan: `X Tahun Y Bulan Z Hari` atau `X Hari` jika < 1 bulan
- Ditampilkan di: halaman detail aset, kolom tabel (opsional singkat), dan QR Code data
- Umur dihitung real-time menggunakan `Carbon` (Laravel)
- Warna indikator:
  - Hijau: < 2 tahun (masih baru)
  - Kuning: 2–4 tahun (pertimbangkan penggantian)
  - Merah: > 4 tahun (sudah tua)

**Contoh Kode:**
```php
// Di Model Asset.php
use Carbon\Carbon;

public function getAgeAttribute(): string
{
    $start = Carbon::parse($this->added_at);
    $now   = Carbon::now();
    $diff  = $start->diff($now);

    if ($diff->y > 0) {
        return "{$diff->y} Tahun {$diff->m} Bulan";
    } elseif ($diff->m > 0) {
        return "{$diff->m} Bulan {$diff->d} Hari";
    } else {
        return "{$diff->d} Hari";
    }
}
```

---

### 8.4 Upload Foto Aset

**Deskripsi:** Setiap aset dapat dilampirkan satu foto.

**Requirement:**
- Field upload foto pada form Create & Edit aset
- Format yang diterima: JPG, JPEG, PNG, WEBP
- Ukuran maksimum: 2 MB
- Foto disimpan di: `storage/app/public/assets/photos/`
- Nama file: `{asset_id}_{timestamp}.jpg` (rename otomatis)
- Jika tidak ada foto, tampilkan placeholder icon perangkat
- Foto lama dihapus otomatis saat diganti
- Preview foto sebelum upload (JavaScript FileReader)
- Foto ditampilkan di halaman detail dan tabel (thumbnail kecil)

---

### 8.5 Generate QR Code per Aset

**Deskripsi:** Setiap aset memiliki QR Code yang berisi informasi lengkap aset tersebut.

**Requirement:**
- QR Code di-generate menggunakan library `simplesoftwareio/simple-qrcode`
- QR Code berisi data JSON:
```json
{
  "asset_id": "ITAM-NTB-0001",
  "asset_name": "Laptop Dell Latitude",
  "category": "Notebook",
  "brand": "Dell",
  "model": "Latitude 5420",
  "serial_number": "SN123456789",
  "store": "Store Jakarta Pusat",
  "condition": "Good",
  "status": "Active",
  "added_at": "2023-01-15"
}
```
- QR Code disimpan sebagai file PNG di `storage/app/public/qrcodes/`
- Di-generate otomatis saat aset dibuat / di-update
- Tombol **"Download QR"** di halaman detail aset (unduh PNG)
- Tombol **"Print QR"** membuka print dialog dengan QR + info singkat aset
- Di tabel aset, ada tombol ikon QR di kolom aksi untuk preview modal

---

### 8.6 Import Massal via Excel

**Deskripsi:** Admin dapat mengupload file Excel untuk menambah banyak aset sekaligus.

**Requirement:**
- Tombol **"Import Excel"** di halaman daftar aset
- Tombol **"Download Template"** menyediakan file `.xlsx` master template
- Template Excel berisi kolom (dengan header):
  | Kolom | Keterangan |
  |-------|------------|
  | asset_name* | Nama aset (wajib) |
  | category_code* | Kode kategori (wajib, mis. NTB) |
  | store_code* | Kode store (wajib, mis. STR-001) |
  | brand | Merek |
  | model | Model |
  | serial_number | Nomor seri |
  | specs | Spesifikasi |
  | condition | good/fair/poor/damaged |
  | status | active/inactive/maintenance |
  | purchase_date | Format: YYYY-MM-DD |
  | warranty_until | Format: YYYY-MM-DD |
  | purchase_price | Angka tanpa titik/koma |
  | location_detail | Lokasi di store |
  | notes | Catatan |

- Kolom bertanda `*` adalah wajib diisi
- Saat import, sistem otomatis generate `asset_id` dan `added_at`
- Validasi per baris: tampilkan error spesifik (baris ke-X, kolom Y: pesan error)
- Data valid tetap diimport, data invalid dilaporkan di ringkasan
- Gunakan package `maatwebsite/excel`
- Upload file max 10 MB, format .xlsx / .xls

---

### 8.7 Export Data ke Excel

**Deskripsi:** Admin dapat mengekspor seluruh atau sebagian data aset ke file Excel.

**Requirement:**
- Tombol **"Export Excel"** di halaman daftar aset
- Export berdasarkan filter aktif (jika ada filter store/kategori/status yang dipilih, hanya ekspor data tersebut)
- File hasil export: `Asset_Inventory_{tanggal}.xlsx`
- Kolom export mencakup semua field aset termasuk umur aset yang sudah dihitung
- Menggunakan package `maatwebsite/excel`

---

### 8.8 Filter & Sort Tabel Aset

**Deskripsi:** Tabel daftar aset memiliki kemampuan filter dan pengurutan.

**Requirement Filter:**
- Filter berdasarkan **Store** (dropdown)
- Filter berdasarkan **Kategori** (dropdown)
- Filter berdasarkan **Status** (dropdown: active/inactive/maintenance/disposed)
- Filter berdasarkan **Kondisi** (dropdown: good/fair/poor/damaged)
- Filter berdasarkan **Rentang Tanggal** penambahan (date range picker)
- **Search bar** global (mencari di: asset_id, asset_name, brand, model, serial_number)
- Tombol **Reset Filter**

**Requirement Sort:**
- Klik header kolom untuk sort ascending/descending
- Indikator arah sort (ikon panah)
- Kolom yang bisa di-sort: Asset ID, Nama Aset, Kategori, Store, Kondisi, Status, Tanggal Ditambahkan, Umur

**Implementasi:**
- Filter & sort menggunakan query parameter di URL (bookmarkable)
- Pagination: 25 item per halaman (dapat diubah ke 50/100)

---

### 8.9 Manajemen Store

**Deskripsi:** Kelola daftar store/cabang yang tersedia.

**Requirement:**
- CRUD Store (Tambah, Lihat, Edit, Hapus)
- Hapus store hanya bisa dilakukan jika tidak ada aset yang terhubung
- Tampilan tabel store dengan kolom: Kode Store, Nama Store, Lokasi, Region, Jumlah Aset

---

### 8.10 Manajemen Kategori

**Deskripsi:** Kelola kategori aset IT.

**Requirement:**
- CRUD Kategori (Tambah, Lihat, Edit, Hapus)
- Hapus kategori hanya bisa dilakukan jika tidak ada aset yang terhubung
- Tampilan tabel: Kode Kategori, Nama Kategori, Deskripsi, Jumlah Aset

---

### 8.11 Dashboard

**Deskripsi:** Halaman utama setelah login, menampilkan ringkasan data.

**Requirement:**
- Kartu statistik ringkasan:
  - Total Aset
  - Total Store
  - Total Kategori
  - Aset dengan kondisi "Damaged"
- Tabel 10 aset terbaru yang ditambahkan
- Tabel 5 aset dengan kondisi terburuk (poor/damaged)

---

## 9. Alur Pengguna (User Flow)

### 9.1 Login
```
Buka aplikasi
   → Halaman Login
      → Input username & password
         → Valid? → Redirect ke Dashboard
         → Tidak valid? → Tampilkan pesan error, stay di login
```

### 9.2 Tambah Aset Baru
```
Dashboard / Halaman Aset
   → Klik tombol "+ Tambah Aset"
      → Form Create Aset terbuka
         → Pilih Kategori → Preview Asset ID auto-generate muncul
         → Pilih Store
         → Isi data aset (nama, brand, model, dll.)
         → Upload foto (opsional)
         → Klik "Simpan"
            → Validasi backend
               → Berhasil → Redirect ke halaman detail aset
                          → QR Code di-generate otomatis
               → Gagal → Kembali ke form dengan pesan error
```

### 9.3 Generate & Download QR Code
```
Halaman Detail Aset
   → Klik "Download QR"
      → File QR PNG terdownload ke perangkat
   → Klik "Print QR"
      → Dialog print browser terbuka (konten: QR + info singkat aset)
   ATAU
Halaman Tabel Aset
   → Klik ikon QR di kolom Aksi
      → Modal popup menampilkan QR Code aset tersebut
```

### 9.4 Import Massal Excel
```
Halaman Aset
   → Klik "Download Template"
      → File template.xlsx terdownload
   → Isi template, simpan
   → Klik "Import Excel"
      → Modal upload file muncul
         → Pilih file .xlsx
         → Klik "Proses Import"
            → Loading indicator
            → Sukses: "X aset berhasil diimport" + ringkasan
            → Ada error: "X berhasil, Y gagal" + tabel detail error per baris
```

---

## 10. Spesifikasi API / Route

### Web Routes (`routes/web.php`)

```php
// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard (protected)
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Aset
    Route::resource('assets', AssetController::class);
    Route::get('assets/{asset}/qr/download', [AssetController::class, 'downloadQr'])->name('assets.qr.download');
    Route::get('assets/{asset}/qr/print', [AssetController::class, 'printQr'])->name('assets.qr.print');
    Route::get('assets/generate-id', [AssetController::class, 'generateId'])->name('assets.generate-id');

    // Excel
    Route::get('assets/export', [ExcelController::class, 'export'])->name('assets.export');
    Route::get('assets/template', [ExcelController::class, 'downloadTemplate'])->name('assets.template');
    Route::post('assets/import', [ExcelController::class, 'import'])->name('assets.import');

    // Store
    Route::resource('stores', StoreController::class);

    // Kategori
    Route::resource('categories', CategoryController::class);
});
```

---

## 11. Keamanan

| Aspek | Implementasi |
|-------|-------------|
| Authentication | Laravel Session Auth, password di-hash Bcrypt |
| Authorization | Middleware `auth` di semua route kecuali login |
| CSRF Protection | Laravel `@csrf` token di semua form |
| Input Validation | Laravel `Request` validation rules di setiap endpoint |
| File Upload | Validasi mime type, ukuran file, rename file otomatis |
| SQL Injection | Eloquent ORM & Query Builder (parameterized query) |
| XSS | Blade `{{ }}` auto-escape, `{!! !!}` hanya jika benar-benar diperlukan |
| Session | Laravel session dengan regenerasi ID setelah login |

---

## 12. Milestone & Timeline

| Fase | Deskripsi | Estimasi |
|------|-----------|----------|
| **Fase 1** | Setup project Laravel + Tailwind + XAMPP, konfigurasi DB, migration, seeder | 1–2 hari |
| **Fase 2** | Modul Auth (Login/Logout), Layout utama (sidebar + header) | 1–2 hari |
| **Fase 3** | CRUD Store & Kategori | 1 hari |
| **Fase 4** | CRUD Aset + Auto ID + Upload Foto + Kalkulasi Umur | 3–4 hari |
| **Fase 5** | Generate QR Code + Download + Print | 1–2 hari |
| **Fase 6** | Import/Export Excel + Template | 2–3 hari |
| **Fase 7** | Filter, Sort, Pagination di tabel | 1–2 hari |
| **Fase 8** | Dashboard + Polish UI (Tailwind dark theme) | 2–3 hari |
| **Fase 9** | Testing, bug fixing, seeder data dummy | 2 hari |
| **Total** | | **~15–20 hari kerja** |

---

## 13. Kriteria Penerimaan (Acceptance Criteria)

### AC-01: Login
- [ ] Halaman login muncul saat mengakses aplikasi tanpa session
- [ ] Login dengan `admin`/`admin` berhasil masuk ke dashboard
- [ ] Login dengan kredensial salah menampilkan pesan error
- [ ] Semua halaman kecuali login di-redirect jika belum login

### AC-02: Tambah Aset
- [ ] Form tambah aset memiliki semua field yang diperlukan
- [ ] Asset ID ter-generate otomatis dan unik per kategori
- [ ] Foto dapat diupload dan ditampilkan
- [ ] Aset tersimpan di database dengan `added_at` terisi otomatis

### AC-03: Umur Aset
- [ ] Kolom umur di tabel menampilkan durasi dari `added_at` ke sekarang
- [ ] Format: "X Tahun Y Bulan" atau "X Bulan Y Hari"
- [ ] Indikator warna sesuai kategori umur

### AC-04: QR Code
- [ ] QR Code ter-generate saat aset disimpan
- [ ] Scan QR menampilkan data JSON aset yang benar
- [ ] Tombol download QR mengunduh file PNG
- [ ] Tombol print QR membuka dialog print

### AC-05: Import Excel
- [ ] Template Excel dapat didownload
- [ ] File Excel valid dapat diimport dan menghasilkan data aset baru
- [ ] File Excel dengan error menampilkan pesan error per baris
- [ ] Asset ID ter-generate otomatis untuk setiap aset yang diimport

### AC-06: Export Excel
- [ ] Tombol export mengunduh file `.xlsx` berisi data aset
- [ ] Export mengikuti filter aktif jika ada

### AC-07: Filter & Sort
- [ ] Filter berdasarkan store, kategori, status, kondisi berfungsi
- [ ] Search bar mencari di semua field yang ditentukan
- [ ] Klik header kolom mengubah urutan data
- [ ] Reset filter mengembalikan ke tampilan semua data

---

## 14. Risiko & Mitigasi

| Risiko | Probabilitas | Dampak | Mitigasi |
|--------|-------------|--------|----------|
| Duplikasi Asset ID saat import massal bersamaan | Rendah | Tinggi | Gunakan database UNIQUE constraint + transaction |
| File foto menghabiskan storage | Sedang | Sedang | Compress foto saat upload (max 800px), monitor storage |
| Import Excel dengan format kolom salah | Tinggi | Sedang | Validasi ketat + pesan error yang jelas per kolom |
| Performa lambat pada >10.000 aset | Rendah (awal) | Sedang | Index database pada kolom filter, pagination |
| Kehilangan data saat XAMPP crash | Rendah | Tinggi | Backup database berkala, export Excel rutin |

---

## 15. Referensi & Catatan Tambahan

### Catatan untuk Developer (Antigravity IDE)

1. **Setup Awal XAMPP:**
   - Pastikan Apache & MySQL aktif di XAMPP Control Panel
   - Buat database baru di phpMyAdmin: `itam_db`
   - Update `.env` Laravel: `DB_DATABASE=itam_db`, `DB_USERNAME=root`, `DB_PASSWORD=`

2. **Storage Link:**
   ```bash
   php artisan storage:link
   ```
   Jalankan sekali untuk menghubungkan `storage/app/public` ke `public/storage`

3. **Install Dependencies:**
   ```bash
   composer require simplesoftwareio/simple-qrcode
   composer require maatwebsite/excel
   npm install
   npm run dev
   ```

4. **Seeder:**
   ```bash
   php artisan db:seed
   ```
   Akan membuat: user admin, 5 kategori awal, 60 store dummy, 20 aset sample

5. **Konfigurasi GD Library (untuk QR Code):**
   - Pastikan extension `php_gd` aktif di `php.ini` XAMPP
   - Restart Apache setelah mengaktifkan

6. **Lokasi file XAMPP:**
   - Project Laravel: `C:\xampp\htdocs\itam\`
   - Akses via browser: `http://localhost/itam/public`
   - Atau gunakan `php artisan serve` pada port 8000

### Konvensi Penamaan
- **Controller:** PascalCase + `Controller` (mis. `AssetController`)
- **Model:** PascalCase singular (mis. `Asset`, `Store`)
- **Migration:** snake_case + timestamp (otomatis Laravel)
- **View:** snake_case (mis. `assets/create.blade.php`)
- **Route name:** dot notation (mis. `assets.index`, `assets.create`)

### Kamus Istilah
| Istilah | Keterangan |
|---------|------------|
| Aset | Perangkat / barang IT yang diinventarisasi |
| Store | Toko / cabang tempat aset berada |
| Asset ID | Kode unik aset yang di-generate sistem |
| Umur Aset | Durasi sejak aset ditambahkan ke sistem |
| Import | Proses menambah banyak aset dari file Excel |
| Export | Proses mengunduh data aset ke file Excel |
| QR Code | Kode gambar yang berisi data aset, bisa di-scan |

---

*Dokumen ini adalah living document. Perubahan requirement harus didiskusikan dan diupdate di versi baru PRD.*

---

**PRD Version:** 1.0.0 | **Last Updated:** 2025 | **Project:** ITAM Internal Store Tracker
