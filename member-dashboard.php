
<?php
session_start();
if (!isset($_SESSION['member_logged_in'])) {
    header('Location: member-login.php');
    exit;
}
require_once __DIR__ . '/config.php';
$pdo = get_db_connection();
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM pinjaman WHERE phone_number = ?");
$stmt->execute([$_SESSION['phone']]);
$total_loans = $stmt->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dashboard - <?php echo htmlspecialchars(get_setting('site_name')); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-bank"></i> <?php echo htmlspecialchars(get_setting('site_name')); ?>
            </a>
            <div class="navbar-nav ms-auto">
                <span class="nav-link">
                    Halo, <?php echo htmlspecialchars($_SESSION['phone']); ?>
                </span>
                <a class="nav-link" href="member-logout.php">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </a>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 mb-4">Selamat Datang!</h1>
            <p class="lead mb-4">Kelola pinjaman Anda dengan mudah dan aman</p>
            <a href="#" class="btn btn-light btn-lg">
                <i class="bi bi-cash-stack"></i> Ajukan Pinjaman
            </a>
        </div>
    </section>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center border-0 shadow">
                    <div class="card-body">
                        <i class="bi bi-receipt display-4 text-primary mb-3"></i>
                        <h5>Pinjaman Aktif</h5>
                        <h2 class="display-5"><?php echo $total_loans; ?></h2>
                        <a href="#" class="btn btn-outline-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

