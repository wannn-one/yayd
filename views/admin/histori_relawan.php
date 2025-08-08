<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

echo '<script>document.body.setAttribute("data-page", "histori_relawan");</script>';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: kelola_pengguna.php");
    exit();
}

$relawan_id = (int)$_GET['id'];

$stmt_user = mysqli_prepare($koneksi, "SELECT nama_lengkap, email FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($stmt_user, 'i', $relawan_id);
mysqli_stmt_execute($stmt_user);
$result_user = mysqli_stmt_get_result($stmt_user);
$relawan = mysqli_fetch_assoc($result_user);

if (!$relawan) {
    echo "<script>alert('Relawan tidak ditemukan!'); window.location.href='kelola_pengguna.php';</script>";
    exit();
}

$stmt_partisipasi = mysqli_prepare($koneksi, "
    SELECT k.nama_kegiatan, k.tanggal_mulai, k.tanggal_selesai, p.status_kehadiran 
    FROM partisipasi_kegiatan p 
    JOIN kegiatan k ON p.id_kegiatan_fk = k.id_kegiatan 
    WHERE p.id_user_fk = ? 
    ORDER BY k.tanggal_mulai DESC
");
mysqli_stmt_bind_param($stmt_partisipasi, 'i', $relawan_id);
mysqli_stmt_execute($stmt_partisipasi);
$result_partisipasi = mysqli_stmt_get_result($stmt_partisipasi);
?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>

    <main class="admin-main-content">
        <h2>Histori Kegiatan Relawan: <?= htmlspecialchars($relawan['nama_lengkap']) ?></h2>
        <p>Email: <?= htmlspecialchars($relawan['email']) ?></p>
        <hr>
        
        <div class="table-container">
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
                    <?php if (mysqli_num_rows($result_partisipasi) > 0): ?>
                        <?php while($histori = mysqli_fetch_assoc($result_partisipasi)): ?>
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
        </div>
        
        <div style="margin-top: 20px;">
            <a href="kelola_pengguna.php" class="btn btn-secondary">Kembali ke Manajemen Pengguna</a>
        </div>
    </main>
</div>

<?php
mysqli_stmt_close($stmt_partisipasi);
?>