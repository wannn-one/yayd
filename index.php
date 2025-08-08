<?php

session_start();
require_once 'config/database.php';
require_once 'views/templates/header.php';

$query_profil = "SELECT visi, misi FROM profil_yayd WHERE id = 1";
$result_profil = mysqli_query($koneksi, $query_profil);
$profil = mysqli_fetch_assoc($result_profil);

$query_kegiatan = "SELECT nama_kegiatan, deskripsi, dokumentasi FROM kegiatan WHERE status = 'Akan Datang' ORDER BY tanggal_mulai ASC LIMIT 3";
$result_kegiatan = mysqli_query($koneksi, $query_kegiatan);

?>

<?php
$hero_slides = [
    [
        'title' => 'Memberdayakan Komunitas Melalui Aksi Bersama',
        'description' => 'Bergabunglah dengan kami untuk membuat perubahan. Berikan waktu sebagai relawan atau donasi untuk mendukung program kami.'
    ],
    [
        'title' => 'Bersama Membangun Masa Depan Cerah',
        'description' => 'Mari bergabung dalam misi mulia kami untuk memberikan harapan dan bantuan kepada anak-anak yatim yang membutuhkan.'
    ],
    [
        'title' => 'Setiap Kontribusi Berarti',
        'description' => 'Dengan keikhlasan hati, mari bersama-sama membantu anak-anak yatim meraih pendidikan dan kehidupan yang lebih baik.'
    ],
    [
        'title' => 'Bersama Kita Bisa Lebih Banyak',
        'description' => 'Setiap tangan yang terulur, setiap hati yang tulus, akan membawa perubahan nyata bagi mereka yang membutuhkan.'
    ]
];
?>

<section class="hero-carousel">
    <div class="hero-slides">
        <?php foreach ($hero_slides as $index => $slide): ?>
        <div class="hero-slide <?= $index === 0 ? 'active' : '' ?>">
            <div class="container">
                <h1><?= htmlspecialchars($slide['title']) ?></h1>
                <p><?= htmlspecialchars($slide['description']) ?></p>
                <div>
                    <a href="<?= BASE_URL; ?>/pilih_peran.php" class="btn btn-primary">Bergabung dengan Kami</a>
                    <a href="<?= BASE_URL; ?>/login.php" class="btn btn-secondary">Masuk</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="section">
    <div class="container">
        <h2 class="section-title">Profil Komunitas Kami</h2>
        <div>
            <h3>Visi Kami:</h3>
            <p><?= htmlspecialchars($profil['visi'] ?? 'Visi belum diatur.'); ?></p>
        </div>
        <div>
            <h3>Misi Kami:</h3>
            <p><?= htmlspecialchars($profil['misi'] ?? 'Misi belum diatur.'); ?></p>
        </div>
    </div>
</section>

<section class="section" style="background-color: #f8f9fa;">
    <div class="container">
        <h2 class="section-title">Kegiatan Mendatang</h2>
        <div class="activities-grid">

            <?php if (mysqli_num_rows($result_kegiatan) > 0): ?>
                <?php while ($kegiatan = mysqli_fetch_assoc($result_kegiatan)): ?>
                    <div class="card">
                        <img src="<?= !empty($kegiatan['dokumentasi']) ? $kegiatan['dokumentasi'] : 'https://placehold.co/600x400/png'; ?>" alt="<?= htmlspecialchars($kegiatan['nama_kegiatan']); ?>">
                        <div class="card-content">
                            <h3><?= htmlspecialchars($kegiatan['nama_kegiatan']); ?></h3>
                            <p><?= htmlspecialchars(substr($kegiatan['deskripsi'], 0, 100)) . '...'; ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Belum ada kegiatan yang akan datang saat ini. Silakan cek kembali nanti.</p>
            <?php endif; ?>

        </div>
    </div>
</section>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.hero-slide');
    
    const backgroundImages = [
        'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=1200&h=600&fit=crop&crop=faces,entropy&auto=format&q=80',
        'https://images.unsplash.com/photo-1593113598332-cd288d649433?w=1200&h=600&fit=crop&crop=faces,entropy&auto=format&q=80',
        'https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?w=1200&h=600&fit=crop&crop=faces,entropy&auto=format&q=80',
        'https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=1200&h=600&fit=crop&crop=faces,entropy&auto=format&q=80'
    ];
    
    let currentSlide = 0;
    
    backgroundImages.forEach(src => {
        const img = new Image();
        img.src = src;
    });
    
    slides.forEach((slide, index) => {
        if (backgroundImages[index]) {
            slide.style.backgroundImage = `url('${backgroundImages[index]}')`;
        }
    });
    
    function showNextSlide() {
        slides[currentSlide].classList.remove('active');
        
        currentSlide = (currentSlide + 1) % slides.length;
        
        slides[currentSlide].classList.add('active');
    }
    
    setInterval(showNextSlide, 5000);
    
    if (slides.length > 0) {
        slides[0].classList.add('active');
    }
});
</script>

<?php
require_once 'views/templates/footer.php';
?>