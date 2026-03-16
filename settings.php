<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php?url=settings');
    exit;
}
require_once 'config.php';

$pdo = get_db_connection();

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $site_name = trim($_POST['site_name'] ?? '');
    
    // Update site_name
    $stmt = $pdo->prepare("UPDATE settings SET value = ? WHERE key_name = 'site_name'");
    $stmt->execute([$site_name]);
    
    // Handle favicon upload
    if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] == 0) {
        $favicon_name = 'favicon_' . time() . '.' . pathinfo($_FILES['favicon']['name'], PATHINFO_EXTENSION);
        $favicon_path = 'uploads/' . $favicon_name;
        if (move_uploaded_file($_FILES['favicon']['tmp_name'], $favicon_path)) {
            $stmt = $pdo->prepare("UPDATE settings SET value = ? WHERE key_name = 'favicon'");
            $stmt->execute([$favicon_path]);
        }
    }
    
    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $logo_name = 'logo_' . time() . '.' . pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $logo_path = 'uploads/' . $logo_name;
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $logo_path)) {
            $stmt = $pdo->prepare("UPDATE settings SET value = ? WHERE key_name = 'logo'");
            $stmt->execute([$logo_path]);
        }
    }
    
    $message = 'Settings updated successfully!';
}

// Fetch current settings
$settings = [];
$stmt = $pdo->query("SELECT key_name, value FROM settings");
while ($row = $stmt->fetch()) {
    $settings[$row['key_name']] = $row['value'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Settings - <?php echo htmlspecialchars($settings['site_name'] ?? 'Harahetta'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <i class="bi bi-bank"></i> <?php echo htmlspecialchars($settings['site_name'] ?? 'Harahetta'); ?> Admin
            </div>
            <div class="list-group list-group-flush">

                

                <a href="dashboard.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-terminal"></i> Console
                </a>
                <button class="list-group-item list-group-item-action text-start" data-bs-toggle="collapse" data-bs-target="#adminManagement" aria-expanded="false" aria-controls="adminManagement">
                    <i class="bi bi-shield"></i> Admin Management
                </button>
                <div class="collapse" id="adminManagement">

                    <a href="admin.php" class="list-group-item list-group-item-action ms-3">
                        <i class="bi bi-person-gear"></i> Admin Management
                    </a>

                    <a href="admin-log.php" class="list-group-item list-group-item-action ms-3">
                        <i class="bi bi-journal-text"></i> Admin Log
                    </a>
                </div>

                <button class="list-group-item list-group-item-action text-start" data-bs-toggle="collapse" data-bs-target="#memberManagement" aria-expanded="false" aria-controls="memberManagement">
                    <i class="bi bi-people"></i> Member Management
                </button>
                <div class="collapse" id="memberManagement">
                    <a href="members.php" class="list-group-item list-group-item-action ms-3">
                        <i class="bi bi-list-ul"></i> Member List
                    </a>
                </div>

                <a href="withdrawal-records.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-cash-coin"></i> Withdrawal Records
                </a>


                <button class="list-group-item list-group-item-action text-start" data-bs-toggle="collapse" data-bs-target="#loans" aria-expanded="false" aria-controls="loans">
                    <i class="bi bi-cash"></i> Loans
                </button>
                <div class="collapse" id="loans">
                    <a href="loans.php" class="list-group-item list-group-item-action ms-3">
                        <i class="bi bi-receipt"></i> Orderer
                    </a>
                </div>

                <a href="settings.php" class="list-group-item list-group-item-action active">
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
                <h2>Website Settings</h2>

                <?php if ($message): ?>
                    <div class="alert alert-success"><?php echo $message; ?></div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="site_name" class="form-label">Site Name</label>
                        <input type="text" class="form-control" id="site_name" name="site_name" value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="favicon" class="form-label">Favicon (Upload new favicon)</label>
                        <input type="file" class="form-control" id="favicon" name="favicon" accept="image/*">
                        <small class="form-text text-muted">Current: <?php echo htmlspecialchars($settings['favicon'] ?? 'None'); ?></small>
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo (Upload new logo)</label>
                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                        <small class="form-text text-muted">Current: <?php echo htmlspecialchars($settings['logo'] ?? 'None'); ?></small>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle sidebar
        $('#sidebarToggle').on('click', function() {
            $('#sidebar-wrapper').toggleClass('show');
        });
    </script>
</body>
</html>