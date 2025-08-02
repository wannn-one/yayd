<?php
// controllers/LaporanController.php
session_start();
require_once '../config/database.php';

// PENTING: Panggil library FPDF
// Sesuaikan path jika nama folder FPDF Anda berbeda
require('../libs/fpdf186/fpdf.php');

function generateLaporanPdf() {
    global $koneksi;

    if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role_id'], [1, 2]) || !isset($_GET['kegiatan_id'])) {
        die("Akses ditolak atau ID Kegiatan tidak ditemukan.");
    }

    $kegiatan_id = (int)$_GET['kegiatan_id'];

    // 1. Ambil detail kegiatan
    $stmt_kegiatan = mysqli_prepare($koneksi, "SELECT nama_kegiatan, tanggal_selesai FROM kegiatan WHERE id_kegiatan = ?");
    mysqli_stmt_bind_param($stmt_kegiatan, 'i', $kegiatan_id);
    mysqli_stmt_execute($stmt_kegiatan);
    $kegiatan = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_kegiatan));

    // 2. Ambil data donasi untuk kegiatan tersebut
    $stmt_donasi = mysqli_prepare($koneksi, "SELECT u.nama_lengkap, d.jumlah_uang, d.tanggal_donasi 
                                             FROM donasi d 
                                             JOIN users u ON d.id_user_donatur_fk = u.id_user 
                                             WHERE d.id_kegiatan_fk = ? AND d.status = 'Diterima' AND d.jenis_donasi = 'Uang'");
    mysqli_stmt_bind_param($stmt_donasi, 'i', $kegiatan_id);
    mysqli_stmt_execute($stmt_donasi);
    $result_donasi = mysqli_stmt_get_result($stmt_donasi);

    // 3. Mulai membuat PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Judul
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Laporan Keuangan Donasi', 0, 1, 'C');
    
    // Detail Kegiatan
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 7, 'Kegiatan: ' . $kegiatan['nama_kegiatan'], 0, 1, 'C');
    $pdf->Cell(0, 7, 'Tanggal Selesai: ' . date('d F Y', strtotime($kegiatan['tanggal_selesai'])), 0, 1, 'C');
    $pdf->Ln(10); // Spasi

    // Header Tabel
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(80, 7, 'Nama Donatur', 1, 0, 'C');
    $pdf->Cell(50, 7, 'Tanggal Donasi', 1, 0, 'C');
    $pdf->Cell(60, 7, 'Jumlah (Rp)', 1, 1, 'C');

    // Isi Tabel
    $pdf->SetFont('Arial', '', 10);
    $total_donasi = 0;
    while($donasi = mysqli_fetch_assoc($result_donasi)){
        $pdf->Cell(80, 6, $donasi['nama_lengkap'], 1);
        $pdf->Cell(50, 6, date('d-m-Y', strtotime($donasi['tanggal_donasi'])), 1, 0, 'C');
        $pdf->Cell(60, 6, number_format($donasi['jumlah_uang']), 1, 1, 'R'); // 'R' = rata kanan
        $total_donasi += $donasi['jumlah_uang'];
    }

    // Baris Total
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(130, 7, 'TOTAL DONASI TERKUMPUL', 1, 0, 'C');
    $pdf->Cell(60, 7, number_format($total_donasi), 1, 1, 'R');

    // 4. Kirim PDF ke browser untuk diunduh
    $pdf->Output('D', 'laporan-keuangan-' . $kegiatan_id . '.pdf');
}


// Router Sederhana
if (isset($_GET['action']) && $_GET['action'] == 'generate_pdf') {
    generateLaporanPdf();
}
?>