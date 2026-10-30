<?php
require_once "session.php";
require_once "koneksi.php";
require_once "helper.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect("../index.php?page=registerpasien");
}

$namapasien = trim($_POST['namapasien'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$konfirmasi = $_POST['konfirmasi_password'] ?? '';
$nohp = trim($_POST['nohp'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');

if (
    $namapasien === '' ||
    $username === '' ||
    $password === '' ||
    $konfirmasi === ''
) {
    redirect("../index.php?page=registerpasien&status=gagal");
}

if (
    strlen($namapasien) > 100 ||
    strlen($username) > 50 ||
    strlen($nohp) > 15
) {
    redirect("../index.php?page=registerpasien&status=gagal");
}

if (strlen($password) < 8) {
    redirect("../index.php?page=registerpasien&status=gagal");
}

if ($password !== $konfirmasi) {
    redirect("../index.php?page=registerpasien&status=password");
}

// Periksa apakah username sudah digunakan.
$sql = "SELECT idpasien FROM pasien WHERE username = ? LIMIT 1";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$akunAda = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if ($akunAda) {
    redirect("../index.php?page=registerpasien&status=duplikat");
}

// Password disimpan dalam bentuk hash.
$hashPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO pasien
        (namapasien, username, password, nohp, alamat)
        VALUES (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param(
    $stmt,
    "sssss",
    $namapasien,
    $username,
    $hashPassword,
    $nohp,
    $alamat
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    redirect("../index.php?page=registerpasien&status=berhasil");
}

mysqli_stmt_close($stmt);
redirect("../index.php?page=registerpasien&status=gagal");
