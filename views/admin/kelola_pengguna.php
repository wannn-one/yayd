<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

// Query untuk mengambil semua pengguna beserta nama perannya
$query = "SELECT u.id_user, u.nama_lengkap, u.email, r.nama_role 
          FROM users u
          JOIN roles r ON u.id_role_fk = r.id_role
          ORDER BY u.created_at DESC";
$result = mysqli_query($koneksi, $query);
?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>

    <main class="admin-main-content">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Manajemen Pengguna</h2>
            <a href="tambah_pengguna.php" class="btn btn-primary">Tambah Pengguna Baru</a>
        </div>
        <hr>
        <table class="table-data">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Peran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while($user = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['nama_lengkap']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['nama_role']) ?></td>
                        <td>
                            <a href="edit_pengguna.php?id=<?= $user['id_user'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                            
                            <?php if ($user['nama_role'] == 'Relawan'): ?>
                            <a href="histori_relawan.php?id=<?= $user['id_user'] ?>" class="btn btn-info btn-sm">Histori</a>
                            <?php endif; ?>
                            
                            <?php if ($_SESSION['user_id'] != $user['id_user']): ?>
                            <a href="../controllers/UserController.php?action=delete&id=<?= $user['id_user'] ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('PERINGATAN: Menghapus pengguna ini akan menghapus data terkait. Yakin ingin melanjutkan?');">
                               Hapus
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">Belum ada pengguna yang terdaftar.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>