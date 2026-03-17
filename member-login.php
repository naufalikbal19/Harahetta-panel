
<?php
session_start();
if (isset($_SESSION['member_logged_in'])) {
    header('Location: member-dashboard.php');
    exit;
}

$error = '';

if ($_POST) {
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($phone) || empty($password)) {
        $error = 'Nomor HP dan password wajib diisi';
    } else {
        try {
            require_once __DIR__ . '/config.php';
            $pdo = get_db_connection();
            // Check if phone exists
            $stmt = $pdo->prepare("SELECT * FROM members WHERE mobile_number = ?");
            $stmt->execute([$phone]);
            $member = $stmt->fetch();

            if ($member) {
                // Login - check password
                if (password_verify($password, $member['password'])) {
                    // Update login info
                    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
                    $update_stmt = $pdo->prepare("UPDATE members SET last_login_time = NOW(), login_time = NOW(), login_ip = ? WHERE id = ?");
                    $update_stmt->execute([$ip, $member['id']]);
                    
                    $_SESSION['member_logged_in'] = true;
                    $_SESSION['member_id'] = $member['id'];
                    $_SESSION['phone'] = $member['mobile_number'];
                    header('Location: member-dashboard.php');
                    exit;
                } else {
                    $error = 'Password salah!';
                }
            } else {
                // Register new member
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
                $stmt = $pdo->prepare("INSERT INTO members (mobile_number, nickname, password, login_ip, joining_ip, joining_time, login_time, last_login_time) VALUES (?, ?, ?, ?, ?, NOW(), NOW(), NOW())");
                $stmt->execute([$phone, $phone, $hashed_password, $ip, $ip]);
                $member_id = $pdo->lastInsertId();
                
                $_SESSION['member_logged_in'] = true;
                $_SESSION['member_id'] = $member_id;
                $_SESSION['phone'] = $phone;
                $_SESSION['nama'] = $phone;
                header('Location: member-dashboard.php');
                exit;
            }
        } catch (PDOException $e) {
            $error = 'Error database: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Login Member </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .login-body {
            padding: 40px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="bi bi-shield-check display-4 mb-3"></i>
            <h3>Selamat Datang</h3>
            <p>Masukkan nomor HP dan password Anda</p>
        </div>
        <div class="login-body">
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-4">
                    <label class="form-label">Nomor HP <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-phone"></i></span>
                        <input type="tel" class="form-control form-control-lg" name="phone" placeholder="08xxxxxxxxxx" required value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                    </div>
                    <div class="form-text">Jika nomor belum terdaftar, akan otomatis dibuat akun baru</div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control form-control-lg" name="password" placeholder="Masukkan password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-box-arrow-in-right"></i> MASUK / DAFTAR
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
