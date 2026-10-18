<?php
$proses = $_GET['proses'];
include "koneksi.php";
session_start();

if ($proses == 'insert') {
    $namadokter = $_POST['namadokter'];
    $spesialis  = $_POST['spesialis'];
    $nohp       = $_POST['nohp'];

    mysqli_query($koneksi, "INSERT INTO dokter SET
    namadokter  ='$namadokter',
    spesialis   ='$spesialis',
    nohp        ='$nohp'");

} elseif ($proses == 'update') {
    $id         = $_POST['iddokter'];
    $namadokter = $_POST['namadokter'];
    $spesialis  = $_POST['spesialis'];
    $nohp       = $_POST['nohp'];

    mysqli_query($koneksi, "UPDATE dokter SET
    namadokter  ='$namadokter',
    spesialis   ='$spesialis',
    nohp        ='$nohp'
    WHERE iddokter='$id'");

} elseif ($proses == 'hapus') {
    $id = $_POST['iddokter'];

    $cek = mysqli_query($koneksi, "SELECT * FROM kunjungan
    WHERE iddokter='$id'");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=dokter&pesan=gagalhapus");
        exit;
    }

    mysqli_query($koneksi, "DELETE FROM dokter
    WHERE iddokter='$id'");
}

header("location:../index.php?page=dokter");
?>