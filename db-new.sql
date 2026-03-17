-- Update members table to new structure with only required columns
USE harahetta_db;

-- Rename existing columns
ALTER TABLE members CHANGE COLUMN id_number id_card_number VARCHAR(20);
ALTER TABLE members CHANGE COLUMN nomor_rekening bank_card_number VARCHAR(30);
ALTER TABLE members CHANGE COLUMN ip_client login_ip VARCHAR(45);
ALTER TABLE members CHANGE COLUMN joined joining_time TIMESTAMP;

-- Add new columns
ALTER TABLE members
ADD COLUMN avatar VARCHAR(255) AFTER mobile_number,
ADD COLUMN level INT DEFAULT 1 AFTER avatar,
ADD COLUMN gender ENUM('male', 'female', 'other') AFTER level,
ADD COLUMN birthday DATE AFTER gender,
ADD COLUMN loan_purpose TEXT AFTER birthday,
ADD COLUMN monthly_income DECIMAL(12,2) AFTER loan_purpose,
ADD COLUMN current_address TEXT AFTER monthly_income,
ADD COLUMN motto VARCHAR(255) AFTER current_address,
ADD COLUMN points INT DEFAULT 0 AFTER balance,
ADD COLUMN consecutive_login_days INT DEFAULT 0 AFTER points,
ADD COLUMN max_consecutive_login_days INT DEFAULT 0 AFTER consecutive_login_days,
ADD COLUMN last_login_time TIMESTAMP NULL AFTER max_consecutive_login_days,
ADD COLUMN login_time TIMESTAMP NULL AFTER last_login_time,
ADD COLUMN number_of_failures INT DEFAULT 0 AFTER login_ip,
ADD COLUMN joining_ip VARCHAR(45) AFTER number_of_failures;

-- Drop unnecessary columns
ALTER TABLE members
DROP COLUMN pending_approval,
DROP COLUMN email,
DROP COLUMN phone,
DROP COLUMN nama,
DROP COLUMN foto_ktp_depan,
DROP COLUMN foto_ktp_belakang,
DROP COLUMN foto_selfie,
DROP COLUMN created_at;

-- Update existing data if needed
UPDATE members SET joining_ip = login_ip WHERE joining_ip IS NULL;
