<?php
require_once realpath(__DIR__ . '/../config/database.php');

session_start();

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php?error=akses_ditolak");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update_profil_yayasan') {
        
        $id = 1;
        $nama_komunitas = mysqli_real_escape_string($koneksi, trim($_POST['nama_komunitas']));
        $visi = mysqli_real_escape_string($koneksi, trim($_POST['visi']));
        $misi = mysqli_real_escape_string($koneksi, trim($_POST['misi']));
        $deskripsi = mysqli_real_escape_string($koneksi, trim($_POST['deskripsi']));
        $alamat_kontak = mysqli_real_escape_string($koneksi, trim($_POST['alamat_kontak']));
        $email_kontak = mysqli_real_escape_string($koneksi, trim($_POST['email_kontak']));
        $telepon_kontak = mysqli_real_escape_string($koneksi, trim($_POST['telepon_kontak']));

        $stmt = mysqli_prepare($koneksi, "
            UPDATE profil_yayd 
            SET nama_komunitas = ?, visi = ?, misi = ?, deskripsi = ?, alamat_kontak = ?, email_kontak = ?, telepon_kontak = ? 
            WHERE id = ?
        ");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'sssssssi', 
                $nama_komunitas, $visi, $misi, $deskripsi, $alamat_kontak, $email_kontak, $telepon_kontak, $id
            );
            
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: ../admin/edit_profil_yayasan.php?status=sukses");
                exit();
            } else {
                mysqli_stmt_close($stmt);
                header("Location: ../admin/edit_profil_yayasan.php?error=update_failed");
                exit();
            }
        } else {
            header("Location: ../admin/edit_profil_yayasan.php?error=prepare_failed");
            exit();
        }
    }
}

// If not POST or action not found, redirect back
header("Location: ../admin/edit_profil_yayasan.php?error=invalid_request");
exit();
?>