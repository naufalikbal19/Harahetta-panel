
CREATE DATABASE IF NOT EXISTS harahetta_db;
USE harahetta_db;

-- Members table
CREATE TABLE IF NOT EXISTS members (
    id INT PRIMARY KEY AUTO_INCREMENT,
    phone VARCHAR(15) UNIQUE NOT NULL,
    nama VARCHAR(100),
    bank VARCHAR(50),
    nomor_rekening VARCHAR(30),
    password VARCHAR(255) NOT NULL,
    foto_ktp_depan VARCHAR(255),
    foto_ktp_belakang VARCHAR(255),
    foto_selfie VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Test member
INSERT IGNORE INTO members (phone, password, nama) VALUES 
('08123456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test Member') 
ON DUPLICATE KEY UPDATE phone=phone; -- password: password

-- Update existing Harahetta-panel tables (pinjaman, admins, settings) if needed
CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    full_name VARCHAR(100),
    role ENUM('admin', 'superadmin') DEFAULT 'admin',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ON DUPLICATE KEY UPDATE id=id;

INSERT IGNORE INTO admins (username, password, email, full_name, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@harahetta.com', 'Super Admin', 'superadmin');

CREATE TABLE IF NOT EXISTS pinjaman (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_number VARCHAR(50),
    username VARCHAR(50),
    phone_number VARCHAR(15),
    uid VARCHAR(20),
    nama_peminjam VARCHAR(100),
    jumlah_pinjaman DECIMAL(12,2),
    loan_period INT,
    sign VARCHAR(255),
    tanggal_pinjam DATE,
    status ENUM('pending', 'proses', 'lunas', 'macet') DEFAULT 'pending',
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS settings (
    key_name VARCHAR(50) PRIMARY KEY,
    value TEXT
) ON DUPLICATE KEY UPDATE value=value;

INSERT IGNORE INTO settings (key_name, value) VALUES 
('site_name', 'Harahetta Pinjaman Sejahtera'),
('favicon', 'favicon.ico');

