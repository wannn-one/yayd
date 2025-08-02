<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

$query = "SELECT d.*, u.nama_lengkap 
          FROM donasi d
          JOIN users u ON d.id_user_donatur_fk = u.id_user
          ORDER BY d.tanggal_donasi DESC";
$result = mysqli_query($koneksi, $query);
?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>

    <main class="admin-main-content">
        <h2>Manajemen Donasi</h2>
        <p>Lihat dan verifikasi semua donasi yang masuk.</p>
        <hr>

        <table class="table-data">
            <thead>
                <tr>
                    <th>Donatur</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Jumlah / Barang</th>
                    <th>Bukti</th>
                    <th>Status</th>
                    <th>Aksi (Ubah Status)</th>
                </tr>
            </thead>
            <tbody>
                <?php while($donasi = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($donasi['nama_lengkap']) ?></td>
                    <td><?= date('d M Y H:i', strtotime($donasi['tanggal_donasi'])) ?></td>
                    <td><?= htmlspecialchars($donasi['jenis_donasi']) ?></td>
                    <td>
                        <?php 
                        if ($donasi['jenis_donasi'] == 'Uang') {
                            echo 'Rp ' . number_format($donasi['jumlah_uang']);
                        } else {
                            echo htmlspecialchars($donasi['nama_barang']);
                        }
                        ?>
                    </td>
                    <td>
                        <?php if(!empty($donasi['bukti_pembayaran'])): ?>
                            <a href="<?= BASE_URL ?>/<?= htmlspecialchars($donasi['bukti_pembayaran']) ?>" target="_blank" class="btn btn-secondary btn-sm">Lihat</a>
                        <?php else: ?>
                            <span class="text-muted">Tidak ada</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="status-chip status-<?= strtolower($donasi['status']) ?>">
                            <?= htmlspecialchars($donasi['status']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($donasi['status'] == 'Pending'): ?>
                            <a href="../controllers/DonasiController.php?action=update_status&id=<?= $donasi['id_donasi'] ?>&status=Diterima" 
                               class="btn btn-success btn-sm" 
                               onclick="return confirm('Terima donasi ini?');">
                               Terima
                            </a>
                            <a href="../controllers/DonasiController.php?action=update_status&id=<?= $donasi['id_donasi'] ?>&status=Ditolak" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Tolak donasi ini?');">
                               Tolak
                            </a>
                        <?php else: ?>
                            <span class="text-muted">Sudah diproses</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</div>

<?php require_once realpath(__DIR__ . '/../templates/footer.php'); ?>