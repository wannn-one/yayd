<?php
require_once realpath(__DIR__ . '/../templates/header.php');
?>

<div class="role-selection-container">
    <div class="role-selection-header">
        <h2>Bergabung dengan YAYD</h2>
        <p>Pilih cara Anda ingin berkontribusi untuk membuat perubahan positif bagi anak-anak yatim</p>
    </div>

    <div class="role-cards">
        <div class="role-card donatur-card">
            <div class="role-icon">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 9.5V11.5L21 9ZM3 9L9 11.5V9.5L3 7V9ZM12 17C12.83 17 13.5 16.33 13.5 15.5S12.83 14 12 14 10.5 14.67 10.5 15.5 11.17 17 12 17ZM12 18.5C10.62 18.5 9.5 19.62 9.5 21H14.5C14.5 19.62 13.38 18.5 12 18.5Z" fill="#007bff"/>
                </svg>
            </div>
            <h3>Donatur</h3>
            <p class="role-description">Berikan dukungan finansial untuk program-program YAYD</p>
            
            <div class="role-benefits">
                <h4>Sebagai Donatur, Anda akan:</h4>
                <ul>
                    <li>✓ Memberikan bantuan dana untuk kebutuhan anak yatim</li>
                    <li>✓ Mendukung program pendidikan dan kesehatan</li>
                    <li>✓ Mendapat laporan transparansi penggunaan dana</li>
                    <li>✓ Menjadi bagian dari komunitas peduli sosial</li>
                    <li>✓ Fleksibilitas dalam jumlah dan waktu donasi</li>
                </ul>
            </div>

            <div class="role-commitment">
                <strong>Komitmen:</strong> Sesuai kemampuan finansial Anda
            </div>

            <a href="<?= BASE_URL ?>/donatur_daftar.php" class="btn btn-primary role-btn">
                Daftar sebagai Donatur
            </a>
        </div>

        <div class="role-card relawan-card">
            <div class="role-icon">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 4C16.88 4 17.67 4.5 18 5.26L20 9H4L6 5.26C6.33 4.5 7.12 4 8 4H16ZM2 10H22V20C22 21.1 21.1 22 20 22H4C2.9 22 2 21.1 2 20V10ZM12 12C10.9 12 10 12.9 10 14S10.9 16 12 16 14 15.1 14 14 13.1 12 12 12Z" fill="#28a745"/>
                </svg>
            </div>
            <h3>Relawan</h3>
            <p class="role-description">Berikan waktu dan tenaga untuk kegiatan langsung dengan anak-anak</p>
            
            <div class="role-benefits">
                <h4>Sebagai Relawan, Anda akan:</h4>
                <ul>
                    <li>✓ Terlibat langsung dalam kegiatan dengan anak yatim</li>
                    <li>✓ Membantu dalam program pendidikan dan pelatihan</li>
                    <li>✓ Mengorganisir acara dan kegiatan sosial</li>
                    <li>✓ Mendapat pengalaman berharga dan networking</li>
                    <li>✓ Berkontribusi sesuai keahlian dan minat Anda</li>
                </ul>
            </div>

            <div class="role-commitment">
                <strong>Komitmen:</strong> Minimal 4 jam per bulan
            </div>

            <a href="<?= BASE_URL ?>/relawan_daftar.php" class="btn btn-success role-btn">
                Daftar sebagai Relawan
            </a>
        </div>
    </div>

    <div class="role-selection-footer">
        <div class="both-roles">
            <h4>Ingin berkontribusi dalam kedua cara?</h4>
            <p>Anda bisa mendaftar sebagai donatur terlebih dahulu, lalu bergabung sebagai relawan kapan saja!</p>
        </div>
        
        <div class="back-to-login">
            <p>Sudah punya akun? <a href="<?= BASE_URL ?>/login.php">Masuk di sini</a></p>
        </div>
    </div>
</div>

<?php 
require_once realpath(__DIR__ . '/../templates/footer.php'); 
?> 