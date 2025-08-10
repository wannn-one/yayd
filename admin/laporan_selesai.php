<?php
// admin/laporan_selesai.php
session_start();

// Keamanan: Pastikan yang mengakses adalah admin yang sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) { 
    header("Location: ../login.php?error=akses_ditolak"); 
    exit(); 
}

// Muat halaman view laporan admin
require_once '../views/admin/laporan_selesai.php';
?> 