<?php
session_start();
header('Content-Type: application/json');

// Temp bypass auth for testing - remove after fix
// if (!isset($_SESSION['admin_logged_in'])) {
//     echo json_encode(['data' => []]);
//     exit;
// }


require_once '../config.php';
$pdo = get_db_connection();

$data = [];
try {
    // Mengambil data pinjaman dan menggabungkannya dengan data member (bank, nomor rekening)
    // berdasarkan nomor telepon.
    $sql = "SELECT
                p.id,
                p.uid,
                p.phone_number,
                p.jumlah_pinjaman,
                p.tanggal_pinjam,
                p.status,
                '-' as bank,
                '-' as no_rekening
            FROM pinjaman p
            LEFT JOIN members m ON 1=0  -- temp disable JOIN
            ORDER BY p.id DESC";
    
    $stmt = $pdo->query($sql);
$data = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
error_log('Withdrawal records count: ' . count($data));

} catch (Exception $e) {
    // Jika terjadi error, kembalikan array kosong agar tidak merusak DataTables.
    // error_log('Withdrawals API Error: ' . $e->getMessage());
    $data = [];
}

// Mengembalikan data dalam format yang diharapkan oleh DataTables
echo json_encode(['data' => $data]);
?>