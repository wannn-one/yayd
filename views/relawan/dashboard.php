<?php

require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

$user_id = $_SESSION['user_id'];

$query_upcoming = "SELECT id_kegiatan, nama_kegiatan, deskripsi, tanggal_mulai, dokumentasi FROM kegiatan WHERE status = 'Akan Datang' ORDER BY tanggal_mulai ASC LIMIT 3";
$result_upcoming = mysqli_query($koneksi, $query_upcoming);

$query_completed = "SELECT id_kegiatan, nama_kegiatan, dokumentasi FROM kegiatan WHERE status = 'Selesai' ORDER BY tanggal_selesai DESC LIMIT 3";
$result_completed = mysqli_query($koneksi, $query_completed);

$query_history = "SELECT k.nama_kegiatan, k.tanggal_mulai, p.status_kehadiran 
                  FROM partisipasi_kegiatan p
                  JOIN kegiatan k ON p.id_kegiatan_fk = k.id_kegiatan
                  WHERE p.id_user_relawan_fk = ?
                  ORDER BY k.tanggal_mulai DESC";
$stmt_history = mysqli_prepare($koneksi, $query_history);

if (!$stmt_history) {
    die("Error preparing history statement: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt_history, 'i', $user_id);
mysqli_stmt_execute($stmt_history);
$result_history = mysqli_stmt_get_result($stmt_history);
?>

<div class="container dashboard-container">
    
    <?php if (isset($_SESSION['status_akun']) && $_SESSION['status_akun'] == 'Pending'): ?>
    <div class="alert-warning" style="margin-bottom: 20px; padding: 15px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; color: #856404;">
        <strong>⚠️ Status Akun: Pending - Akses Dibatasi</strong><br>
        Akun Anda sedang dalam proses verifikasi oleh admin. <strong>Anda tidak dapat mendaftar kegiatan</strong> hingga admin melakukan aktivasi terhadap akun Anda.
        <br><br>
        <strong>Untuk mempercepat verifikasi:</strong>
        <ul style="margin: 10px 0 0 20px;">
            <li>Pastikan data profil Anda sudah lengkap</li>
            <li>Tunggu maksimal 2x24 jam untuk aktivasi</li>
        </ul>
    </div>
    <?php endif; ?>
    
    <section class="dashboard-section">
        <h1>Kegiatan & Acara</h1>

        <h2 class="sub-section-title">Kegiatan Mendatang</h2>
        <div class="event-list">
            <?php if (mysqli_num_rows($result_upcoming) > 0): ?>
                <?php while($kegiatan = mysqli_fetch_assoc($result_upcoming)): ?>
                <div class="event-row">
                    <div class="event-text">
                        <span>Akan Datang</span>
                        <h3><?= htmlspecialchars($kegiatan['nama_kegiatan']) ?></h3>
                        <p><?= htmlspecialchars(substr($kegiatan['deskripsi'], 0, 150)) ?>...</p>
                        <a href="<?= BASE_URL ?>/kegiatan_detail.php?id=<?= $kegiatan['id_kegiatan'] ?>" class="details-link">Lihat Detail &rarr;</a>
                    </div>
                    <div class="event-image">
                        <img src="<?= !empty($kegiatan['dokumentasi']) ? (filter_var($kegiatan['dokumentasi'], FILTER_VALIDATE_URL) ? $kegiatan['dokumentasi'] : BASE_URL . '/' . $kegiatan['dokumentasi']) : 'https://placehold.co/600x400/png'; ?>" alt="Gambar Kegiatan">
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Tidak ada kegiatan yang akan datang saat ini.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="dashboard-section">
        <h2 class="sub-section-title">Kegiatan Selesai</h2>
        <div class="event-list">
            <?php if (mysqli_num_rows($result_completed) > 0): ?>
                <?php while($kegiatan = mysqli_fetch_assoc($result_completed)): ?>
                <div class="event-row">
                    <div class="event-text">
                        <span>Selesai</span>
                        <h3><?= htmlspecialchars($kegiatan['nama_kegiatan']) ?></h3>
                        <p>Kegiatan ini telah selesai dilaksanakan. Terima kasih atas partisipasi Anda!</p>
                        <a href="<?= BASE_URL ?>/kegiatan_detail.php?id=<?= $kegiatan['id_kegiatan'] ?>" class="details-link">Lihat Detail &rarr;</a>
                    </div>
                    <div class="event-image">
                        <img src="<?= !empty($kegiatan['dokumentasi']) ? (filter_var($kegiatan['dokumentasi'], FILTER_VALIDATE_URL) ? $kegiatan['dokumentasi'] : BASE_URL . '/' . $kegiatan['dokumentasi']) : 'https://placehold.co/600x400/png'; ?>" alt="Gambar Kegiatan">
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Belum ada kegiatan yang selesai.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="dashboard-section">
        <h2 class="sub-section-title">Riwayat Partisipasi Saya</h2>
        <div class="history-card">
            <div class="history-header">
                <div class="col">Kegiatan</div>
                <div class="col">Tanggal</div>
                <div class="col">Status</div>
            </div>

            <?php if (mysqli_num_rows($result_history) > 0): ?>
                <?php while($histori = mysqli_fetch_assoc($result_history)): ?>
                    <div class="history-row">
                        <div class="col"><?= htmlspecialchars($histori['nama_kegiatan']) ?></div>
                        <div class="col"><?= date('d M Y', strtotime($histori['tanggal_mulai'])) ?></div>
                        <div class="col">
                            <span class="status-chip status-<?= strtolower($histori['status_kehadiran']) ?>">
                                <?= htmlspecialchars($histori['status_kehadiran']) ?>
                            </span>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 20px; color: #666;">
                    Anda belum mengikuti kegiatan apapun.
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php 
mysqli_stmt_close($stmt_history);
require_once realpath(__DIR__ . '/../templates/footer.php'); 
?>