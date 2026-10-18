<?php
$proses = $_GET['proses'];
include "koneksi.php";
session_start();

if ($proses == 'insert') {
    $idpasien   = $_POST['idpasien'];
    $iduser     = $_SESSION['iduser'];
    $iddokter   = $_POST['iddokter'];
    $idobat     = $_POST['idobat'];
    $tanggal    = $_POST['tanggal'];
    $keluhan    = $_POST['keluhan'];
    $biaya      = $_POST['biaya'];

    mysqli_query($koneksi, "INSERT INTO kunjungan SET
    idpasien    ='$idpasien',
    iduser      ='$iduser',
    iddokter    ='$iddokter',
    idobat      ='$idobat',
    tanggal     ='$tanggal',
    keluhan     ='$keluhan',
    biaya       ='$biaya'");

} elseif ($proses == 'update') {
    $id         = $_POST['idkunjungan'];
    $idpasien   = $_POST['idpasien'];
    $iddokter   = $_POST['iddokter'];
    $idobat     = $_POST['idobat'];
    $tanggal    = $_POST['tanggal'];
    $keluhan    = $_POST['keluhan'];
    $biaya      = $_POST['biaya'];

    mysqli_query($koneksi, "UPDATE kunjungan SET
    idpasien    ='$idpasien',
    iduser      ='$iduser',
    iddokter    ='$iddokter',
    idobat      ='$idobat',
    tanggal     ='$tanggal',
    keluhan     ='$keluhan',
    biaya       ='$biaya'
    WHERE idkunjungan='$id'");

} elseif ($proses == 'hapus') {
    $id = $_POST['idkunjungan'];

    mysqli_query($koneksi, "DELETE FROM kunjungan
    WHERE idkunjungan='$id'");
}

header("location:../index.php?page=kunjungan");
?>