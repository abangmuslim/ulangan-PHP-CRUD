<?php
$proses = $_GET['proses'];
include "koneksi.php";
session_start();

if ($proses == 'insert') {
    $namaobat   = $_POST['namaobat'];
    $jenisobat  = $_POST['jenisobat'];
    $stok       = $_POST['stok'];
    $harga      = $_POST['harga'];

    mysqli_query($koneksi, "INSERT INTO obat SET
    namaobat    ='$namaobat',
    jenisobat   ='$jenisobat',
    stok        ='$stok',
    harga       ='$harga'");

} elseif ($proses == 'update') {
    $id         = $_POST['idobat'];
    $namaobat   = $_POST['namaobat'];
    $jenisobat  = $_POST['jenisobat'];
    $stok       = $_POST['stok'];
    $harga      = $_POST['harga'];

    mysqli_query($koneksi, "UPDATE obat SET
    namaobat    ='$namaobat',
    jenisobat   ='$jenisobat',
    stok        ='$stok',
    harga       ='$harga'
    WHERE idobat='$id'");

} elseif ($proses == 'hapus') {
    $id = $_POST['idobat'];

    $cek = mysqli_query($koneksi, "SELECT * FROM kunjungan
    WHERE idobat='$id'");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=obat&pesan=gagalhapus");
        exit;
    }

    mysqli_query($koneksi, "DELETE FROM obat
    WHERE idobat='$id'");
}

header("location:../index.php?page=obat");
?>