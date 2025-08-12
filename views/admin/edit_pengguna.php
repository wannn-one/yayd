<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: kelola_pengguna.php");
    exit();
}

$user_id = (int)$_GET['id'];

$stmt_user = mysqli_prepare($koneksi, "
    SELECT u.*, r.nama_role 
    FROM users u 
    JOIN roles r ON u.id_role_fk = r.id_role 
    WHERE u.id_user = ?
");
mysqli_stmt_bind_param($stmt_user, 'i', $user_id);
mysqli_stmt_execute($stmt_user);
$result_user = mysqli_stmt_get_result($stmt_user);
$user = mysqli_fetch_assoc($result_user);

$roles = mysqli_query($koneksi, "SELECT * FROM roles ORDER BY nama_role");
?>
<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>
    <main class="admin-main-content center-content">
        <div class="form-container form-container-lg" style="margin: 0;">
            <h2>Edit Pengguna: <?= htmlspecialchars($user['nama_lengkap']) ?></h2>
            <form action="<?= BASE_URL ?>/controllers/UserController.php" method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">
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
                    <input type="text" name="nomor_telepon" value="<?= htmlspecialchars($user['nomor_telepon']) ?>" placeholder="Contoh: 081234567890">
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" rows="3" placeholder="Alamat lengkap"><?= htmlspecialchars($user['alamat']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki" <?= ($user['jenis_kelamin'] == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="Perempuan" <?= ($user['jenis_kelamin'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Alasan Bergabung</label>
                    <textarea name="alasan_bergabung" rows="3" placeholder="Alasan ingin bergabung dengan YAYD"><?= htmlspecialchars($user['alasan_bergabung']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Password Baru (Kosongkan jika tidak ingin diubah)</label>
                    <input type="password" name="password">
                </div>
                <div class="form-group">
                    <label>Peran</label>

                    <?php if ($_SESSION['user_id'] == $user['id_user']): // Cek jika user yang diedit adalah diri sendiri ?>
                        <select name="id_role_fk_disabled" required disabled>
                            <option><?= htmlspecialchars($user['nama_role']) ?></option>
                        </select>
                        <input type="hidden" name="id_role_fk" value="<?= $user['id_role_fk'] ?>">
                        <small>Anda tidak dapat mengubah peran Anda sendiri.</small>
                    
                    <?php else: // Jika user yang diedit adalah orang lain ?>
                        <select name="id_role_fk" required>
                            <?php 
                            // Reset pointer array roles karena mungkin sudah digunakan sebelumnya
                            mysqli_data_seek($roles, 0); 
                            while($role = mysqli_fetch_assoc($roles)): 
                            ?>
                            <option value="<?= $role['id_role'] ?>" <?= ($user['id_role_fk'] == $role['id_role']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($role['nama_role']) ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    <?php endif; ?>
                            
                </div>
                <button type="submit" class="btn">Update Pengguna</button>
            </form>
        </div>
    </main>
</div>