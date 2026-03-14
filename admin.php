
<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php?url=admin');
    exit;
}
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Admin Management - <?php echo htmlspecialchars(get_setting('site_name', 'Harahetta Pinjaman Sejahtera')); ?></title>
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

                    <a href="admin.php" class="list-group-item list-group-item-action ms-3 active">
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

        <div id="page-content-wrapper">
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
                <h2>Admin Management</h2>
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Daftar Admin</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="bi bi-plus"></i> Tambah Admin
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="adminTable" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Nama Lengkap</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Terdaftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Add/Edit Modal -->
            <div class="modal fade" id="addModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah/Edit Admin</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form id="adminForm">
                            <div class="modal-body">
                                <input type="hidden" id="id" name="id">
                                <div class="mb-3">
                                    <label class="form-label">Username *</label>
                                    <input type="text" class="form-control" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="full_name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password * (kosongkan untuk tidak ubah)</label>
                                    <input type="password" class="form-control" name="password">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <select class="form-select" name="role">
                                        <option value="admin">Admin</option>
                                        <option value="superadmin">Super Admin</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                                        <label class="form-check-label" for="is_active">
                                            Aktif
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        var table = $('#adminTable').DataTable({


                ajax: {
                    url: 'api/admin.php?action=list',
                    dataSrc: function(json) {
                        console.log('List response:', json);
                        return json;
                    }
                },


            columns: [
                { data: 'id' },
                { data: 'username' },
                { data: 'email' },
                { data: 'full_name' },
                { data: 'role', render: function(data) {
                    return data === 'superadmin' ? '<span class="badge bg-danger">Super Admin</span>' : '<span class="badge bg-info">Admin</span>';
                }},
                { data: 'is_active', render: function(data) {
                    return data ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>';
                }},
                { data: 'created_at' },
                { data: null, orderable: false, render: function(data) {
                    return '<button class="btn btn-sm btn-info edit-btn" data-id="' + data.id + '"><i class="bi bi-pencil"></i></button> ' +
                           '<button class="btn btn-sm btn-danger delete-btn" data-id="' + data.id + '"><i class="bi bi-trash"></i></button>';
                }}
            ]
        });

        $('#adminForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'api/admin.php',
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,



                success: function(response) {
                    console.log('Save API Response:', response);
                    if (response && response.success === true) {
                        table.ajax.reload();
                        $('#addModal').modal('hide');
                        alert('Admin berhasil disimpan!');
                    } else {
                        alert('Error atau response tidak valid. Console: ' + JSON.stringify(response));
                    }
                }



            });
        });

        $('[data-bs-target="#addModal"]').on('click', function() {
            $('#adminForm')[0].reset();
            $('#id').val('');
            $('.modal-title').text('Tambah Admin');
        });

        $(document).on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            $.get('api/admin.php?id=' + id, function(data) {
                $('#id').val(data.id);
                $('#adminForm input[name="username"]').val(data.username);
                $('#adminForm input[name="email"]').val(data.email);
                $('#adminForm input[name="full_name"]').val(data.full_name);
                $('#adminForm select[name="role"]').val(data.role);
                $('#adminForm input[name="is_active"]').prop('checked', data.is_active);
                $('.modal-title').text('Edit Admin');
                $('#addModal').modal('show');
            });
        });

        $(document).on('click', '.delete-btn', function() {
            if (confirm('Hapus admin ini?')) {
                var id = $(this).data('id');
                $.post('api/admin.php', {id: id, action: 'delete'}, function(response) {
                    if (response.success) {
                        table.ajax.reload();
                    } else {
                        alert('Error: ' + response.error);
                    }
                });
            }
        });

        $('#addModal').on('hidden.bs.modal', function() {
            $('#adminForm')[0].reset();
            $('#id').val('');
        });

        $('#sidebarToggle').on('click', function() {
            $('#sidebar-wrapper').toggleClass('show');
        });
    });
    </script>
</body>
</html>

