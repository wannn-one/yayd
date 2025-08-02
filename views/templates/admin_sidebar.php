<?php

$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="admin-sidebar">
    <div class="sidebar-header">
        <a href="<?= BASE_URL ?>/admin/index.php" class="logo">YAYD</a>
        <span>Admin</span>
    </div>
    <ul class="sidebar-nav">
        <li class="<?= ($current_page == 'index.php') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/index.php"><i class="fa fa-tachometer-alt"></i> Dasbor</a>
        </li>
        
        <li class="<?= ($current_page == 'kelola_donasi.php') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/kelola_donasi.php"><i class="fa fa-donate"></i> Kelola Donasi</a>
        </li>

        <li class="<?= ($current_page == 'kelola_pengguna.php') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/kelola_pengguna.php"><i class="fa fa-users"></i> Kelola Pengguna</a>
        </li>

        <li class="<?= ($current_page == 'kelola_kegiatan.php') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/kelola_kegiatan.php"><i class="fa fa-calendar-alt"></i> Kelola Kegiatan</a>
        </li>

        <li class="<?= ($current_page == 'laporan.php') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/laporan.php"><i class="fa fa-chart-line"></i> Kelola Laporan</a>
        </li>
    </ul>
</aside>