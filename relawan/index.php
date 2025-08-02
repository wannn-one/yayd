<?php
// relawan/index.php
session_start();

// Cek apakah pengguna sudah login dan rolenya adalah Relawan (ID=3)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: ../login.php?error=akses_ditolak");
    exit();
}

// Muat halaman view dashboard
require_once '../views/relawan/dashboard.php';