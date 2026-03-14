<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php?url=home');
    exit;
}
require_once 'config.php';

$pdo = get_db_connection();

// Stats (sama seperti dashboard)
$stmt = $pdo->query("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status='pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status='proses' THEN 1 ELSE 0 END) as proses,
    SUM(CASE WHEN status='lunas' THEN 1 ELSE 0 END) as lunas,
    SUM(CASE WHEN status='macet' THEN 1 ELSE 0 END) as macet,
    SUM(jumlah_pinjaman) as total_jumlah
FROM pinjaman");
$stats = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Home - <?php echo htmlspecialchars(get_setting('site_name', 'Harahetta Pinjaman Sejahtera')); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.0.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="<?php echo htmlspecialchars(get_setting('favicon', 'favicon.ico')); ?>">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <i class="bi bi-bank"></i> <?php echo htmlspecialchars(get_setting('site_name', 'Harahetta')); ?> Admin
            </div>
            <div class="list-group list-group-flush">
                <a href="home.php" class="list-group-item list-group-item-action active">
                    <i class="bi bi-house"></i> Home
                </a>
                <a href="dashboard.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-terminal"></i> Console
                </a>
                <button class="list-group-item list-group-item-action text-start" data-bs-toggle="collapse" data-bs-target="#adminManagement" aria-expanded="false" aria-controls="adminManagement">
                    <i class="bi bi-shield"></i> Admin Management
                </button>
                <div class="collapse" id="adminManagement">
                    <a href="#" class="list-group-item list-group-item-action ms-3">
                        <i class="bi bi-person-gear"></i> Admin Management
                    </a>
                    <a href="#" class="list-group-item list-group-item-action ms-3">
                        <i class="bi bi-journal-text"></i> Admin Log
                    </a>
                </div>
                <button class="list-group-item list-group-item-action text-start" data-bs-toggle="collapse" data-bs-target="#memberManagement" aria-expanded="false" aria-controls="memberManagement">
                    <i class="bi bi-people"></i> Member Management
                </button>
                <div class="collapse" id="memberManagement">
                    <a href="#" class="list-group-item list-group-item-action ms-3">
                        <i class="bi bi-cash-coin"></i> Withdrawal Records
                    </a>
                </div>
                <button class="list-group-item list-group-item-action text-start" data-bs-toggle="collapse" data-bs-target="#loans" aria-expanded="false" aria-controls="loans">
                    <i class="bi bi-cash"></i> Loans
                </button>
                <div class="collapse" id="loans">
                    <a href="#" class="list-group-item list-group-item-action ms-3">
                        <i class="bi bi-receipt"></i> Orderer
                    </a>
                </div>
                <a href="settings.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-gear"></i> Settings
                </a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Navbar -->
            <nav id="navbar-wrapper" class="navbar navbar-expand-lg navbar-light bg-light">
                <button class="btn btn-outline-secondary d-md-none" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="ms-auto">
                    <span class="navbar-text me-3">Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                    <a class="btn btn-outline-danger btn-sm" href="logout.php">
                        <i class="bi bi-box-arrow-right"></i> Keluar
                    </a>
                </div>
            </nav>

            <div class="container-fluid mt-4">
                <h2>Selamat Datang di <?php echo htmlspecialchars(get_setting('site_name', 'Harahetta Pinjaman Sejahtera')); ?></h2>
                <p class="lead">Panel utama untuk mengelola sistem pinjaman Anda.</p>

                <!-- Welcome Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Selamat Datang!</h5>
                                <p class="card-text">Ini adalah halaman home panel admin. Dari sini Anda dapat mengakses berbagai fitur sistem pinjaman.</p>
                                <a href="dashboard.php" class="btn btn-primary">Lihat Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-primary text-white mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5>Total Pinjaman</h5>
                                        <div class="h4"><?php echo $stats['total']; ?></div>
                                    </div>
                                    <div>
                                        <i class="bi bi-cash-stack h1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="dashboard.php">View Details</a>
                                <div class="small text-white"><i class="bi bi-chevron-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-warning text-white mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5>Pending</h5>
                                        <div class="h4"><?php echo $stats['pending']; ?></div>
                                    </div>
                                    <div>
                                        <i class="bi bi-clock h1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="dashboard.php">View Details</a>
                                <div class="small text-white"><i class="bi bi-chevron-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-info text-white mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5>Proses</h5>
                                        <div class="h4"><?php echo $stats['proses']; ?></div>
                                    </div>
                                    <div>
                                        <i class="bi bi-gear h1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="dashboard.php">View Details</a>
                                <div class="small text-white"><i class="bi bi-chevron-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-success text-white mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5>Lunas</h5>
                                        <div class="h4"><?php echo $stats['lunas']; ?></div>
                                    </div>
                                    <div>
                                        <i class="bi bi-check-circle h1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="dashboard.php">View Details</a>
                                <div class="small text-white"><i class="bi bi-chevron-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Informasi Sistem</h5>
                            </div>
                            <div class="card-body">
                                <p>Sistem ini digunakan untuk mengelola pinjaman di Harahetta Pinjaman Sejahtera.</p>
                                <ul>
                                    <li>Kelola data pinjaman</li>
                                    <li>Monitor status pinjaman</li>
                                    <li>Generate laporan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Aktivitas Terbaru</h5>
                            </div>
                            <div class="card-body">
                                <p>Belum ada aktivitas terbaru.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle sidebar
        $("#sidebarToggle").click(function(e) {
            e.preventDefault();
            $("#sidebar-wrapper").toggleClass("show");
        });
    </script>
</body>
</html>