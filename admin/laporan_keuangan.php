<?php
// admin/laporan_keuangan.php
session_start();

// Keamanan: Pastikan yang mengakses adalah admin yang sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) { 
    // Jika tidak, tendang ke halaman login
    header("Location: ../login.php?error=akses_ditolak"); 
    exit(); 
}

// Jika aman, muat file view yang sebenarnya
require_once '../views/admin/laporan_keuangan.php';