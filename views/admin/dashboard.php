<?php
require_once realpath(__DIR__ . '/../../config/database.php');
require_once realpath(__DIR__ . '/../templates/header.php');

$total_donasi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id_donasi) as total FROM donasi"))['total'];
$jumlah_relawan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id_user) as total FROM users WHERE id_role_fk = 3"))['total'];
$kegiatan_selesai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id_kegiatan) as total FROM kegiatan WHERE status = 'Selesai'"))['total'];
$total_terkumpul = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(jumlah_uang) as total FROM donasi WHERE status = 'Diterima'"))['total'];
$query_chart = "SELECT 
                    ANY_VALUE(DATE_FORMAT(tanggal_donasi, '%b %Y')) as bulan_tahun, 
                    SUM(jumlah_uang) as total
                FROM donasi
                WHERE 
                    tanggal_donasi > DATE_SUB(NOW(), INTERVAL 6 MONTH) AND 
                    jenis_donasi = 'Uang' AND 
                    status = 'Diterima'
                GROUP BY 
                    YEAR(tanggal_donasi), MONTH(tanggal_donasi)
                ORDER BY 
                    YEAR(tanggal_donasi), MONTH(tanggal_donasi) ASC";

$result_chart = mysqli_query($koneksi, $query_chart);

if ($result_chart === false) {
    die("ERROR PADA QUERY GRAFIK: " . mysqli_error($koneksi));
}

$chart_labels = [];
$chart_data = [];
while($row = mysqli_fetch_assoc($result_chart)){
    $chart_labels[] = $row['bulan_tahun'];
    $chart_data[] = $row['total'];
}

?>

<div class="admin-wrapper">
    <?php require_once realpath(__DIR__ . '/../templates/admin_sidebar.php'); ?>

    <main class="admin-main-content">
        <h1>Dasbor</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <h4>Total Donasi</h4>
                <p><?= number_format($total_donasi) ?></p>
            </div>
            <div class="stat-card">
                <h4>Jumlah Relawan</h4>
                <p><?= number_format($jumlah_relawan) ?></p>
            </div>
            <div class="stat-card">
                <h4>Kegiatan Selesai</h4>
                <p><?= number_format($kegiatan_selesai) ?></p>
            </div>
            <div class="stat-card">
                <h4>Total Dana Terkumpul</h4>
                <p>Rp.<?= number_format($total_terkumpul) ?></p>
            </div>
        </div>

        <div class="chart-container">
            <h3>Tren Donasi 6 Bulan Terakhir</h3>
            <canvas id="donasiChart" width="400" height="200"></canvas>
        </div>

        <script>
        const ctx = document.getElementById('donasiChart').getContext('2d');
        const donasiChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($chart_labels) ?>,
                datasets: [{
                    label: 'Donasi (Rp)',
                    data: <?= json_encode($chart_data) ?>,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        </script>

    </main>
</div>