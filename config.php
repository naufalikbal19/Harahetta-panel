<?php
/**
 * Konfigurasi Database - Sama seperti FastAdmin style
 * DB: localhost, harahetta_db, user: root, pass: kosong (ubah jika perlu)
 */

$config = [
    'database' => [
        'type'     => 'mysql',
        'hostname' => '127.0.0.1',
        'hostport' => '',
        'database' => 'harahetta_db',
        'username' => 'root',
        'password' => '', // Ubah password MySQL jika ada
        'charset'  => 'utf8mb4',
        'prefix'   => '',
    ]
];

if (!function_exists('get_db_connection')) {
function get_db_connection() {
    global $config;
    $db = $config['database'];
    $dsn = "mysql:host={$db['hostname']};dbname={$db['database']};charset={$db['charset']}";
    try {
        $pdo = new PDO($dsn, $db['username'], $db['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die('Koneksi DB gagal: ' . $e->getMessage());
    }
}
}

if (!function_exists('get_setting')) {
function get_setting($key, $default = '') {
    static $settings = null;
    if ($settings === null) {
        $pdo = get_db_connection();
        $stmt = $pdo->query("SELECT key_name, value FROM settings");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['key_name']] = $row['value'];
        }
    }
    return $settings[$key] ?? $default;
}
}

