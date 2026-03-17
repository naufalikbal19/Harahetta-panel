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
                                    <th>ID</th>
                                    <th>Credit Score</th>
                                    <th>Nickname</th>
                                    <th>Password</th>
                                    <th>Withdrawal Password</th>
                                    <th>Mobile Number</th>
                                    <th>Avatar</th>
                                    <th>Level</th>
                                    <th>Gender</th>
                                    <th>Name</th>
                                    <th>ID Card Number</th>
                                    <th>Bank</th>
                                    <th>Bank Card Number</th>
                                    <th>Birthday</th>
                                    <th>Loan Purpose</th>
                                    <th>Monthly Income</th>
                                    <th>Current Address</th>
                                    <th>Motto</th>
                                    <th>Balance</th>
                                    <th>Points</th>
                                    <th>Consecutive Login Days</th>
                                    <th>Maximum Consecutive Login Days</th>
                                    <th>Last Login Time</th>
                                    <th>Login Time</th>
                                    <th>Login IP</th>
                                    <th>Number of Failures</th>
                                    <th>Joining IP</th>
                                    <th>Joining Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
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
                { data: 'credit_score' },
                { data: 'nickname' },
                { data: 'password' },
                { data: 'withdrawal_password' },
                { data: 'mobile_number' },
                { data: 'avatar', render: function(data) { return data ? '<img src="' + data + '" style="width:50px;height:50px;object-fit:cover;" class="rounded">' : '-'; } },
                { data: 'level' },
                { data: 'gender' },
                { data: 'name' },
                { data: 'id_card_number' },
                { data: 'bank' },
                { data: 'bank_card_number' },
                { data: 'birthday' },
                { data: 'loan_purpose' },
                { data: 'monthly_income' },
                { data: 'current_address' },
                { data: 'motto' },
                { data: 'balance' },
                { data: 'points' },
                { data: 'consecutive_login_days' },
                { data: 'max_consecutive_login_days' },
                { data: 'last_login_time' },
                { data: 'login_time' },
                { data: 'login_ip' },
                { data: 'number_of_failures' },
                { data: 'joining_ip' },
                { data: 'joining_time' },
                { data: 'status', render: function(data) {
                    var color = {'normal':'success', 'blocked':'danger'};
                    return '<span class="badge bg-' + (color[data] || 'secondary') + '">' + data + '</span>';
                }},
                { data: null, orderable: false, render: function(data) {
                    return '<button class="btn btn-sm btn-info edit-btn" data-id="' + data.id + '"><i class="bi bi-pencil"></i></button> ' +
                           '<button class="btn btn-sm btn-danger delete-btn" data-id="' + data.id + '"><i class="bi bi-trash"></i></button>';
                }}
            ],
            order: [[0, 'desc']]
        });

        $('#sidebarToggle').on('click', function() {
            $('#sidebar-wrapper').toggleClass('show');
        });

        // Edit
        $(document).on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            $.get('api/members.php?action=get&id=' + id, function(data) {
                if (data && !data.error) {
                    // Populate modal fields
                    $('#edit_id').val(data.id);
                    $('#edit_credit_score').val(data.credit_score);
                    $('#edit_nickname').val(data.nickname);
                    $('#edit_password').val(data.password);
                    $('#edit_withdrawal_password').val(data.withdrawal_password);
                    $('#edit_mobile_number').val(data.mobile_number);
                    $('#edit_level').val(data.level);
                    $('#edit_gender').val(data.gender);
                    $('#edit_name').val(data.name);
                    $('#edit_id_card_number').val(data.id_card_number);
                    $('#edit_bank').val(data.bank);
                    $('#edit_bank_card_number').val(data.bank_card_number);
                    $('#edit_birthday').val(data.birthday);
                    $('#edit_loan_purpose').val(data.loan_purpose);
                    $('#edit_monthly_income').val(data.monthly_income);
                    $('#edit_current_address').val(data.current_address);
                    $('#edit_motto').val(data.motto);
                    $('#edit_balance').val(data.balance);
                    $('#edit_points').val(data.points);
                    $('#edit_consecutive_login_days').val(data.consecutive_login_days);
                    $('#edit_max_consecutive_login_days').val(data.max_consecutive_login_days);
                    $('#edit_number_of_failures').val(data.number_of_failures);
                    $('#edit_status').val(data.status);
                    
                    var modal = new bootstrap.Modal(document.getElementById('editModal'));
                    modal.show();
                } else {
                    alert(data.error || 'Gagal mengambil data member');
                }
            });
        });

        // Save Changes
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            $.post('api/members.php', $(this).serialize() + '&action=update', function(response) {
                if (response.success) {
                    var modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                    modal.hide();
                    table.ajax.reload();
                    alert(response.message || 'Berhasil diupdate');
                } else {
                    alert(response.message || 'Gagal update');
                }
            }, 'json');
        });

        // Delete
        $(document).on('click', '.delete-btn', function() {
            if (confirm('Hapus member ini?')) {
                var id = $(this).data('id');
                $.post('api/members.php', {id: id, action: 'delete'}, function(response) {
                    if (response.success) {
                        table.ajax.reload();
                        alert('Berhasil dihapus');
                    } else {
                        alert('Gagal hapus');
                    }
                }, 'json');
            }
        });
    });
    </script>

    <!-- Edit Member Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Credit Score</label>
                                <input type="number" class="form-control" name="credit_score" id="edit_credit_score">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nickname</label>
                                <input type="text" class="form-control" name="nickname" id="edit_nickname">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="edit_password">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Withdrawal Password</label>
                                <input type="text" class="form-control" name="withdrawal_password" id="edit_withdrawal_password">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" class="form-control" name="mobile_number" id="edit_mobile_number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Level</label>
                                <input type="number" class="form-control" name="level" id="edit_level">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender</label>
                                <select class="form-select" name="gender" id="edit_gender">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" id="edit_name">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ID Card Number</label>
                                <input type="text" class="form-control" name="id_card_number" id="edit_id_card_number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bank</label>
                                <input type="text" class="form-control" name="bank" id="edit_bank">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bank Card Number</label>
                                <input type="text" class="form-control" name="bank_card_number" id="edit_bank_card_number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Birthday</label>
                                <input type="date" class="form-control" name="birthday" id="edit_birthday">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Loan Purpose</label>
                            <textarea class="form-control" name="loan_purpose" id="edit_loan_purpose"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Monthly Income</label>
                                <input type="number" step="0.01" class="form-control" name="monthly_income" id="edit_monthly_income">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Balance</label>
                                <input type="number" step="0.01" class="form-control" name="balance" id="edit_balance">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Points</label>
                                <input type="number" class="form-control" name="points" id="edit_points">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Consecutive Login Days</label>
                                <input type="number" class="form-control" name="consecutive_login_days" id="edit_consecutive_login_days">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Max Consecutive Login Days</label>
                                <input type="number" class="form-control" name="max_consecutive_login_days" id="edit_max_consecutive_login_days">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Number of Failures</label>
                                <input type="number" class="form-control" name="number_of_failures" id="edit_number_of_failures">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Current Address</label>
                            <textarea class="form-control" name="current_address" id="edit_current_address"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Motto</label>
                            <input type="text" class="form-control" name="motto" id="edit_motto">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit_status">
                                <option value="normal">Normal</option>
                                <option value="blocked">Blocked</option>
                            </select>
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
</body>
</html>
