<?php

require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

$user_id = $_SESSION['user_id'];

$query_donasi = "SELECT jumlah_uang, nama_barang, tanggal_donasi, status FROM donasi WHERE id_user_donatur_fk = ? ORDER BY tanggal_donasi DESC";
$stmt_donasi = mysqli_prepare($koneksi, $query_donasi);
mysqli_stmt_bind_param($stmt_donasi, 'i', $user_id);
mysqli_stmt_execute($stmt_donasi);
$result_donasi = mysqli_stmt_get_result($stmt_donasi);

$query_kegiatan_selesai = "SELECT id_kegiatan, nama_kegiatan, deskripsi, tanggal_selesai, dokumentasi FROM kegiatan WHERE status = 'Selesai' ORDER BY tanggal_selesai DESC";
$result_kegiatan_selesai = mysqli_query($koneksi, $query_kegiatan_selesai);

?>

<div class="container dashboard-container">
    
    <section class="dashboard-section">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Riwayat Donasi</h2>
            <a href="<?= BASE_URL ?>/donatur/form_donasi.php" class="btn btn-primary">Buat Donasi Baru</a>
        </div>
        <div class="history-card">
            <div class="history-header">
                <div class="col">Jumlah</div>
                <div class="col">Tanggal</div>
                <div class="col">Status</div>
            </div>

            <?php if (mysqli_num_rows($result_donasi) > 0): ?>
                <?php while($donasi = mysqli_fetch_assoc($result_donasi)): ?>
                    <div class="history-row">
                        <div class="col">
                            <?= !empty($donasi['jumlah_uang']) ? 'Rp ' . number_format($donasi['jumlah_uang']) : htmlspecialchars($donasi['nama_barang']); ?>
                        </div>
                        <div class="col"><?= date('Y-m-d', strtotime($donasi['tanggal_donasi'])) ?></div>
                        <div class="col">
                            <span class="status-chip status-<?= strtolower($donasi['status']) ?>"><?= htmlspecialchars($donasi['status']) ?></span>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 20px; color: #666;">
                    Anda belum memiliki riwayat donasi.
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="dashboard-section">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Laporan Kegiatan</h2>
            <a href="<?= BASE_URL ?>/donatur/laporan_selesai.php" class="btn btn-secondary">Lihat Semua Laporan</a>
        </div>
        
        <div class="activities-grid">
            <?php if (mysqli_num_rows($result_kegiatan_selesai) > 0): ?>
                <?php $count = 0; while (($kegiatan = mysqli_fetch_assoc($result_kegiatan_selesai)) && $count < 3): $count++; ?>
                    <div class="card">
                        <img src="<?= !empty($kegiatan['dokumentasi']) ? (filter_var($kegiatan['dokumentasi'], FILTER_VALIDATE_URL) ? $kegiatan['dokumentasi'] : BASE_URL . '/' . $kegiatan['dokumentasi']) : 'https://placehold.co/600x400/png'; ?>" alt="Gambar Kegiatan">
                        <div class="card-content">
                            <h4><?= htmlspecialchars($kegiatan['nama_kegiatan']); ?></h4>
                            <p><?= htmlspecialchars(substr($kegiatan['deskripsi'], 0, 80)) . '...'; ?></p>
                            <a href="<?= BASE_URL ?>/controllers/LaporanController.php?action=pdf&id=<?= $kegiatan['id_kegiatan'] ?>" class="btn btn-info">Unduh Laporan PDF</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Belum ada kegiatan yang selesai untuk ditampilkan laporannya.</p>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php 
require_once realpath(__DIR__ . '/../templates/footer.php'); 
?>