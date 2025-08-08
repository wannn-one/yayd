<?php
require_once realpath(__DIR__ . '/../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update_profil_yayasan') {
        
        $id = 1;
        $nama_yayasan = $_POST['nama_yayasan'];
        $alamat = $_POST['alamat'];
        $nomor_telepon = $_POST['nomor_telepon'];
        $email = $_POST['email'];
        $website = $_POST['website'];
        $visi = $_POST['visi'];
        $misi = $_POST['misi'];

        $stmt = mysqli_prepare($koneksi, "
            UPDATE profil_yayd 
            SET nama_yayasan = ?, alamat = ?, nomor_telepon = ?, email = ?, website = ?, visi = ?, misi = ? 
            WHERE id = ?
        ");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'sssssssi', 
                $nama_yayasan, $alamat, $nomor_telepon, $email, $website, $visi, $misi, $id
            );
            
            if (mysqli_stmt_execute($stmt)) {
                header("Location: ../admin/edit_profil_yayasan.php?success=profil_updated");
            } else {
                header("Location: ../admin/edit_profil_yayasan.php?error=update_failed");
            }
            mysqli_stmt_close($stmt);
        } else {
            header("Location: ../admin/edit_profil_yayasan.php?error=prepare_failed");
        }
    }
}

if (!headers_sent()) {
    header("Location: ../admin/edit_profil_yayasan.php");
}
exit();
?>