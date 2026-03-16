
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

$members = [];
try {
    // 1. Ambil semua kolom yang diperlukan oleh DataTables (id_number, credit_score, dll.)
    //    Saya berasumsi nama kolom di database sesuai dengan yang ada di JavaScript DataTables.
    $sql = "SELECT id, id_number, credit_score, phone, withdrawal_password,ip_client, status FROM members ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    $members = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (Exception $e) {
    // Jika terjadi error, kembalikan array kosong agar tidak merusak DataTables di client-side.
    // Anda bisa menambahkan logging error di sini jika perlu: error_log($e->getMessage());
    $members = [];
}
header('Content-Type: application/json');
// 2. Bungkus output dalam properti 'data' yang diharapkan oleh DataTables
echo json_encode(['data' => $members]);
?>
