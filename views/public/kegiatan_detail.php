<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../../index.php");
    exit();
}

$kegiatan_id = (int)$_GET['id'];

$query = "SELECT * FROM kegiatan ORDER BY tanggal_mulai DESC";
$result = mysqli_query($koneksi, $query);
$kegiatan_data = mysqli_fetch_all($result, MYSQLI_ASSOC);

$current_kegiatan = null;
foreach ($kegiatan_data as $kegiatan) {
    if ($kegiatan['id_kegiatan'] == $kegiatan_id) {
        $current_kegiatan = $kegiatan;
        break;
    }
}

if (!$current_kegiatan) {
    header("Location: ../../index.php");
    exit();
}

$is_relawan = isset($_SESSION['role_id']) && $_SESSION['role_id'] == 3;
$sudah_daftar = false;

$stmt = null;
if ($is_relawan) {
    $stmt = mysqli_prepare($koneksi, "SELECT COUNT(*) FROM partisipasi_kegiatan WHERE id_kegiatan_fk = ? AND id_user_relawan_fk = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ii', $kegiatan_id, $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
        $result_check = mysqli_stmt_get_result($stmt);
        $sudah_daftar = (mysqli_fetch_row($result_check)[0] > 0);
    }
}
?>

<div class="container dashboard-container">
    <a href="<?= BASE_URL ?>/relawan/index.php" class="btn btn-secondary">&larr; Kembali ke Beranda</a>

    <div class="detail-wrapper">
        <div class="detail-image">
            <img src="<?= !empty($current_kegiatan['dokumentasi']) ? (filter_var($current_kegiatan['dokumentasi'], FILTER_VALIDATE_URL) ? $current_kegiatan['dokumentasi'] : BASE_URL . '/' . $current_kegiatan['dokumentasi']) : 'https://placehold.co/600x400/png'; ?>" alt="Activity Image">
        </div>
        <div class="detail-info">
            <h1><?= htmlspecialchars($current_kegiatan['nama_kegiatan']) ?></h1>
            <div class="detail-meta">
                <span><strong>Lokasi:</strong> <?= htmlspecialchars($current_kegiatan['lokasi']) ?></span>
                <span><strong>Tanggal:</strong> <?= date('d F Y, H:i', strtotime($current_kegiatan['tanggal_mulai'])) ?> WIB</span>
            </div>
            <p><?= nl2br(htmlspecialchars($current_kegiatan['deskripsi'])) ?></p>

            <?php if ($is_relawan): // Tampilkan tombol hanya untuk Relawan ?>
                <?php if ($sudah_daftar): ?>
                    <button class="btn btn-secondary" disabled>Anda Sudah Terdaftar</button>
                <?php else: ?>
                    <form action="<?= BASE_URL ?>/controllers/PartisipasiController.php" method="POST">
                        <input type="hidden" name="action" value="register_kegiatan">
                        <input type="hidden" name="id_kegiatan" value="<?= $current_kegiatan['id_kegiatan'] ?>">
                        <button type="submit" class="btn btn-primary">Daftar Sekarang</button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
if ($stmt) {
    mysqli_stmt_close($stmt);
}
require_once realpath(__DIR__ . '/../templates/footer.php'); 
?>