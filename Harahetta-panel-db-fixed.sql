
-- UPDATE EXISTING harahetta_db
USE harahetta_db;

-- Add new members table columns if not exist
ALTER TABLE members ADD COLUMN IF NOT EXISTS id_number VARCHAR(20) UNIQUE AFTER id;
ALTER TABLE members ADD COLUMN IF NOT EXISTS credit_score INT DEFAULT 500 AFTER id_number;
ALTER TABLE members ADD COLUMN IF NOT EXISTS withdrawal_password VARCHAR(20) AFTER credit_score;
ALTER TABLE members ADD COLUMN IF NOT EXISTS ip_client VARCHAR(45) AFTER withdrawal_password;
ALTER TABLE members ADD COLUMN IF NOT EXISTS status ENUM('normal', 'suspended', 'blocked') DEFAULT 'normal' AFTER ip_client;

-- Generate id_number for existing members
UPDATE members SET id_number = CONCAT('MEM', LPAD(id, 8, '0')) WHERE id_number IS NULL;

-- Test member
UPDATE members SET credit_score = 500, status = 'normal', ip_client = '127.0.0.1' WHERE phone = '08123456789';
INSERT IGNORE INTO members (phone, password, id_number, credit_score, withdrawal_password, ip_client, status, nama) VALUES 
('08123456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'MEM00000001', 500, '123456', '127.0.0.1', 'normal', 'Test Member');

