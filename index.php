<?php

session_start();
require_once 'config/database.php';
require_once 'views/templates/header.php';

$query_profil = "SELECT visi, misi FROM profil_yayd WHERE id = 1";
$result_profil = mysqli_query($koneksi, $query_profil);
$profil = mysqli_fetch_assoc($result_profil);

$query_kegiatan = "SELECT nama_kegiatan, deskripsi FROM kegiatan WHERE status = 'Akan Datang' ORDER BY tanggal_mulai ASC LIMIT 3";
$result_kegiatan = mysqli_query($koneksi, $query_kegiatan);

?>

<section class="hero">
    <div class="container">
        <h1>Memberdayakan Komunitas Melalui Aksi Bersama</h1>
        <p>Bergabunglah dengan kami untuk membuat perubahan. Berikan waktu sebagai relawan atau donasi untuk mendukung program kami.</p>
        <div>
            <a href="<?= BASE_URL; ?>/pilih_peran.php" class="btn btn-primary">Bergabung dengan Kami</a>
            <a href="<?= BASE_URL; ?>/login.php" class="btn btn-secondary">Masuk</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <h2 class="section-title">Profil Komunitas Kami</h2>
        <div>
            <h3>Visi Kami:</h3>
            <p><?= htmlspecialchars($profil['visi'] ?? 'Visi belum diatur.'); ?></p>
        </div>
        <div>
            <h3>Misi Kami:</h3>
            <p><?= htmlspecialchars($profil['misi'] ?? 'Misi belum diatur.'); ?></p>
        </div>
    </div>
</section>

<section class="section" style="background-color: #f8f9fa;">
    <div class="container">
        <h2 class="section-title">Kegiatan Mendatang</h2>
        <div class="activities-grid">

            <?php if (mysqli_num_rows($result_kegiatan) > 0): ?>
                <?php while ($kegiatan = mysqli_fetch_assoc($result_kegiatan)): ?>
                    <div class="card">
                        <img src="<?= !empty($kegiatan['dokumentasi']) ? $kegiatan['dokumentasi'] : 'https://placehold.co/600x400/png'; ?>" alt="<?= htmlspecialchars($kegiatan['nama_kegiatan']); ?>">
                        <div class="card-content">
                            <h3><?= htmlspecialchars($kegiatan['nama_kegiatan']); ?></h3>
                            <p><?= htmlspecialchars(substr($kegiatan['deskripsi'], 0, 100)) . '...'; ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Belum ada kegiatan yang akan datang saat ini. Silakan cek kembali nanti.</p>
            <?php endif; ?>

        </div>
    </div>
</section>


<?php
require_once 'views/templates/footer.php';
?>