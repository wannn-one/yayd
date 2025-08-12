USE `yayd`;

INSERT INTO `users` (`id_role_fk`, `status_akun`, `nama_lengkap`, `email`, `password`, `nomor_telepon`, `alamat`, `jenis_kelamin`, `alasan_bergabung`) VALUES
(1, 'Aktif', 'Admin', 'admin@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081211110000', 'Jl. Admin No. 1, Surabaya', 'Laki-laki', 'Sebagai admin sistem untuk mengelola YAYD'),
(2, 'Pending', 'Ahmad Fauzi', 'donatur.ahmad@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081211110001', 'Jl. Kemang Raya No. 15, Jakarta Selatan', 'Laki-laki', 'Ingin berkontribusi membantu anak-anak yatim melalui donasi rutin'),
(2, 'Pending', 'Bella Puspita', 'donatur.bella@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081211110002', 'Jl. Diponegoro No. 88, Bandung', 'Perempuan', 'Tergerak hati untuk berbagi kepada sesama yang membutuhkan'),
(2, 'Aktif', 'Chandra Wijaya', 'donatur.chandra@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081211110003', 'Jl. Veteran No. 22, Malang', 'Laki-laki', 'Mendukung pendidikan dan kesejahteraan anak yatim piatu'),
(3, 'Pending', 'Kevin Sanjaya', 'relawan.kevin@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081311110011', 'Jl. Kertajaya No. 45, Surabaya', 'Laki-laki', 'Ingin terlibat langsung dalam kegiatan sosial dan membantu anak yatim'),
(3, 'Aktif', 'Lina Marlina', 'relawan.lina@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081311110012', 'Jl. Raya Darmo No. 77, Surabaya', 'Perempuan', 'Memiliki passion dalam bidang pendidikan anak dan ingin berbagi ilmu'),
(3, 'Aktif', 'Muhammad Zidan', 'relawan.zidan@example.com', '$2y$10$L0cxFDS/GXtCW1Gl5nxLG.tXf/78uq9/FZRcjpxSsC/sO/A1qZKtK', '081311110013', 'Jl. Gubeng Kertajaya No. 12, Surabaya', 'Laki-laki', 'Sebagai mahasiswa ingin mengaplikasikan ilmu dan pengalaman untuk membantu sesama');

-- Section: Tambah Kegiatan Asli
INSERT INTO `kegiatan` (`id_kegiatan`, `nama_kegiatan`, `deskripsi`, `tanggal_mulai`, `tanggal_selesai`, `lokasi`, `status`, `dokumentasi`) VALUES
(1, 'Pengabdian Masyarakat', 'Kegiatan pengabdian masyarakat di desa binaan, meliputi penyuluhan kesehatan dan pembagian sembako.', '2024-11-10 08:00:00', '2024-11-10 15:00:00', 'Desa Sumber Makmur', 'Selesai', 'assets/uploads/kegiatan/kegiatan_6895e8291a4c55.67246142.jpg'),
(2, 'Volume 12: Griya PMI', 'Kunjungan dan donasi untuk para lansia di Griya PMI, diisi dengan acara hiburan dan pemeriksaan kesehatan ringan.', '2025-02-22 09:00:00', '2025-02-22 13:00:00', 'Griya PMI Surabaya', 'Selesai', 'assets/uploads/kegiatan/kegiatan_6895e7498919d6.53778109.jpg'),
(3, 'Volume 13: Anak TPA', 'Mengajar dan bermain bersama anak-anak TPA di wilayah Keputih, serta memberikan donasi alat tulis dan Al-Quran.', '2025-05-18 15:00:00', '2025-05-18 17:00:00', 'TPA Al-Hikmah, Keputih', 'Selesai', 'assets/uploads/kegiatan/kegiatan_6895e728674b27.61378004.jpg'),
(4, 'Volume 14: Pengakraban + Bagi Maem', 'Acara keakraban internal relawan sekaligus berbagi makanan kepada masyarakat yang membutuhkan di sekitar area Taman Bungkul.', '2025-09-20 16:00:00', '2025-09-20 19:00:00', 'Taman Bungkul, Surabaya', 'Akan Datang', 'assets/uploads/kegiatan/kegiatan_6895e933d42a68.66470343.jpg'),
(5, 'Volume 15', 'Detail kegiatan akan segera diumumkan. Fokus pada bantuan pendidikan untuk anak-anak prasejahtera.', '2025-11-29 09:00:00', '2025-11-29 15:00:00', 'Akan Diumumkan', 'Akan Datang', 'assets/uploads/kegiatan/kegiatan_6895e45e0a40c5.21896281.jpg'),
(6, 'YAYD X BEM STIQ ISYKARIMA', 'Kolaborasi dalam menyelenggarakan seminar keagamaan dan penggalangan dana untuk pembangunan masjid di desa terpencil.', '2026-01-25 08:30:00', '2026-01-25 16:00:00', 'Kampus STIQ ISYKARIMA', 'Akan Datang', 'assets/uploads/kegiatan/kegiatan_6895e336b1dfe7.49325414.JPG');

-- Section: Tambah Dummy Donasi Terkait Kegiatan Asli
-- Asumsi ID Donatur: 2, 3, 4. ID Kegiatan: 1-6.
INSERT INTO `donasi` (`id_user_donatur_fk`, `id_kegiatan_fk`, `jenis_donasi`, `jumlah_uang`, `nama_barang`, `metode`, `status`, `tanggal_donasi`) VALUES
(2, 4, 'Uang', 250000.00, NULL, 'Transfer', 'Diterima', '2025-08-01 10:00:00'),
(3, 4, 'Barang', NULL, '50 Nasi Kotak', 'OTS', 'Diterima', '2025-07-30 11:30:00'),
(4, NULL, 'Uang', 500000.00, NULL, 'Transfer', 'Pending', '2025-07-28 09:00:00'),
(2, 1, 'Uang', 750000.00, NULL, 'Transfer', 'Diterima', '2024-11-01 18:00:00'),
(3, 2, 'Barang', NULL, 'Paket Sembako & Alat Kebersihan', 'COD', 'Diterima', '2025-02-15 12:00:00'),
(4, 3, 'Uang', 300000.00, NULL, 'Transfer', 'Diterima', '2025-05-10 20:00:00');

-- Section: Tambah Dummy Partisipasi Relawan Terkait Kegiatan Asli
-- Asumsi ID Relawan: 5, 6, 7. ID Kegiatan: 1-6.
INSERT INTO `partisipasi_kegiatan` (`id_user_relawan_fk`, `id_kegiatan_fk`, `status_kehadiran`) VALUES
(6, 4, 'Terdaftar'), -- Lina daftar di Volume 14
(7, 4, 'Terdaftar'), -- Zidan daftar di Volume 14
(5, 5, 'Terdaftar'), -- Kevin daftar di Volume 15
(6, 1, 'Hadir'),     -- Lina hadir di Pengabdian Masyarakat
(7, 2, 'Hadir'),     -- Zidan hadir di Griya PMI
(6, 3, 'Batal');     -- Lina batal hadir di Anak TPA

-- --------------------------------------------------------

COMMIT;
