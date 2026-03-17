<?php
require 'config.php';
$pdo = get_db_connection();
$stmt = $pdo->query('DESCRIBE members');
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Current columns in members table:\n";
foreach ($columns as $col) {
    echo $col['Field'] . " - " . $col['Type'] . "\n";
}
?>