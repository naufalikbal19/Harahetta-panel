<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php?url=dashboard');
    exit;
}

require_once 'config.php';


$pdo = get_db_connection();

// Stats
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
    <title>Dashboard - <?php echo htmlspecialchars(get_setting('site_name', 'Harahetta Pinjaman Sejahtera')); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="<?php echo htmlspecialchars(get_setting('favicon', 'favicon.ico')); ?>">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <i class="bi bi-bank"></i> <?php echo htmlspecialchars(get_setting('site_name', 'Harahetta')); ?> Admin
            </div>
            <div class="list-group list-group-flush">
                <a href="home.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-house"></i> Home
                </a>
                <a href="dashboard.php" class="list-group-item list-group-item-action active">
                    <i class="bi bi-terminal"></i> Console
                </a>
                <button class="list-group-item list-group-item-action text-start" data-bs-toggle="collapse" data-bs-target="#adminManagement" aria-expanded="false" aria-controls="adminManagement">
                    <i class="bi bi-shield"></i> Admin Management
                </button>
                <div class="collapse" id="adminManagement">

                    <a href="admin.php" class="list-group-item list-group-item-action ms-3">
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
                    <a class="btn btn-outline-danger btn-sm" href="logout.php">
                        <i class="bi bi-box-arrow-right"></i> Keluar
                    </a>
                </div>
            </nav>

            <div class="container-fluid mt-4">
                <h2>Dashboard <?php echo htmlspecialchars(get_setting('site_name', 'Pinjaman Sejahtera')); ?></h2>

                <!-- Stats Cards -->

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
                                <a class="small text-white stretched-link" href="loans.php">View Details</a>
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
                                <a class="small text-white stretched-link" href="loans.php">View Details</a>
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
                                <a class="small text-white stretched-link" href="loans.php">View Details</a>
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
                                <a class="small text-white stretched-link" href="loans.php">View Details</a>
                                <div class="small text-white"><i class="bi bi-chevron-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Toggle sidebar only
        $('#sidebarToggle').on('click', function() {
            $('#sidebar-wrapper').toggleClass('show');
        });
    });
    </script>
</body>
</html>

            <!-- <div class="card-header d-flex justify-content-between">
                <h5>Daftar Pinjaman</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus"></i> Tambah Baru
                </button>
            </div> -->
            <!-- <div class="card-body">
                <table id="pinjamanTable" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>ID</th>
                            <th>Order Number</th>
                            <th>Username</th>
                            <th>Phone Number</th>
                            <th>Uid</th>
                            <th>Loan Amount</th>
                            <th>Loan Period</th>
                            <th>Sign</th>
                            <th>Application Time</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div> -->
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <!-- <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah/Edit Pinjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="pinjamanForm">
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id">
                        <div class="mb-3">
                            <label class="form-label">Order Number</label>
                            <span class="form-control bg-light" readonly>Auto generated</span>
                        </div>
                            <label class="form-label">UID</label>
                            <span class="form-control bg-light" readonly>Auto generated</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone_number">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">UID</label>
                            <input type="text" class="form-control" name="uid">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Peminjam</label>
                            <input type="text" class="form-control" name="nama_peminjam" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Loan Amount</label>
                            <input type="number" class="form-control" name="jumlah_pinjaman" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Loan Period (days)</label>
                            <input type="number" class="form-control" name="loan_period">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sign (path)</label>
                            <input type="text" class="form-control" name="sign">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Application Time</label>
                            <input type="date" class="form-control" name="tanggal_pinjam" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="proses">Proses</option>
                                <option value="lunas">Lunas</option>
                                <option value="macet">Macet</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" name="keterangan" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div> -->


    <script>
    $(document).ready(function() {
        console.log('jQuery loaded:', typeof $);
        console.log('DataTable loaded:', typeof $.fn.DataTable);
        if (typeof $.fn.DataTable === 'undefined') {
            console.error('DataTable not loaded');
            return;
        }
        var table = $('#pinjamanTable').DataTable({
            data: [],
            columns: [
                {
                    data: null,
                    orderable: false,
                    render: function ( data, type, row ) {
                        return '<input type="checkbox" class="row-select" value="' + row.id + '">';
                    }
                },
                { data: 'id' },
                { data: 'order_number' },
                { data: 'username' },
                { data: 'phone_number' },
                { data: 'uid' },
                { data: 'jumlah_pinjaman', render: $.fn.dataTable.render.number(',', '.', 2, 'Rp ') },
                { data: 'loan_period' },
                { data: 'sign', render: function(data) {
                    return data ? '<img src="' + data + '" style="width:50px;height:30px">' : '-';
                }},
                { data: 'tanggal_pinjam' },
                { data: 'status', render: function(data) {
                    var color = {'pending':'warning', 'proses':'info', 'lunas':'success', 'macet':'danger'};
                    return '<span class="badge bg-' + (color[data] || 'secondary') + '">' + data + '</span>';
                }},
                { data: null, orderable: false, render: function(data) {
                    return '<button class="btn btn-sm btn-info edit-btn" data-id="' + data.id + '"><i class="bi bi-pencil"></i></button> ' +
                           '<button class="btn btn-sm btn-danger delete-btn" data-id="' + data.id + '"><i class="bi bi-trash"></i></button>';
                }}
            ],
            // Use the correct i18n file name for Indonesian localization
            // language: { url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/Indonesian.json' },
            order: [[0, 'desc']]
        });
        console.log('DataTable initialized');
        
        // Checkbox select all
        $('#selectAll').on('change', function() {
            $('.row-select').prop('checked', this.checked);
        });
        $(document).on('change', '.row-select', function() {
            var checked = $('.row-select:checked').length;
            $('#selectAll').prop('checked', checked === $('.row-select').length && checked > 0);
        });
        
        // Load data into DataTable (manual fetch)
        function loadTable() {
            $.ajax({
                url: 'api/loans.php?action=list',
                dataType: 'json',
                success: function(data) {
                    console.log('Manual ajax success:', data);
                    table.clear().rows.add(data).draw();
                },
                error: function(xhr, status, error) {
                    console.log('Manual ajax error:', status, error);
                }
            });
        }

        loadTable();

        // Add/Edit
        $('#pinjamanForm').on('submit', function(e) {
            e.preventDefault();
            console.log('Form submit');
            $.ajax({
                url: 'api/loans.php',
                type: 'POST',
                data: $(this).serialize() + '&action=save',
                success: function(response) {
                    console.log('Save success:', response);
                    if (table) {
                        loadTable();
                    }
                    var modalEl = document.getElementById('addModal');
                    var modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modalInstance.hide();
                },
                error: function(xhr, status, error) {
                    console.log('Save error:', status, error, xhr.responseText);
                }
            });
        });

        // Reset form when add button clicked
        $('button[data-bs-target="#addModal"]').on('click', function() {
            $('#pinjamanForm')[0].reset();
            $('#id').val('');
            $('#addModal .modal-title').text('Tambah Pinjaman Baru');
        });

        // Edit
        $(document).on('click', '.edit-btn', function() {
            var id = parseInt($(this).attr('data-id'));
            console.log('Edit button clicked, id:', id);
            if (!id) {
                alert('Invalid ID');
                return;
            }
            $.ajax({
                url: 'api/loans.php?id=' + id,
                type: 'GET',
                success: function(data) {
                    console.log('Edit response:', data, typeof data);
                    if (data && typeof data === 'object' && !Array.isArray(data) && data.id) {
                        $('#id').val(data.id);
                        $('#pinjamanForm input[name=order_number]').val(data.order_number || '');
                        $('#pinjamanForm input[name=username]').val(data.username || '');
                        $('#pinjamanForm input[name=phone_number]').val(data.phone_number || '');
                        $('#pinjamanForm input[name=uid]').val(data.uid || '');
                        $('#pinjamanForm input[name=nama_peminjam]').val(data.nama_peminjam || '');
                        $('#pinjamanForm input[name=jumlah_pinjaman]').val(data.jumlah_pinjaman);
                        $('#pinjamanForm input[name=loan_period]').val(data.loan_period || '');
                        $('#pinjamanForm input[name=sign]').val(data.sign || '');
                        $('#pinjamanForm select[name=status]').val(data.status);
                        $('#pinjamanForm input[name=tanggal_pinjam]').val(data.tanggal_pinjam);
                        $('#pinjamanForm textarea[name=keterangan]').val(data.keterangan || '');
                        $('#addModal .modal-title').text('Edit Pinjaman');
                        var modalEl = document.getElementById('addModal');
                        var modalInstance = new bootstrap.Modal(modalEl);
                        modalInstance.show();
                    } else {
                        var errMsg = (data && data.error) ? data.error : 'Data tidak ditemukan atau response tidak valid';
                        console.error('Edit fetch gagal:', data, errMsg);
                        alert(errMsg);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Edit error:', status, error, xhr.responseText);
                    alert('Error loading data: ' + xhr.responseText);
                }
            });
        });

        // Delete
        $(document).on('click', '.delete-btn', function() {
            if (confirm('Hapus data ini?')) {
                var id = $(this).data('id');
                console.log('Delete button clicked, id:', id);
                $.ajax({
                    url: 'api/loans.php',
                    type: 'POST',
                    data: {id: id, action: 'delete'},
                    success: function(response) {
                        console.log('Delete success:', response);
                        if (table) {
                            loadTable();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Delete error:', status, error, xhr.responseText);
                    }
                });
            }
        });

        // Reset modal
        $('#addModal').on('hidden.bs.modal', function() {
            $('#pinjamanForm')[0].reset();
            $('#id').val('');
            $('#addModal .modal-title').text('Tambah Pinjaman Baru');
        });

        // Toggle sidebar
        $('#sidebarToggle').on('click', function() {
            $('#sidebar-wrapper').toggleClass('show');
        });
    });
    </script>
</body>
</html>

