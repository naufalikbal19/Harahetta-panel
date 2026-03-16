<?php
require_once 'config.php';
$pdo = get_db_connection();
echo "<h2>DB Test</h2>";
$stmt = $pdo->query('SELECT COUNT(*) FROM pinjaman');
echo 'Pinjaman count: ' . $stmt->fetchColumn() . '<br>';

$stmt = $pdo->query('SELECT COUNT(*) FROM members');
echo 'Members count: ' . $stmt->fetchColumn() . '<br>';

$stmt = $pdo->query('SELECT COUNT(*) FROM pinjaman');
echo 'Join count: ' . $stmt->fetchColumn() . '<br>';

$result = $pdo->query("SELECT p.phone_number, m.phone FROM pinjaman p LEFT JOIN members m ON RIGHT(TRIM(p.phone_number),13) COLLATE utf8mb4_unicode_ci = RIGHT(TRIM(m.phone),13) COLLATE utf8mb4_unicode_ci LIMIT 5");
echo '<br>Sample phones:<br><pre>' . print_r($result->fetchAll(PDO::FETCH_ASSOC), true) . '</pre>';

echo '<hr><a href="test-db.php">Refresh</a> | <a href="withdrawal-records.php">Withdrawal Records</a>';
?>

