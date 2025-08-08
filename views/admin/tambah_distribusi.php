<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

$result_kegiatan = mysqli_query($koneksi, "SELECT id_kegiatan, nama_kegiatan FROM kegiatan ORDER BY nama_kegiatan ASC");
?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>
    <main class="admin-main-content center-content">
        <div class="form-container form-container-lg" style="margin: 0;">
            <h2>Catat Distribusi Donasi Baru</h2>
            <form action="<?= BASE_URL ?>/controllers/DistribusiController.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create">
                
                <div class="form-group">
                    <label>Tanggal Distribusi</label>
                    <input type="datetime-local" name="tanggal_distribusi" required>
                </div>
                <div class="form-group">
                    <label>Penerima Manfaat</label>
                    <input type="text" name="penerima" placeholder="Contoh: Panti Asuhan Al-Ikhlas" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi Penyaluran</label>
                    <textarea name="deskripsi" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>Jenis Distribusi</label>
                    <select id="jenis_distribusi" name="jenis_distribusi" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Uang">Uang Tunai</option>
                        <option value="Barang">Barang / Logistik</option>
                    </select>
                </div>
                <div id="form-uang" style="display:none;" class="form-group">
                    <label>Nominal (Rp)</label>
                    <input type="number" name="nominal" placeholder="Contoh: 5000000">
                </div>
                <div id="form-barang" style="display:none;" class="form-group">
                    <label>Item Barang</label>
                    <input type="text" name="item_barang" placeholder="Contoh: 50 paket sembako">
                </div>
                <div class="form-group">
                    <label>Terkait Kegiatan (Opsional)</label>
                    <select name="id_kegiatan_fk">
                        <option value="">Distribusi Umum</option>
                        <?php while($kegiatan = mysqli_fetch_assoc($result_kegiatan)): ?>
                            <option value="<?= $kegiatan['id_kegiatan'] ?>"><?= htmlspecialchars($kegiatan['nama_kegiatan']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Foto Dokumentasi (Opsional)</label>
                    <input type="file" name="dokumentasi">
                </div>

                <button type="submit" class="btn">Simpan Data</button>
            </form>
        </div>
    </main>
</div>

<script>
document.getElementById('jenis_distribusi').addEventListener('change', function () {
    var formUang = document.getElementById('form-uang');
    var formBarang = document.getElementById('form-barang');
    if (this.value === 'Uang') {
        formUang.style.display = 'block';
        formBarang.style.display = 'none';
    } else if (this.value === 'Barang') {
        formUang.style.display = 'none';
        formBarang.style.display = 'block';
    } else {
        formUang.style.display = 'none';
        formBarang.style.display = 'none';
    }
});
</script>