
<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode([]);
    exit;
}


require_once '../config.php';
$pdo = get_db_connection();

$action = $_GET['action'] ?? $_POST['action'] ?? 'list';

switch ($action) {

    case 'list':
        $sql = "SELECT id_number, credit_score, nama, phone, withdrawal_password, ip_client, status, created_at 
                FROM members ORDER BY created_at DESC";
        $stmt = $pdo->query($sql);
        echo json_encode($stmt->fetchAll());
        break;

}
?>

