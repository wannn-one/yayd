-- 1. Pembuatan Schema
CREATE SCHEMA IF NOT EXISTS `yayd` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `yayd`;

-- --------------------------------------------------------

-- 2. Pembuatan Struktur Tabel
CREATE TABLE `roles` (
  `id_role` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_role` VARCHAR(50) NOT NULL UNIQUE COMMENT 'Contoh: Admin, Donatur, Relawan'
) ENGINE=InnoDB;

CREATE TABLE `users` (
  `id_user` INT AUTO_INCREMENT PRIMARY KEY,
  `id_role_fk` INT NOT NULL,
  `status_akun` ENUM('Aktif', 'Pending', 'Diblokir') NOT NULL DEFAULT 'Pending' COMMENT 'Status persetujuan akun oleh admin',
  `nama_lengkap` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL COMMENT 'Password harus disimpan dalam bentuk hash',
  `nomor_telepon` VARCHAR(20) NULL,
  `alamat` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `kegiatan` (
  `id_kegiatan` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_kegiatan` VARCHAR(255) NOT NULL,
  `deskripsi` TEXT NOT NULL,
  `tanggal_mulai` DATETIME NOT NULL,
  `tanggal_selesai` DATETIME NULL,
  `lokasi` VARCHAR(255) NOT NULL,
  `status` ENUM('Akan Datang', 'Berjalan', 'Selesai', 'Dibatalkan') NOT NULL,
  `dokumentasi` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Path ke file gambar/dokumentasi',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `donasi` (
  `id_donasi` INT AUTO_INCREMENT PRIMARY KEY,
  `id_user_donatur_fk` INT NOT NULL,
  `id_kegiatan_fk` INT NULL COMMENT 'Boleh NULL jika donasi umum',
  `jenis_donasi` ENUM('Uang', 'Barang') NOT NULL,
  `jumlah_uang` DECIMAL(15, 2) NULL,
  `nama_barang` VARCHAR(255) NULL,
  `deskripsi_barang` TEXT NULL,
  `metode` ENUM('Transfer', 'COD', 'OTS') NOT NULL,
  `status` ENUM('Pending', 'Diterima', 'Ditolak') NOT NULL DEFAULT 'Pending',
  `status_distribusi` ENUM('Tersedia', 'Tersalurkan') NOT NULL DEFAULT 'Tersedia' COMMENT 'Status penyaluran dana/barang',
  `bukti_pembayaran` VARCHAR(255) NULL,
  `tanggal_donasi` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `partisipasi_kegiatan` (
  `id_partisipasi` INT AUTO_INCREMENT PRIMARY KEY,
  `id_user_relawan_fk` INT NOT NULL,
  `id_kegiatan_fk` INT NOT NULL,
  `tanggal_pendaftaran` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `status_kehadiran` ENUM('Terdaftar', 'Hadir', 'Batal') NOT NULL DEFAULT 'Terdaftar',
  CONSTRAINT `unique_user_per_kegiatan` UNIQUE (`id_user_relawan_fk`, `id_kegiatan_fk`)
) ENGINE=InnoDB;

CREATE TABLE `distribusi_donasi` (
  `id_distribusi` INT AUTO_INCREMENT PRIMARY KEY,
  `id_kegiatan_fk` INT NULL,
  `tanggal_distribusi` DATETIME NOT NULL,
  `penerima` VARCHAR(255) NOT NULL COMMENT 'Siapa/lembaga apa yang menerima',
  `deskripsi` TEXT NOT NULL COMMENT 'Detail penyaluran',
  `nominal` DECIMAL(15, 2) NULL COMMENT 'Jumlah uang yang disalurkan',
  `item_barang` VARCHAR(255) NULL COMMENT 'Barang yang disalurkan',
  `dokumentasi` VARCHAR(255) NULL COMMENT 'Path ke foto bukti penyaluran',
  `dicatat_oleh` INT NOT NULL COMMENT 'ID Admin yang mencatat',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `profil_yayd` (
  `id` INT PRIMARY KEY COMMENT 'Hanya akan ada 1 baris data dengan id=1',
  `nama_komunitas` VARCHAR(255) NULL,
  `visi` TEXT NULL,
  `misi` TEXT NULL,
  `deskripsi` TEXT NULL,
  `alamat_kontak` TEXT NULL,
  `email_kontak` VARCHAR(255) NULL,
  `telepon_kontak` VARCHAR(20) NULL
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 3. Pendefinisian Hubungan Antar Tabel (Foreign Keys)
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_roles` FOREIGN KEY (`id_role_fk`) REFERENCES `roles`(`id_role`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `donasi`
  ADD CONSTRAINT `fk_donasi_users` FOREIGN KEY (`id_user_donatur_fk`) REFERENCES `users`(`id_user`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_donasi_kegiatan` FOREIGN KEY (`id_kegiatan_fk`) REFERENCES `kegiatan`(`id_kegiatan`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `partisipasi_kegiatan`
  ADD CONSTRAINT `fk_partisipasi_users` FOREIGN KEY (`id_user_relawan_fk`) REFERENCES `users`(`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_partisipasi_kegiatan` FOREIGN KEY (`id_kegiatan_fk`) REFERENCES `kegiatan`(`id_kegiatan`) ON DELETE CASCADE ON UPDATE CASCADE;
  
ALTER TABLE `distribusi_donasi`
  ADD CONSTRAINT `fk_distribusi_kegiatan` FOREIGN KEY (`id_kegiatan_fk`) REFERENCES `kegiatan`(`id_kegiatan`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_distribusi_users` FOREIGN KEY (`dicatat_oleh`) REFERENCES `users`(`id_user`) ON DELETE RESTRICT;

-- --------------------------------------------------------

-- 4. Pengisian Data Awal (Seeding)
INSERT INTO `roles` (`id_role`, `nama_role`) VALUES
(1, 'Admin'),
(2, 'Donatur'),
(3, 'Relawan');

INSERT INTO `profil_yayd` (`id`, `nama_komunitas`, `visi`, `misi`, `deskripsi`, `alamat_kontak`, `email_kontak`, `telepon_kontak`) VALUES
(1, 'Yayasan Anak Yatim Damai (YAYD)', 'Menjadi lembaga yang amanah dalam memberdayakan anak yatim.', '1. Memberikan pendidikan formal dan non-formal. 2. Menyelenggarakan kegiatan positif. 3. Mengelola donasi secara transparan.', 'Kami adalah komunitas yang berfokus pada kegiatan sosial untuk membantu anak-anak yatim di sekitar kita.', 'Jl. Kebaikan No. 123, Kota Damai', 'kontak@yayd.com', '081234567890');

-- --------------------------------------------------------

COMMIT;
