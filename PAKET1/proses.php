<?php
include "koneksi.php";

$proses = isset($_GET['proses']) ? $_GET['proses'] : '';

if ($proses == 'insert') {
        $namapasien     = $_POST['namapasien'];
        $jeniskelamin   = $_POST['jeniskelamin'];
        $alamat         = $_POST['alamat'];
        $nohp           = $_POST['nohp'];

    mysqli_query($koneksi, "INSERT INTO pasien SET
        namapasien      ='$namapasien',
        jeniskelamin    ='$jeniskelamin',
        alamat          ='$alamat',
        nohp            ='$nohp'");

} elseif ($proses == 'update') {
        $id             = $_POST['idpasien'];
        $namapasien     = $_POST['namapasien'];
        $jeniskelamin   = $_POST['jeniskelamin'];
        $alamat         = $_POST['alamat'];
        $nohp           = $_POST['nohp'];

    mysqli_query($koneksi, "UPDATE pasien SET
        namapasien      ='$namapasien',
        jeniskelamin    ='$jeniskelamin',
        alamat          ='$alamat',
        nohp            ='$nohp'
        WHERE idpasien  ='$id'");

} elseif ($proses == 'hapus') {
        $id             = $_POST['idpasien'];

    mysqli_query($koneksi, "DELETE FROM pasien
        WHERE idpasien  ='$id'");
}

header("location:index.php");
exit;
?>
