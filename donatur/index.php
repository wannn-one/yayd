<?php
// donatur/index.php

session_start();

// Cek apakah pengguna sudah login dan apakah rolenya adalah Donatur (ID=2)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    // Jika tidak, tendang ke halaman login
    header("Location: ../login.php?error=akses_ditolak");
    exit();
}

// Jika lolos pengecekan, muat halaman view dashboard
require_once '../views/donatur/dashboard.php';