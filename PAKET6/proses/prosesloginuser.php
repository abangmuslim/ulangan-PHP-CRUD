<?php
require_once "session.php";
require_once "koneksi.php";
require_once "helper.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect("../index.php?page=loginuser");
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    redirect("../index.php?page=loginuser&error=kosong");
}

$sql = "SELECT iduser, namauser, username, password
        FROM `user`
        WHERE username = ?
        LIMIT 1";

$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if ($user && password_verify($password, $user['password'])) {
    session_regenerate_id(true);

    // Bersihkan identitas role sebelumnya.
    unset($_SESSION['idpasien'], $_SESSION['namapasien']);

    $_SESSION['iduser'] = $user['iduser'];
    $_SESSION['namauser'] = $user['namauser'];

    redirect("../index.php?page=dashboarduser");
}

redirect("../index.php?page=loginuser&error=gagal");
