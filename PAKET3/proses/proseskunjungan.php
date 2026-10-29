
<?php
$proses = $_GET['proses'];
include __DIR__ . "/../koneksi.php";

if ($proses == 'insert') {
        $idpasien   = $_POST['idpasien'];
        $tanggal    = $_POST['tanggal'];
        $keluhan    = $_POST['keluhan'];
        $biaya      = $_POST['biaya'];

    mysqli_query($koneksi, "INSERT INTO kunjungan SET
        idpasien    ='$idpasien',
        tanggal     ='$tanggal',
        keluhan     ='$keluhan',
        biaya       ='$biaya'");

} elseif ($proses == 'update') {
        $id         = $_POST['idkunjungan'];
        $idpasien   = $_POST['idpasien'];
        $tanggal    = $_POST['tanggal'];
        $keluhan    = $_POST['keluhan'];
        $biaya      = $_POST['biaya'];

    mysqli_query($koneksi, "UPDATE kunjungan SET
        idpasien    ='$idpasien',
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
exit;
?>
