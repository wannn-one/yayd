<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

echo '<script>document.body.setAttribute("data-page", "kelola_kegiatan");</script>';

$query = "SELECT * FROM kegiatan ORDER BY tanggal_mulai DESC";
$result = mysqli_query($koneksi, $query);
?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>

    <main class="admin-main-content">
        <div class="page-header">
            <h2>Kelola Kegiatan</h2>
            <a href="tambah_kegiatan.php" class="btn btn-primary">Tambah Kegiatan Baru</a>
        </div>
        <hr>
        
        <div class="table-container">
            <table class="table-data">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Kegiatan</th>
                        <th>Tanggal Mulai</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($kegiatan = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>
                            <img src="<?= !empty($kegiatan['dokumentasi']) ? (filter_var($kegiatan['dokumentasi'], FILTER_VALIDATE_URL) ? $kegiatan['dokumentasi'] : BASE_URL . '/' . $kegiatan['dokumentasi']) : 'https://placehold.co/600x400/png' ?>" 
                                 alt="<?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>" 
                                 style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                        </td>
                        <td><?= htmlspecialchars($kegiatan['nama_kegiatan']) ?></td>
                        <td><?= date('d M Y', strtotime($kegiatan['tanggal_mulai'])) ?></td>
                        <td><?= htmlspecialchars($kegiatan['lokasi']) ?></td>
                        <td><?= htmlspecialchars($kegiatan['status']) ?></td>
                        <td>
                            <a href="edit_kegiatan.php?id=<?= $kegiatan['id_kegiatan'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                            <a href="absensi_kegiatan.php?id=<?= $kegiatan['id_kegiatan'] ?>" class="btn btn-info btn-sm">Absensi</a>
                            <a href="../controllers/KegiatanController.php?action=delete&id=<?= $kegiatan['id_kegiatan'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?');">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>