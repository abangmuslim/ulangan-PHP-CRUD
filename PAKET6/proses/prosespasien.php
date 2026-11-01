
<?php
require_once __DIR__ . "/koneksi.php";
session_start();

$aksi = $_GET['aksi'];


// ========================================
// TAMBAH PASIEN
// ========================================
if ($aksi == "tambah") {

    $namapasien = $_POST['namapasien'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nohp = $_POST['nohp'];
    $alamat = $_POST['alamat'];

    $foto = "";

    if ($_FILES['foto']['name'] != "") {
        $namafoto = $_FILES['foto']['name'];
        $ekstensi = strtolower(pathinfo($namafoto, PATHINFO_EXTENSION));
        $foto = "pasien_" . date("YmdHis") . "_" . rand(100, 999) . "." . $ekstensi;

        move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/images/pasien/" . $foto);
    }

    // Cek username
    $cek = mysqli_query($koneksi, "SELECT * FROM pasien WHERE username='$username'");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=createpasien&status=duplikat");
        exit;
    }

    mysqli_query($koneksi, "INSERT INTO pasien SET
        namapasien='$namapasien',
        username='$username',
        password='$password',
        nohp='$nohp',
        alamat='$alamat',
        foto='$foto'
    ");

    header("location:../index.php?page=pasien");
}


// ========================================
// UBAH PASIEN
// ========================================
elseif ($aksi == "ubah") {

    $idpasien = $_POST['idpasien'];
    $namapasien = $_POST['namapasien'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nohp = $_POST['nohp'];
    $alamat = $_POST['alamat'];
    $fotolama = $_POST['fotolama'];

    $foto = $fotolama;

    // Upload foto baru
    if ($_FILES['foto']['name'] != "") {
        $namafoto = $_FILES['foto']['name'];
        $ekstensi = strtolower(pathinfo($namafoto, PATHINFO_EXTENSION));
        $foto = "pasien_" . date("YmdHis") . "_" . rand(100, 999) . "." . $ekstensi;

        move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/images/pasien/" . $foto);
    }

    // Cek username
    $cek = mysqli_query($koneksi, "
        SELECT * FROM pasien
        WHERE username='$username' AND idpasien!='$idpasien'
    ");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=editpasien&idpasien=$idpasien&status=duplikat");
        exit;
    }

    // Jika password diisi, password ikut diubah
    if ($password != "") {
        $password = password_hash($password, PASSWORD_DEFAULT);

        mysqli_query($koneksi, "UPDATE pasien SET
            namapasien='$namapasien',
            username='$username',
            password='$password',
            nohp='$nohp',
            alamat='$alamat',
            foto='$foto'
            WHERE idpasien='$idpasien'
        ");
    } else {
        mysqli_query($koneksi, "UPDATE pasien SET
            namapasien='$namapasien',
            username='$username',
            nohp='$nohp',
            alamat='$alamat',
            foto='$foto'
            WHERE idpasien='$idpasien'
        ");
    }

    // Hapus foto lama jika diganti
    if ($foto != $fotolama && $fotolama != "") {
        $filelama = "../assets/images/pasien/" . $fotolama;

        if (file_exists($filelama)) {
            unlink($filelama);
        }
    }

    header("location:../index.php?page=pasien");
}


// ========================================
// HAPUS PASIEN
// ========================================
elseif ($aksi == "hapus") {

    $idpasien = $_POST['idpasien'];

    // Cek agar tidak menghapus akun sendiri
    if (isset($_SESSION['idpasien']) && $_SESSION['idpasien'] == $idpasien) {
        header("location:../index.php?page=pasien&status=hapusdirisendiri");
        exit;
    }

    // Cek apakah pasien memiliki data kunjungan
    $cek = mysqli_query($koneksi, "SELECT * FROM kunjungan WHERE idpasien='$idpasien'");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=pasien&status=masihdigunakan");
        exit;
    }

    // Cek apakah pasien memiliki data pendaftaran checkup
    $cek = mysqli_query($koneksi, "SELECT * FROM pendaftarancheckup WHERE idpasien='$idpasien'");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=pasien&status=masihdigunakan");
        exit;
    }

    // Ambil foto pasien
    $data = mysqli_query($koneksi, "SELECT foto FROM pasien WHERE idpasien='$idpasien'");
    $pasien = mysqli_fetch_assoc($data);

    if ($pasien) {
        $foto = $pasien['foto'];

        mysqli_query($koneksi, "DELETE FROM pasien WHERE idpasien='$idpasien'");

        // Hapus file foto
        if ($foto != "") {
            $filefoto = "../assets/images/pasien/" . $foto;

            if (file_exists($filefoto)) {
                unlink($filefoto);
            }
        }
    }

    header("location:../index.php?page=pasien");
}


// ========================================
// AKSI TIDAK DIKENAL
// ========================================
else {
    header("location:../index.php?page=pasien");
}

?>
