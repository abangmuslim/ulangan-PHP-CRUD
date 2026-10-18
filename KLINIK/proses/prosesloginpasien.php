<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/helper.php';

if (!isPost()) {
    redirect('../index.php?page=loginpasien');
}

$username = ambilPost('username');
$password = $_POST['password'] ?? '';

if ($username === '' || !is_string($password) || $password === '') {
    $_SESSION['error_login'] = 'Username dan password wajib diisi.';
    redirect('../index.php?page=loginpasien');
}

$stmt = mysqli_prepare($koneksi, "SELECT idpasien, namapasien, username, password, foto FROM pasien WHERE username = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pasien = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$pasien || empty($pasien['password']) || !password_verify($password, $pasien['password'])) {
    $_SESSION['error_login'] = 'Username atau password salah.';
    redirect('../index.php?page=loginpasien');
}

session_regenerate_id(true);

unset($_SESSION['iduser'], $_SESSION['role'], $_SESSION['iddokter']);
$_SESSION['idpasien'] = $pasien['idpasien'];
$_SESSION['namapasien'] = $pasien['namapasien'];
$_SESSION['username'] = $pasien['username'];
$_SESSION['foto'] = $pasien['foto'] ?? 'default.png';
$_SESSION['last_activity'] = time();

redirect('../index.php?page=dashboardpasien');
?>