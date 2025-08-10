<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

echo '<script>document.body.setAttribute("data-page", "laporan");</script>';

// Query untuk mendapatkan kegiatan yang sudah selesai
$query = "SELECT * FROM kegiatan WHERE status = 'Selesai' ORDER BY tanggal_selesai DESC";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error executing query: " . mysqli_error($koneksi));
}
?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>

    <main class="admin-main-content">
        <div class="page-header">
            <h1>Laporan Donasi Kegiatan</h1>
            <p>Generate laporan donasi untuk kegiatan yang telah selesai</p>
        </div>

        <div class="table-container">
            <table class="table-data">
                <thead>
                    <tr>
                        <th>Nama Kegiatan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($kegiatan = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= htmlspecialchars($kegiatan['nama_kegiatan']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($kegiatan['tanggal_mulai'])) ?></td>
                                <td><?= $kegiatan['tanggal_selesai'] ? date('d/m/Y H:i', strtotime($kegiatan['tanggal_selesai'])) : '-' ?></td>
                                <td><?= htmlspecialchars($kegiatan['lokasi']) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= BASE_URL ?>/controllers/LaporanController.php?action=pdf&id=<?= $kegiatan['id_kegiatan'] ?>" 
                                           class="btn btn-danger btn-sm" target="_blank">
                                            <i class="fa fa-file-pdf"></i> PDF
                                        </a>
                                        <a href="<?= BASE_URL ?>/controllers/LaporanController.php?action=excel&id=<?= $kegiatan['id_kegiatan'] ?>" 
                                           class="btn btn-success btn-sm">
                                            <i class="fa fa-file-excel"></i> Excel
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Belum ada kegiatan yang selesai</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<style>
.page-header {
    margin-bottom: 2rem;
}

.page-header h1 {
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.page-header p {
    color: #7f8c8d;
    margin: 0;
}

.table-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.table-data {
    width: 100%;
    border-collapse: collapse;
}

.table-data th {
    background: #34495e;
    color: white;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
}

.table-data td {
    padding: 1rem;
    border-bottom: 1px solid #ecf0f1;
}

.table-data tr:hover {
    background: #f8f9fa;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    text-align: center;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    transition: all 0.3s ease;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.8rem;
}

.btn-danger {
    background: #e74c3c;
    color: white;
}

.btn-danger:hover {
    background: #c0392b;
    color: white;
}

.btn-success {
    background: #27ae60;
    color: white;
}

.btn-success:hover {
    background: #229954;
    color: white;
}

.text-center {
    text-align: center;
    color: #7f8c8d;
    font-style: italic;
}
</style>

<?php require_once realpath(__DIR__ . '/../templates/footer.php'); ?> 