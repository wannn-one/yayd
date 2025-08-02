-- --------------------------------------------------------
-- Seeder Lengkap untuk Database YAYD (Hash Diperbarui)
-- --------------------------------------------------------

USE `yayd`;

-- Section: Tambah 20 Dummy Users (10 Donatur, 10 Relawan)
-- Password untuk semua user dummy di bawah ini adalah: "123123"
INSERT INTO `users` (`id_role_fk`, `nama_lengkap`, `email`, `password`, `nomor_telepon`) VALUES
-- Donatur
(2, 'Ahmad Fauzi', 'donatur.ahmad@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081211110001'),
(2, 'Bella Puspita', 'donatur.bella@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081211110002'),
(2, 'Chandra Wijaya', 'donatur.chandra@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081211110003'),
(2, 'Diana Sari', 'donatur.diana@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081211110004'),
(2, 'Eko Prasetyo', 'donatur.eko@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081211110005'),
(2, 'Fitri Handayani', 'donatur.fitri@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081211110006'),
(2, 'Gilang Ramadhan', 'donatur.gilang@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081211110007'),
(2, 'Hana Pertiwi', 'donatur.hana@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081211110008'),
(2, 'Indra Kusuma', 'donatur.indra@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081211110009'),
(2, 'Jihan Nabila', 'donatur.jihan@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081211110010'),
-- Relawan
(3, 'Kevin Sanjaya', 'relawan.kevin@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081311110011'),
(3, 'Lina Marlina', 'relawan.lina@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081311110012'),
(3, 'Muhammad Zidan', 'relawan.zidan@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081311110013'),
(3, 'Nina Rosita', 'relawan.nina@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081311110014'),
(3, 'Oscar Mahendra', 'relawan.oscar@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081311110015'),
(3, 'Putri Amelia', 'relawan.putri@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081311110016'),
(3, 'Qori Ramadhani', 'relawan.qori@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081311110017'),
(3, 'Rangga Saputra', 'relawan.rangga@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081311110018'),
(3, 'Sari Wulandari', 'relawan.sari@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081311110019'),
(3, 'Toni Firmansyah', 'relawan.toni@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081311110020');

-- Section: Tambah 10 Dummy Kegiatan
INSERT INTO `kegiatan` (`nama_kegiatan`, `deskripsi`, `tanggal_mulai`, `tanggal_selesai`, `lokasi`, `status`, `dokumentasi`) VALUES
('Webinar: Menjadi Relawan Cerdas', 'Pelatihan online tentang manajemen relawan, komunikasi efektif, dan cara memaksimalkan dampak sosial di era digital.', '2025-08-10 19:00:00', '2025-08-10 21:00:00', 'Online via Zoom', 'Akan Datang', 'assets/uploads/kegiatan/webinar.jpg'),
('Pasar Murah Kebutuhan Pokok', 'Menyediakan sembako dengan harga terjangkau untuk masyarakat pra-sejahtera di wilayah Kecamatan Rungkut.', '2025-08-25 08:00:00', '2025-08-25 12:00:00', 'Lapangan Kecamatan Rungkut', 'Akan Datang', 'assets/uploads/kegiatan/pasarmurah.jpg'),
('Donasi Buku untuk Taman Baca', 'Mengumpulkan dan menyalurkan buku-buku layak baca untuk membangun taman bacaan baru di kampung nelayan.', '2025-09-01 09:00:00', '2025-09-30 17:00:00', 'Surabaya & Sekitarnya', 'Akan Datang', 'assets/uploads/kegiatan/buku.jpg'),
('Pemeriksaan Kesehatan Gratis', 'Bekerja sama dengan fakultas kedokteran untuk memberikan layanan pemeriksaan kesehatan dasar gratis untuk lansia.', '2025-10-12 08:00:00', '2025-10-12 14:00:00', 'Balai RW 05, Wonokromo', 'Akan Datang', 'assets/uploads/kegiatan/kesehatan.jpg'),
('Lomba Cerdas Cermat Anak Yatim', 'Mengadakan lomba cerdas cermat tingkat SD untuk anak-anak panti asuhan se-Surabaya dengan hadiah beasiswa.', '2025-11-15 09:00:00', '2025-11-15 16:00:00', 'Aula Dinas Pendidikan Surabaya', 'Akan Datang', 'assets/uploads/kegiatan/lomba.jpg'),
('Santunan & Buka Bersama 1446 H', 'Kegiatan tahunan berbagi kebahagiaan dengan anak yatim di bulan Ramadhan.', '2025-03-22 16:00:00', '2025-03-22 19:00:00', 'Masjid Al-Falah, Surabaya', 'Selesai', 'assets/uploads/kegiatan/bukber.jpg'),
('Aksi Tanam 1000 Pohon Mangrove', 'Penanaman pohon mangrove untuk mencegah abrasi di pesisir timur Surabaya.', '2024-12-08 07:30:00', '2024-12-08 12:00:00', 'Ekowisata Mangrove Wonorejo', 'Selesai', 'assets/uploads/kegiatan/mangrove.jpg'),
('Pembagian Daging Qurban 1445 H', 'Distribusi daging qurban kepada lebih dari 500 keluarga yang membutuhkan.', '2024-06-17 09:00:00', '2024-06-18 17:00:00', 'Kantor YAYD & Sekitarnya', 'Selesai', 'assets/uploads/kegiatan/qurban2.jpg'),
('Renovasi Panti Asuhan Al-Ikhlas', 'Membantu merenovasi atap dan sanitasi Panti Asuhan Al-Ikhlas yang rusak akibat hujan deras.', '2024-02-10 08:00:00', '2024-02-25 17:00:00', 'Panti Asuhan Al-Ikhlas', 'Selesai', 'assets/uploads/kegiatan/renovasi.jpg'),
('Workshop Kerajinan Tangan Daur Ulang', 'Mengajarkan ibu-ibu di kampung binaan untuk membuat kerajinan tangan dari sampah plastik.', '2023-11-20 10:00:00', '2023-11-20 15:00:00', 'Balai Kampung Kapasan', 'Selesai', 'assets/uploads/kegiatan/daurulang.jpg');

-- Section: Tambah 30 Dummy Donasi (Termasuk Riwayat Lama)
-- Asumsi ID Admin=1. ID Donatur baru: 2-11. ID Relawan baru: 12-21. ID Kegiatan baru: 1-10.
INSERT INTO `donasi` (`id_user_donatur_fk`, `id_kegiatan_fk`, `jenis_donasi`, `jumlah_uang`, `nama_barang`, `metode`, `status`, `tanggal_donasi`) VALUES
(2, 2, 'Uang', 200000.00, NULL, 'Transfer', 'Diterima', '2025-07-25 10:00:00'),
(3, 2, 'Uang', 150000.00, NULL, 'Transfer', 'Diterima', '2025-07-20 11:30:00'),
(4, 3, 'Barang', NULL, '50 Buku Cerita Anak', 'OTS', 'Diterima', '2025-07-15 14:00:00'),
(5, NULL, 'Uang', 300000.00, NULL, 'Transfer', 'Pending', '2025-07-10 09:00:00'),
(6, 4, 'Uang', 100000.00, NULL, 'Transfer', 'Diterima', '2025-06-28 18:00:00'),
(7, 4, 'Barang', NULL, 'Paket Obat-obatan P3K', 'OTS', 'Diterima', '2025-06-15 12:00:00'),
(8, 1, 'Uang', 50000.00, NULL, 'Transfer', 'Diterima', '2025-06-10 20:00:00'),
(9, NULL, 'Uang', 400000.00, NULL, 'Transfer', 'Diterima', '2025-05-22 13:00:00'),
(10, 5, 'Uang', 250000.00, NULL, 'Transfer', 'Diterima', '2025-05-18 16:45:00'),
(2, 3, 'Barang', NULL, '2 Rak Buku Kayu', 'COD', 'Diterima', '2025-05-05 11:00:00'),
(3, NULL, 'Uang', 100000.00, NULL, 'Transfer', 'Diterima', '2025-04-30 08:00:00'),
(4, 1, 'Uang', 75000.00, NULL, 'Transfer', 'Diterima', '2025-04-12 19:30:00'),
(5, 6, 'Uang', 2000000.00, NULL, 'Transfer', 'Diterima', '2025-03-10 10:00:00'),
(6, 6, 'Barang', NULL, '100 Box Kurma', 'OTS', 'Diterima', '2025-03-05 15:00:00'),
(7, NULL, 'Uang', 500000.00, NULL, 'Transfer', 'Diterima', '2025-02-20 17:00:00'),
(8, 7, 'Uang', 150000.00, NULL, 'Transfer', 'Diterima', '2024-12-01 09:00:00'),
(9, 7, 'Barang', NULL, '50 Bibit Mangrove', 'OTS', 'Diterima', '2024-11-25 14:00:00'),
(10, 8, 'Uang', 2500000.00, NULL, 'Transfer', 'Diterima', '2024-06-10 11:00:00'),
(2, 8, 'Uang', 3000000.00, NULL, 'Transfer', 'Diterima', '2024-06-05 16:00:00'),
(3, 9, 'Uang', 1000000.00, NULL, 'Transfer', 'Diterima', '2024-02-05 08:00:00'),
(4, 9, 'Barang', NULL, '5 Sak Semen', 'COD', 'Diterima', '2024-02-01 13:00:00'),
(5, 10, 'Uang', 500000.00, NULL, 'Transfer', 'Diterima', '2023-11-15 10:00:00'),
(6, 10, 'Barang', NULL, 'Peralatan Kerajinan Tangan', 'OTS', 'Diterima', '2023-11-10 14:00:00'),
(7, NULL, 'Uang', 200000.00, NULL, 'Transfer', 'Diterima', '2023-10-20 19:00:00'),
(8, NULL, 'Uang', 100000.00, NULL, 'Transfer', 'Diterima', '2023-09-18 12:00:00'),
(9, NULL, 'Uang', 50000.00, NULL, 'Transfer', 'Diterima', '2023-08-17 09:00:00'),
(10, NULL, 'Uang', 75000.00, NULL, 'Transfer', 'Diterima', '2023-07-22 15:00:00'),
(2, NULL, 'Uang', 125000.00, NULL, 'Transfer', 'Diterima', '2023-06-30 21:00:00'),
(3, NULL, 'Uang', 150000.00, NULL, 'Transfer', 'Diterima', '2023-05-25 18:00:00'),
(4, NULL, 'Uang', 200000.00, NULL, 'Transfer', 'Ditolak', '2023-04-10 11:00:00');

-- Section: Tambah 40 Dummy Partisipasi Relawan
INSERT INTO `partisipasi_kegiatan` (`id_user_relawan_fk`, `id_kegiatan_fk`, `status_kehadiran`, `tanggal_pendaftaran`) VALUES
(12, 1, 'Terdaftar', '2025-07-20 10:00:00'), (13, 1, 'Terdaftar', '2025-07-21 11:00:00'),
(14, 1, 'Terdaftar', '2025-07-22 12:00:00'), (15, 1, 'Terdaftar', '2025-07-23 13:00:00'),
(16, 2, 'Terdaftar', '2025-07-24 14:00:00'), (17, 2, 'Terdaftar', '2025-07-25 15:00:00'),
(18, 2, 'Terdaftar', '2025-07-26 16:00:00'), (19, 3, 'Terdaftar', '2025-07-27 17:00:00'),
(20, 3, 'Terdaftar', '2025-07-28 18:00:00'), (21, 4, 'Terdaftar', '2025-07-29 19:00:00'),
(12, 4, 'Terdaftar', '2025-07-30 20:00:00'), (13, 5, 'Terdaftar', '2025-07-31 21:00:00'),
(14, 5, 'Terdaftar', '2025-08-01 10:00:00'), (15, 2, 'Terdaftar', '2025-08-02 11:00:00'),
(16, 3, 'Terdaftar', '2025-08-03 12:00:00'),
(17, 6, 'Hadir', '2025-03-15 10:00:00'), (18, 6, 'Hadir', '2025-03-16 11:00:00'),
(19, 6, 'Batal', '2025-03-17 12:00:00'), (20, 6, 'Hadir', '2025-03-18 13:00:00'),
(21, 7, 'Hadir', '2024-12-01 14:00:00'), (12, 7, 'Hadir', '2024-12-02 15:00:00'),
(13, 7, 'Hadir', '2024-12-03 16:00:00'), (14, 7, 'Hadir', '2024-12-04 17:00:00'),
(15, 8, 'Hadir', '2024-06-10 18:00:00'), (16, 8, 'Hadir', '2024-06-11 19:00:00'),
(17, 8, 'Hadir', '2024-06-12 20:00:00'), (18, 8, 'Hadir', '2024-06-13 21:00:00'),
(19, 9, 'Hadir', '2024-02-05 10:00:00'), (20, 9, 'Batal', '2024-02-06 11:00:00'),
(21, 9, 'Hadir', '2024-02-07 12:00:00'), (12, 9, 'Hadir', '2024-02-08 13:00:00'),
(13, 10, 'Hadir', '2023-11-15 14:00:00'), (14, 10, 'Hadir', '2023-11-16 15:00:00'),
(15, 10, 'Hadir', '2023-11-17 16:00:00'), (16, 10, 'Hadir', '2023-11-18 17:00:00'),
(17, 10, 'Hadir', '2023-11-19 18:00:00'), (18, 10, 'Hadir', '2023-11-19 19:00:00'),
(19, 10, 'Hadir', '2023-11-19 20:00:00'), (20, 10, 'Hadir', '2023-11-19 21:00:00');

-- --------------------------------------------------------

COMMIT;