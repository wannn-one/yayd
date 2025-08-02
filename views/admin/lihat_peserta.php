<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

// Pastikan ID kegiatan ada di URL
if (!isset($_GET['id'])) {
    header("Location: kelola_kegiatan.php");
    exit();
}
$kegiatan_id = (int)$_GET['id'];

// Ambil nama kegiatan untuk judul halaman
$stmt_kegiatan = mysqli_prepare($koneksi, "SELECT nama_kegiatan FROM kegiatan WHERE id_kegiatan = ?");
mysqli_stmt_bind_param($stmt_kegiatan, 'i', $kegiatan_id);
mysqli_stmt_execute($stmt_kegiatan);
$kegiatan = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_kegiatan));
mysqli_stmt_close($stmt_kegiatan);

if (!$kegiatan) {
    header("Location: kelola_kegiatan.php");
    exit();
}

// Query utama: Ambil daftar peserta dengan JOIN ke tabel users
$query = "SELECT u.nama_lengkap, u.email, u.nomor_telepon, p.tanggal_pendaftaran
          FROM partisipasi_kegiatan p
          JOIN users u ON p.id_user_relawan_fk = u.id_user
          WHERE p.id_kegiatan_fk = ?
          ORDER BY u.nama_lengkap ASC";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, 'i', $kegiatan_id);
mysqli_stmt_execute($stmt);
$result_peserta = mysqli_stmt_get_result($stmt);
?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>

    <main class="admin-main-content">
        <h2>Daftar Peserta: <?= htmlspecialchars($kegiatan['nama_kegiatan']) ?></h2>
        <a href="kelola_kegiatan.php" class="btn btn-secondary" style="margin-bottom: 20px;">&larr; Kembali ke Daftar Kegiatan</a>
        <hr>

        <table class="table-data">
            <thead>
                <tr>
                    <th>Nama Lengkap Peserta</th>
                    <th>Email</th>
                    <th>Nomor Telepon</th>
                    <th>Tanggal Mendaftar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result_peserta) > 0): ?>
                    <?php while($peserta = mysqli_fetch_assoc($result_peserta)): ?>
                    <tr>
                        <td><?= htmlspecialchars($peserta['nama_lengkap']) ?></td>
                        <td><?= htmlspecialchars($peserta['email']) ?></td>
                        <td><?= htmlspecialchars($peserta['nomor_telepon']) ?></td>
                        <td><?= date('d M Y, H:i', strtotime($peserta['tanggal_pendaftaran'])) ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">Belum ada relawan yang mendaftar untuk kegiatan ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>

<?php
mysqli_stmt_close($stmt);
?>