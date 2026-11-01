
<?php
include "koneksi.php";
session_start();

$aksi = $_GET['aksi'];

$folderFoto = "../assets/images/dokter/";

// BATAS UKURAN FOTO 2 MB
$batasFoto = 2 * 1024 * 1024;


// ==================================================
// TAMBAH DOKTER
// ==================================================
if ($aksi == 'tambah') {

    $namadokter = $_POST['namadokter'];
    $spesialis  = $_POST['spesialis'];
    $nohp       = $_POST['nohp'];
    $alamat     = $_POST['alamat'];

    $foto = "";

    // Upload foto dokter
    if ($_FILES['foto']['error'] != 4) {

        if ($_FILES['foto']['error'] != 0) {
            header("location:../index.php?page=dokter&status=fotogagal");
            exit;
        }

        if ($_FILES['foto']['size'] > $batasFoto) {
            header("location:../index.php?page=dokter&status=fotobesar");
            exit;
        }

        $gambar = getimagesize($_FILES['foto']['tmp_name']);

        if ($gambar == false) {
            header("location:../index.php?page=dokter&status=fototidakvalid");
            exit;
        }

        $mime = $gambar['mime'];

        if ($mime == 'image/jpeg') {
            $ekstensi = 'jpg';
        } elseif ($mime == 'image/png') {
            $ekstensi = 'png';
        } elseif ($mime == 'image/webp') {
            $ekstensi = 'webp';
        } else {
            header("location:../index.php?page=dokter&status=fototidakvalid");
            exit;
        }

        $foto = "dokter_" . uniqid() . "." . $ekstensi;

        move_uploaded_file(
            $_FILES['foto']['tmp_name'],
            $folderFoto . $foto
        );
    }

    mysqli_query($koneksi, "INSERT INTO dokter SET
    namadokter ='$namadokter',
    spesialis  ='$spesialis',
    nohp       ='$nohp',
    alamat     ='$alamat',
    foto       ='$foto'");


// ==================================================
// UBAH DOKTER
// ==================================================
} elseif ($aksi == 'ubah') {

    $id         = $_POST['iddokter'];
    $namadokter = $_POST['namadokter'];
    $spesialis  = $_POST['spesialis'];
    $nohp       = $_POST['nohp'];
    $alamat     = $_POST['alamat'];
    $fotolama   = $_POST['fotolama'];

    $foto = $_FILES['foto']['name'];

    // Jika memilih foto baru
    if ($foto != "") {

        if ($_FILES['foto']['error'] != 0) {
            header("location:../index.php?page=dokter&status=fotogagal");
            exit;
        }

        if ($_FILES['foto']['size'] > $batasFoto) {
            header("location:../index.php?page=dokter&status=fotobesar");
            exit;
        }

        $gambar = getimagesize($_FILES['foto']['tmp_name']);

        if ($gambar == false) {
            header("location:../index.php?page=dokter&status=fototidakvalid");
            exit;
        }

        $mime = $gambar['mime'];

        if ($mime == 'image/jpeg') {
            $ekstensi = 'jpg';
        } elseif ($mime == 'image/png') {
            $ekstensi = 'png';
        } elseif ($mime == 'image/webp') {
            $ekstensi = 'webp';
        } else {
            header("location:../index.php?page=dokter&status=fototidakvalid");
            exit;
        }

        $foto = "dokter_" . uniqid() . "." . $ekstensi;

        move_uploaded_file(
            $_FILES['foto']['tmp_name'],
            $folderFoto . $foto
        );

        // Hapus foto lama
        if ($fotolama != "" && file_exists($folderFoto . $fotolama)) {
            unlink($folderFoto . $fotolama);
        }

    } else {
        $foto = $fotolama;
    }

    mysqli_query($koneksi, "UPDATE dokter SET
    namadokter ='$namadokter',
    spesialis  ='$spesialis',
    nohp       ='$nohp',
    alamat     ='$alamat',
    foto       ='$foto'
    WHERE iddokter='$id'");


// ==================================================
// HAPUS DOKTER
// ==================================================
} elseif ($aksi == 'hapus') {

    $id = $_POST['iddokter'];

    // Cek jadwal dokter
    $cekjadwal = mysqli_query($koneksi, "SELECT * FROM jadwaldokter
    WHERE iddokter='$id'");

    // Cek kunjungan dokter
    $cekkunjungan = mysqli_query($koneksi, "SELECT * FROM kunjungan
    WHERE iddokter='$id'");

    if (mysqli_num_rows($cekjadwal) > 0 || mysqli_num_rows($cekkunjungan) > 0) {

        header("location:../index.php?page=dokter&status=terpakai");
        exit;
    }

    // Ambil foto dokter
    $data = mysqli_query($koneksi, "SELECT * FROM dokter
    WHERE iddokter='$id'");

    $dokter = mysqli_fetch_array($data);

    // Hapus foto
    if ($dokter['foto'] != "" && file_exists($folderFoto . $dokter['foto'])) {
        unlink($folderFoto . $dokter['foto']);
    }

    mysqli_query($koneksi, "DELETE FROM dokter
    WHERE iddokter='$id'");
}

header("location:../index.php?page=dokter");
?>
