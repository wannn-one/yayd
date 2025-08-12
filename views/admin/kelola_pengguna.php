<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

echo '<script>document.body.setAttribute("data-page", "kelola_pengguna");</script>';
$query = "SELECT u.id_user, u.nama_lengkap, u.email, r.nama_role, u.status_akun, u.alasan_bergabung, u.nomor_telepon, u.alamat, u.jenis_kelamin
          FROM users u
          JOIN roles r ON u.id_role_fk = r.id_role
          ORDER BY u.created_at DESC";
$result = mysqli_query($koneksi, $query);
?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>

    <main class="admin-main-content">
        <div class="page-header">
            <h2>Manajemen Pengguna</h2>
            <a href="tambah_pengguna.php" class="btn btn-primary">Tambah Pengguna Baru</a>
        </div>
        <hr>
        
        <div class="table-container">
            <table class="table-data">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Peran</th>
                    <th>Status Akun</th>
                    <th>Alasan Bergabung</th>
                    <th>Nomor Telepon</th>
                    <th>Alamat</th>
                    <th>Jenis Kelamin</th>
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
                            <span class="status-chip status-<?= strtolower($user['status_akun']) ?>"><?= htmlspecialchars($user['status_akun']) ?></span>
                        </td>
                        <td style="max-width: 200px;">
                            <?php if (!empty($user['alasan_bergabung'])): ?>
                                <span title="<?= htmlspecialchars($user['alasan_bergabung']) ?>">
                                    <?= htmlspecialchars(strlen($user['alasan_bergabung']) > 50 ? substr($user['alasan_bergabung'], 0, 50) . '...' : $user['alasan_bergabung']) ?>
                                </span>
                            <?php else: ?>
                                <em class="text-muted">Tidak ada alasan</em>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($user['nomor_telepon']) ?></td>
                        <td><?= htmlspecialchars($user['alamat']) ?></td>
                        <td><?= htmlspecialchars($user['jenis_kelamin']) ?></td>
                        <td>
                            <a href="edit_pengguna.php?id=<?= $user['id_user'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                            
                            <?php if ($user['nama_role'] == 'Relawan'): ?>
                            <a href="histori_relawan.php?id=<?= $user['id_user'] ?>" class="btn btn-info btn-sm">Histori</a>
                            <?php endif; ?>
                            
                            <?php if ($_SESSION['user_id'] != $user['id_user']): ?>
                                <form action="../controllers/UserController.php" method="POST" style="display:inline-block; margin-left: 5px;">
                                    <input type="hidden" name="action" value="update_status_akun">
                                    <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">
                                    <select name="status_akun" onchange="this.form.submit()" title="Ubah Status Akun">
                                        <option value="Aktif" <?= $user['status_akun'] == 'Aktif' ? 'selected' : '' ?>>Aktifkan</option>
                                        <option value="Pending" <?= $user['status_akun'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="Diblokir" <?= $user['status_akun'] == 'Diblokir' ? 'selected' : '' ?>>Blokir</option>
                                    </select>
                                </form>

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
                        <td colspan="6" style="text-align: center;">Belum ada pengguna yang terdaftar.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            </table>
        </div>
    </main>
</div>