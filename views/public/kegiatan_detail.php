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
    <?php
    // Handle alert messages
    if (isset($_GET['alert'])) {
        $alert = $_GET['alert'];
        $message = '';
        $alert_class = '';
        
        if ($alert == 'daftar_sukses') {
            $message = 'Berhasil mendaftar kegiatan! Anda sudah terdaftar sebagai relawan.';
            $alert_class = 'alert-success';
        } elseif ($alert == 'sudah_terdaftar') {
            $message = 'Anda sudah terdaftar untuk kegiatan ini sebelumnya.';
            $alert_class = 'alert-info';
        } elseif ($alert == 'gagal_daftar') {
            $message = 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.';
            $alert_class = 'alert-error';
        } elseif ($alert == 'akun_pending') {
            $message = 'Maaf, Anda belum dapat mendaftar kegiatan karena akun Anda masih dalam status pending. Silakan tunggu hingga admin melakukan verifikasi dan aktivasi terhadap akun Anda.';
            $alert_class = 'alert-warning';
        }
        
        if ($message) {
            echo '<div class="alert ' . $alert_class . '" id="alertMessage">' . $message . '</div>';
        }
    }
    ?>
    
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
                <?php if (isset($_SESSION['status_akun']) && $_SESSION['status_akun'] == 'Pending'): ?>
                    <div style="padding: 15px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; color: #856404; margin-top: 15px;">
                        <strong>Akun Pending</strong><br>
                        Anda belum dapat mendaftar kegiatan karena akun masih dalam status verifikasi admin.
                    </div>
                <?php elseif ($sudah_daftar): ?>
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

<style>
.alert {
    padding: 12px 16px;
    margin-bottom: 20px;
    border-radius: 6px;
    font-weight: 500;
    position: relative;
    animation: slideDown 0.3s ease-out;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-info {
    background-color: #cce6ff;
    color: #004085;
    border: 1px solid #99d1ff;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-warning {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert.fade-out {
    animation: fadeOut 0.5s ease-out forwards;
}

@keyframes fadeOut {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-10px);
    }
}
</style>

<script>
// Auto-hide alert after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alertMessage = document.getElementById('alertMessage');
    if (alertMessage) {
        setTimeout(function() {
            alertMessage.classList.add('fade-out');
            setTimeout(function() {
                alertMessage.remove();
                
                // Remove alert parameter from URL without page reload
                if (window.history.replaceState) {
                    const url = new URL(window.location);
                    url.searchParams.delete('alert');
                    window.history.replaceState({}, '', url);
                }
            }, 500);
        }, 5000);
    }
});
</script>

<?php 
if ($stmt) {
    mysqli_stmt_close($stmt);
}
require_once realpath(__DIR__ . '/../templates/footer.php'); 
?>