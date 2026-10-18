
<?php
require_once __DIR__ . '/session.php';

// Pastikan logout hanya melalui metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=home');
    exit;
}

// Simpan parameter cookie sebelum sesi dihapus
$parameterCookie = session_get_cookie_params();

// Kosongkan seluruh data sesi
$_SESSION = [];

// Hapus cookie session
if (ini_get('session.use_cookies')) {
    setcookie(session_name(), '', [
        'expires' => time() - 42000,
        'path' => $parameterCookie['path'],
        'domain' => $parameterCookie['domain'],
        'secure' => $parameterCookie['secure'],
        'httponly' => $parameterCookie['httponly'],
        'samesite' => $parameterCookie['samesite'] ?? 'Lax'
    ]);
}

// Hancurkan sesi
session_destroy();

// Kembali ke halaman utama aplikasi
header('Location: index.php?page=home');
exit;
