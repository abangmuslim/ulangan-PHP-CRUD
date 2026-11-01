
<?php
require_once __DIR__ . "/koneksi.php";
session_start();

$aksi = $_GET['aksi'];


// ========================================
// TAMBAH OBAT
// ========================================
if ($aksi == "tambah") {

    $idmerk = $_POST['idmerk'];
    $idkategori = $_POST['idkategori'];
    $namaobat = $_POST['namaobat'];
    $harga = $_POST['harga'];
    $tanggalstok = $_POST['tanggalstok'];
    $stok = $_POST['stok'];

    $foto = "";

    if ($_FILES['foto']['name'] != "") {
        $namafoto = $_FILES['foto']['name'];
        $ekstensi = strtolower(pathinfo($namafoto, PATHINFO_EXTENSION));
        $foto = "obat_" . date("YmdHis") . "_" . rand(100, 999) . "." . $ekstensi;

        move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/images/obat/" . $foto);
    }

    // Cek nama obat
    $cek = mysqli_query($koneksi, "SELECT * FROM obat WHERE namaobat='$namaobat'");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=createobat&status=duplikat");
        exit;
    }

    mysqli_query($koneksi, "INSERT INTO obat SET
        idmerk='$idmerk',
        idkategori='$idkategori',
        namaobat='$namaobat',
        harga='$harga',
        tanggalstok='$tanggalstok',
        stok='$stok',
        foto='$foto'
    ");

    header("location:../index.php?page=obat");
}


// ========================================
// UBAH OBAT
// ========================================
elseif ($aksi == "ubah") {

    $idobat = $_POST['idobat'];
    $idmerk = $_POST['idmerk'];
    $idkategori = $_POST['idkategori'];
    $namaobat = $_POST['namaobat'];
    $harga = $_POST['harga'];
    $tanggalstok = $_POST['tanggalstok'];
    $stok = $_POST['stok'];
    $fotolama = $_POST['fotolama'];

    $foto = $fotolama;

    // Upload foto baru
    if ($_FILES['foto']['name'] != "") {
        $namafoto = $_FILES['foto']['name'];
        $ekstensi = strtolower(pathinfo($namafoto, PATHINFO_EXTENSION));
        $foto = "obat_" . date("YmdHis") . "_" . rand(100, 999) . "." . $ekstensi;

        move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/images/obat/" . $foto);
    }

    // Cek nama obat
    $cek = mysqli_query($koneksi, "
        SELECT * FROM obat
        WHERE namaobat='$namaobat' AND idobat!='$idobat'
    ");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=editobat&idobat=$idobat&status=duplikat");
        exit;
    }

    // Update data obat
    mysqli_query($koneksi, "UPDATE obat SET
        idmerk='$idmerk',
        idkategori='$idkategori',
        namaobat='$namaobat',
        harga='$harga',
        tanggalstok='$tanggalstok',
        stok='$stok',
        foto='$foto'
        WHERE idobat='$idobat'
    ");

    // Hapus foto lama jika diganti
    if ($foto != $fotolama && $fotolama != "") {
        $filelama = "../assets/images/obat/" . $fotolama;

        if (file_exists($filelama)) {
            unlink($filelama);
        }
    }

    header("location:../index.php?page=obat");
}


// ========================================
// HAPUS OBAT
// ========================================
elseif ($aksi == "hapus") {

    $idobat = $_POST['idobat'];

    // Cek apakah obat masih digunakan dalam detil kunjungan
    $cek = mysqli_query($koneksi, "SELECT * FROM detilkunjungan WHERE idobat='$idobat'");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=obat&status=masihdigunakan");
        exit;
    }

    // Ambil foto obat
    $data = mysqli_query($koneksi, "SELECT foto FROM obat WHERE idobat='$idobat'");
    $obat = mysqli_fetch_assoc($data);

    if ($obat) {
        $foto = $obat['foto'];

        mysqli_query($koneksi, "DELETE FROM obat WHERE idobat='$idobat'");

        // Hapus file foto
        if ($foto != "") {
            $filefoto = "../assets/images/obat/" . $foto;

            if (file_exists($filefoto)) {
                unlink($filefoto);
            }
        }
    }

    header("location:../index.php?page=obat");
}


// ========================================
// AKSI TIDAK DIKENAL
// ========================================
else {
    header("location:../index.php?page=obat");
}

?>
