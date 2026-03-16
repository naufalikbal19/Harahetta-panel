
USE harahetta_db;

-- Skip if columns exist (manual check)
SET @column_exists = (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = 'harahetta_db' AND TABLE_NAME = 'members' AND COLUMN_NAME = 'id_number'
);
SET @sql = IF(@column_exists = 0, 
    'ALTER TABLE members ADD COLUMN id_number VARCHAR(20) UNIQUE AFTER id', 
    'SELECT "id_number already exists" as status');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @column_exists = (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = 'harahetta_db' AND TABLE_NAME = 'members' AND COLUMN_NAME = 'credit_score'
);
SET @sql = IF(@column_exists = 0, 
    'ALTER TABLE members ADD COLUMN credit_score INT DEFAULT 500 AFTER id_number', 
    'SELECT "credit_score already exists" as status');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Repeat for other columns...
SET @column_exists = (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = 'harahetta_db' AND TABLE_NAME = 'members' AND COLUMN_NAME = 'withdrawal_password'
);
SET @sql = IF(@column_exists = 0, 
    'ALTER TABLE members ADD COLUMN withdrawal_password VARCHAR(20) AFTER credit_score', 
    'SELECT "withdrawal_password already exists" as status');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @column_exists = (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = 'harahetta_db' AND TABLE_NAME = 'members' AND COLUMN_NAME = 'ip_client'
);
SET @sql = IF(@column_exists = 0, 
    'ALTER TABLE members ADD COLUMN ip_client VARCHAR(45) AFTER withdrawal_password', 
    'SELECT "ip_client already exists" as status');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @column_exists = (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = 'harahetta_db' AND TABLE_NAME = 'members' AND COLUMN_NAME = 'status'
);
SET @sql = IF(@column_exists = 0, 
    'ALTER TABLE members ADD COLUMN status ENUM("normal", "suspended", "blocked") DEFAULT "normal" AFTER ip_client', 
    'SELECT "status already exists" as status');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Generate data
UPDATE members SET 
    id_number = CONCAT('MEM', LPAD(id, 8, '0')),
    withdrawal_password = LPAD(RAND()*1000000, 6, '0'),
    ip_client = '127.0.0.1',
    status = 'normal'
WHERE id_number IS NULL OR withdrawal_password IS NULL;

SELECT 'SUCCESS: All columns ready!' as status;
DESCRIBE members;
SELECT * FROM members LIMIT 5;

