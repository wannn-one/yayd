<?php require_once realpath(__DIR__ . '/../templates/header.php'); ?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>

    <main class="admin-main-content center-content">
        <div class="form-container" style="margin: 0;">
            <h2>Tambah Kegiatan Baru</h2>
            
            <form action="<?= BASE_URL ?>/controllers/KegiatanController.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create">
                
                <div class="form-group">
                    <label>Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input type="datetime-local" name="tanggal_mulai" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Selesai (Opsional)</label>
                    <input type="datetime-local" name="tanggal_selesai">
                </div>
                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" name="lokasi" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="Akan Datang">Akan Datang</option>
                        <option value="Berjalan">Berjalan</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Dokumentasi/Gambar (Opsional, Maks 2MB)</label>
                    <input type="file" name="dokumentasi" accept="image/png, image/jpeg, image/jpg">
                </div>

                <button type="submit" class="btn">Simpan Kegiatan</button>
            </form>
        </div>
    </main>
</div>