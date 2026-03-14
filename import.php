<?php
require_once 'config.php';
$pdo = get_db_connection();

$sql = file_get_contents('db.sql');
$statements = array_filter(array_map('trim', explode(';', $sql)));

foreach ($statements as $statement) {
    if (!empty($statement)) {
        try {
            $pdo->exec($statement);
            echo "Executed: " . substr($statement, 0, 50) . "...\n";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}

echo "Import completed.\n";
?>