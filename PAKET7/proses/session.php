<?php
// Mencegah akses langsung
if (basename($_SERVER['SCRIPT_NAME']) === 'session.php') {
    http_response_code(403);
    exit('Akses langsung tidak diizinkan.');
}

// Pengaturan session
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_only_cookies', '1');
    ini_set('session.use_strict_mode', '1');

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_start();
}

// Batas waktu tidak aktif: 1 jam
$batasWaktuSession = 3600;

if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    if (
        !isset($_SESSION['aktivitas_terakhir']) ||
        time() - $_SESSION['aktivitas_terakhir'] > $batasWaktuSession
    ) {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $parameterCookie = session_get_cookie_params();

            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $parameterCookie['path'],
                'domain' => $parameterCookie['domain'],
                'secure' => $parameterCookie['secure'],
                'httponly' => $parameterCookie['httponly'],
                'samesite' => $parameterCookie['samesite'] ?? 'Lax'
            ]);
        }

        session_destroy();
        session_start();
        session_regenerate_id(true);
    } else {
        $_SESSION['aktivitas_terakhir'] = time();
    }
}

// Memeriksa apakah pengguna sudah login
function sudahLogin() {
    return isset($_SESSION['login']) && $_SESSION['login'] === true;
}

// Mengambil jenis akun yang sedang login
function jenisAkun() {
    return $_SESSION['jenis_akun'] ?? '';
}

// Memeriksa role admin atau petugas
function punyaRole($role) {
    return sudahLogin()
        && ($_SESSION['jenis_akun'] ?? '') === 'user'
        && ($_SESSION['role'] ?? '') === $role;
}

// Memeriksa apakah akun termasuk salah satu role
function punyaSalahSatuRole(array $roles) {
    return sudahLogin()
        && ($_SESSION['jenis_akun'] ?? '') === 'user'
        && in_array($_SESSION['role'] ?? '', $roles, true);
}

// Memeriksa apakah pasien sedang login
function loginPasien() {
    return sudahLogin() && jenisAkun() === 'pasien';
}

// Memeriksa apakah dokter sedang login
function loginDokter() {
    return sudahLogin() && jenisAkun() === 'dokter';
}

// Memeriksa apakah admin sedang login
function loginAdmin() {
    return punyaRole('admin');
}

// Memeriksa apakah petugas sedang login
function loginPetugas() {
    return punyaRole('petugas');
}

// Mengharuskan pengguna sudah login
function wajibLogin() {
    if (!sudahLogin()) {
        header('Location: index.php?page=loginuser');
        exit;
    }
}

// Mengharuskan role tertentu
function wajibRole($role) {
    wajibLogin();

    if (!punyaRole($role)) {
        http_response_code(403);
        header('Location: index.php?page=403');
        exit;
    }
}

// Mengharuskan akun pasien
function wajibPasien() {
    wajibLogin();

    if (!loginPasien()) {
        http_response_code(403);
        header('Location: index.php?page=403');
        exit;
    }
}

// Mengharuskan akun dokter
function wajibDokter() {
    wajibLogin();

    if (!loginDokter()) {
        http_response_code(403);
        header('Location: index.php?page=403');
        exit;
    }
}