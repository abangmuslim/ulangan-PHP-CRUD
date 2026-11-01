
<?php
require_once __DIR__ . "/koneksi.php";
session_start();

$aksi = $_GET['aksi'];


// ========================================
// TAMBAH KATEGORI
// ========================================
if ($aksi == "tambah") {

    $namakategori = $_POST['namakategori'];

    // Cek nama kategori
    $cek = mysqli_query($koneksi, "SELECT * FROM kategori WHERE namakategori='$namakategori'");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=createkategori&status=duplikat");
        exit;
    }

    mysqli_query($koneksi, "INSERT INTO kategori SET
        namakategori='$namakategori'
    ");

    header("location:../index.php?page=kategori");
}


// ========================================
// UBAH KATEGORI
// ========================================
elseif ($aksi == "ubah") {

    $idkategori = $_POST['idkategori'];
    $namakategori = $_POST['namakategori'];

    // Cek nama kategori
    $cek = mysqli_query($koneksi, "
        SELECT * FROM kategori
        WHERE namakategori='$namakategori' AND idkategori!='$idkategori'
    ");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=editkategori&idkategori=$idkategori&status=duplikat");
        exit;
    }

    mysqli_query($koneksi, "UPDATE kategori SET
        namakategori='$namakategori'
        WHERE idkategori='$idkategori'
    ");

    header("location:../index.php?page=kategori");
}


// ========================================
// HAPUS KATEGORI
// ========================================
elseif ($aksi == "hapus") {

    $idkategori = $_POST['idkategori'];

    // Cek apakah kategori masih digunakan oleh obat
    $cek = mysqli_query($koneksi, "SELECT * FROM obat WHERE idkategori='$idkategori'");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=kategori&status=masihdigunakan");
        exit;
    }

    mysqli_query($koneksi, "DELETE FROM kategori WHERE idkategori='$idkategori'");

    header("location:../index.php?page=kategori");
}


// ========================================
// AKSI TIDAK DIKENAL
// ========================================
else {
    header("location:../index.php?page=kategori");
}

?>
