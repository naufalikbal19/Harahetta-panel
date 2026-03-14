-- Rekreasi DB untuk Harahetta Admin Panel Pinjaman Sejahtera
-- Jalankan: mysql -u root -p < db.sql (ubah user/pass jika perlu)
-- DB: harahetta_db, Table: pinjaman

CREATE DATABASE IF NOT EXISTS harahetta_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE harahetta_db;

DROP TABLE IF EXISTS `pinjaman`;
CREATE TABLE `pinjaman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) DEFAULT NULL COMMENT 'Order Number',
  `username` varchar(50) DEFAULT NULL COMMENT 'Username',
  `phone_number` varchar(20) DEFAULT NULL COMMENT 'Phone Number',
  `uid` varchar(50) DEFAULT NULL COMMENT 'UID',
  `nama_peminjam` varchar(100) DEFAULT NULL COMMENT 'Nama Peminjam',
  `jumlah_pinjaman` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Loan Amount',
  `loan_period` int(11) DEFAULT NULL COMMENT 'Loan Period (days)',

  `sign` varchar(255) DEFAULT NULL COMMENT 'Signature path',
  `id_front` varchar(255) DEFAULT NULL COMMENT 'Foto Depan ID Card',
  `id_back` varchar(255) DEFAULT NULL COMMENT 'Foto Belakang ID Card',
  `selfie` varchar(255) DEFAULT NULL COMMENT 'Photo Selfie',
  `bank` varchar(50) DEFAULT NULL COMMENT 'Bank',
  `no_rekening` varchar(50) DEFAULT NULL COMMENT 'Nomor Rekening',
  `tanggal_pinjam` date NOT NULL COMMENT 'Application Time',

  `status` enum('pending','proses','lunas','macet') NOT NULL DEFAULT 'pending' COMMENT 'Status',
  `keterangan` text DEFAULT NULL COMMENT 'Keterangan',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabel Pinjaman';

-- Sample data (more dummy)
INSERT INTO `pinjaman` (`order_number`, `username`, `phone_number`, `uid`, `nama_peminjam`, `jumlah_pinjaman`, `loan_period`, `sign`, `tanggal_pinjam`, `status`, `keterangan`) VALUES
('ORD2024001', 'user001', 'user001', 'U2024001ABC', 'John Doe', 5000000.00, 30, 'signs/001.jpg', '2024-01-15', 'pending', 'Pinjaman modal usaha'),
('ORD2024002', 'user002', 'user002', 'U2024002DEF', 'Jane Smith', 10000000.00, 60, 'signs/002.jpg', '2024-02-01', 'proses', 'Pinjaman rumah'),
('ORD2024003', 'user003', 'user003', 'U2024003GHI', 'Bob Johnson', 7500000.00, 45, 'signs/003.jpg', '2023-12-10', 'lunas', 'Pinjaman lunas tepat waktu'),
('ORD2024004', 'user004', 'user004', 'U2024004JKL', 'Alice Brown', 3000000.00, 30, 'signs/004.jpg', '2024-03-01', 'macet', 'Belum bayar cicilan'),
('ORD2024005', 'user005', 'user005', 'U2024005MNO', 'Test User 5', 2000000.00, 15, 'signs/005.jpg', '2024-04-01', 'pending', 'Test dummy'),
('ORD2024006', 'user006', 'user006', 'U2024006PQR', 'Demo User 6', 15000000.00, 90, 'signs/006.jpg', '2024-04-05', 'proses', 'Demo pinjaman besar'),
('ORD2024007', 'user007', 'user007', 'U2024007STU', 'User Seven', 8000000.00, 60, 'signs/007.jpg', '2024-03-20', 'lunas', 'Lunas dummy'),
('ORD2024008', 'user008', 'user008', 'U2024008VWX', 'Borrower 8', 4000000.00, 30, 'signs/008.jpg', '2024-04-10', 'macet', 'Macet test');

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key_name` varchar(50) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_name` (`key_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabel Settings';


-- Default settings
INSERT INTO `settings` (`key_name`, `value`) VALUES
('site_name', 'Harahetta Pinjaman Sejahtera'),
('favicon', 'favicon.ico'),
('logo', 'logo.png');

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `role` enum('superadmin','admin') DEFAULT 'admin',
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Admin Users';

-- Default superadmin
INSERT INTO `admins` (`username`, `password`, `full_name`, `role`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Admin', 'superadmin');
-- password: password

