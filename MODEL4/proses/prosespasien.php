<?php
$proses = $_GET['proses'];
include "koneksi.php";
session_start();

if ($proses == 'insert') {
    $namapasien     = $_POST['namapasien'];
    $jeniskelamin   = $_POST['jeniskelamin'];
    $alamat         = $_POST['alamat'];
    $nohp           = $_POST['nohp'];

    mysqli_query($koneksi, "INSERT INTO pasien SET
    namapasien     ='$namapasien',
    jeniskelamin   ='$jeniskelamin',
    alamat         ='$alamat',
    nohp           ='$nohp'");

} elseif ($proses == 'update') {
    $id = $_POST['idpasien'];
    $namapasien     = $_POST['namapasien'];
    $jeniskelamin   = $_POST['jeniskelamin'];
    $alamat         = $_POST['alamat'];
    $nohp           = $_POST['nohp'];

    mysqli_query($koneksi, "UPDATE pasien SET
    namapasien     ='$namapasien',
    jeniskelamin   ='$jeniskelamin',
    alamat         ='$alamat',
    nohp           ='$nohp'
    WHERE idpasien='$id'");

} elseif ($proses == 'hapus') {
    $id = $_POST['idpasien'];

    $cek = mysqli_query($koneksi, "SELECT * FROM kunjungan
    WHERE idpasien='$id'");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=pasien&pesan=gagalhapus");
        exit;
    }

    mysqli_query($koneksi, "DELETE FROM pasien
    WHERE idpasien='$id'");
}

header("location:../index.php?page=pasien");
?>