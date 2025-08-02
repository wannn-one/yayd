<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

// Query untuk mengambil kegiatan yang sudah selesai
$query = "SELECT id_kegiatan, nama_kegiatan, tanggal_selesai FROM kegiatan WHERE status = 'Selesai' ORDER BY tanggal_selesai DESC";
$result = mysqli_query($koneksi, $query);
?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>

    <main class="admin-main-content">
        <h2>Laporan Keuangan per Kegiatan</h2>
        <p>Pilih kegiatan yang telah selesai untuk mengunduh laporan donasi dalam format PDF.</p>
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
                                <i class="fa fa-file-pdf"></i> Download PDF
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align: center;">Belum ada kegiatan yang selesai.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>