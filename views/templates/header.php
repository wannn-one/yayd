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
    <title>Yho Akhirat Yo Dunyo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<header>
    <div class="container">
        <nav>
            <div class="logo">
                <a href="<?php 
                    if (isset($_SESSION['user_id']) && isset($_SESSION['role_id'])) {
                        // Jika sudah login, arahkan ke dashboard sesuai role
                        if ($_SESSION['role_id'] == 1) {
                            echo BASE_URL . '/views/admin/dashboard.php';
                        } elseif ($_SESSION['role_id'] == 2) {
                            echo BASE_URL . '/views/donatur/dashboard.php';
                        } elseif ($_SESSION['role_id'] == 3) {
                            echo BASE_URL . '/views/relawan/dashboard.php';
                        } else {
                            echo BASE_URL;
                        }
                    } else {
                        // Jika belum login, ke halaman utama
                        echo BASE_URL;
                    }
                ?>">
                    <img src="<?= BASE_URL; ?>/assets/images/YAYD_LOGO_NO_BG.png" alt="Logo YAYD" style="width: 20%; height: auto;">
                </a>
            </div>
            
            <button class="burger-menu" id="burgerMenu" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div class="nav-menu" id="navMenu">
                <ul>
                    <?php 
                    if (isset($_SESSION['user_id'])): $role_id = $_SESSION['role_id'];

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
                        <li><a href="<?= BASE_URL; ?>/pilih_peran.php" class="btn btn-primary">Bergabung</a></li>
                        <li><a href="<?= BASE_URL; ?>/login.php" class="btn btn-secondary">Masuk</a></li>
                    
                    <?php endif; ?>

                </ul>
            </div>
        </nav>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const burgerMenu = document.getElementById('burgerMenu');
    const navMenu = document.getElementById('navMenu');

    const adminToggle = document.getElementById('adminToggle');
    const adminSidebar = document.getElementById('adminSidebar');

    function closeBurgerMenu() {
        burgerMenu.classList.remove('active');
        navMenu.classList.remove('active');
    }

    if (adminToggle) {
        adminToggle.addEventListener('click', function() {
            if (window.innerWidth <= 768 && navMenu.classList.contains('active')) {
                closeBurgerMenu();
            }
        });
    }

    burgerMenu.addEventListener('click', function() {
        burgerMenu.classList.toggle('active');
        navMenu.classList.toggle('active');
        
        if (navMenu.classList.contains('active')) {
            document.dispatchEvent(new CustomEvent('burgerMenuOpened'));
        }
    });

    const navLinks = navMenu.querySelectorAll('a');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            closeBurgerMenu();
        });
    });

    document.addEventListener('click', function(e) {
        if (!burgerMenu.contains(e.target) && !navMenu.contains(e.target)) {
            closeBurgerMenu();
        }
    });

    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeBurgerMenu();
        }
    });

    document.addEventListener('adminSidebarOpened', function() {
        if (window.innerWidth <= 768 && navMenu.classList.contains('active')) {
            closeBurgerMenu();
        }
    });
});
</script>

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