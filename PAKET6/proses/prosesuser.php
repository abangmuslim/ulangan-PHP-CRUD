
<?php
require_once __DIR__ . "/koneksi.php";
session_start();

$aksi = $_GET['aksi'];

// ========================================
// TAMBAH USER
// ========================================
if ($aksi == "tambah") {

    $namauser = $_POST['namauser'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nohp = $_POST['nohp'];
    $alamat = $_POST['alamat'];

    $foto = "";

    if ($_FILES['foto']['name'] != "") {
        $namafoto = $_FILES['foto']['name'];
        $ekstensi = strtolower(pathinfo($namafoto, PATHINFO_EXTENSION));
        $foto = "user_" . date("YmdHis") . "_" . rand(100, 999) . "." . $ekstensi;

        move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/images/user/" . $foto);
    }

    // Cek username
    $cek = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username'");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=createuser&status=duplikat");
        exit;
    }

    mysqli_query($koneksi, "INSERT INTO user SET
        namauser='$namauser',
        username='$username',
        password='$password',
        nohp='$nohp',
        alamat='$alamat',
        foto='$foto'
    ");

    header("location:../index.php?page=user");
}


// ========================================
// UBAH USER
// ========================================
elseif ($aksi == "ubah") {

    $iduser = $_POST['iduser'];
    $namauser = $_POST['namauser'];
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
        $foto = "user_" . date("YmdHis") . "_" . rand(100, 999) . "." . $ekstensi;

        move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/images/user/" . $foto);
    }

    // Cek username
    $cek = mysqli_query($koneksi, "
        SELECT * FROM user
        WHERE username='$username' AND iduser!='$iduser'
    ");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=edituser&iduser=$iduser&status=duplikat");
        exit;
    }

    // Jika password diisi, password ikut diubah
    if ($password != "") {
        $password = password_hash($password, PASSWORD_DEFAULT);

        mysqli_query($koneksi, "UPDATE user SET
            namauser='$namauser',
            username='$username',
            password='$password',
            nohp='$nohp',
            alamat='$alamat',
            foto='$foto'
            WHERE iduser='$iduser'
        ");
    } else {
        mysqli_query($koneksi, "UPDATE user SET
            namauser='$namauser',
            username='$username',
            nohp='$nohp',
            alamat='$alamat',
            foto='$foto'
            WHERE iduser='$iduser'
        ");
    }

    // Hapus foto lama jika diganti
    if ($foto != $fotolama && $fotolama != "") {
        $filelama = "../assets/images/user/" . $fotolama;

        if (file_exists($filelama)) {
            unlink($filelama);
        }
    }

    header("location:../index.php?page=user");
}


// ========================================
// HAPUS USER
// ========================================
elseif ($aksi == "hapus") {

    $iduser = $_POST['iduser'];

    // Cek agar tidak menghapus akun sendiri
    if (isset($_SESSION['iduser']) && $_SESSION['iduser'] == $iduser) {
        header("location:../index.php?page=user&status=hapusdirisendiri");
        exit;
    }

    // Cek apakah user memiliki data kunjungan
    $cek = mysqli_query($koneksi, "SELECT * FROM kunjungan WHERE iduser='$iduser'");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=user&status=masihdigunakan");
        exit;
    }

    // Ambil foto user
    $data = mysqli_query($koneksi, "SELECT foto FROM user WHERE iduser='$iduser'");
    $user = mysqli_fetch_assoc($data);

    if ($user) {
        $foto = $user['foto'];

        mysqli_query($koneksi, "DELETE FROM user WHERE iduser='$iduser'");

        // Hapus file foto
        if ($foto != "") {
            $filefoto = "../assets/images/user/" . $foto;

            if (file_exists($filefoto)) {
                unlink($filefoto);
            }
        }
    }

    header("location:../index.php?page=user");
}


// ========================================
// AKSI TIDAK DIKENAL
// ========================================
else {
    header("location:../index.php?page=user");
}

?>
