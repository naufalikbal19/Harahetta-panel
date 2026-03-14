
<?php
header('Content-Type: application/json');
require_once '../config.php';

$pdo = get_db_connection();

$action = $_GET['action'] ?? $_POST['action'] ?? 'list';

if ($action === 'list') {
    $stmt = $pdo->query("SELECT l.*, a.username 
        FROM admin_logs l 
        LEFT JOIN admins a ON l.admin_id = a.id 
        ORDER BY l.created_at DESC");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
?>

