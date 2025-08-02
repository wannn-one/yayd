<?php
session_start();
require_once '../config/database.php';

function handleDeleteUser() {
    global $koneksi;

    if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1 || !isset($_GET['id'])) {
        header("Location: ../login.php");
        exit();
    }

    $id_user_to_delete = (int)$_GET['id'];

    if ($id_user_to_delete == $_SESSION['user_id']) {
        header("Location: ../admin/kelola_pengguna.php?error=tidak_bisa_hapus_diri_sendiri");
        exit();
    }

    $sql = "DELETE FROM users WHERE id_user = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_user_to_delete);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/kelola_pengguna.php?status=hapus_sukses");
    } else {
        header("Location: ../admin/kelola_pengguna.php?error=gagal_hapus");
    }
    mysqli_stmt_close($stmt);
}

function handleCreateUser() {
    global $koneksi;

    $nama = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $id_role = (int)$_POST['id_role_fk'];
    
    $sql = "INSERT INTO users (nama_lengkap, email, password, id_role_fk) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 'sssi', $nama, $email, $password, $id_role);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/kelola_pengguna.php?status=create_sukses");
    } else {
        header("Location: ../admin/tambah_pengguna.php?error=gagal");
    }
}

function handleUpdateUser() {
    global $koneksi;

    $id_user = (int)$_POST['id_user'];
    $nama = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $id_role = (int)$_POST['id_role_fk'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET nama_lengkap = ?, email = ?, password = ?, id_role_fk = ? WHERE id_user = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, 'sssii', $nama, $email, $password, $id_role, $id_user);
    } else {
        $sql = "UPDATE users SET nama_lengkap = ?, email = ?, id_role_fk = ? WHERE id_user = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, 'ssii', $nama, $email, $id_role, $id_user);
    }

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/kelola_pengguna.php?status=update_sukses");
    } else {
        header("Location: ../admin/edit_pengguna.php?id=$id_user&error=gagal");
    }
}

function handleUpdateProfile() {
    global $koneksi;

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }

    $id_user = $_SESSION['user_id'];

    if ($id_user != (int)$_POST['id_user']) {
        header("Location: ../profil/index.php?error=akses_ditolak");
        exit();
    }

    $nama = $_POST['nama_lengkap'];
    $email = $_POST['email'];

    if (!empty($_POST['password']) && $_POST['password'] == $_POST['konfirmasi_password']) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET nama_lengkap = ?, email = ?, password = ? WHERE id_user = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, 'sssi', $nama, $email, $password, $id_user);
    } else {
        $sql = "UPDATE users SET nama_lengkap = ?, email = ? WHERE id_user = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, 'ssi', $nama, $email, $id_user);
    }

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['nama_lengkap'] = $nama;
        header("Location: ../profil/index.php?status=update_sukses");
    } else {
        header("Location: ../profil/index.php?error=gagal");
    }
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'delete':
            handleDeleteUser();
            break;
        default:
            header("Location: ../admin/index.php");
            break;
    }
} elseif (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'create':
            handleCreateUser();
            break;
        case 'update':
            handleUpdateUser();
            break;
        case 'update_profile':
            handleUpdateProfile();
            break;
        default:
            header("Location: ../admin/index.php");
            break;
    }
} else {
    header("Location: ../admin/index.php");
}
?>