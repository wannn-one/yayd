# YAYD - Yho Akhirat Yho Dunyo

## Tentang Aplikasi

YAYD (Yho Akhirat Yho Dunyo) adalah platform komunitas berbasis web yang komprehensif untuk mendukung anak-anak yatim melalui kegiatan sosial yang terorganisir dan pengelolaan donasi yang transparan. Platform ini berfungsi sebagai jembatan antara donatur, relawan, dan yayasan untuk menciptakan dampak nyata dalam kehidupan anak-anak yatim.

**Visi:** Menjadi lembaga yang amanah dalam memberdayakan anak yatim.

**Misi:**
1. Memberikan pendidikan formal dan non-formal
2. Menyelenggarakan kegiatan positif
3. Mengelola donasi secara transparan

## Fitur Utama

### 🎭 Sistem Multi-Role
- **Admin**: Pengelolaan sistem lengkap dan pengawasan
- **Donatur**: Pengelolaan kontribusi finansial
- **Relawan**: Partisipasi kegiatan dan keterlibatan komunitas

### 💰 Pengelolaan Donasi
- **Jenis Donasi Beragam**: Dukungan untuk donasi uang dan barang
- **Metode Pembayaran Fleksibel**: Transfer, COD (Cash on Delivery), dan OTS (On The Spot)
- **Pelacakan Transparan**: Riwayat donasi lengkap dan pelacakan status
- **Donasi Spesifik atau Umum**: Kaitkan donasi ke kegiatan tertentu atau kontribusi ke dana umum

### 📅 Pengelolaan Kegiatan
- **Organisasi Acara**: Buat dan kelola kegiatan komunitas
- **Pendaftaran Relawan**: Memungkinkan relawan mendaftar untuk kegiatan
- **Pelacakan Status**: Pantau kemajuan kegiatan (Akan Datang, Berjalan, Selesai, Dibatalkan)
- **Dokumentasi**: Pengelolaan foto dan dokumentasi kegiatan

### 👥 Pengelolaan Pengguna
- **Autentikasi Aman**: Sistem registrasi dan login pengguna
- **Kontrol Akses Berbasis Peran**: Izin berbeda untuk jenis pengguna berbeda
- **Pengelolaan Profil**: Informasi pribadi dan detail kontak

### 📊 Dashboard Admin
- **Pengelolaan Pengguna**: Tambah, edit, dan kelola pengguna sistem
- **Pengawasan Donasi**: Pantau dan setujui donasi
- **Koordinasi Kegiatan**: Kelola semua kegiatan komunitas
- **Sistem Laporan**: Buat laporan komprehensif

### 🌐 Antarmuka Publik
- **Profil Komunitas**: Tampilkan visi, misi, dan deskripsi organisasi
- **Kegiatan Mendatang**: Showcaset acara komunitas yang direncanakan
- **Registrasi Mudah**: Pemilihan peran dan proses registrasi sederhana

## Fitur Versi 2.0

### 💰 Pengelolaan Donasi
- **Distribusi Donasi**: Admin dapat mencatat semua pengeluaran/penyaluran donasi untuk transparansi penuh.
- **Laporan Keuangan Excel**: Laporan keuangan lengkap (pemasukan & pengeluaran) yang dapat diunduh dalam format .xlsx.

### 📅 Manajemen Konten & Kegiatan
- **Kelola Konten Terpusat**: Admin dapat mengelola semua konten dari satu menu, termasuk:
   - **Profil Yayasan**: Mengubah Visi, Misi, dan info kontak yang tampil di halaman publik.

### 👥 Pengelolaan Pengguna
- **Approval Pendaftaran Relawan**: Setiap relawan baru harus disetujui oleh Admin sebelum akunnya aktif dan bisa login.
- **Absensi Digital**: Admin dapat mencatat kehadiran relawan (Hadir, Batal) pada setiap kegiatan.

## Cara Setup

### Persyaratan
- **XAMPP** (hanya untuk Apache dan PHP)
- **MySQL Workbench CE** (untuk pengelolaan database)
- **FPDF Library** (untuk generate laporan PDF)
- **Web Browser** (Chrome, Firefox, dll.)
- **Git** (opsional, untuk cloning)

### Langkah Instalasi

#### 1. Clone atau Download Project
```bash
# Opsi 1: Clone dengan Git
git clone [repository-url] yayd

# Opsi 2: Download dan ekstrak ke xampp/htdocs/yayd
```

#### 2. Setup XAMPP
1. Jalankan **XAMPP Control Panel**
2. Start layanan **Apache** saja (tidak perlu MySQL karena menggunakan MySQL Workbench)
3. Pastikan project berada di `C:\xampp\htdocs\yayd`

#### 3. Konfigurasi Database dengan MySQL Workbench CE
1. Buka **MySQL Workbench CE**
2. Buat koneksi baru atau gunakan koneksi yang sudah ada
3. Buat database baru bernama `yayd`:
   ```sql
   CREATE DATABASE yayd;
   USE yayd;
   ```
4. Import schema database:
   - Buka file `script/init.sql` dari folder project
   - Copy dan jalankan seluruh isi file tersebut di MySQL Workbench
   - Opsional: Jalankan juga `script/seed.sql` untuk data contoh tambahan

#### 4. Install FPDF Library
1. Download FPDF dari [http://www.fpdf.org/](http://www.fpdf.org/)
2. Ekstrak file ke folder `libs/fpdf186/` di dalam project
3. Struktur yang benar: `yayd/libs/fpdf186/fpdf.php`

#### 5. Install Depedencies dengan Composer
1. Buka Command Prompt (CMD) atau Terminal.
2. Navigasi ke direktori project:
   ```bash
   cd C:\xampp\htdocs\yayd
   ```
3. Jalankan perintah berikut untuk menginstal dependensi:
   ```bash
   composer install
   ```
4. Jika ada error, pastikan Anda memiliki Composer terinstal.

#### 5. Konfigurasi Koneksi Database
1. Buka file `config/config.php`
2. Perbarui kredensial database sesuai dengan setup MySQL Workbench Anda:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');          // Sesuaikan dengan username MySQL Anda
   define('DB_PASS', '123123');        // Ganti dengan password MySQL Anda
   define('DB_NAME', 'yayd');
   ```
3. Perbarui base URL jika diperlukan:
   ```php
   define('BASE_URL', 'http://localhost/yayd');
   ```

#### 6. Akses Aplikasi
1. Buka web browser
2. Navigasi ke: `http://localhost/yayd`
3. Halaman utama akan muncul menampilkan platform komunitas YAYD

### Akses Default
Setelah menjalankan script database, Anda dapat menggunakan role default berikut:
- **Admin** (ID: 1)
- **Donatur** (ID: 2)
- **Relawan** (ID: 3)

### Akun Tes
- **Admin**: admin@example.com / 123123
- **Donatur**: donatur.ahmad@example.com / 123123
- **Relawan**: relawan.lina@example.com / 123123

### Struktur File
```
yayd/
├── admin/              # Panel admin dan pengelolaan
├── donatur/            # Dashboard donatur dan fitur
├── relawan/            # Dashboard relawan dan fitur
├── assets/             # File CSS, JS, dan gambar
├── config/             # Konfigurasi database dan aplikasi
├── controllers/        # Controller logika bisnis
├── views/              # Template dan komponen UI
├── script/             # Script inisialisasi database
├── profil/             # Pengelolaan profil
└── libs/               # Library tambahan
```

### Troubleshooting
- **Masalah Koneksi Database**: Pastikan MySQL berjalan di MySQL Workbench dan kredensial benar
- **Error Permission**: Pastikan permission file yang tepat di direktori htdocs
- **Masalah Style/CSS**: Periksa apakah file `assets/css/style.css` dapat diakses
- **Masalah Session**: Pastikan PHP session support telah diaktifkan di XAMPP

---

*For more information, contact: kontak@yayd.com | 081234567890* 