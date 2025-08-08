<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: kelola_kegiatan.php");
    exit();
}

$kegiatan_id = (int)$_GET['id'];

$stmt = mysqli_prepare($koneksi, "SELECT * FROM kegiatan WHERE id_kegiatan = ?");
mysqli_stmt_bind_param($stmt, 'i', $kegiatan_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$kegiatan = mysqli_fetch_assoc($result);

if (!$kegiatan) {
    header("Location: kelola_kegiatan.php");
    exit();
}
?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>

    <main class="admin-main-content center-content">
        <div class="form-container" style="margin: 0;">
            <h2>Edit Kegiatan</h2>
            <form action="<?= BASE_URL ?>/controllers/KegiatanController.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id_kegiatan" value="<?= $kegiatan['id_kegiatan'] ?>">
                
                <div class="form-group">
                    <label>Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" value="<?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>" required>
                </div>
                <div class="form-group textarea-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" rows="4" required><?= htmlspecialchars($kegiatan['deskripsi']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input type="datetime-local" name="tanggal_mulai" value="<?= date('Y-m-d\TH:i', strtotime($kegiatan['tanggal_mulai'])) ?>" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Selesai (Opsional)</label>
                    <input type="datetime-local" name="tanggal_selesai" value="<?= $kegiatan['tanggal_selesai'] ? date('Y-m-d\TH:i', strtotime($kegiatan['tanggal_selesai'])) : '' ?>">
                </div>
                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" name="lokasi" value="<?= htmlspecialchars($kegiatan['lokasi']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="Akan Datang" <?= ($kegiatan['status'] == 'Akan Datang') ? 'selected' : '' ?>>Akan Datang</option>
                        <option value="Berjalan" <?= ($kegiatan['status'] == 'Berjalan') ? 'selected' : '' ?>>Berjalan</option>
                        <option value="Selesai" <?= ($kegiatan['status'] == 'Selesai') ? 'selected' : '' ?>>Selesai</option>
                        <option value="Dibatalkan" <?= ($kegiatan['status'] == 'Dibatalkan') ? 'selected' : '' ?>>Dibatalkan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Dokumentasi/Gambar (Opsional)</label>
                    <input type="file" name="dokumentasi" accept="image/png, image/jpeg, image/jpg">
                    <?php if (!empty($kegiatan['dokumentasi'])): ?>
                        <div style="margin-top: 10px;">
                            <p>Gambar saat ini:</p>
                            <img src="<?= filter_var($kegiatan['dokumentasi'], FILTER_VALIDATE_URL) ? $kegiatan['dokumentasi'] : BASE_URL . '/' . $kegiatan['dokumentasi'] ?>" 
                                 alt="Dokumentasi Kegiatan" 
                                 style="max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 4px;">
                            <br>
                            <small><a href="<?= filter_var($kegiatan['dokumentasi'], FILTER_VALIDATE_URL) ? $kegiatan['dokumentasi'] : BASE_URL . '/' . $kegiatan['dokumentasi'] ?>" target="_blank">Lihat ukuran penuh</a></small>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn">Update Kegiatan</button>
            </form>
        </div>
    </main>
</div>