<?php
require_once realpath(__DIR__ . '/../templates/header.php');
?>

<div class="form-container">
    <h2>Masuk Akun</h2>

    <?php
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'sukses_daftar') {
            echo '<div class="alert-success">Pendaftaran berhasil! Silakan masuk.</div>';
        } elseif ($_GET['status'] == 'sukses_daftar_pending') {
            echo '<div class="alert-warning" style="background-color: #fff3cd; border-color: #ffeaa7; color: #856404;">
                    <strong>Pendaftaran berhasil!</strong><br>
                    Akun Anda sedang dalam status pending dan menunggu verifikasi admin. Anda tetap dapat login dan menggunakan platform, namun admin akan segera melakukan aktivasi terhadap akun Anda.
                  </div>';
        }
    }
    if (isset($_GET['error'])) {
        if ($_GET['error'] == 'login_gagal') {
            echo '<div class="alert-error">Email atau kata sandi salah.</div>';
        } elseif ($_GET['error'] == 'akun_diblokir') {
            echo '<div class="alert-error">Akun Anda telah diblokir oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.</div>';
        }
    }
    ?>

    <form action="<?= BASE_URL ?>/controllers/AuthController.php" method="POST">
        <input type="hidden" name="action" value="login">

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Kata Sandi</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn">Masuk</button>
        </div>

        <div class="form-footer">
            <p>Belum punya akun? <a href="<?= BASE_URL ?>/pilih_peran.php">Daftar di sini</a></p>
        </div>
    </form>
</div>

<?php 
require_once realpath(__DIR__ . '/../templates/footer.php'); 
?>