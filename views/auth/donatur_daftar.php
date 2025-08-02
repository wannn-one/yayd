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