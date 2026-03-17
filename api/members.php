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
        $id = $_GET['id'] ?? $_POST['id'] ?? $_GET['id_card_number'] ?? $_POST['id_card_number'] ?? null;
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM members WHERE id_card_number = ? OR id = ?");
            $stmt->execute([$id, $id]);
            $member = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($member ?: ['error' => 'Member not found']);
        } else {
            echo json_encode(['error' => 'ID required']);
        }
        break;

    case 'update':
        $id = $_POST['id'] ?? null;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID required']);
            break;
        }
        $update_data = [
            'credit_score' => $_POST['credit_score'] ?? 0,
            'nickname' => $_POST['nickname'] ?? '',
            'password' => $_POST['password'] ?? '',
            'withdrawal_password' => $_POST['withdrawal_password'] ?? '',
            'mobile_number' => $_POST['mobile_number'] ?? '',
            'level' => $_POST['level'] ?? 1,
            'gender' => $_POST['gender'] ?? 'other',
            'name' => $_POST['name'] ?? '',
            'id_card_number' => $_POST['id_card_number'] ?? '',
            'bank' => $_POST['bank'] ?? '',
            'bank_card_number' => $_POST['bank_card_number'] ?? '',
            'birthday' => $_POST['birthday'] ?? null,
            'loan_purpose' => $_POST['loan_purpose'] ?? '',
            'monthly_income' => $_POST['monthly_income'] ?? 0,
            'current_address' => $_POST['current_address'] ?? '',
            'motto' => $_POST['motto'] ?? '',
            'balance' => $_POST['balance'] ?? 0,
            'points' => $_POST['points'] ?? 0,
            'consecutive_login_days' => $_POST['consecutive_login_days'] ?? 0,
            'max_consecutive_login_days' => $_POST['max_consecutive_login_days'] ?? 0,
            'number_of_failures' => $_POST['number_of_failures'] ?? 0,
            'status' => $_POST['status'] ?? 'normal'
        ];
        $set_sql = implode(', ', array_map(fn($k) => "$k = ?", array_keys($update_data)));
        $sql = "UPDATE members SET $set_sql WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute(array_values($update_data) + [$id]);
        echo json_encode(['success' => $success, 'message' => $success ? 'Updated' : 'Update failed']);
        break;

    case 'delete':
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM members WHERE id = ?");
            $success = $stmt->execute([$id]);
            echo json_encode(['success' => $success, 'message' => $success ? 'Deleted' : 'Delete failed']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID required']);
        }
        break;

    default: // list
        try {
            $sql = "SELECT id, credit_score, nickname, password, withdrawal_password, mobile_number, avatar, level, gender, name, id_card_number, bank, bank_card_number, birthday, loan_purpose, monthly_income, current_address, motto, balance, points, consecutive_login_days, max_consecutive_login_days, last_login_time, login_time, login_ip, number_of_failures, joining_ip, joining_time, status FROM members ORDER BY id DESC";
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

