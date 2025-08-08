<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

$pemasukan_res = mysqli_query($koneksi, "SELECT SUM(jumlah_uang) as total FROM donasi WHERE status = 'Diterima' AND jenis_donasi = 'Uang'");
$total_pemasukan = mysqli_fetch_assoc($pemasukan_res)['total'] ?? 0;
$pengeluaran_res = mysqli_query($koneksi, "SELECT SUM(nominal) as total FROM distribusi_donasi WHERE nominal IS NOT NULL");
$total_pengeluaran = mysqli_fetch_assoc($pengeluaran_res)['total'] ?? 0;
$saldo_akhir = $total_pemasukan - $total_pengeluaran;

$limit = 15;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$total_data_query = "SELECT COUNT(*) FROM ((SELECT tanggal_donasi as tanggal FROM donasi d JOIN users u ON d.id_user_donatur_fk = u.id_user WHERE d.status = 'Diterima' AND d.jenis_donasi = 'Uang' AND d.jumlah_uang > 0) UNION ALL (SELECT tanggal_distribusi as tanggal FROM distribusi_donasi WHERE nominal IS NOT NULL AND nominal > 0)) as total_transaksi";
$total_result = mysqli_query($koneksi, $total_data_query);
$total_rows = mysqli_fetch_row($total_result)[0];
$total_pages = ceil($total_rows / $limit);

$query_transaksi = "(SELECT tanggal_donasi as tanggal, CONCAT('Donasi dari ', u.nama_lengkap) as deskripsi, jumlah_uang as debit, 0 as kredit FROM donasi d JOIN users u ON d.id_user_donatur_fk = u.id_user WHERE d.status = 'Diterima' AND d.jenis_donasi = 'Uang' AND d.jumlah_uang > 0) UNION ALL (SELECT tanggal_distribusi as tanggal, deskripsi, 0 as debit, nominal as kredit FROM distribusi_donasi WHERE nominal IS NOT NULL AND nominal > 0) ORDER BY tanggal ASC LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($koneksi, $query_transaksi);
mysqli_stmt_bind_param($stmt, 'ii', $limit, $offset);
mysqli_stmt_execute($stmt);
$result_transaksi = mysqli_stmt_get_result($stmt);
?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>

    <main class="admin-main-content">
        <h2>Laporan Keuangan</h2>
        <p>Pratinjau laporan keuangan lengkap (pemasukan dan pengeluaran).</p>
        <hr>

        <div class="summary-cards">
            <div class="summary-card">
                <h4>Total Pemasukan</h4>
                <p>Rp <?= number_format($total_pemasukan) ?></p>
            </div>
            <div class="summary-card">
                <h4>Total Pengeluaran</h4>
                <p>Rp <?= number_format($total_pengeluaran) ?></p>
            </div>
            <div class="summary-card saldo">
                <h4>Saldo Akhir</h4>
                <p>Rp <?= number_format($saldo_akhir) ?></p>
            </div>
        </div>

        <h3 style="margin-top: 30px;">Riwayat Transaksi (Halaman <?= $page ?> dari <?= $total_pages ?>)</h3>
        
        <div class="table-container">
            <table class="table-data">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Pemasukan</th>
                    <th>Pengeluaran</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result_transaksi) > 0): ?>
                    <?php while ($transaksi = mysqli_fetch_assoc($result_transaksi)): ?>
                    <tr>
                        <td><?= date('d M Y', strtotime($transaksi['tanggal'])) ?></td>
                        <td><?= htmlspecialchars($transaksi['deskripsi']) ?></td>
                        <td><?= ($transaksi['debit'] > 0) ? 'Rp ' . number_format($transaksi['debit']) : '-' ?></td>
                        <td><?= ($transaksi['kredit'] > 0) ? 'Rp ' . number_format($transaksi['kredit']) : '-' ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align: center;">Tidak ada data transaksi untuk ditampilkan.</td></tr>
                <?php endif; ?>
            </tbody>
            </table>
        </div>
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= ($page == $i) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>

        <div class="download-section">
            <form action="<?= BASE_URL ?>/controllers/LaporanController.php" method="POST" style="display: inline-block;">
                <input type="hidden" name="action" value="export_excel">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-download"></i> Unduh Laporan Excel (Lengkap)
                </button>
            </form>
        </div>
    </main>
</div>