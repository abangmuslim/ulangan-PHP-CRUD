<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/helper.php';

if (!isPost()) {
    redirect('../index.php?page=logindokter');
}

$username = ambilPost('username');
$password = $_POST['password'] ?? '';

if ($username === '' || !is_string($password) || $password === '') {
    $_SESSION['error_login'] = 'Username dan password wajib diisi.';
    redirect('../index.php?page=logindokter');
}

$stmt = mysqli_prepare($koneksi, "SELECT iddokter, namadokter, username, password, foto FROM dokter WHERE username = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$dokter = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$dokter || empty($dokter['password']) || !password_verify($password, $dokter['password'])) {
    $_SESSION['error_login'] = 'Username atau password salah.';
    redirect('../index.php?page=logindokter');
}

session_regenerate_id(true);

unset($_SESSION['iduser'], $_SESSION['role'], $_SESSION['idpasien']);
$_SESSION['iddokter'] = $dokter['iddokter'];
$_SESSION['namadokter'] = $dokter['namadokter'];
$_SESSION['username'] = $dokter['username'];
$_SESSION['foto'] = $dokter['foto'] ?? 'default.png';
$_SESSION['last_activity'] = time();

redirect('../index.php?page=dashboarddokter');
?>