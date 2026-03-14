
<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Member List - <?php echo htmlspecialchars(get_setting('site_name')); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
</head>
<body>
    <div id="wrapper">
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <i class="bi bi-bank"></i> <?php echo htmlspecialchars(get_setting('site_name')); ?> Admin
            </div>
            <div class="list-group list-group-flush">
                <a href="dashboard.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-house"></i> Home
                </a>
                <a href="dashboard.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-terminal"></i> Console
                </a>
                <button class="list-group-item list-group-item-action text-start" data-bs-toggle="collapse" data-bs-target="#adminManagement">
                    <i class="bi bi-shield"></i> Admin Management
                </button>
                <div class="collapse">
                    <a href="admin.php" class="list-group-item list-group-item-action ms-3">
                        <i class="bi bi-person-gear"></i> Admin Management
                    </a>
                </div>
                <button class="list-group-item list-group-item-action text-start active" data-bs-toggle="collapse" data-bs-target="#memberManagement">
                    <i class="bi bi-people"></i> Member Management
                </button>
                <div class="collapse show">
                    <a href="members.php" class="list-group-item list-group-item-action ms-3 active">
                        <i class="bi bi-list-ul"></i> Member List
                    </a>
                    <a href="#" class="list-group-item list-group-item-action ms-3">
                        <i class="bi bi-cash-coin"></i> Withdrawal Records
                    </a>
                </div>
                <a href="settings.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-gear"></i> Settings
                </a>
            </div>
        </div>

        <div id="page-content-wrapper">
            <nav class="navbar navbar-light bg-light">
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
                <h2>Daftar Member</h2>
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Semua Member</h5>
                    </div>
                    <div class="card-body">
                        <table id="memberTable" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nomor HP</th>
                                    <th>Nama</th>
                                    <th>Bank</th>
                                    <th>Nomor Rekening</th>
                                    <th>Foto KTP Depan</th>
                                    <th>Foto KTP Belakang</th>
                                    <th>Foto Selfie</th>
                                    <th>Terdaftar</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        var table = $('#memberTable').DataTable({
            ajax: 'api/members.php?action=list',
            columns: [
                { data: 'id' },
                { data: 'phone' },
                { data: 'nama' },
                { data: 'bank' },
                { data: 'nomor_rekening' },
                { data: 'foto_ktp_depan', render: function(data) {
                    return data ? '<img src="' + data + '" style="width:50px;height:50px;object-fit:cover;" class="rounded">' : '-';
                }},
                { data: 'foto_ktp_belakang', render: function(data) {
                    return data ? '<img src="' + data + '" style="width:50px;height:50px;object-fit:cover;" class="rounded">' : '-';
                }},
                { data: 'foto_selfie', render: function(data) {
                    return data ? '<img src="' + data + '" style="width:50px;height:50px;object-fit:cover;" class="rounded">' : '-';
                }},
                { data: 'created_at' }
            ]
        });

        $('#sidebarToggle').on('click', function() {
            $('#sidebar-wrapper').toggleClass('show');
        });
    });
    </script>
</body>
</html>

