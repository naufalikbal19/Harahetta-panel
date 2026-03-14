<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'config.php';

if (isset($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
// Generate simple captcha
if (!isset($_SESSION['captcha'])) {
    $_SESSION['captcha'] = rand(1000, 9999);
}

if ($_POST) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $captcha = $_POST['captcha'] ?? '';
    

    // Check captcha
    if ($captcha != $_SESSION['captcha']) {
        $error = 'Captcha salah!';


    } else {
        try {
            $pdo = get_db_connection();
            $stmt = $pdo->prepare("SELECT id, username, full_name, password FROM admins WHERE username = ? AND is_active = 1");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();
        } catch (Exception $e) {
            $error = 'Database error. Jalankan setup.php terlebih dahulu.';
        }

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['username'] = $admin['username'];
            $_SESSION['full_name'] = $admin['full_name'];
            if (isset($_POST['keeplogin'])) {
                $_SESSION['expires'] = time() + 86400;
            }
            unset($_SESSION['captcha']);
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Username atau password salah!';
        }
    }


    // Regenerate captcha on error
    $_SESSION['captcha'] = rand(1000, 9999);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Login Admin - <?php echo htmlspecialchars(get_setting('site_name', 'Harahetta Pinjaman Sejahtera')); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="<?php echo htmlspecialchars(get_setting('favicon', 'favicon.ico')); ?>">
</head>
<body>
    <div class="container">
        <div class="login-wrapper">
            <div class="login-screen">
                <div class="well">
                    <div class="login-head">
                        <h3>Panel Admin <?php echo htmlspecialchars(get_setting('site_name', 'Pinjaman Sejahtera')); ?></h3>
                        <small>Sistem Manajemen Pinjaman</small>
                    </div>
                    <div class="login-form">
                        <img class="profile-img-card" src="https://via.placeholder.com/100?text=Admin" alt="Admin Avatar"/>
                        <p class="profile-name-card">
                            <i class="bi bi-person-circle"></i> Masuk ke Dashboard
                        </p>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form action="" method="post" id="login-form">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" placeholder="Pengguna" name="username" autocomplete="off" required>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" placeholder="Kata Sandi" name="password" autocomplete="off" required>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                <input type="text" class="form-control" placeholder="Captcha: <?php echo $_SESSION['captcha']; ?>" name="captcha" autocomplete="off" required>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="keeplogin" id="keeplogin">
                                <label class="form-check-label" for="keeplogin">
                                    Ingat saya (24 jam)
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 login-btn mt-3">
                                <i class="bi bi-box-arrow-in-right"></i> MASUK
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

