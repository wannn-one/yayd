<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

// Ambil daftar peran dari database untuk dropdown
$roles = mysqli_query($koneksi, "SELECT * FROM roles");
?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>
    <main class="admin-main-content center-content">
        <div class="form-container form-container-lg" style="margin: 0;">
            <h2>Tambah Pengguna Baru</h2>
            <form action="<?= BASE_URL ?>/controllers/UserController.php" method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Peran</label>
                    <select name="id_role_fk" required>
                        <option value="">-- Pilih Peran --</option>
                        <?php while($role = mysqli_fetch_assoc($roles)): ?>
                        <option value="<?= $role['id_role'] ?>"><?= htmlspecialchars($role['nama_role']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn">Simpan Pengguna</button>
            </form>
        </div>
    </main>
</div>