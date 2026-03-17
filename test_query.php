<?php
require 'config.php';
$pdo = get_db_connection();
$stmt = $pdo->query('SELECT id, credit_score, balance, pending_approval, name, nickname, email, mobile_number, withdrawal_password, joined, ip_client AS ip, status FROM members ORDER BY id DESC LIMIT 5');
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['data' => $data]);
?>