
<?php
require_once __DIR__ . "/koneksi.php";
session_start();

$aksi = $_GET['aksi'];


// ========================================
// TAMBAH MERK
// ========================================
if ($aksi == "tambah") {

    $namamerk = $_POST['namamerk'];

    // Cek nama merk
    $cek = mysqli_query($koneksi, "SELECT * FROM merk WHERE namamerk='$namamerk'");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=createmerk&status=duplikat");
        exit;
    }

    mysqli_query($koneksi, "INSERT INTO merk SET
        namamerk='$namamerk'
    ");

    header("location:../index.php?page=merk");
}


// ========================================
// UBAH MERK
// ========================================
elseif ($aksi == "ubah") {

    $idmerk = $_POST['idmerk'];
    $namamerk = $_POST['namamerk'];

    // Cek nama merk
    $cek = mysqli_query($koneksi, "
        SELECT * FROM merk
        WHERE namamerk='$namamerk' AND idmerk!='$idmerk'
    ");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=editmerk&idmerk=$idmerk&status=duplikat");
        exit;
    }

    mysqli_query($koneksi, "UPDATE merk SET
        namamerk='$namamerk'
        WHERE idmerk='$idmerk'
    ");

    header("location:../index.php?page=merk");
}


// ========================================
// HAPUS MERK
// ========================================
elseif ($aksi == "hapus") {

    $idmerk = $_POST['idmerk'];

    // Cek apakah merk masih digunakan oleh obat
    $cek = mysqli_query($koneksi, "SELECT * FROM obat WHERE idmerk='$idmerk'");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=merk&status=masihdigunakan");
        exit;
    }

    mysqli_query($koneksi, "DELETE FROM merk WHERE idmerk='$idmerk'");

    header("location:../index.php?page=merk");
}


// ========================================
// AKSI TIDAK DIKENAL
// ========================================
else {
    header("location:../index.php?page=merk");
}

?>
