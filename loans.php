
<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php?url=loans');
    exit;
}
require_once 'config.php';

$pdo = get_db_connection();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Loans - <?php echo htmlspecialchars(get_setting('site_name', 'Harahetta Pinjaman Sejahtera')); ?></title>
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
        <!-- Sidebar (same as dashboard) -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <i class="bi bi-bank"></i> <?php echo htmlspecialchars(get_setting('site_name', 'Harahetta')); ?> Admin
            </div>
            <div class="list-group list-group-flush">
                <a href="home.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-house"></i> Home
                </a>
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
                <button class="list-group-item list-group-item-action text-start" data-bs-toggle="collapse" data-bs-target="#loans" aria-expanded="true" aria-controls="loans">
                    <i class="bi bi-cash"></i> Loans
                </button>
                <div class="collapse show" id="loans">
                    <a href="loans.php" class="list-group-item list-group-item-action ms-3 active">
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
                <h2>Daftar Pinjaman</h2>

                <!-- Table Pinjaman -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Daftar Pinjaman</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="bi bi-plus"></i> Tambah Baru
                        </button>
                    </div>
                    <div class="card-body">
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
                    </div>
                </div>
            </div>

            <!-- Add/Edit Modal -->
            <div class="modal fade" id="addModal" tabindex="-1">
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
                                    <label class="form-label">Sign</label>
                                    <input type="file" class="form-control" name="sign_file" accept="image/*">
                                    <small class="text-muted">Upload gambar tanda tangan (JPG/PNG)</small>
                                    <div id="sign-preview" class="mt-2"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Foto Depan Id Card</label>
                                    <input type="file" class="form-control" name="id_front_file" accept="image/*">
                                    <div id="id-front-preview" class="mt-2"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Foto Belakang Id Card</label>
                                    <input type="file" class="form-control" name="id_back_file" accept="image/*">
                                    <div id="id-back-preview" class="mt-2"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Photo Selfie</label>
                                    <input type="file" class="form-control" name="selfie_file" accept="image/*">
                                    <div id="selfie-preview" class="mt-2"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Bank</label>
                                    <input type="text" class="form-control" name="bank">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nomor Rekening</label>
                                    <input type="text" class="form-control" name="no_rekening">
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
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        var table = $('#pinjamanTable').DataTable({
            ajax: {
                url: 'api/loans.php?action=list',
                dataSrc: ''
            },
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
            order: [[0, 'desc']]
        });

        // Checkbox select all
        $('#selectAll').on('change', function() {
            $('.row-select').prop('checked', this.checked);
        });
        $(document).on('change', '.row-select', function() {
            var checked = $('.row-select:checked').length;
            $('#selectAll').prop('checked', checked === $('.row-select').length && checked > 0);
        });

        // Load data (auto with ajax)



        // File preview handlers
        function previewFile(input, previewId) {
            var file = input.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#' + previewId).html('<img src="' + e.target.result + '" style="max-width:200px;max-height:100px;border:1px solid #ddd;border-radius:4px;">');
                };
                reader.readAsDataURL(file);
            }
        }

        $('input[name="sign_file"]').on('change', function() {
            previewFile(this, 'sign-preview');
        });
        $('input[name="id_front_file"]').on('change', function() {
            previewFile(this, 'id-front-preview');
        });
        $('input[name="id_back_file"]').on('change', function() {
            previewFile(this, 'id-back-preview');
        });
        $('input[name="selfie_file"]').on('change', function() {
            previewFile(this, 'selfie-preview');
        });


        // Add/Edit - use FormData for file upload
        $('#pinjamanForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('action', 'save');
            $.ajax({
                url: 'api/loans.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        table.ajax.reload();
                        var modalEl = document.getElementById('addModal');
                        var modal = bootstrap.Modal.getInstance(modalEl);
                        modal.hide();
                        $('#sign-preview').empty();
                    } else {
                        alert('Error: ' + (response.error || 'Unknown'));
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        });


        // Reset form
        $('[data-bs-target="#addModal"]').on('click', function() {
            $('#pinjamanForm')[0].reset();
            $('#id').val('');
            $('.modal-title').text('Tambah Pinjaman Baru');
        });

        // Edit
        $(document).on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            $.get('api/loans.php?id=' + id, function(data) {
                if (data.id) {
                    $('#id').val(data.id);

                    $('#pinjamanForm input[name="username"]').val(data.username);
                    $('#pinjamanForm input[name="phone_number"]').val(data.phone_number);
                    $('#pinjamanForm input[name="uid"]').val(data.uid);
                    $('#pinjamanForm input[name="nama_peminjam"]').val(data.nama_peminjam);
                    $('#pinjamanForm input[name="jumlah_pinjaman"]').val(data.jumlah_pinjaman);
                    $('#pinjamanForm input[name="loan_period"]').val(data.loan_period);
                    $('#pinjamanForm input[name="sign"]').val(data.sign || '');
                    $('#pinjamanForm input[name="id_front"]').val(data.id_front || '');
                    $('#pinjamanForm input[name="id_back"]').val(data.id_back || '');
                    $('#pinjamanForm input[name="selfie"]').val(data.selfie || '');
                    $('#pinjamanForm input[name="bank"]').val(data.bank || '');
                    $('#pinjamanForm input[name="no_rekening"]').val(data.no_rekening || '');
                    $('#pinjamanForm select[name="status"]').val(data.status);
                    $('#pinjamanForm input[name="tanggal_pinjam"]').val(data.tanggal_pinjam);
                    $('#pinjamanForm textarea[name="keterangan"]').val(data.keterangan);

                    $('.modal-title').text('Edit Pinjaman');
                    new bootstrap.Modal(document.getElementById('addModal')).show();
                } else {
                    alert(data.error || 'Data tidak ditemukan');
                }
            });
        });

        // Delete
        $(document).on('click', '.delete-btn', function() {
            if (confirm('Hapus pinjaman ini?')) {
                var id = $(this).data('id');
                $.post('api/loans.php', {id: id, action: 'delete'}, function(response) {
                    if (response.success) {
                        table.ajax.reload();
                    } else {
                        alert('Error: ' + response.error);
                    }
                });
            }
        });

        $('#addModal').on('hidden.bs.modal', function () {
            $('#pinjamanForm')[0].reset();
            $('#id').val('');
            $('.modal-title').text('Tambah Pinjaman Baru');
        });

        $('#sidebarToggle').on('click', function() {
            $('#sidebar-wrapper').toggleClass('show');
        });
    });
    </script>
</body>
</html>

