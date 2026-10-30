<?php
require_once "session.php";
require_once "koneksi.php";
require_once "helper.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect("../index.php?page=loginpasien");
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    redirect("../index.php?page=loginpasien&error=kosong");
}

$sql = "SELECT idpasien, namapasien, username, password
        FROM pasien
        WHERE username = ?
        LIMIT 1";

$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$pasien = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (
    $pasien &&
    !empty($pasien['password']) &&
    password_verify($password, $pasien['password'])
) {
    session_regenerate_id(true);

    // Bersihkan identitas role sebelumnya.
    unset($_SESSION['iduser'], $_SESSION['namauser']);

    $_SESSION['idpasien'] = $pasien['idpasien'];
    $_SESSION['namapasien'] = $pasien['namapasien'];

    redirect("../index.php?page=dashboardpasien");
}

redirect("../index.php?page=loginpasien&error=gagal");
