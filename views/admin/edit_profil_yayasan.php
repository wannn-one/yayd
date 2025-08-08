<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

$profil_query = "SELECT * FROM profil_yayd WHERE id = 1 LIMIT 1";
$profil_result = mysqli_query($koneksi, $profil_query);
$profil = mysqli_fetch_assoc($profil_result);
?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>

    <main class="admin-main-content">
        <h2>Edit Profil Yayasan</h2>
        <p>Perbarui informasi publik yang akan tampil di halaman utama website.</p>
        <hr>

        <?php if(isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
            <div class="alert-success">Profil yayasan berhasil diperbarui!</div>
        <?php endif; ?>

        <div class="form-container-lg" style="margin: 0;">
            <form action="<?= BASE_URL ?>/controllers/ProfilController.php" method="POST">
                <input type="hidden" name="action" value="update_profil_yayasan">
                
                <div class="form-group">
                    <label>Nama Komunitas</label>
                    <input type="text" name="nama_komunitas" value="<?= htmlspecialchars($profil['nama_komunitas']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Visi</label>
                    <textarea name="visi" rows="3" required><?= htmlspecialchars($profil['visi']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Misi</label>
                    <textarea name="misi" rows="5" required><?= htmlspecialchars($profil['misi']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Deskripsi Singkat</label>
                    <textarea name="deskripsi" rows="4" required><?= htmlspecialchars($profil['deskripsi']) ?></textarea>
                </div>
                <hr>
                <div class="form-group">
                    <label>Alamat Kontak</label>
                    <input type="text" name="alamat_kontak" value="<?= htmlspecialchars($profil['alamat_kontak']) ?>">
                </div>
                <div class="form-group">
                    <label>Email Kontak</label>
                    <input type="email" name="email_kontak" value="<?= htmlspecialchars($profil['email_kontak']) ?>">
                </div>
                <div class="form-group">
                    <label>Telepon Kontak</label>
                    <input type="text" name="telepon_kontak" value="<?= htmlspecialchars($profil['telepon_kontak']) ?>">
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </main>
</div>