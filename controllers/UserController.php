<?php
require_once realpath(__DIR__ . '/../config/database.php');

function createUser($nama_lengkap, $email, $password, $id_role_fk) {
    global $koneksi;
    
    if(mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email'")) > 0) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=email_sudah_ada");
        exit();
    }
    
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $status_akun = 'Aktif';
    
    $stmt = mysqli_prepare($koneksi, "INSERT INTO users (nama_lengkap, email, password, id_role_fk, status_akun) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sssis', $nama_lengkap, $email, $password_hash, $id_role_fk, $status_akun);
    
    if(mysqli_stmt_execute($stmt)) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?success=user_created");
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=create_failed");
    }
}

function updateUser($id_user, $nama_lengkap, $email, $id_role_fk, $password = null) {
    global $koneksi;
    
    $check_email = mysqli_query($koneksi, "SELECT id_user FROM users WHERE email = '$email' AND id_user != $id_user");
    if(mysqli_num_rows($check_email) > 0) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=email_sudah_ada");
        exit();
    }
    
    if ($password && !empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($koneksi, "UPDATE users SET nama_lengkap = ?, email = ?, password = ?, id_role_fk = ? WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt, 'sssii', $nama_lengkap, $email, $password_hash, $id_role_fk, $id_user);
    } else {
        $stmt = mysqli_prepare($koneksi, "UPDATE users SET nama_lengkap = ?, email = ?, id_role_fk = ? WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt, 'ssii', $nama_lengkap, $email, $id_role_fk, $id_user);
    }
    
    if(mysqli_stmt_execute($stmt)) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?success=user_updated");
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=update_failed");
    }
}

function updateProfile($id_user, $nama_lengkap, $email, $password = null) {
    global $koneksi;
    
    $check_email = mysqli_query($koneksi, "SELECT id_user FROM users WHERE email = '$email' AND id_user != $id_user");
    if(mysqli_num_rows($check_email) > 0) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=email_sudah_ada");
        exit();
    }
    
    if ($password && !empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($koneksi, "UPDATE users SET nama_lengkap = ?, email = ?, password = ? WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt, 'sssi', $nama_lengkap, $email, $password_hash, $id_user);
    } else {
        $stmt = mysqli_prepare($koneksi, "UPDATE users SET nama_lengkap = ?, email = ? WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt, 'ssi', $nama_lengkap, $email, $id_user);
    }
    
    if(mysqli_stmt_execute($stmt)) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?success=profil_updated");
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=update_failed");
    }
}

function deleteUser($id_user) {
    global $koneksi;
    
    mysqli_begin_transaction($koneksi);
    
    try {
        mysqli_query($koneksi, "DELETE FROM partisipasi_kegiatan WHERE id_user_fk = $id_user");
        mysqli_query($koneksi, "DELETE FROM donasi WHERE id_user_donatur_fk = $id_user");
        mysqli_query($koneksi, "DELETE FROM users WHERE id_user = $id_user");
        
        mysqli_commit($koneksi);
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?success=user_deleted");
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=delete_failed");
    }
}

function updateStatusAkun($id_user, $status_akun) {
    global $koneksi;
    
    $allowed_status = ['Aktif', 'Pending', 'Diblokir'];
    if (!in_array($status_akun, $allowed_status)) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=status_invalid");
        exit();
    }
    
    $stmt = mysqli_prepare($koneksi, "UPDATE users SET status_akun = ? WHERE id_user = ?");
    mysqli_stmt_bind_param($stmt, 'si', $status_akun, $id_user);
    
    if(mysqli_stmt_execute($stmt)) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?success=status_updated");
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=status_update_failed");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'create':
            createUser($_POST['nama_lengkap'], $_POST['email'], $_POST['password'], $_POST['id_role_fk']);
            break;
        case 'update':
            updateUser($_POST['id_user'], $_POST['nama_lengkap'], $_POST['email'], $_POST['id_role_fk'], $_POST['password'] ?? null);
            break;
        case 'update_profile':
            updateProfile($_POST['id_user'], $_POST['nama_lengkap'], $_POST['email'], $_POST['password'] ?? null);
            break;
        case 'update_status_akun':
            updateStatusAkun($_POST['id_user'], $_POST['status_akun']);
            break;
        default:
            header("Location: ../admin/kelola_pengguna.php?error=invalid_action");
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete') {
    deleteUser($_GET['id']);
} else {
    header("Location: ../admin/kelola_pengguna.php");
}
?>