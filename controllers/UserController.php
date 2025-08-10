<?php
require_once realpath(__DIR__ . '/../config/database.php');

function createUser($nama_lengkap, $email, $password, $id_role_fk) {
    global $koneksi;
    
    // Sanitize input
    $nama_lengkap = mysqli_real_escape_string($koneksi, trim($nama_lengkap));
    $email = mysqli_real_escape_string($koneksi, trim($email));
    $id_role_fk = (int)$id_role_fk;
    
    // Check if email already exists using prepared statement
    $stmt_check = mysqli_prepare($koneksi, "SELECT id_user FROM users WHERE email = ?");
    if (!$stmt_check) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=database_error");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt_check, 's', $email);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);
    
    if(mysqli_num_rows($result_check) > 0) {
        mysqli_stmt_close($stmt_check);
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=email_sudah_ada");
        exit();
    }
    mysqli_stmt_close($stmt_check);
    
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $status_akun = 'Aktif';
    
    $stmt = mysqli_prepare($koneksi, "INSERT INTO users (nama_lengkap, email, password, id_role_fk, status_akun) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=database_error");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, 'sssis', $nama_lengkap, $email, $password_hash, $id_role_fk, $status_akun);
    
    if(mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?success=user_created");
    } else {
        mysqli_stmt_close($stmt);
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=create_failed");
    }
    exit();
}

function updateUser($id_user, $nama_lengkap, $email, $id_role_fk, $password = null) {
    global $koneksi;
    
    // Sanitize input
    $id_user = (int)$id_user;
    $nama_lengkap = mysqli_real_escape_string($koneksi, trim($nama_lengkap));
    $email = mysqli_real_escape_string($koneksi, trim($email));
    $id_role_fk = (int)$id_role_fk;
    
    // Check if email already exists for other users using prepared statement
    $stmt_check = mysqli_prepare($koneksi, "SELECT id_user FROM users WHERE email = ? AND id_user != ?");
    if (!$stmt_check) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=database_error");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt_check, 'si', $email, $id_user);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);
    
    if(mysqli_num_rows($result_check) > 0) {
        mysqli_stmt_close($stmt_check);
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=email_sudah_ada");
        exit();
    }
    mysqli_stmt_close($stmt_check);
    
    if ($password && !empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($koneksi, "UPDATE users SET nama_lengkap = ?, email = ?, password = ?, id_role_fk = ? WHERE id_user = ?");
        if (!$stmt) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=database_error");
            exit();
        }
        mysqli_stmt_bind_param($stmt, 'sssii', $nama_lengkap, $email, $password_hash, $id_role_fk, $id_user);
    } else {
        $stmt = mysqli_prepare($koneksi, "UPDATE users SET nama_lengkap = ?, email = ?, id_role_fk = ? WHERE id_user = ?");
        if (!$stmt) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=database_error");
            exit();
        }
        mysqli_stmt_bind_param($stmt, 'ssii', $nama_lengkap, $email, $id_role_fk, $id_user);
    }
    
    if(mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?success=user_updated");
    } else {
        mysqli_stmt_close($stmt);
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=update_failed");
    }
    exit();
}

function updateProfile($id_user, $nama_lengkap, $email, $nomor_telepon = null, $password = null, $konfirmasi_password = null) {
    global $koneksi;
    
    // Sanitize input data
    $id_user = (int)$id_user;
    $nama_lengkap = mysqli_real_escape_string($koneksi, trim($nama_lengkap));
    $email = mysqli_real_escape_string($koneksi, trim($email));
    $nomor_telepon = $nomor_telepon ? mysqli_real_escape_string($koneksi, trim($nomor_telepon)) : null;
    
    // Validate password confirmation if password is provided
    if (!empty($password) && $password !== $konfirmasi_password) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=password_tidak_cocok");
        exit();
    }
    
    // Check if email already exists for other users using prepared statement
    $stmt_check = mysqli_prepare($koneksi, "SELECT id_user FROM users WHERE email = ? AND id_user != ?");
    if (!$stmt_check) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=database_error");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt_check, 'si', $email, $id_user);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);
    
    if (mysqli_num_rows($result_check) > 0) {
        mysqli_stmt_close($stmt_check);
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=email_sudah_ada");
        exit();
    }
    mysqli_stmt_close($stmt_check);
    
    // Update user profile
    if ($password && !empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($koneksi, "UPDATE users SET nama_lengkap = ?, email = ?, nomor_telepon = ?, password = ? WHERE id_user = ?");
        if (!$stmt) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=database_error");
            exit();
        }
        mysqli_stmt_bind_param($stmt, 'ssssi', $nama_lengkap, $email, $nomor_telepon, $password_hash, $id_user);
    } else {
        $stmt = mysqli_prepare($koneksi, "UPDATE users SET nama_lengkap = ?, email = ?, nomor_telepon = ? WHERE id_user = ?");
        if (!$stmt) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=database_error");
            exit();
        }
        mysqli_stmt_bind_param($stmt, 'sssi', $nama_lengkap, $email, $nomor_telepon, $id_user);
    }
    
    if(mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?status=update_sukses");
    } else {
        mysqli_stmt_close($stmt);
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=update_failed");
    }
    exit();
}

function deleteUser($id_user) {
    global $koneksi;
    
    $id_user = (int)$id_user;
    
    mysqli_begin_transaction($koneksi);
    
    try {
        // Use prepared statements for security
        $stmt1 = mysqli_prepare($koneksi, "DELETE FROM partisipasi_kegiatan WHERE id_user_relawan_fk = ?");
        mysqli_stmt_bind_param($stmt1, 'i', $id_user);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_close($stmt1);
        
        $stmt2 = mysqli_prepare($koneksi, "DELETE FROM donasi WHERE id_user_donatur_fk = ?");
        mysqli_stmt_bind_param($stmt2, 'i', $id_user);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);
        
        $stmt3 = mysqli_prepare($koneksi, "DELETE FROM users WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt3, 'i', $id_user);
        mysqli_stmt_execute($stmt3);
        mysqli_stmt_close($stmt3);
        
        mysqli_commit($koneksi);
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?success=user_deleted");
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=delete_failed");
    }
    exit();
}

function updateStatusAkun($id_user, $status_akun) {
    global $koneksi;
    
    $id_user = (int)$id_user;
    $status_akun = mysqli_real_escape_string($koneksi, trim($status_akun));
    
    $allowed_status = ['Aktif', 'Pending', 'Diblokir'];
    if (!in_array($status_akun, $allowed_status)) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=status_invalid");
        exit();
    }
    
    $stmt = mysqli_prepare($koneksi, "UPDATE users SET status_akun = ? WHERE id_user = ?");
    if (!$stmt) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=database_error");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, 'si', $status_akun, $id_user);
    
    if(mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?success=status_updated");
    } else {
        mysqli_stmt_close($stmt);
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=status_update_failed");
    }
    exit();
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
            updateProfile(
                $_POST['id_user'], 
                $_POST['nama_lengkap'], 
                $_POST['email'], 
                $_POST['nomor_telepon'] ?? null, 
                $_POST['password'] ?? null, 
                $_POST['konfirmasi_password'] ?? null
            );
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