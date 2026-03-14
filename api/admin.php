
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

        try {
            if ($id) {
                $update_fields = [];
                $params = [];
                foreach ($data as $key => $value) {
                    $update_fields[] = "$key=?";
                    $params[] = $value;
                }
                $sql = 'UPDATE admins SET ' . implode(', ', $update_fields) . ' WHERE id=?';
                $params[] = $id;
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                
                if ($password) {
                    $stmt = $pdo->prepare('UPDATE admins SET password = ? WHERE id = ?');
                    $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $id]);
                }
            } else {

                $fields = array_keys($data);
                $fields[] = 'password';
                $placeholders = implode(',', array_fill(0, count($fields), '?'));
                $sql = 'INSERT INTO admins (' . implode(', ', $fields) . ') VALUES (' . $placeholders . ')';
                $params = array_values($data);
                $params[] = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);

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

