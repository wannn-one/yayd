<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

// 1. Ambil ID kegiatan dari URL dan pastikan valid
if (!isset($_GET['id'])) {
    header("Location: " . BASE_URL); // Kembali ke home jika tidak ada ID
    exit();
}
$kegiatan_id = (int)$_GET['id'];

// 2. Ambil semua data kegiatan dari database
$stmt_kegiatan = mysqli_prepare($koneksi, "SELECT * FROM kegiatan WHERE id_kegiatan = ?");
mysqli_stmt_bind_param($stmt_kegiatan, 'i', $kegiatan_id);
mysqli_stmt_execute($stmt_kegiatan);
$result_kegiatan = mysqli_stmt_get_result($stmt_kegiatan);
$kegiatan = mysqli_fetch_assoc($result_kegiatan);

// Jika kegiatan dengan ID tersebut tidak ditemukan, kembali ke home
if (!$kegiatan) {
    header("Location: " . BASE_URL);
    exit();
}

// 3. Cek apakah user adalah relawan & sudah terdaftar di kegiatan ini
$sudah_terdaftar = false;
if ($_SESSION['role_id'] == 3) { // Cek jika rolenya adalah Relawan
    $user_id = $_SESSION['user_id'];
    $stmt_cek = mysqli_prepare($koneksi, "SELECT id_partisipasi FROM partisipasi_kegiatan WHERE id_user_relawan_fk = ? AND id_kegiatan_fk = ?");
    mysqli_stmt_bind_param($stmt_cek, 'ii', $user_id, $kegiatan_id);
    mysqli_stmt_execute($stmt_cek);
    mysqli_stmt_store_result($stmt_cek);
    if (mysqli_stmt_num_rows($stmt_cek) > 0) {
        $sudah_terdaftar = true;
    }
    mysqli_stmt_close($stmt_cek);
}
?>

<div class="container dashboard-container">
    <a href="<?= BASE_URL ?>/relawan/index.php" class="btn btn-secondary">&larr; Kembali ke Beranda</a>

    <div class="detail-wrapper">
        <div class="detail-image">
            <img src="<?= !empty($kegiatan['dokumentasi']) ? $kegiatan['dokumentasi'] : 'https://placehold.co/600x400/png'; ?>" alt="Activity Image">
        </div>
        <div class="detail-info">
            <h1><?= htmlspecialchars($kegiatan['nama_kegiatan']) ?></h1>
            <div class="detail-meta">
                <span><strong>Lokasi:</strong> <?= htmlspecialchars($kegiatan['lokasi']) ?></span>
                <span><strong>Tanggal:</strong> <?= date('d F Y, H:i', strtotime($kegiatan['tanggal_mulai'])) ?> WIB</span>
            </div>
            <p><?= nl2br(htmlspecialchars($kegiatan['deskripsi'])) ?></p>

            <?php if ($_SESSION['role_id'] == 3): // Tampilkan tombol hanya untuk Relawan ?>
                <?php if ($sudah_terdaftar): ?>
                    <button class="btn btn-secondary" disabled>Anda Sudah Terdaftar</button>
                <?php else: ?>
                    <form action="<?= BASE_URL ?>/controllers/PartisipasiController.php" method="POST">
                        <input type="hidden" name="action" value="register_kegiatan">
                        <input type="hidden" name="id_kegiatan" value="<?= $kegiatan['id_kegiatan'] ?>">
                        <button type="submit" class="btn btn-primary">Daftar Sekarang</button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
mysqli_stmt_close($stmt_kegiatan);
require_once realpath(__DIR__ . '/../templates/footer.php'); 
?>