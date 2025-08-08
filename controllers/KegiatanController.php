<?php
require_once realpath(__DIR__ . '/../config/database.php');

function createKegiatan() {
    global $koneksi;
    
    $nama = $_POST['nama_kegiatan'];
    $deskripsi = $_POST['deskripsi'];
    $lokasi = $_POST['lokasi'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = empty($_POST['tanggal_selesai']) ? null : $_POST['tanggal_selesai'];
    $status = $_POST['status'];

    $dokumentasi_url = 'https://placehold.co/400x300/png';
    
    if (isset($_FILES['dokumentasi']) && $_FILES['dokumentasi']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = $_FILES['dokumentasi']['type'];
        $file_size = $_FILES['dokumentasi']['size'];
        $max_size = 5 * 1024 * 1024;
        
        if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
            $upload_dir = realpath(__DIR__ . '/../assets/uploads/kegiatan/');
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_extension = pathinfo($_FILES['dokumentasi']['name'], PATHINFO_EXTENSION);
            $unique_name = uniqid('kegiatan_', true) . '.' . $file_extension;
            $upload_path = $upload_dir . '/' . $unique_name;
            
            if (move_uploaded_file($_FILES['dokumentasi']['tmp_name'], $upload_path)) {
                $dokumentasi_url = 'assets/uploads/kegiatan/' . $unique_name;
            }
        }
    }
    
    $stmt = mysqli_prepare($koneksi, "INSERT INTO kegiatan (nama_kegiatan, deskripsi, lokasi, tanggal_mulai, tanggal_selesai, status, dokumentasi) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sssssss', $nama, $deskripsi, $lokasi, $tanggal_mulai, $tanggal_selesai, $status, $dokumentasi_url);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/kelola_kegiatan.php?success=kegiatan_created");
    } else {
        header("Location: ../admin/tambah_kegiatan.php?error=create_failed");
    }
    
    mysqli_stmt_close($stmt);
}

function updateKegiatan() {
    global $koneksi;
    
    $id = (int)$_POST['id_kegiatan'];
    $nama = $_POST['nama_kegiatan'];
    $deskripsi = $_POST['deskripsi'];
    $lokasi = $_POST['lokasi'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = empty($_POST['tanggal_selesai']) ? null : $_POST['tanggal_selesai'];
    $status = $_POST['status'];
    
    $stmt_old = mysqli_prepare($koneksi, "SELECT dokumentasi FROM kegiatan WHERE id_kegiatan = ?");
    mysqli_stmt_bind_param($stmt_old, 'i', $id);
    mysqli_stmt_execute($stmt_old);
    $result_old = mysqli_stmt_get_result($stmt_old);
    $old_data = mysqli_fetch_assoc($result_old);
    $dokumentasi_url = $old_data['dokumentasi'];
    
    if (isset($_FILES['dokumentasi']) && $_FILES['dokumentasi']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = $_FILES['dokumentasi']['type'];
        $file_size = $_FILES['dokumentasi']['size'];
        $max_size = 5 * 1024 * 1024;
        
        if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
            $upload_dir = realpath(__DIR__ . '/../assets/uploads/kegiatan/');
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_extension = pathinfo($_FILES['dokumentasi']['name'], PATHINFO_EXTENSION);
            $unique_name = uniqid('kegiatan_', true) . '.' . $file_extension;
            $upload_path = $upload_dir . '/' . $unique_name;
            
            if (move_uploaded_file($_FILES['dokumentasi']['tmp_name'], $upload_path)) {
                if ($old_data['dokumentasi'] && 
                    $old_data['dokumentasi'] !== 'https://placehold.co/400x300/png' && 
                    file_exists(__DIR__ . '/../' . $old_data['dokumentasi'])) {
                    unlink(__DIR__ . '/../' . $old_data['dokumentasi']);
                }
                
                $dokumentasi_url = 'assets/uploads/kegiatan/' . $unique_name;
            }
        }
    }
    
    if (empty($dokumentasi_url)) {
        $dokumentasi_url = 'https://placehold.co/400x300/png';
    }
    
    $stmt = mysqli_prepare($koneksi, "UPDATE kegiatan SET nama_kegiatan = ?, deskripsi = ?, lokasi = ?, tanggal_mulai = ?, tanggal_selesai = ?, status = ?, dokumentasi = ? WHERE id_kegiatan = ?");
    mysqli_stmt_bind_param($stmt, 'sssssssi', $nama, $deskripsi, $lokasi, $tanggal_mulai, $tanggal_selesai, $status, $dokumentasi_url, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/kelola_kegiatan.php?success=kegiatan_updated");
    } else {
        header("Location: ../admin/edit_kegiatan.php?id=$id&error=update_failed");
    }
    
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt_old);
}

function deleteKegiatan() {
    global $koneksi;
    
    $id = (int)$_GET['id'];
    
    mysqli_begin_transaction($koneksi);
    
    try {
        mysqli_query($koneksi, "DELETE FROM partisipasi_kegiatan WHERE id_kegiatan_fk = $id");
        mysqli_query($koneksi, "DELETE FROM donasi WHERE id_kegiatan_fk = $id");
        mysqli_query($koneksi, "DELETE FROM distribusi_donasi WHERE id_kegiatan_fk = $id");
        mysqli_query($koneksi, "DELETE FROM kegiatan WHERE id_kegiatan = $id");
        
        mysqli_commit($koneksi);
        header("Location: ../admin/kelola_kegiatan.php?success=kegiatan_deleted");
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        header("Location: ../admin/kelola_kegiatan.php?error=delete_failed");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'create':
            createKegiatan();
            break;
        case 'update':
            updateKegiatan();
            break;
        default:
            header("Location: ../admin/kelola_kegiatan.php?error=invalid_action");
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete') {
    deleteKegiatan();
} else {
    header("Location: ../admin/kelola_kegiatan.php");
}
exit();
?>