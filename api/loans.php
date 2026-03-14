<?php
session_start();
header('Content-Type: application/json');
// Bypass auth temporarily for testing
// if (!isset($_SESSION['admin_logged_in'])) {
//     exit(json_encode(['error' => 'Unauthorized']));
// }

require_once '../config.php';
require_once '../config.php'; // Force load function
$pdo = get_db_connection();

if (isset($_GET['id'])) {
    // Get single for edit
    try {
        $id = (int)$_GET['id'];
        if (!$id) {
            echo json_encode(['error' => 'ID tidak diberikan']);
        } else {
            $stmt = $pdo->prepare('SELECT * FROM pinjaman WHERE id=?');
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                echo json_encode($data);
            } else {
                echo json_encode(['error' => 'Data tidak ditemukan untuk ID ' . $id]);
            }
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'DB Error: ' . $e->getMessage()]);
    }
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? 'list';

switch ($action) {
    case 'list':
        // Bypass auth for list temporarily
        $stmt = $pdo->query('SELECT * FROM pinjaman ORDER BY id DESC');
        echo json_encode($stmt->fetchAll());
        break;

    case 'save':
        $id = $_POST['id'] ?? 0;



        $data = [
            'order_number' => $_POST['order_number'] ?? 'ORD' . date('Ymd') . rand(100,999),
            'username' => trim($_POST['username'] ?? ''),
            'phone_number' => trim($_POST['phone_number'] ?? ''),
            'uid' => $_POST['uid'] ?? 'U' . date('Y') . rand(1000,9999) . chr(65+rand(0,25)) . chr(65+rand(0,25)),
            'nama_peminjam' => trim($_POST['nama_peminjam'] ?? ''),
            'jumlah_pinjaman' => floatval($_POST['jumlah_pinjaman'] ?? 0),
            'loan_period' => intval($_POST['loan_period'] ?? 0),
            'sign' => trim($_POST['sign'] ?? ''),
            'id_front' => trim($_POST['id_front'] ?? ''),
            'id_back' => trim($_POST['id_back'] ?? ''),
            'selfie' => trim($_POST['selfie'] ?? ''),
            'bank' => trim($_POST['bank'] ?? ''),
            'no_rekening' => trim($_POST['no_rekening'] ?? ''),
            'status' => $_POST['status'] ?? 'pending',
            'tanggal_pinjam' => $_POST['tanggal_pinjam'] ?? date('Y-m-d'),
            'keterangan' => trim($_POST['keterangan'] ?? '')
        ];





        $data['sign'] = trim($_POST['sign'] ?? '');

        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $allowed = ['jpg', 'jpeg', 'png'];

        // Sign upload
        if (isset($_FILES['sign_file']) && $_FILES['sign_file']['error'] === UPLOAD_ERR_OK) {
            $file_ext = strtolower(pathinfo($_FILES['sign_file']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, $allowed)) {
                $unique_name = uniqid() . '_sign_' . time() . '.' . $file_ext;
                $upload_path = $upload_dir . $unique_name;
                if (move_uploaded_file($_FILES['sign_file']['tmp_name'], $upload_path)) {
                    $data['sign'] = 'uploads/' . $unique_name;
                }
            }
        }

        // ID Front upload
        if (isset($_FILES['id_front_file']) && $_FILES['id_front_file']['error'] === UPLOAD_ERR_OK) {
            $file_ext = strtolower(pathinfo($_FILES['id_front_file']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, $allowed)) {
                $unique_name = uniqid() . '_idfront_' . time() . '.' . $file_ext;
                $upload_path = $upload_dir . $unique_name;
                if (move_uploaded_file($_FILES['id_front_file']['tmp_name'], $upload_path)) {
                    $data['id_front'] = 'uploads/' . $unique_name;
                }
            }
        }

        // ID Back upload
        if (isset($_FILES['id_back_file']) && $_FILES['id_back_file']['error'] === UPLOAD_ERR_OK) {
            $file_ext = strtolower(pathinfo($_FILES['id_back_file']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, $allowed)) {
                $unique_name = uniqid() . '_idback_' . time() . '.' . $file_ext;
                $upload_path = $upload_dir . $unique_name;
                if (move_uploaded_file($_FILES['id_back_file']['tmp_name'], $upload_path)) {
                    $data['id_back'] = 'uploads/' . $unique_name;
                }
            }
        }

        // Selfie upload
        if (isset($_FILES['selfie_file']) && $_FILES['selfie_file']['error'] === UPLOAD_ERR_OK) {
            $file_ext = strtolower(pathinfo($_FILES['selfie_file']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, $allowed)) {
                $unique_name = uniqid() . '_selfie_' . time() . '.' . $file_ext;
                $upload_path = $upload_dir . $unique_name;
                if (move_uploaded_file($_FILES['selfie_file']['tmp_name'], $upload_path)) {
                    $data['selfie'] = 'uploads/' . $unique_name;
                }
            }
        }


        try {
            if ($id) {
                // Update
                $sql = 'UPDATE pinjaman SET ' . implode(', ', array_map(fn($k) => "$k=?", array_keys($data))) . ' WHERE id=?';
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array_values($data) + [$id]);
            } else {
                // Insert
                $sql = 'INSERT INTO pinjaman (' . implode(', ', array_keys($data)) . ') VALUES (' . implode(',', array_fill(0, count($data), '?')) . ')';
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array_values($data));
            }
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'DB Error: ' . $e->getMessage()]);
        }

        break;

    case 'delete':
        $id = $_POST['id'] ?? $_GET['id'] ?? 0;
        try {
            $stmt = $pdo->prepare('DELETE FROM pinjaman WHERE id=?');
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'DB Error: ' . $e->getMessage()]);
        }
        break;

    default:
        // Get single for edit
        try {
            $id = (int)($_GET['id'] ?? 0);
            if (!$id) {
                echo json_encode(['error' => 'ID tidak diberikan']);
            } else {
                $stmt = $pdo->prepare('SELECT * FROM pinjaman WHERE id=?');
                $stmt->execute([$id]);
                $data = $stmt->fetch();
                if ($data) {
                    echo json_encode($data);
                } else {
                    echo json_encode(['error' => 'Data tidak ditemukan untuk ID ' . $id]);
                }
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'DB Error: ' . $e->getMessage()]);
        }
}
?>

