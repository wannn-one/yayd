<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

// Pastikan ID relawan ada di URL
if (!isset($_GET['id'])) {
    header("Location: kelola_pengguna.php");
    exit();
}
$relawan_id = (int)$_GET['id'];

// Ambil nama relawan untuk judul halaman
$stmt_user = mysqli_prepare($koneksi, "SELECT nama_lengkap FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($stmt_user, 'i', $relawan_id);
mysqli_stmt_execute($stmt_user);
$result_user = mysqli_stmt_get_result($stmt_user);
$relawan = mysqli_fetch_assoc($result_user);
mysqli_stmt_close($stmt_user);

// Jika relawan tidak ditemukan, kembalikan ke daftar
if (!$relawan) {
    header("Location: kelola_pengguna.php");
    exit();
}

// Query utama: Ambil histori partisipasi dengan JOIN ke tabel kegiatan
$query = "SELECT k.nama_kegiatan, k.tanggal_mulai, p.tanggal_pendaftaran, p.status_kehadiran
          FROM partisipasi_kegiatan p
          JOIN kegiatan k ON p.id_kegiatan_fk = k.id_kegiatan
          WHERE p.id_user_relawan_fk = ?
          ORDER BY k.tanggal_mulai DESC";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, 'i', $relawan_id);
mysqli_stmt_execute($stmt);
$result_histori = mysqli_stmt_get_result($stmt);
?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>

    <main class="admin-main-content">
        <h2>Histori Partisipasi: <?= htmlspecialchars($relawan['nama_lengkap']) ?></h2>
        <a href="kelola_pengguna.php" class="btn btn-secondary" style="margin-bottom: 20px;">&larr; Kembali ke Daftar Pengguna</a>
        <hr>

        <table class="table-data">
            <thead>
                <tr>
                    <th>Nama Kegiatan</th>
                    <th>Tanggal Kegiatan</th>
                    <th>Tanggal Mendaftar</th>
                    <th>Status Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result_histori) > 0): ?>
                    <?php while($histori = mysqli_fetch_assoc($result_histori)): ?>
                    <tr>
                        <td><?= htmlspecialchars($histori['nama_kegiatan']) ?></td>
                        <td><?= date('d M Y', strtotime($histori['tanggal_mulai'])) ?></td>
                        <td><?= date('d M Y, H:i', strtotime($histori['tanggal_pendaftaran'])) ?></td>
                        <td><?= htmlspecialchars($histori['status_kehadiran']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">Relawan ini belum pernah mendaftar di kegiatan manapun.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>

<?php
mysqli_stmt_close($stmt);
?>