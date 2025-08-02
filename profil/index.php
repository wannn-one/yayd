<?php
// profil/index.php
session_start();

// Siapapun yang sudah login bisa mengakses halaman ini
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php?error=harus_login");
    exit();
}

// Muat halaman view
require_once '../views/profil/index.php';