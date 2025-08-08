<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

$query = "SELECT * FROM kegiatan WHERE status = 'Selesai' ORDER BY tanggal_selesai DESC";
$result = mysqli_query($koneksi, $query);
?>

<div class="container dashboard-container">
    <section class="dashboard-section">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Laporan Kegiatan Selesai</h2>
            <a href="<?= BASE_URL ?>/donatur/index.php" class="btn btn-secondary">&larr; Kembali ke Dashboard</a>
        </div>
        <p>Lihat rekapitulasi donasi untuk setiap kegiatan yang telah selesai dengan mengunduh laporan PDF.</p>
        <hr>
        <table class="table-data">
            <thead>
                <tr>
                    <th>Nama Kegiatan</th>
                    <th>Tanggal Selesai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while($kegiatan = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($kegiatan['nama_kegiatan']) ?></td>
                        <td><?= date('d M Y', strtotime($kegiatan['tanggal_selesai'])) ?></td>
                        <td>
                            <a href="../controllers/LaporanController.php?action=generate_pdf&kegiatan_id=<?= $kegiatan['id_kegiatan'] ?>" class="btn btn-primary btn-sm" target="_blank">
                                <i class="fa fa-file-pdf"></i> Download Laporan
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align: center;">Belum ada laporan kegiatan yang tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>

<?php 
require_once realpath(__DIR__ . '/../templates/footer.php'); 
?>