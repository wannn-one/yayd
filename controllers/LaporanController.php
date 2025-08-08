<?php
require_once realpath(__DIR__ . '/../config/database.php');

require_once realpath(__DIR__ . '/../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once realpath(__DIR__ . '/../libs/fpdf186/fpdf.php');

function generatePDF($kegiatan_id) {
    global $koneksi;
    
    if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
        header("Location: ../login.php?error=akses_ditolak");
        exit();
    }
    
    $stmt_kegiatan = mysqli_prepare($koneksi, "SELECT * FROM kegiatan WHERE id_kegiatan = ?");
    mysqli_stmt_bind_param($stmt_kegiatan, 'i', $kegiatan_id);
    mysqli_stmt_execute($stmt_kegiatan);
    $kegiatan = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_kegiatan));
    
    $stmt_donasi = mysqli_prepare($koneksi, "
        SELECT d.*, u.nama_lengkap 
        FROM donasi d 
        JOIN users u ON d.id_user_donatur_fk = u.id_user 
        WHERE d.id_kegiatan_fk = ? AND d.status = 'Diterima'
        ORDER BY d.tanggal_donasi ASC
    ");
    mysqli_stmt_bind_param($stmt_donasi, 'i', $kegiatan_id);
    mysqli_stmt_execute($stmt_donasi);
    $result_donasi = mysqli_stmt_get_result($stmt_donasi);
    
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetMargins(15, 15, 15);
    
    // Header
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'LAPORAN DONASI KEGIATAN', 0, 1, 'C');
    $pdf->Ln(5);
    
    // Info Kegiatan
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 7, 'Nama Kegiatan:', 0, 0);
    $pdf->Cell(0, 7, $kegiatan['nama_kegiatan'], 0, 1);
    $pdf->Cell(40, 7, 'Lokasi:', 0, 0);
    $pdf->Cell(0, 7, $kegiatan['lokasi'], 0, 1);
    $pdf->Cell(40, 7, 'Tanggal:', 0, 0);
    $pdf->Cell(0, 7, date('d F Y', strtotime($kegiatan['tanggal_mulai'])), 0, 1);
    $pdf->Ln(10);
    
    // Header Tabel
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(10, 7, 'No', 1, 0, 'C');
    $pdf->Cell(60, 7, 'Nama Donatur', 1, 0, 'C');
    $pdf->Cell(40, 7, 'Jenis Donasi', 1, 0, 'C');
    $pdf->Cell(40, 7, 'Jumlah', 1, 0, 'C');
    $pdf->Cell(30, 7, 'Tanggal', 1, 1, 'C');
    
    // Data Tabel
    $pdf->SetFont('Arial', '', 9);
    $no = 1;
    $total_uang = 0;
    
    while ($donasi = mysqli_fetch_assoc($result_donasi)) {
        $pdf->Cell(10, 6, $no++, 1, 0, 'C');
        $pdf->Cell(60, 6, $donasi['nama_lengkap'], 1, 0);
        $pdf->Cell(40, 6, $donasi['jenis_donasi'], 1, 0, 'C');
        
        if ($donasi['jenis_donasi'] == 'Uang') {
            $pdf->Cell(40, 6, 'Rp ' . number_format($donasi['jumlah_uang'], 0, ',', '.'), 1, 0, 'R');
            $total_uang += $donasi['jumlah_uang'];
        } else {
            $pdf->Cell(40, 6, $donasi['deskripsi_barang'], 1, 0);
        }
        
        $pdf->Cell(30, 6, date('d/m/Y', strtotime($donasi['tanggal_donasi'])), 1, 1, 'C');
    }
    
    // Total
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(150, 7, 'Total Donasi Uang:', 1, 0, 'R');
    $pdf->Cell(30, 7, 'Rp ' . number_format($total_uang, 0, ',', '.'), 1, 1, 'R');
    
    $pdf->Output('I', 'laporan_donasi_' . $kegiatan['nama_kegiatan'] . '.pdf');
}

function generateExcel($kegiatan_id) {
    global $koneksi;
    
    if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
        header("Location: ../login.php?error=akses_ditolak");
        exit();
    }
    
    $stmt_kegiatan = mysqli_prepare($koneksi, "SELECT * FROM kegiatan WHERE id_kegiatan = ?");
    mysqli_stmt_bind_param($stmt_kegiatan, 'i', $kegiatan_id);
    mysqli_stmt_execute($stmt_kegiatan);
    $kegiatan = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_kegiatan));
    
    $stmt_donasi = mysqli_prepare($koneksi, "
        SELECT d.*, u.nama_lengkap 
        FROM donasi d 
        JOIN users u ON d.id_user_donatur_fk = u.id_user 
        WHERE d.id_kegiatan_fk = ? AND d.status = 'Diterima'
        ORDER BY d.tanggal_donasi ASC
    ");
    mysqli_stmt_bind_param($stmt_donasi, 'i', $kegiatan_id);
    mysqli_stmt_execute($stmt_donasi);
    $result_donasi = mysqli_stmt_get_result($stmt_donasi);
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    $sheet->setCellValue('A1', 'LAPORAN DONASI KEGIATAN');
    $sheet->mergeCells('A1:E1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
    $sheet->setCellValue('A3', 'Nama Kegiatan:');
    $sheet->setCellValue('B3', $kegiatan['nama_kegiatan']);
    $sheet->setCellValue('A4', 'Lokasi:');
    $sheet->setCellValue('B4', $kegiatan['lokasi']);
    $sheet->setCellValue('A5', 'Tanggal:');
    $sheet->setCellValue('B5', date('d F Y', strtotime($kegiatan['tanggal_mulai'])));
    
    $sheet->setCellValue('A7', 'No');
    $sheet->setCellValue('B7', 'Nama Donatur');
    $sheet->setCellValue('C7', 'Jenis Donasi');
    $sheet->setCellValue('D7', 'Jumlah');
    $sheet->setCellValue('E7', 'Tanggal');
    
    $sheet->getStyle('A7:E7')->getFont()->setBold(true);
    
    $row = 8;
    $no = 1;
    $total_uang = 0;
    
    while ($donasi = mysqli_fetch_assoc($result_donasi)) {
        $sheet->setCellValue('A' . $row, $no++);
        $sheet->setCellValue('B' . $row, $donasi['nama_lengkap']);
        $sheet->setCellValue('C' . $row, $donasi['jenis_donasi']);
        
        if ($donasi['jenis_donasi'] == 'Uang') {
            $sheet->setCellValue('D' . $row, $donasi['jumlah_uang']);
            $total_uang += $donasi['jumlah_uang'];
        } else {
            $sheet->setCellValue('D' . $row, $donasi['deskripsi_barang']);
        }
        
        $sheet->setCellValue('E' . $row, date('d/m/Y', strtotime($donasi['tanggal_donasi'])));
        $row++;
    }
    
    $sheet->setCellValue('C' . ($row + 1), 'Total Donasi Uang:');
    $sheet->setCellValue('D' . ($row + 1), $total_uang);
    $sheet->getStyle('C' . ($row + 1) . ':D' . ($row + 1))->getFont()->setBold(true);
    
    foreach (range('A', 'E') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    
    $writer = new Xlsx($spreadsheet);
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="laporan_donasi_' . $kegiatan['nama_kegiatan'] . '.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer->save('php://output');
    exit();
}

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    $kegiatan_id = (int)($_GET['id'] ?? 0);
    
    switch ($action) {
        case 'pdf':
            generatePDF($kegiatan_id);
            break;
        case 'excel':
            generateExcel($kegiatan_id);
            break;
        default:
            header("Location: ../donatur/laporan.php");
            break;
    }
} else {
    header("Location: ../donatur/laporan.php");
}
exit();
?>