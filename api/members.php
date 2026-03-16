<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['data' => []]);
    exit;
}

require_once '../config.php';
$pdo = get_db_connection();
$action = $_GET['action'] ?? $_POST['action'] ?? 'list';

switch ($action) {
    case 'get':
        $id = $_GET['id'] ?? $_POST['id'] ?? $_GET['id_number'] ?? $_POST['id_number'] ?? null;
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM members WHERE id_number = ? OR id = ?");
            $stmt->execute([$id, $id]);
            $member = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($member ?: ['error' => 'Member not found']);
        } else {
            echo json_encode(['error' => 'ID required']);
        }
        break;

    case 'update':
        $id_number = $_POST['id_number'] ?? null;
        if (!$id_number) {
            echo json_encode(['success' => false, 'message' => 'ID required']);
            break;
        }
        $update_data = [
            'credit_score' => $_POST['credit_score'] ?? 0,
            'phone' => $_POST['phone'] ?? '',
            'withdrawal_password' => $_POST['withdrawal_password'] ?? '',
            'ip_client' => $_POST['ip_client'] ?? '',
            'status' => $_POST['status'] ?? 'normal'
        ];
        $set_sql = implode(', ', array_map(fn($k) => "$k = ?", array_keys($update_data)));
        $sql = "UPDATE members SET $set_sql WHERE id_number = ?";
        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute(array_values($update_data) + [$id_number]);
        echo json_encode(['success' => $success, 'message' => $success ? 'Updated' : 'Update failed']);
        break;

    case 'delete':
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM members WHERE id_number = ? OR id = ?");
            $stmt->execute([$id, $id]);
        }
        echo json_encode(['success' => true]);
        break;

    default: // list
        try {
            $sql = "SELECT id, id_number, credit_score, phone, withdrawal_password, ip_client, status FROM members ORDER BY id DESC";
            $stmt = $pdo->query($sql);
            $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $members = [];
        }
        echo json_encode(['data' => $members]);
        break;
}
?>

