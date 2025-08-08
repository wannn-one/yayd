<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

$stmt = mysqli_prepare($koneksi, "SELECT * FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, 'i', $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
?>

<div class="container">
    <div class="form-container" style="margin-top: 40px;">
        <h2>Profil Saya</h2>
        <p>Perbarui informasi akun Anda di sini.</p>
        
        <?php if(isset($_GET['status']) && $_GET['status'] == 'update_sukses'): ?>
            <div class="alert-success">Profil berhasil diperbarui!</div>
        <?php endif; ?>
        <?php if(isset($_GET['error'])): ?>
            <div class="alert-error">Terjadi kesalahan. Silakan coba lagi.</div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/controllers/UserController.php" method="POST">
            <input type="hidden" name="action" value="update_profile">
            <input type="hidden" name="id_user" value="<?= $user_id ?>">

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($user['nama_lengkap']) ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="text" name="nomor_telepon" value="<?= htmlspecialchars($user['nomor_telepon']) ?>">
            </div>
            <hr style="margin: 20px 0;">
            <div class="form-group">
                <label>Password Baru (Kosongkan jika tidak ingin diubah)</label>
                <input type="password" name="password">
            </div>
             <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="konfirmasi_password">
            </div>
            <button type="submit" class="btn">Update Profil</button>
        </form>
    </div>
</div>

<?php 
require_once realpath(__DIR__ . '/../templates/footer.php'); 
?>