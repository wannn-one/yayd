<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

echo '<script>document.body.setAttribute("data-page", "laporan_pdf");</script>';

$query = "SELECT * FROM kegiatan WHERE status = 'Selesai' ORDER BY tanggal_selesai DESC";
$result = mysqli_query($koneksi, $query);
?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>

    <main class="admin-main-content">
        <h2>Kelola Laporan PDF</h2>
        <p>Generate dan kelola laporan dalam format PDF.</p>
        <hr>
        
        <div class="table-container">
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
        </div>
    </main>
</div>