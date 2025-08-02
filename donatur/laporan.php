<?php
// donatur/laporan.php
session_start();

// Keamanan: Hanya Donatur (role_id = 2) yang sudah login boleh masuk
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) { 
    header("Location: ../login.php?error=akses_ditolak"); 
    exit(); 
}
require_once '../views/donatur/laporan.php';