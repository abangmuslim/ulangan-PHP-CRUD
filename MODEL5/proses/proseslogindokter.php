<?php
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

include "koneksi.php";

$query = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username'");
$user = mysqli_fetch_assoc($query);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['iduser'] = $user['iduser'];
    $_SESSION['namauser'] = $user['namauser'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['statuslogin'] = true;

    header("location:../index.php?page=home");
} else {
    header("location:../index.php?page=login&pesan=Gagal");
}
?>