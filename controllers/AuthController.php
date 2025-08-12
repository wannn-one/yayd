<?php
// controllers/AuthController.php

session_start();
require_once '../config/database.php'; // Perhatikan ../ untuk naik satu level folder

/**
 * Menangani logika pendaftaran pengguna.
 */
function handleRegistration() {
    global $koneksi; // Menggunakan variabel koneksi global dari database.php

    // 1. Memeriksa apakah request datang dari metode POST
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        header("Location: ../donatur_daftar.php"); // Redirect ke salah satu form default
        exit();
    }

    // 2. Mengambil data dari form
    $nama = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];
    $id_role = (int)$_POST['id_role_fk'];
    
    // New fields
    $nomor_telepon = $_POST['nomor_telepon'] ?? null;
    $alamat = $_POST['alamat'] ?? null;
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? null;
    $alasan_bergabung = $_POST['alasan_bergabung'] ?? null;

    // 3. Validasi
    $redirect_url = ($id_role == 2) ? "../donatur_daftar.php" : "../relawan_daftar.php";
    if (empty($nama) || empty($email) || empty($password) || empty($id_role)) {
        header("Location: $redirect_url?error=data_tidak_lengkap");
        exit();
    }
    if ($password !== $konfirmasi_password) {
        header("Location: $redirect_url?error=password_tidak_cocok");
        exit();
    }
    
    // 4. Cek email duplikat
    $sql_cek_email = "SELECT id_user FROM users WHERE email = ?";
    $stmt_cek = mysqli_prepare($koneksi, $sql_cek_email);
    mysqli_stmt_bind_param($stmt_cek, 's', $email);
    mysqli_stmt_execute($stmt_cek);
    mysqli_stmt_store_result($stmt_cek);

    if (mysqli_stmt_num_rows($stmt_cek) > 0) {
        header("Location: $redirect_url?error=email_terdaftar");
        exit();
    }
    mysqli_stmt_close($stmt_cek); // Tutup statement cek email di sini
    
    // 5. Tentukan status akun berdasarkan peran
    $status_akun = 'Pending'; // Semua user baru harus pending dan menunggu approval admin
    
    // 6. Hashing password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 7. Perbarui Query INSERT untuk menyertakan kolom baru
    $sql_insert = "INSERT INTO users (nama_lengkap, email, password, id_role_fk, status_akun, nomor_telepon, alamat, jenis_kelamin, alasan_bergabung) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($koneksi, $sql_insert);
    
    // 8. Sesuaikan tipe data dan variabel yang diikat
    mysqli_stmt_bind_param($stmt_insert, 'sssisssss', $nama, $email, $hashed_password, $id_role, $status_akun, $nomor_telepon, $alamat, $jenis_kelamin, $alasan_bergabung);

    // 9. Eksekusi dan redirect
    if (mysqli_stmt_execute($stmt_insert)) {
        // Semua user baru mendapat status pending
        header("Location: ../login.php?status=sukses_daftar_pending");
        exit();
    } else {
        echo "Error: Gagal menyimpan data.";
    }

    // Tutup statement dan koneksi
    mysqli_stmt_close($stmt_insert);
    mysqli_close($koneksi);
}

/**
 * Menangani logika login pengguna.
 */
function handleLogin() {
    global $koneksi;

    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        header("Location: ../login.php");
        exit();
    }

    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    // 1. Ambil data user berdasarkan email
    $sql = "SELECT id_user, nama_lengkap, password, id_role_fk, status_akun FROM users WHERE email = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        // 2. Jika user ditemukan, verifikasi password
        if (password_verify($password, $user['password'])) {
            // Cek jika akun diblokir
            if ($user['status_akun'] == 'Diblokir') {
                header("Location: ../login.php?error=akun_diblokir");
                exit();
            }
            
            // 3. Jika password cocok, buat session
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['role_id'] = $user['id_role_fk'];
            $_SESSION['status_akun'] = $user['status_akun']; // Simpan status akun di session

            // 4. Redirect berdasarkan role
            if ($user['id_role_fk'] == 1) { // Admin
                header("Location: ../admin/index.php"); 
            } elseif ($user['id_role_fk'] == 2) { // Donatur
                header("Location: ../donatur/index.php"); 
            } elseif ($user['id_role_fk'] == 3) { // Relawan
                header("Location: ../relawan/index.php"); 
            }
            exit();
        }
    }
    
    // Jika user tidak ditemukan atau password salah
    header("Location: ../login.php?error=login_gagal");
    exit();
}

// Router Sederhana di dalam Controller
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'register') {
        handleRegistration();
    } elseif ($_POST['action'] == 'login') {
        handleLogin();
    }
} else {
    header('Location: ../index.php');
    exit();
}
?>