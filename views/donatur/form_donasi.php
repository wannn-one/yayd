<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: " . BASE_URL . "/login.php?error=akses_ditolak");
    exit();
}

// Check if user account is pending
if (isset($_SESSION['status_akun']) && $_SESSION['status_akun'] == 'Pending') {
    ?>
    <div class="form-container">
        <h2>Akses Dibatasi</h2>
        <div class="alert-warning" style="padding: 20px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; color: #856404; margin-bottom: 20px;">
            <strong>Akun Anda Sedang Pending</strong><br>
            Maaf, Anda belum dapat membuat donasi karena akun Anda masih dalam status pending. 
            Silakan tunggu hingga admin melakukan verifikasi dan aktivasi terhadap akun Anda.
            <br><br>
            <strong>Untuk mempercepat proses verifikasi:</strong>
            <ul style="margin-top: 10px;">
                <li>Pastikan data profil Anda sudah lengkap</li>
                <li>Hubungi admin jika diperlukan</li>
            </ul>
        </div>
        <a href="<?= BASE_URL ?>/donatur/index.php" class="btn">← Kembali ke Dashboard</a>
    </div>
    <?php
    require_once realpath(__DIR__ . '/../templates/footer.php');
    exit();
}

$query_kegiatan = "SELECT * FROM kegiatan WHERE status IN ('Akan Datang', 'Sedang Berlangsung') ORDER BY tanggal_mulai ASC";
$result_kegiatan = mysqli_query($koneksi, $query_kegiatan);
?>

<div class="form-container">
    <h2>Formulir Donasi</h2>
    <p>Setiap kontribusi Anda sangat berarti bagi kami.</p>

    <form action="<?= BASE_URL ?>/controllers/DonasiController.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="create">

        <div class="form-group">
            <label for="jenis_donasi">Jenis Donasi</label>
            <select id="jenis_donasi" name="jenis_donasi" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="Uang">Uang</option>
                <option value="Barang">Barang</option>
            </select>
        </div>

        <div id="form-uang" style="display: none;">
            <div class="form-group">
                <label for="jumlah_uang">Jumlah Uang (Rp)</label>
                <input type="number" id="jumlah_uang" name="jumlah_uang" placeholder="Contoh: 50000">
            </div>
            <div class="form-group">
                <label for="metode_pembayaran">Metode Pembayaran</label>
                <select id="metode_pembayaran" name="metode_pembayaran">
                    <option value="Transfer">Transfer</option>
                </select>
            </div>
            <div class="form-group">
                <label for="bukti_pembayaran">Upload Bukti Transfer (Opsional)</label>
                <input type="file" id="bukti_pembayaran" name="bukti_pembayaran">
            </div>
        </div>

        <div id="form-barang" style="display: none;">
            <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" id="nama_barang" name="nama_barang" placeholder="Contoh: Buku Tulis 1 lusin">
            </div>
            <div class="form-group">
                <label for="deskripsi_barang">Deskripsi Barang (Opsional)</label>
                <textarea id="deskripsi_barang" name="deskripsi_barang" rows="3"></textarea>
            </div>
             <div class="form-group">
                <label for="metode_penyerahan">Metode Penyerahan</label>
                <select id="metode_penyerahan" name="metode">
                    <option value="Diantar">Diantar ke Sekretariat</option>
                    <option value="Diambil">Minta Diambil oleh Tim</option>
                    <option value="OTS">Diberikan di Lokasi (OTS)</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="id_kegiatan_fk">Alokasikan Donasi Untuk (Opsional)</label>
            <select id="id_kegiatan_fk" name="id_kegiatan_fk">
                <option value="">Donasi Umum (Tidak terikat kegiatan)</option>
                <?php while($kegiatan = mysqli_fetch_assoc($result_kegiatan)): ?>
                    <option value="<?= $kegiatan['id_kegiatan'] ?>"><?= htmlspecialchars($kegiatan['nama_kegiatan']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn">Kirim Donasi</button>
        </div>
    </form>
</div>

<script>
document.getElementById('jenis_donasi').addEventListener('change', function () {
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

<?php 
require_once realpath(__DIR__ . '/../templates/footer.php'); 
?>