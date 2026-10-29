<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/helper.php';

if (!isPost()) {
    redirect('../index.php?page=loginuser');
}

$username = ambilPost('username');
$password = $_POST['password'] ?? '';

if ($username === '' || !is_string($password) || $password === '') {
    setFlash('error', 'Username dan password wajib diisi.');
    $_SESSION['error_login'] = 'Username dan password wajib diisi.';
    redirect('../index.php?page=loginuser');
}

$stmt = mysqli_prepare($koneksi, "SELECT iduser, namauser, username, password, role, foto FROM user WHERE username = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['error_login'] = 'Username atau password salah.';
    redirect('../index.php?page=loginuser');
}

if (!in_array($user['role'], ['admin', 'petugas'], true)) {
    $_SESSION['error_login'] = 'Role akun tidak valid.';
    redirect('../index.php?page=loginuser');
}

session_regenerate_id(true);

unset($_SESSION['idpasien'], $_SESSION['iddokter']);
$_SESSION['iduser'] = $user['iduser'];
$_SESSION['namauser'] = $user['namauser'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];
$_SESSION['foto'] = $user['foto'] ?? 'default.png';
$_SESSION['last_activity'] = time();

if ($user['role'] === 'admin') {
    redirect('../index.php?page=dashboardadmin');
}

redirect('../index.php?page=dashboardpetugas');
?>