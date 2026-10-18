<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/helper.php';

if (!isPost()) {
    redirect('../index.php?page=registerpasien');
}

$namapasien = ambilPost('namapasien');
$username = ambilPost('username');
$nohp = ambilPost('nohp');
$alamat = ambilPost('alamat');
$password = $_POST['password'] ?? '';
$konfirmasi = $_POST['konfirmasi_password'] ?? '';

if (
    $namapasien === '' ||
    $username === '' ||
    !is_string($password) ||
    !is_string($konfirmasi) ||
    $password === '' ||
    $konfirmasi === ''
) {
    $_SESSION['error_register'] = 'Nama, username, dan password wajib diisi.';
    redirect('../index.php?page=registerpasien');
}

if ($password !== $konfirmasi) {
    $_SESSION['error_register'] = 'Konfirmasi password tidak sama.';
    redirect('../index.php?page=registerpasien');
}

if (strlen($password) < 8) {
    $_SESSION['error_register'] = 'Password minimal 8 karakter.';
    redirect('../index.php?page=registerpasien');
}

if (strlen($username) > 50 || strlen($namapasien) > 100) {
    $_SESSION['error_register'] = 'Nama atau username melebihi batas karakter.';
    redirect('../index.php?page=registerpasien');
}

// Periksa apakah username sudah digunakan pasien lain
$stmt = mysqli_prepare($koneksi, "SELECT idpasien FROM pasien WHERE username = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$usernameAda = mysqli_num_rows($result) > 0;
mysqli_stmt_close($stmt);

if ($usernameAda) {
    $_SESSION['error_register'] = 'Username sudah digunakan. Silakan pilih username lain.';
    redirect('../index.php?page=registerpasien');
}

// Upload foto pasien jika ada
$hasilUpload = uploadFoto(
    $_FILES['foto'] ?? [],
    'assets/images/pasien',
    $username
);

if (!$hasilUpload['success']) {
    $_SESSION['error_register'] = $hasilUpload['message'];
    redirect('../index.php?page=registerpasien');
}

$foto = $hasilUpload['filename'] ?? 'default.png';
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = mysqli_prepare(
    $koneksi,
    "INSERT INTO pasien (namapasien, username, password, nohp, alamat, foto)
     VALUES (?, ?, ?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "ssssss",
    $namapasien,
    $username,
    $passwordHash,
    $nohp,
    $alamat,
    $foto
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    $_SESSION['success_register'] = 'Registrasi berhasil. Silakan login menggunakan akun Anda.';
    redirect('../index.php?page=loginpasien');
}

mysqli_stmt_close($stmt);

// Hapus foto yang telanjur diunggah jika penyimpanan data gagal
if ($foto !== 'default.png') {
    hapusFoto($foto, 'assets/images/pasien');
}

$_SESSION['error_register'] = 'Registrasi gagal. Silakan coba kembali.';
redirect('../index.php?page=registerpasien');
?>