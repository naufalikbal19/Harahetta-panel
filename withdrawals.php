<?php
session_start();
header('Content-Type: application/json');

// Temp bypass auth for testing - remove after fix
// if (!isset($_SESSION['admin_logged_in'])) {
//     echo json_encode(['data' => []]);
//     exit;
// }


require_once '../config.php';
$pdo = get_db_connection();

$action = $_GET['action'] ?? $_POST['action'] ?? 'list';

switch ($action) {
    case 'get':
        $id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("SELECT * FROM pinjaman WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($data ?: ['error' => 'Data not found']);
        break;

    case 'update':
        $id = $_POST['id'] ?? 0;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID missing']);
            break;
        }
        // Update hanya field yang relevan untuk Withdrawal Records
        $stmt = $pdo->prepare("UPDATE pinjaman SET status = ?, bank = ?, no_rekening = ? WHERE id = ?");
        $success = $stmt->execute([
            $_POST['status'] ?? 'pending',
            $_POST['bank'] ?? '',
            $_POST['no_rekening'] ?? '',
            $id
        ]);
        echo json_encode(['success' => $success]);
        break;

    case 'delete':
        $id = $_POST['id'] ?? 0;
        $stmt = $pdo->prepare("DELETE FROM pinjaman WHERE id = ?");
        $success = $stmt->execute([$id]);
        echo json_encode(['success' => $success]);
        break;

    default: // list
        try {
            $sql = "SELECT id, uid, phone_number, jumlah_pinjaman, tanggal_pinjam, status, bank, no_rekening 
                    FROM pinjaman ORDER BY id DESC";
            $stmt = $pdo->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['data' => $data]);
        } catch (Exception $e) {
            // Log the error for debugging purposes
            error_log("Withdrawals API Error (list action): " . $e->getMessage());
            echo json_encode(['data' => []]);
        }
        break;
}