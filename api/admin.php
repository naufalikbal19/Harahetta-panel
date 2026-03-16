
<?php
// Clear output buffer and set headers first
ob_start();
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}
header('Content-Type: application/json');

require_once '../config.php';

try {
    $pdo = get_db_connection();
    $action = $_GET['action'] ?? $_POST['action'] ?? 'list';

    // Ensure admins table exists with test data
    $pdo->exec("CREATE TABLE IF NOT EXISTS admins (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100),
        full_name VARCHAR(100),
        role ENUM('admin', 'superadmin') DEFAULT 'admin',
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Insert test admin if none exist
    $stmt = $pdo->query("SELECT COUNT(*) FROM admins WHERE username = 'admin'");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO admins (username, password, email, full_name, role) VALUES
            ('admin', '" . password_hash('password', PASSWORD_DEFAULT) . "', 'admin@harahetta.com', 'Super Admin', 'superadmin')");
    }

    // Debugging: Log POST data and determined action
    error_log("API Admin: Raw POST data: " . print_r($_POST, true));
    error_log("API Admin: Raw GET data: " . print_r($_GET, true));

        switch ($action) {
        case 'get':
            $id = $_GET['id'] ?? 0;
            if ($id) {
                $stmt = $pdo->prepare('SELECT id, username, email, full_name, role, is_active, created_at FROM admins WHERE id=?');
                $stmt->execute([$id]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode(['success' => true, 'data' => $data ?: null]);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'ID admin tidak diberikan']);
            }
            break;

        case 'list':
            try {
                $stmt = $pdo->query('SELECT id, username, email, full_name, role, is_active, created_at FROM admins ORDER BY created_at DESC');
                $rows = $stmt->fetchAll();
            } catch (Exception $e) {
                $rows = [];
            }
            echo json_encode(['data' => $rows]);
            break;

        case 'save':
            $id = $_POST['id'] ?? 0;
            $password = $_POST['password'] ?? '';
            
            error_log("API Admin: ID: " . $id . ", Password provided: " . (!empty($password) ? 'Yes' : 'No'));
            // Checkbox yang tidak dicentang tidak akan dikirim dalam data POST
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            $data = [
                'username' => trim($_POST['username'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'full_name' => trim($_POST['full_name'] ?? ''),
                'role' => $_POST['role'] ?? 'admin',
                'is_active' => $is_active
            ];

            if (empty($data['username'])) {
                echo json_encode(['success' => false, 'error' => 'Username wajib diisi']);
                break;
            }
            

            if ($id) {
                // Update admin yang ada
                if ($password) {
                    $data['password'] = password_hash($password, PASSWORD_DEFAULT);
                }
                error_log("API Admin: Data sebelum update: " . print_r($data, true));
                $update_fields = [];
                foreach (array_keys($data) as $key) {
                    $update_fields[] = "$key = :$key";
                }
                $sql = 'UPDATE admins SET ' . implode(', ', $update_fields) . ' WHERE id = :id';
                $data['id'] = $id;
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute($data);
                if (!$result) {
                    error_log("Update failed: " . print_r($stmt->errorInfo(), true));
                    echo json_encode(['success' => false, 'error' => 'Update admin gagal']);
                    break;
                }
            } else {
                // Tambah admin baru
                error_log("API Admin: Adding new admin");
                $data['password'] = password_hash($password ?: 'password', PASSWORD_DEFAULT);
                $fields = implode(', ', array_keys($data));
                $placeholders = ':' . implode(', :', array_keys($data));
                $sql = "INSERT INTO admins ($fields) VALUES ($placeholders)";
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute($data);
                 if (!$result) {
                    error_log("Insert failed: " . print_r($stmt->errorInfo(), true));
                    echo json_encode(['success' => false, 'error' => 'Tambah admin gagal']);
                    break;
                }
            }
            echo json_encode(['success' => true, 'message' => 'Admin berhasil disimpan']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? $_GET['id'] ?? 0;
            $stmt = $pdo->prepare('DELETE FROM admins WHERE id=?');
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        default:
            http_response_code(400); // Bad Request
            echo json_encode(['data' => [], 'error' => 'Aksi tidak valid']);
            break;
    }
} catch (Exception $e) {
    error_log("Admin API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['data' => [], 'error' => 'Server error']);
}

?>
