<?php

require_once realpath(__DIR__ . '/../templates/header.php'); 
?>

<div class="form-container">
    <h2>Pendaftaran Akun Donatur</h2>
    <p>Terima kasih atas niat baik Anda untuk berkontribusi.</p>

    <?php
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
        $pesan = '';
        if ($error == 'password_tidak_cocok') {
            $pesan = 'Kata sandi dan konfirmasi kata sandi tidak cocok!';
        } elseif ($error == 'email_terdaftar') {
            $pesan = 'Email yang Anda masukkan sudah terdaftar. Silakan gunakan email lain.';
        } elseif ($error == 'data_tidak_lengkap') {
            $pesan = 'Mohon lengkapi semua data yang diperlukan.';
        }
        echo '<div class="alert-error">' . $pesan . '</div>';
    }
    ?>
    
    <form action="<?= BASE_URL ?>/controllers/AuthController.php" method="POST">
        <input type="hidden" name="action" value="register">
        <input type="hidden" name="id_role_fk" value="2">

        <div class="form-group">
            <label for="nama_lengkap">Nama Lengkap</label>
            <input type="text" id="nama_lengkap" name="nama_lengkap" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Kata Sandi</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="konfirmasi_password">Konfirmasi Kata Sandi</label>
            <input type="password" id="konfirmasi_password" name="konfirmasi_password" required>
        </div>
        
        <div class="form-group">
            <label for="nomor_telepon">Nomor Telepon</label>
            <input type="text" id="nomor_telepon" name="nomor_telepon" placeholder="Contoh: 081234567890">
        </div>
        
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <textarea id="alamat" name="alamat" rows="3" placeholder="Alamat lengkap"></textarea>
        </div>
        
        <div class="form-group">
            <label for="jenis_kelamin">Jenis Kelamin</label>
            <select id="jenis_kelamin" name="jenis_kelamin">
                <option value="">-- Pilih Jenis Kelamin --</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="alasan_bergabung">Alasan Bergabung</label>
            <textarea id="alasan_bergabung" name="alasan_bergabung" rows="3" placeholder="Ceritakan alasan Anda ingin berkontribusi sebagai donatur"></textarea>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn">Daftar sebagai Donatur</button>
        </div>
        
        <div class="form-footer">
            <p><a href="<?= BASE_URL ?>/pilih_peran.php">← Kembali ke pilihan peran</a></p>
            <p>Sudah punya akun? <a href="<?= BASE_URL ?>/login.php">Masuk di sini</a></p>
        </div>
    </form>
</div>

<?php 
require_once realpath(__DIR__ . '/../templates/footer.php'); 
?>