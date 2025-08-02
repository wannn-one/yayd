<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once realpath(__DIR__ . '/../../config/config.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YAYD - Yayasan Anak Yatim Damai</title>
    <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<header>
    <div class="container">
        <nav>
            <a href="<?= BASE_URL; ?>" class="logo">YAYD</a>
            <ul>
                <?php 
                if (isset($_SESSION['user_id'])):
                    $role_id = $_SESSION['role_id'];

                    if ($role_id == 2): ?>
                        <li><a href="<?= BASE_URL; ?>/donatur/index.php">Dasbor</a></li>
                        <li><a href="<?= BASE_URL; ?>/donatur/form_donasi.php" class="btn btn-primary">Buat Donasi</a></li>
                    
                    <?php elseif ($role_id == 3): ?>
                        <li><a href="<?= BASE_URL; ?>/relawan/index.php">Dasbor</a></li>
                        <li><a href="#" class="btn btn-primary">Cari Kegiatan</a></li>

                    <?php elseif ($role_id == 1): ?>
                        <li><a href="<?= BASE_URL; ?>/admin/index.php">Dasbor Admin</a></li>
                    
                    <?php endif; ?>

                    <li><a href="<?= BASE_URL; ?>/profil/index.php">Profil Saya</a></li>

                    <li><a href="<?= BASE_URL; ?>/logout.php" class="btn btn-secondary">Keluar</a></li>

                <?php else: ?>
                    <li><a href="<?= BASE_URL; ?>">Beranda</a></li>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Program</a></li>
                    <li><a href="#" class="btn btn-primary">Bergabung</a></li>
                    <li><a href="<?= BASE_URL; ?>/login.php" class="btn btn-secondary">Masuk</a></li>
                
                <?php endif; ?>

            </ul>
        </nav>
    </div>
</header>

<?php
$current_page = basename($_SERVER['PHP_SELF']);
$main_class = 'main-content';

if (in_array($current_page, ['login.php', 'donatur_daftar.php', 'relawan_daftar.php'])) {
    $main_class .= ' form-page';
} elseif ($current_page === 'index.php') {
    $main_class .= ' home-page';
}
?>

<main class="<?= $main_class ?>">