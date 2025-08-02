<?php
// controllers/KegiatanController.php
session_start();
require_once '../config/database.php';

// Fungsi untuk membuat kegiatan baru
function handleCreateKegiatan() {
    global $koneksi;
    if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
        header("Location: ../login.php");
        exit();
    }

    $nama = $_POST['nama_kegiatan'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    // Ambil tanggal selesai, set ke NULL jika kosong
    $tanggal_selesai = !empty($_POST['tanggal_selesai']) ? $_POST['tanggal_selesai'] : NULL;
    $lokasi = $_POST['lokasi'];
    $status = $_POST['status'];
    $dokumentasi_path = "https://placehold.co/600x400/png";

    // Logika upload file, sekarang menggunakan input 'dokumentasi'
    if (isset($_FILES['dokumentasi']) && $_FILES['dokumentasi']['error'] == 0) {
        $target_dir = "../assets/uploads/kegiatan/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = uniqid() . '-' . basename($_FILES["dokumentasi"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ['jpg', 'jpeg', 'png'];
        if ($_FILES["dokumentasi"]["size"] > 2000000) {
            header("Location: ../admin/tambah_kegiatan.php?error=ukuran_terlalu_besar");
            exit();
        }
        if (!in_array($imageFileType, $allowed_types)) {
            header("Location: ../admin/tambah_kegiatan.php?error=tipe_file_salah");
            exit();
        }

        if (move_uploaded_file($_FILES["dokumentasi"]["tmp_name"], $target_file)) {
            $dokumentasi_path = "assets/uploads/kegiatan/" . $file_name;
        }
    }

    // Query SQL sekarang menyertakan 'tanggal_selesai' dan 'dokumentasi'
    $sql = "INSERT INTO kegiatan (nama_kegiatan, deskripsi, tanggal_mulai, tanggal_selesai, lokasi, status, dokumentasi) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 'sssssss', $nama, $deskripsi, $tanggal_mulai, $tanggal_selesai, $lokasi, $status, $dokumentasi_path);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/kelola_kegiatan.php?status=create_sukses");
    } else {
        header("Location: ../admin/tambah_kegiatan.php?error=gagal");
    }
    mysqli_stmt_close($stmt);
}

function handleDeleteKegiatan() {
    global $koneksi;
    if ($_SERVER["REQUEST_METHOD"] != "GET" || !isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
        header("Location: ../login.php");
        exit();
    }

    $id = (int)$_GET['id'];

    $sql = "DELETE FROM kegiatan WHERE id_kegiatan = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/kelola_kegiatan.php?status=hapus_sukses");
    } else {
        header("Location: ../admin/kelola_kegiatan.php?error=gagal_hapus");
    }
    mysqli_stmt_close($stmt);
}

function handleUpdateKegiatan() {
    global $koneksi;
    if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
        header("Location: ../login.php");
        exit();
    }

    $id = (int)$_POST['id_kegiatan'];
    $nama = $_POST['nama_kegiatan'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = !empty($_POST['tanggal_selesai']) ? $_POST['tanggal_selesai'] : NULL;
    $lokasi = $_POST['lokasi'];
    $status = $_POST['status'];

    // Ambil dokumentasi lama dari database
    $stmt_old = mysqli_prepare($koneksi, "SELECT dokumentasi FROM kegiatan WHERE id_kegiatan = ?");
    mysqli_stmt_bind_param($stmt_old, 'i', $id);
    mysqli_stmt_execute($stmt_old);
    $result_old = mysqli_stmt_get_result($stmt_old);
    $old_data = mysqli_fetch_assoc($result_old);
    $dokumentasi_path = $old_data['dokumentasi'];
    mysqli_stmt_close($stmt_old);

    // Jika ada file baru yang diupload
    if (isset($_FILES['dokumentasi']) && $_FILES['dokumentasi']['error'] == 0) {
        $target_dir = "../assets/uploads/kegiatan/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = uniqid() . '-' . basename($_FILES["dokumentasi"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ['jpg', 'jpeg', 'png'];
        if ($_FILES["dokumentasi"]["size"] > 2000000) {
            header("Location: ../admin/edit_kegiatan.php?id=$id&error=ukuran_terlalu_besar");
            exit();
        }
        if (!in_array($imageFileType, $allowed_types)) {
            header("Location: ../admin/edit_kegiatan.php?id=$id&error=tipe_file_salah");
            exit();
        }

        if (move_uploaded_file($_FILES["dokumentasi"]["tmp_name"], $target_file)) {
            // Hapus file lama jika bukan placeholder URL
            if ($dokumentasi_path && !filter_var($dokumentasi_path, FILTER_VALIDATE_URL)) {
                if (file_exists("../" . $dokumentasi_path)) {
                    unlink("../" . $dokumentasi_path);
                }
            }
            $dokumentasi_path = "assets/uploads/kegiatan/" . $file_name;
        }
    }

    // Jika dokumentasi kosong atau NULL, set ke placeholder
    if (empty($dokumentasi_path)) {
        $dokumentasi_path = "https://placehold.co/600x400/png";
    }

    $sql = "UPDATE kegiatan SET nama_kegiatan=?, deskripsi=?, tanggal_mulai=?, tanggal_selesai=?, lokasi=?, status=?, dokumentasi=? WHERE id_kegiatan=?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 'sssssssi', $nama, $deskripsi, $tanggal_mulai, $tanggal_selesai, $lokasi, $status, $dokumentasi_path, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/kelola_kegiatan.php?status=update_sukses");
    } else {
        header("Location: ../admin/edit_kegiatan.php?id=$id&error=gagal");
    }
    mysqli_stmt_close($stmt);
}

// Router sederhana
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'create') {
        handleCreateKegiatan();
    } elseif ($_POST['action'] == 'update') {
        handleUpdateKegiatan();
    }
} elseif (isset($_GET['action'])) {
    if ($_GET['action'] == 'delete') {
        handleDeleteKegiatan();
    }
}
?>