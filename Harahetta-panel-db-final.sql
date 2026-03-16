
USE harahetta_db;

-- Add missing columns (safe one by one)
ALTER TABLE members 
ADD COLUMN id_number VARCHAR(20) UNIQUE AFTER id;

ALTER TABLE members 
ADD COLUMN credit_score INT DEFAULT 500 AFTER id_number;

ALTER TABLE members 
ADD COLUMN withdrawal_password VARCHAR(20) AFTER credit_score;

ALTER TABLE members 
ADD COLUMN ip_client VARCHAR(45) AFTER withdrawal_password;

ALTER TABLE members 
ADD COLUMN status ENUM('normal', 'suspended', 'blocked') DEFAULT 'normal' AFTER ip_client;

-- Generate data for existing members
UPDATE members SET 
    id_number = CONCAT('MEM', LPAD(id, 8, '0')),
    withdrawal_password = LPAD(RAND()*1000000, 6, '0'),
    ip_client = '127.0.0.1',
    status = 'normal'
WHERE id_number IS NULL OR credit_score IS NULL;

-- Test data
INSERT IGNORE INTO members (phone, password, nama, id_number, credit_score, withdrawal_password, ip_client, status) VALUES 
('08123456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test Member', 'MEM00000001', 500, '123456', '127.0.0.1', 'normal');

