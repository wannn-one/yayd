<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

echo '<script>document.body.setAttribute("data-page", "kelola_distribusi");</script>';

$query = "SELECT d.*, k.nama_kegiatan, u.nama_lengkap as nama_admin 
          FROM distribusi_donasi d 
          LEFT JOIN kegiatan k ON d.id_kegiatan_fk = k.id_kegiatan
          JOIN users u ON d.dicatat_oleh = u.id_user
          ORDER BY d.tanggal_distribusi DESC";
$result = mysqli_query($koneksi, $query);
?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>

    <main class="admin-main-content">
        <div class="page-header">
            <h2>Distribusi Donasi</h2>
            <a href="tambah_distribusi.php" class="btn btn-primary">Tambah Distribusi Baru</a>
        </div>
        <hr>
        
        <div class="table-container">
            <table class="table-data">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Penerima</th>
                        <th>Deskripsi</th>
                        <th>Nominal / Item</th>
                        <th>Untuk Kegiatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while($distribusi = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= date('d M Y', strtotime($distribusi['tanggal_distribusi'])) ?></td>
                            <td><?= htmlspecialchars($distribusi['penerima']) ?></td>
                            <td><?= htmlspecialchars($distribusi['deskripsi']) ?></td>
                            <td>
                                <?= !empty($distribusi['nominal']) ? 'Rp ' . number_format($distribusi['nominal']) : htmlspecialchars($distribusi['item_barang']); ?>
                            </td>
                            <td><?= htmlspecialchars($distribusi['nama_kegiatan'] ?? 'Umum') ?></td>
                            <td>
                            <a href="edit_distribusi.php?id=<?= $distribusi['id_distribusi'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                            <a href="<?= BASE_URL ?>/controllers/DistribusiController.php?action=delete&id=<?= $distribusi['id_distribusi'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data distribusi ini?');">
                                Hapus
                            </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align: center;">Belum ada data distribusi yang tercatat.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>