
<?php

require_once "session.php";
require_once "helper.php";

// Kosongkan seluruh data sesi.
$_SESSION = [];

// Hapus cookie session jika menggunakan cookie.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Hancurkan sesi di server.
session_destroy();

// Kembali ke halaman beranda Paket 6.
header("Location: /2026/5ULANGANKOMPETENSI/PAKET6/index.php?page=home");
exit;
