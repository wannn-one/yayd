<?php
// controllers/PartisipasiController.php
session_start();
require_once '../config/database.php';

function handleRegisterKegiatan() {
    global $koneksi;

    // Cek keamanan: hanya relawan yang login boleh mendaftar
    if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
        header("Location: ../login.php");
        exit();
    }

    $id_user = $_SESSION['user_id'];
    $id_kegiatan = (int)$_POST['id_kegiatan'];

    // Cek agar tidak mendaftar dua kali (meskipun sudah ada di frontend)
    $sql_cek = "SELECT id_partisipasi FROM partisipasi_kegiatan WHERE id_user_relawan_fk = ? AND id_kegiatan_fk = ?";
    $stmt_cek = mysqli_prepare($koneksi, $sql_cek);
    mysqli_stmt_bind_param($stmt_cek, 'ii', $id_user, $id_kegiatan);
    mysqli_stmt_execute($stmt_cek);
    mysqli_stmt_store_result($stmt_cek);

    if (mysqli_stmt_num_rows($stmt_cek) > 0) {
        header("Location: ../relawan/index.php?error=sudah_terdaftar");
        exit();
    }
    
    // Insert pendaftaran baru
    $sql_insert = "INSERT INTO partisipasi_kegiatan (id_user_relawan_fk, id_kegiatan_fk) VALUES (?, ?)";
    $stmt_insert = mysqli_prepare($koneksi, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, 'ii', $id_user, $id_kegiatan);

    if (mysqli_stmt_execute($stmt_insert)) {
        header("Location: ../relawan/index.php?status=daftar_sukses");
    } else {
        header("Location: ../relawan/index.php?error=gagal_daftar");
    }
    
    mysqli_stmt_close($stmt_cek);
    mysqli_stmt_close($stmt_insert);
    mysqli_close($koneksi);
}


// Router Sederhana
if (isset($_POST['action']) && $_POST['action'] == 'register_kegiatan') {
    handleRegisterKegiatan();
} else {
    header('Location: ../index.php');
}
?>