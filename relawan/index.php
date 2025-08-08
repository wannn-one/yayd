<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: ../login.php?error=akses_ditolak");
    exit();
}

require_once '../views/relawan/dashboard.php';
?>