<?php
require_once 'config.php';
$pdo = get_db_connection();

$sql = file_get_contents('db.sql');

// Split by semicolon, but handle CREATE DATABASE separately
$statements = array_filter(array_map('trim', explode(';', $sql)));

foreach ($statements as $statement) {
    if (!empty($statement) && !preg_match('/^--/', $statement)) {
        try {
            $pdo->exec($statement);
            echo "Executed: " . substr($statement, 0, 50) . "...<br>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "<br>";
        }
    }
}

echo "Setup completed.";
?>