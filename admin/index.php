<?php
// admin/index.php
session_start();

// Wajib: Cek apakah pengguna sudah login dan rolenya adalah Admin (ID=1)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php?error=akses_ditolak");
    exit();
}

// Muat halaman view dashboard admin
require_once '../views/admin/dashboard.php';