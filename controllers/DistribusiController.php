<?php
// controllers/DistribusiController.php
session_start();
require_once '../config/database.php';

function handleCreateDistribusi() {
    global $koneksi;
    if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
        header("Location: ../login.php");
        exit();
    }

    $id_kegiatan = !empty($_POST['id_kegiatan_fk']) ? (int)$_POST['id_kegiatan_fk'] : NULL;
    $tanggal_distribusi = $_POST['tanggal_distribusi'];
    $penerima = $_POST['penerima'];
    $deskripsi = $_POST['deskripsi'];
    $nominal = ($_POST['jenis_distribusi'] == 'Uang') ? (float)$_POST['nominal'] : NULL;
    $item_barang = ($_POST['jenis_distribusi'] == 'Barang') ? $_POST['item_barang'] : NULL;
    $dicatat_oleh = $_SESSION['user_id'];
    $dokumentasi_path = NULL;

    // Logika upload file dokumentasi
    if (isset($_FILES['dokumentasi']) && $_FILES['dokumentasi']['error'] == 0) {
        $target_dir = "../assets/uploads/distribusi/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = uniqid() . '-' . basename($_FILES["dokumentasi"]["name"]);
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES["dokumentasi"]["tmp_name"], $target_file)) {
            $dokumentasi_path = "assets/uploads/distribusi/" . $file_name;
        }
    }

    $sql = "INSERT INTO distribusi_donasi (id_kegiatan_fk, tanggal_distribusi, penerima, deskripsi, nominal, item_barang, dokumentasi, dicatat_oleh) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $sql);
    // Tipe data: i(int), s(string), s, s, d(double), s, s, i
    mysqli_stmt_bind_param($stmt, 'isssdssi', $id_kegiatan, $tanggal_distribusi, $penerima, $deskripsi, $nominal, $item_barang, $dokumentasi_path, $dicatat_oleh);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/kelola_distribusi.php?status=create_sukses");
    } else {
        header("Location: ../admin/tambah_distribusi.php?error=gagal");
    }
    exit();
}

function handleDeleteDistribusi() {
    global $koneksi;
    if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1 || !isset($_GET['id'])) {
        header("Location: ../login.php");
        exit();
    }

    $id = (int)$_GET['id'];
    $sql = "DELETE FROM distribusi_donasi WHERE id_distribusi = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/kelola_distribusi.php?status=hapus_sukses");
    } else {
        header("Location: ../admin/kelola_distribusi.php?error=gagal_hapus");
    }
    exit();
}

function handleUpdateDistribusi() {
    global $koneksi;
    if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
        header("Location: ../login.php");
        exit();
    }

    // Ambil semua data dari form
    $id_distribusi = (int)$_POST['id_distribusi'];
    $id_kegiatan = !empty($_POST['id_kegiatan_fk']) ? (int)$_POST['id_kegiatan_fk'] : NULL;
    $tanggal_distribusi = $_POST['tanggal_distribusi'];
    $penerima = $_POST['penerima'];
    $deskripsi = $_POST['deskripsi'];
    $nominal = ($_POST['jenis_distribusi'] == 'Uang') ? (float)$_POST['nominal'] : NULL;
    $item_barang = ($_POST['jenis_distribusi'] == 'Barang') ? $_POST['item_barang'] : NULL;
    $dokumentasi_path = NULL;

    // --- Logika Update Gambar ---
    // 1. Ambil path gambar lama dari database
    $stmt_old_img = mysqli_prepare($koneksi, "SELECT dokumentasi FROM distribusi_donasi WHERE id_distribusi = ?");
    mysqli_stmt_bind_param($stmt_old_img, 'i', $id_distribusi);
    mysqli_stmt_execute($stmt_old_img);
    $old_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_old_img));
    $dokumentasi_path = $old_data['dokumentasi']; // Set path awal ke gambar lama
    mysqli_stmt_close($stmt_old_img);

    // 2. Cek jika ada file baru yang diupload
    if (isset($_FILES['dokumentasi']) && $_FILES['dokumentasi']['error'] == 0) {
        $target_dir = "../assets/uploads/distribusi/";
        // (Anda bisa menambahkan validasi ukuran dan tipe file di sini seperti di fungsi create)
        
        $file_name = uniqid() . '-' . basename($_FILES["dokumentasi"]["name"]);
        $target_file = $target_dir . $file_name;
        
        // Jika upload file baru berhasil, gunakan path baru
        if (move_uploaded_file($_FILES["dokumentasi"]["tmp_name"], $target_file)) {
            // Hapus file lama jika ada untuk menghemat space (opsional tapi bagus)
            if (!empty($dokumentasi_path) && file_exists("../" . $dokumentasi_path)) {
                unlink("../" . $dokumentasi_path);
            }
            $dokumentasi_path = "assets/uploads/distribusi/" . $file_name;
        }
    }
    // Jika tidak ada file baru, $dokumentasi_path akan tetap berisi path gambar yang lama.

    // --- Query UPDATE ---
    $sql = "UPDATE distribusi_donasi SET 
                id_kegiatan_fk = ?, 
                tanggal_distribusi = ?, 
                penerima = ?, 
                deskripsi = ?, 
                nominal = ?, 
                item_barang = ?, 
                dokumentasi = ? 
            WHERE id_distribusi = ?";
            
    $stmt = mysqli_prepare($koneksi, $sql);
    // Tipe data: i(int), s(string), s, s, d(double), s, s, i
    mysqli_stmt_bind_param($stmt, 'isssdssi', $id_kegiatan, $tanggal_distribusi, $penerima, $deskripsi, $nominal, $item_barang, $dokumentasi_path, $id_distribusi);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/kelola_distribusi.php?status=update_sukses");
    } else {
        header("Location: ../admin/edit_distribusi.php?id=$id_distribusi&error=gagal");
    }
    exit();
}


// Router Final di DistribusiController.php
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'create') {
        handleCreateDistribusi();
    } elseif ($_POST['action'] == 'update') {
        handleUpdateDistribusi();
    }
} elseif (isset($_GET['action'])) {
    if ($_GET['action'] == 'delete') {
        handleDeleteDistribusi();
    }
}
?>