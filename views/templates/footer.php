</main>

<footer>
    <div class="container">
        <?php
        if (!isset($koneksi)) {
            require_once __DIR__ . '/../../config/database.php';
        }
        
        if (!isset($profil) || empty($profil)) {
            $query_profil_footer = "SELECT alamat_kontak, email_kontak, telepon_kontak FROM profil_yayd WHERE id = 1";
            $result_profil_footer = mysqli_query($koneksi, $query_profil_footer);
            $profil_footer = mysqli_fetch_assoc($result_profil_footer);
        } else {
            $profil_footer = $profil;
        }
        ?>
        
        <div class="footer-content">
            <div class="footer-section">
                <h3>Yho Akherat Yho Dunyo (YAYD)</h3>
                <p>Berdedikasi untuk memberikan bantuan dan dukungan kepada anak-anak yatim dan dhuafa di seluruh Indonesia.</p>
            </div>
            
            <div class="footer-section">
                <h3>Hubungi Kami</h3>
                <div class="contact-info">
                    <?php if (!empty($profil_footer['alamat_kontak'])): ?>
                    <div class="contact-item">
                        <span>📍</span>
                        <p><?= htmlspecialchars($profil_footer['alamat_kontak']); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($profil_footer['email_kontak'])): ?>
                    <div class="contact-item">
                        <span>📧</span>
                        <p><a href="mailto:<?= htmlspecialchars($profil_footer['email_kontak']); ?>"><?= htmlspecialchars($profil_footer['email_kontak']); ?></a></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($profil_footer['telepon_kontak'])): ?>
                    <div class="contact-item">
                        <span>📞</span>
                        <p><a href="tel:<?= htmlspecialchars($profil_footer['telepon_kontak']); ?>"><?= htmlspecialchars($profil_footer['telepon_kontak']); ?></a></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="footer-section">
                <h3>Tautan Cepat</h3>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL; ?>/">Beranda</a></li>
                    <li><a href="<?= BASE_URL; ?>/pilih_peran.php">Bergabung</a></li>
                    <li><a href="<?= BASE_URL; ?>/login.php">Masuk</a></li>
                    <li><a href="<?= BASE_URL; ?>/tentang.php">Tentang Kami</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?= date('Y'); ?> YAYD. Semua hak dilindungi.</p>
        </div>
    </div>
</footer>

</body>
</html>