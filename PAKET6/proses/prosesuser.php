
<?php
require_once "koneksi.php";

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : "";

// ========================================
// TAMBAH USER
// ========================================
if ($aksi == "tambah") {

    $namauser = mysqli_real_escape_string($koneksi, trim($_POST['namauser']));
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = trim($_POST['password']);
    $nohp = mysqli_real_escape_string($koneksi, trim($_POST['nohp']));
    $alamat = mysqli_real_escape_string($koneksi, trim($_POST['alamat']));

    if ($namauser == "" || $username == "" || $password == "") {
        header("Location: ../index.php?page=createuser&status=kosong");
        exit;
    }

    // Cek username
    $cek = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username'");

    if (mysqli_num_rows($cek) > 0) {
        header("Location: ../index.php?page=createuser&status=duplikat");
        exit;
    }

    // Enkripsi password
    $passwordhash = password_hash($password, PASSWORD_DEFAULT);

    // Upload foto
    $foto = "";

    if (isset($_FILES['foto']) && $_FILES['foto']['name'] != "") {

        if ($_FILES['foto']['error'] != 0) {
            header("Location: ../index.php?page=createuser&status=fotogagal");
            exit;
        }

        if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
            header("Location: ../index.php?page=createuser&status=fotobesar");
            exit;
        }

        $infofoto = getimagesize($_FILES['foto']['tmp_name']);

        if ($infofoto === false) {
            header("Location: ../index.php?page=createuser&status=fototidakvalid");
            exit;
        }

        $mime = $infofoto['mime'];

        if ($mime == "image/jpeg") {
            $ekstensi = "jpg";
        } elseif ($mime == "image/png") {
            $ekstensi = "png";
        } elseif ($mime == "image/webp") {
            $ekstensi = "webp";
        } else {
            header("Location: ../index.php?page=createuser&status=fototidakvalid");
            exit;
        }

        $foto = "user_" . date("YmdHis") . "_" . rand(100, 999) . "." . $ekstensi;

        $tujuan = "../assets/images/user/" . $foto;

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $tujuan)) {
            header("Location: ../index.php?page=createuser&status=fotogagal");
            exit;
        }
    }

    $query = mysqli_query($koneksi, "
        INSERT INTO user
        (namauser, username, password, nohp, alamat, foto)
        VALUES
        ('$namauser', '$username', '$passwordhash', '$nohp', '$alamat', '$foto')
    ");

    if ($query) {
        header("Location: ../index.php?page=user&status=tambahberhasil");
        exit;
    } else {
        if ($foto != "" && file_exists("../assets/images/user/" . $foto)) {
            unlink("../assets/images/user/" . $foto);
        }

        header("Location: ../index.php?page=createuser&status=gagal");
        exit;
    }
}


// ========================================
// UBAH USER
// ========================================
elseif ($aksi == "ubah") {

    $iduser = intval($_POST['iduser']);

    $namauser = mysqli_real_escape_string($koneksi, trim($_POST['namauser']));
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = trim($_POST['password']);
    $nohp = mysqli_real_escape_string($koneksi, trim($_POST['nohp']));
    $alamat = mysqli_real_escape_string($koneksi, trim($_POST['alamat']));
    $fotolama = mysqli_real_escape_string($koneksi, $_POST['fotolama']);

    if ($iduser <= 0 || $namauser == "" || $username == "") {
        header("Location: ../index.php?page=edituser&iduser=$iduser&status=kosong");
        exit;
    }

    // Cek username selain milik user yang sedang diedit
    $cek = mysqli_query($koneksi, "
        SELECT * FROM user
        WHERE username='$username' AND iduser!='$iduser'
    ");

    if (mysqli_num_rows($cek) > 0) {
        header("Location: ../index.php?page=edituser&iduser=$iduser&status=duplikat");
        exit;
    }

    // Foto lama digunakan jika tidak upload foto baru
    $foto = $fotolama;

    // Upload foto baru
    if (isset($_FILES['foto']) && $_FILES['foto']['name'] != "") {

        if ($_FILES['foto']['error'] != 0) {
            header("Location: ../index.php?page=edituser&iduser=$iduser&status=fotogagal");
            exit;
        }

        if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
            header("Location: ../index.php?page=edituser&iduser=$iduser&status=fotobesar");
            exit;
        }

        $infofoto = getimagesize($_FILES['foto']['tmp_name']);

        if ($infofoto === false) {
            header("Location: ../index.php?page=edituser&iduser=$iduser&status=fototidakvalid");
            exit;
        }

        $mime = $infofoto['mime'];

        if ($mime == "image/jpeg") {
            $ekstensi = "jpg";
        } elseif ($mime == "image/png") {
            $ekstensi = "png";
        } elseif ($mime == "image/webp") {
            $ekstensi = "webp";
        } else {
            header("Location: ../index.php?page=edituser&iduser=$iduser&status=fototidakvalid");
            exit;
        }

        $fotonama = "user_" . date("YmdHis") . "_" . rand(100, 999) . "." . $ekstensi;

        $tujuan = "../assets/images/user/" . $fotonama;

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $tujuan)) {
            header("Location: ../index.php?page=edituser&iduser=$iduser&status=fotogagal");
            exit;
        }

        $foto = $fotonama;
    }

    // Jika password diisi, password ikut diubah
    if ($password != "") {

        $passwordhash = password_hash($password, PASSWORD_DEFAULT);

        $query = mysqli_query($koneksi, "
            UPDATE user SET
                namauser='$namauser',
                username='$username',
                password='$passwordhash',
                nohp='$nohp',
                alamat='$alamat',
                foto='$foto'
            WHERE iduser='$iduser'
        ");

    } else {

        $query = mysqli_query($koneksi, "
            UPDATE user SET
                namauser='$namauser',
                username='$username',
                nohp='$nohp',
                alamat='$alamat',
                foto='$foto'
            WHERE iduser='$iduser'
        ");
    }

    if ($query) {

        // Hapus foto lama setelah foto baru berhasil disimpan
        if ($foto != $fotolama && $fotolama != "") {
            $filelama = "../assets/images/user/" . $fotolama;

            if (file_exists($filelama)) {
                unlink($filelama);
            }
        }

        header("Location: ../index.php?page=user&status=ubahberhasil");
        exit;

    } else {

        // Hapus foto baru jika proses update gagal
        if ($foto != $fotolama && $foto != "") {
            $filebaru = "../assets/images/user/" . $foto;

            if (file_exists($filebaru)) {
                unlink($filebaru);
            }
        }

        header("Location: ../index.php?page=edituser&iduser=$iduser&status=gagal");
        exit;
    }
}


// ========================================
// HAPUS USER
// ========================================
elseif ($aksi == "hapus") {

    $iduser = intval($_POST['iduser']);

    if ($iduser <= 0) {
        header("Location: ../index.php?page=user&status=gagal");
        exit;
    }

    // Cegah user menghapus akun yang sedang digunakan
    if (isset($_SESSION['iduser']) && $_SESSION['iduser'] == $iduser) {
        header("Location: ../index.php?page=user&status=hapusdirisendiri");
        exit;
    }

    // Cek apakah user masih memiliki data kunjungan
    $cek = mysqli_query($koneksi, "
        SELECT idkunjungan FROM kunjungan
        WHERE iduser='$iduser'
        LIMIT 1
    ");

    if (mysqli_num_rows($cek) > 0) {
        header("Location: ../index.php?page=user&status=masihdigunakan");
        exit;
    }

    // Ambil nama foto sebelum data dihapus
    $data = mysqli_query($koneksi, "SELECT foto FROM user WHERE iduser='$iduser'");
    $user = mysqli_fetch_assoc($data);

    if (!$user) {
        header("Location: ../index.php?page=user&status=datatidakada");
        exit;
    }

    $foto = $user['foto'];

    $query = mysqli_query($koneksi, "DELETE FROM user WHERE iduser='$iduser'");

    if ($query) {

        if ($foto != "") {
            $filefoto = "../assets/images/user/" . $foto;

            if (file_exists($filefoto)) {
                unlink($filefoto);
            }
        }

        header("Location: ../index.php?page=user&status=hapusberhasil");
        exit;

    } else {
        header("Location: ../index.php?page=user&status=gagal");
        exit;
    }
}


// ========================================
// AKSI TIDAK DIKENAL
// ========================================
else {
    header("Location: ../index.php?page=user");
    exit;
}
