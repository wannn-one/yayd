<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: kelola_distribusi.php");
    exit();
}

$distribusi_id = (int)$_GET['id'];

$stmt = mysqli_prepare($koneksi, "SELECT * FROM distribusi_donasi WHERE id_distribusi = ?");
mysqli_stmt_bind_param($stmt, 'i', $distribusi_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$distribusi = mysqli_fetch_assoc($result);

if (!$distribusi) {
    echo "<script>alert('Data distribusi tidak ditemukan!'); window.location.href='kelola_distribusi.php';</script>";
    exit();
}

$query_kegiatan = "SELECT id_kegiatan, nama_kegiatan FROM kegiatan ORDER BY nama_kegiatan";
$result_kegiatan = mysqli_query($koneksi, $query_kegiatan);

$jenis_distribusi = $distribusi['jenis_distribusi'] ?? 'uang';
?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>

    <main class="admin-main-content center-content">
        <div class="form-container form-container-lg" style="margin: 0;">
            <h2>Edit Data Distribusi</h2>
            <form action="<?= BASE_URL ?>/controllers/DistribusiController.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id_distribusi" value="<?= $distribusi['id_distribusi'] ?>">
                
                <div class="form-group">
                    <label>Tanggal Distribusi</label>
                    <input type="datetime-local" name="tanggal_distribusi" value="<?= date('Y-m-d\TH:i', strtotime($distribusi['tanggal_distribusi'])) ?>" required>
                </div>
                <div class="form-group">
                    <label>Penerima Manfaat</label>
                    <input type="text" name="penerima" value="<?= htmlspecialchars($distribusi['penerima']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi Penyaluran</label>
                    <textarea name="deskripsi" rows="3" required><?= htmlspecialchars($distribusi['deskripsi']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Jenis Distribusi</label>
                    <select id="jenis_distribusi" name="jenis_distribusi" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Uang" <?= ($jenis_distribusi == 'Uang') ? 'selected' : '' ?>>Uang Tunai</option>
                        <option value="Barang" <?= ($jenis_distribusi == 'Barang') ? 'selected' : '' ?>>Barang / Logistik</option>
                    </select>
                </div>
                <div id="form-uang" style="display:none;" class="form-group">
                    <label>Nominal (Rp)</label>
                    <input type="number" name="nominal" value="<?= htmlspecialchars($distribusi['nominal'] ?? '') ?>">
                </div>
                <div id="form-barang" style="display:none;" class="form-group">
                    <label>Item Barang</label>
                    <input type="text" name="item_barang" value="<?= htmlspecialchars($distribusi['item_barang'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Terkait Kegiatan (Opsional)</label>
                    <select name="id_kegiatan_fk">
                        <option value="">Distribusi Umum</option>
                        <?php while($kegiatan = mysqli_fetch_assoc($result_kegiatan)): ?>
                            <option value="<?= $kegiatan['id_kegiatan'] ?>" <?= ($distribusi['id_kegiatan_fk'] == $kegiatan['id_kegiatan']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Ganti Foto Dokumentasi (Opsional)</label>
                    <input type="file" name="dokumentasi">
                    <?php if (!empty($distribusi['dokumentasi'])): ?>
                        <p style="font-size: 12px; margin-top: 5px;">File saat ini: <a href="<?= BASE_URL ?>/<?= htmlspecialchars($distribusi['dokumentasi']) ?>" target="_blank">Lihat Gambar</a></p>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn">Update Data</button>
            </form>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jenisDistribusiSelect = document.getElementById('jenis_distribusi');
    const formUang = document.getElementById('form-uang');
    const formBarang = document.getElementById('form-barang');

    function toggleFields() {
        if (jenisDistribusiSelect.value === 'Uang') {
            formUang.style.display = 'block';
            formBarang.style.display = 'none';
        } else if (jenisDistribusiSelect.value === 'Barang') {
            formUang.style.display = 'none';
            formBarang.style.display = 'block';
        } else {
            formUang.style.display = 'none';
            formBarang.style.display = 'none';
        }
    }

    // Panggil fungsi saat halaman pertama kali dimuat
    toggleFields();

    // Panggil fungsi setiap kali dropdown berubah
    jenisDistribusiSelect.addEventListener('change', toggleFields);
});
</script>