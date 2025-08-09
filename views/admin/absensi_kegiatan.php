<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

echo '<script>document.body.setAttribute("data-page", "absensi_kegiatan");</script>';

$kegiatan_id = (int)$_GET['id'];

$stmt_kegiatan = mysqli_prepare($koneksi, "SELECT * FROM kegiatan WHERE id_kegiatan = ?");
mysqli_stmt_bind_param($stmt_kegiatan, 'i', $kegiatan_id);
mysqli_stmt_execute($stmt_kegiatan);
$result_kegiatan = mysqli_stmt_get_result($stmt_kegiatan);
$kegiatan = mysqli_fetch_assoc($result_kegiatan);

$stmt_peserta = mysqli_prepare($koneksi, "SELECT u.nama_lengkap, u.email, p.status_kehadiran, p.id_partisipasi FROM partisipasi_kegiatan p JOIN users u ON p.id_user_relawan_fk = u.id_user WHERE p.id_kegiatan_fk = ? ORDER BY u.nama_lengkap ASC");
mysqli_stmt_bind_param($stmt_peserta, 'i', $kegiatan_id);
mysqli_stmt_execute($stmt_peserta);
$result_peserta = mysqli_stmt_get_result($stmt_peserta);
?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>

    <main class="admin-main-content">
        <h2>Absensi Kegiatan: <?= htmlspecialchars($kegiatan['nama_kegiatan']) ?></h2>
        <p>Tanggal: <?= date('d M Y', strtotime($kegiatan['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($kegiatan['tanggal_selesai'])) ?></p>
        <p>Lokasi: <?= htmlspecialchars($kegiatan['lokasi']) ?></p>
        <hr>
        
        <div class="table-container">
            <table class="table-data">
                <thead>
                    <tr>
                        <th>Nama Lengkap Peserta</th>
                        <th>Email</th>
                        <th>Status Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result_peserta) > 0): ?>
                        <?php while($peserta = mysqli_fetch_assoc($result_peserta)): ?>
                        <tr>
                            <td><?= htmlspecialchars($peserta['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($peserta['email']) ?></td>
                            <td>
                                <form action="<?= BASE_URL ?>/controllers/PartisipasiController.php" method="POST">
                                    <input type="hidden" name="action" value="update_absensi">
                                    <input type="hidden" name="id_partisipasi" value="<?= $peserta['id_partisipasi'] ?>">
                                    <select name="status_kehadiran" onchange="this.form.submit()" class="form-control-sm">
                                        <option value="Terdaftar" <?= $peserta['status_kehadiran'] == 'Terdaftar' ? 'selected' : '' ?>>Terdaftar</option>
                                        <option value="Hadir" <?= $peserta['status_kehadiran'] == 'Hadir' ? 'selected' : '' ?>>Hadir</option>
                                        <option value="Batal" <?= $peserta['status_kehadiran'] == 'Batal' ? 'selected' : '' ?>>Batal</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3" style="text-align: center;">Belum ada relawan yang mendaftar untuk kegiatan ini.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 20px;">
            <a href="kelola_kegiatan.php" class="btn btn-secondary">Kembali ke Kelola Kegiatan</a>
        </div>
    </main>
</div>