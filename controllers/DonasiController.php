<?php
// controllers/DonasiController.php
session_start();
require_once '../config/database.php';

function handleCreateDonation() {
    global $koneksi;

    // Security check: pastikan user adalah donatur yang login
    if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
        header("Location: ../login.php");
        exit();
    }

    // Check if donatur account is pending
    if (isset($_SESSION['status_akun']) && $_SESSION['status_akun'] == 'Pending') {
        header("Location: ../donatur/index.php?error=akun_pending");
        exit();
    }

    // Ambil data dari form
    $id_user_donatur = $_SESSION['user_id'];
    $jenis_donasi = $_POST['jenis_donasi'];
    $id_kegiatan = !empty($_POST['id_kegiatan_fk']) ? (int)$_POST['id_kegiatan_fk'] : NULL;
    
    $jumlah_uang = NULL;
    $nama_barang = NULL;
    $deskripsi_barang = NULL;
    $metode = NULL;
    $bukti_pembayaran_path = NULL;

    if ($jenis_donasi == 'Uang') {
        $jumlah_uang = (float)$_POST['jumlah_uang'];
        $metode = $_POST['metode_pembayaran'];

        // Proses upload file bukti pembayaran jika ada
        if (isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] == 0) {
            $target_dir = "../assets/uploads/bukti_transfer/";
            // Buat nama file unik untuk mencegah tumpang tindih
            $file_name = uniqid() . '-' . basename($_FILES["bukti_pembayaran"]["name"]);
            $target_file = $target_dir . $file_name;
            
            // Pindahkan file yang di-upload ke direktori tujuan
            if (move_uploaded_file($_FILES["bukti_pembayaran"]["tmp_name"], $target_file)) {
                $bukti_pembayaran_path = "assets/uploads/bukti_transfer/" . $file_name;
            }
        }
    } elseif ($jenis_donasi == 'Barang') {
        $nama_barang = $_POST['nama_barang'];
        $deskripsi_barang = $_POST['deskripsi_barang'];
        $metode = $_POST['metode'];
    }

    // Simpan ke database menggunakan prepared statements
    $sql = "INSERT INTO donasi (id_user_donatur_fk, id_kegiatan_fk, jenis_donasi, jumlah_uang, nama_barang, deskripsi_barang, metode, bukti_pembayaran, status, tanggal_donasi) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())";
    
    $stmt = mysqli_prepare($koneksi, $sql);
    // Tipe data: i(int), i(int), s(string), d(double), s, s, s, s
    mysqli_stmt_bind_param($stmt, 'iisdssss', $id_user_donatur, $id_kegiatan, $jenis_donasi, $jumlah_uang, $nama_barang, $deskripsi_barang, $metode, $bukti_pembayaran_path);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../donatur/index.php?status=donasi_sukses");
    } else {
        header("Location: ../donatur/form_donasi.php?error=gagal");
    }
    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
}

function handleUpdateStatus() {
    global $koneksi;

    // Security check: hanya admin yang boleh
    if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
        header("Location: ../login.php");
        exit();
    }

    $id_donasi = (int)$_POST['id_donasi'];
    $status = $_POST['status'];

    // Validasi status untuk keamanan
    $allowed_statuses = ['Pending', 'Diterima', 'Ditolak'];
    if (!in_array($status, $allowed_statuses)) {
        header("Location: ../views/admin/kelola_donasi.php?error=status_tidak_valid");
        exit();
    }

    $sql = "UPDATE donasi SET status = ? WHERE id_donasi = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $status, $id_donasi);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../views/admin/kelola_donasi.php?status=update_sukses");
    } else {
        header("Location: ../views/admin/kelola_donasi.php?error=gagal_update");
    }
    mysqli_stmt_close($stmt);
}

// Router sederhana
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'create') {
        handleCreateDonation();
    } elseif ($_POST['action'] == 'update_status') {
        handleUpdateStatus();
    }
}
?>