
<?php
session_start();
header('Content-Type: application/json');
require_once '../config.php';

$pdo = get_db_connection();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare('SELECT id, username, email, full_name, role, is_active, created_at FROM admins WHERE id=?');
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($data) {
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'Admin tidak ditemukan']);
    }
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? 'list';

switch ($action) {
    case 'list':
        $stmt = $pdo->query('SELECT id, username, email, full_name, role, is_active, created_at FROM admins ORDER BY created_at DESC');
        echo json_encode($stmt->fetchAll());
        break;

    case 'save':
        $id = $_POST['id'] ?? 0;
        $password = $_POST['password'] ?? '';
        
        $data = [
            'username' => trim($_POST['username'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'full_name' => trim($_POST['full_name'] ?? ''),
            'role' => $_POST['role'] ?? 'admin',
            'is_active' => (int)($_POST['is_active'] ?? 1)
        ];

        if ($password) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        try {
            if ($id) {
                $sql = 'UPDATE admins SET ' . implode(', ', array_map(fn($k) => "$k=?", array_keys($data))) . ' WHERE id=?';
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array_values($data) + [$id]);
            } else {
                $sql = 'INSERT INTO admins (' . implode(', ', array_keys($data)) . ', password) VALUES (' . implode(',', array_fill(0, count($data), '?')) . ', ?)';
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array_values($data) + [password_hash($_POST['password'], PASSWORD_DEFAULT)]);
            }
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'DB Error: ' . $e->getMessage()]);
        }
        break;

    case 'delete':
        $id = $_POST['id'] ?? $_GET['id'] ?? 0;
        try {
            $stmt = $pdo->prepare('DELETE FROM admins WHERE id=?');
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'DB Error: ' . $e->getMessage()]);
        }
        break;
}
?>

