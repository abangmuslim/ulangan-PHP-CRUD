<?php
$proses = $_GET['proses'];
include "koneksi.php";
session_start();

if ($proses == 'insert') {
    $namauser     = $_POST['namauser'];
    $username     = $_POST['username'];
    $password     = password_hash($_POST['password'], PASSWORD_DEFAULT);

    mysqli_query($koneksi, "INSERT INTO user SET
    namauser    ='$namauser',
    username    ='$username',
    password    ='$password'");

} elseif ($proses == 'update') {
    $id = $_POST['iduser'];
    $namauser     = $_POST['namauser'];
    $username     = $_POST['username'];
    $password     = $_POST['password'];

    if ($password != '') {
        $password = password_hash($password, PASSWORD_DEFAULT);

        mysqli_query($koneksi, "UPDATE user SET
        namauser    ='$namauser',
        username    ='$username',
        password    ='$password'
        WHERE iduser='$id'");
    } else {
        mysqli_query($koneksi, "UPDATE user SET
        namauser    ='$namauser',
        username    ='$username'
        WHERE iduser='$id'");
    }

} elseif ($proses == 'hapus') {
    $id = $_POST['iduser'];

    if ($id == $_SESSION['iduser']) {
        header("location:../index.php?page=user&pesan=gagallogin");
        exit;
    }

    $cek = mysqli_query($koneksi, "SELECT * FROM kunjungan
    WHERE iduser='$id'");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=user&pesan=gagalhapus");
        exit;
    }

    mysqli_query($koneksi, "DELETE FROM user
    WHERE iduser='$id'");
}

header("location:../index.php?page=user");
?>