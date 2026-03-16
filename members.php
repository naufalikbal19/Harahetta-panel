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
    <title>Member List - <?php echo htmlspecialchars(get_setting('site_name', 'Harahetta Pinjaman Sejahtera')); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
                    <a href="members.php" class="list-group-item list-group-item-action ms-3 active">
                        <i class="bi bi-list-ul"></i> Member List
                    </a>
                </div>

                <a href="#" class="list-group-item list-group-item-action">
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
                    <a class="btn btn-outline-danger btn-sm" href="../logout.php">
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
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>ID Number</th>
                                    <th>Credit Score</th>
                                    <th>Phone</th>
                                    <th>withdrawal Password</th>
                                    <th>Ip Client</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
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
                {
                    data: null,
                    orderable: false,
                    render: function ( data, type, row ) {
                        return '<input type="checkbox" class="row-select" value="' + (row.id_number || row.id) + '">';
                    }
                },
                { data: 'id_number' },
                { data: 'credit_score' },
                { data: 'phone' },
                { data: 'withdrawal_password' },
                { data: 'ip_client' },
                { data: 'status', render: function(data) {
                    var color = {'normal':'success', 'blocked':'danger'};
                    return '<span class="badge bg-' + (color[data] || 'secondary') + '">' + data + '</span>';
                }},
                { data: null, orderable: false, render: function(data) {
                    return '<button class="btn btn-sm btn-info edit-btn" data-id="' + (data.id_number || data.id) + '"><i class="bi bi-pencil"></i></button> ' +
                           '<button class="btn btn-sm btn-danger delete-btn" data-id="' + (data.id_number || data.id) + '"><i class="bi bi-trash"></i></button>';
                }}
            ],
            order: [[1, 'desc']]
        });

        // Checkbox select all
        $('#selectAll').on('change', function() {
            $('.row-select').prop('checked', this.checked);
        });

        // Edit
        $(document).on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            $.get('api/members.php?id=' + id, function(data) {
                alert('Edit member ' + id + '\nData: ' + JSON.stringify(data, null, 2));
            }).fail(function() {
                alert('Error loading member data');
            });
        });

        // Delete
        $(document).on('click', '.delete-btn', function() {
            if (confirm('Hapus member ini?')) {
                var id = $(this).data('id');
                $.post('api/members.php', {id: id, action: 'delete'}, function(response) {
                    table.ajax.reload();
                });
            }
        });

        $('#sidebarToggle').on('click', function() {
            $('#sidebar-wrapper').toggleClass('show');
        });
    });
    </script>
</body>
</html>

