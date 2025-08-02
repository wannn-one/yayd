<?php
// yayd/kegiatan_detail.php
session_start();

// Halaman detail bisa dilihat oleh siapa saja yang sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=harus_login");
    exit();
}

// Muat halaman view
require_once 'views/public/kegiatan_detail.php';