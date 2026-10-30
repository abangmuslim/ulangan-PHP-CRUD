
<?php
require_once "koneksi.php";

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : "";


// ========================================
// TAMBAH PASIEN
// ========================================
if ($aksi == "tambah") {

    $namapasien = mysqli_real_escape_string($koneksi, trim($_POST['namapasien']));
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = trim($_POST['password']);
    $nohp = mysqli_real_escape_string($koneksi, trim($_POST['nohp']));
    $alamat = mysqli_real_escape_string($koneksi, trim($_POST['alamat']));

    if ($namapasien == "" || $username == "" || $password == "") {
        header("Location: ../index.php?page=createpasien&status=kosong");
        exit;
    }

    // Cek username
    $cek = mysqli_query($koneksi, "
        SELECT * FROM pasien WHERE username='$username'
    ");

    if (mysqli_num_rows($cek) > 0) {
        header("Location: ../index.php?page=createpasien&status=duplikat");
        exit;
    }

    // Enkripsi password
    $passwordhash = password_hash($password, PASSWORD_DEFAULT);

    // Upload foto
    $foto = "";

    if (isset($_FILES['foto']) && $_FILES['foto']['name'] != "") {

        if ($_FILES['foto']['error'] != 0) {
            header("Location: ../index.php?page=createpasien&status=fotogagal");
            exit;
        }

        if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
            header("Location: ../index.php?page=createpasien&status=fotobesar");
            exit;
        }

        $infofoto = getimagesize($_FILES['foto']['tmp_name']);

        if ($infofoto === false) {
            header("Location: ../index.php?page=createpasien&status=fototidakvalid");
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
            header("Location: ../index.php?page=createpasien&status=fototidakvalid");
            exit;
        }

        $foto = "pasien_" . date("YmdHis") . "_" . rand(100, 999) . "." . $ekstensi;

        $tujuan = "../assets/images/pasien/" . $foto;

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $tujuan)) {
            header("Location: ../index.php?page=createpasien&status=fotogagal");
            exit;
        }
    }

    $query = mysqli_query($koneksi, "
        INSERT INTO pasien
        (namapasien, username, password, nohp, alamat, foto)
        VALUES
        ('$namapasien', '$username', '$passwordhash', '$nohp', '$alamat', '$foto')
    ");

    if ($query) {
        header("Location: ../index.php?page=pasien&status=tambahberhasil");
        exit;
    } else {

        if ($foto != "" && file_exists("../assets/images/pasien/" . $foto)) {
            unlink("../assets/images/pasien/" . $foto);
        }

        header("Location: ../index.php?page=createpasien&status=gagal");
        exit;
    }
}


// ========================================
// UBAH PASIEN
// ========================================
elseif ($aksi == "ubah") {

    $idpasien = intval($_POST['idpasien']);

    $namapasien = mysqli_real_escape_string($koneksi, trim($_POST['namapasien']));
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = trim($_POST['password']);
    $nohp = mysqli_real_escape_string($koneksi, trim($_POST['nohp']));
    $alamat = mysqli_real_escape_string($koneksi, trim($_POST['alamat']));
    $fotolama = mysqli_real_escape_string($koneksi, $_POST['fotolama']);

    if ($idpasien <= 0 || $namapasien == "" || $username == "") {
        header("Location: ../index.php?page=editpasien&idpasien=$idpasien&status=kosong");
        exit;
    }

    // Cek username selain milik pasien yang sedang diedit
    $cek = mysqli_query($koneksi, "
        SELECT * FROM pasien
        WHERE username='$username' AND idpasien!='$idpasien'
    ");

    if (mysqli_num_rows($cek) > 0) {
        header("Location: ../index.php?page=editpasien&idpasien=$idpasien&status=duplikat");
        exit;
    }

    // Pertahankan foto lama
    $foto = $fotolama;

    // Upload foto baru
    if (isset($_FILES['foto']) && $_FILES['foto']['name'] != "") {

        if ($_FILES['foto']['error'] != 0) {
            header("Location: ../index.php?page=editpasien&idpasien=$idpasien&status=fotogagal");
            exit;
        }

        if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
            header("Location: ../index.php?page=editpasien&idpasien=$idpasien&status=fotobesar");
            exit;
        }

        $infofoto = getimagesize($_FILES['foto']['tmp_name']);

        if ($infofoto === false) {
            header("Location: ../index.php?page=editpasien&idpasien=$idpasien&status=fototidakvalid");
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
            header("Location: ../index.php?page=editpasien&idpasien=$idpasien&status=fototidakvalid");
            exit;
        }

        $fotonama = "pasien_" . date("YmdHis") . "_" . rand(100, 999) . "." . $ekstensi;

        $tujuan = "../assets/images/pasien/" . $fotonama;

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $tujuan)) {
            header("Location: ../index.php?page=editpasien&idpasien=$idpasien&status=fotogagal");
            exit;
        }

        $foto = $fotonama;
    }

    // Jika password diisi, password ikut diubah
    if ($password != "") {

        $passwordhash = password_hash($password, PASSWORD_DEFAULT);

        $query = mysqli_query($koneksi, "
            UPDATE pasien SET
                namapasien='$namapasien',
                username='$username',
                password='$passwordhash',
                nohp='$nohp',
                alamat='$alamat',
                foto='$foto'
            WHERE idpasien='$idpasien'
        ");

    } else {

        $query = mysqli_query($koneksi, "
            UPDATE pasien SET
                namapasien='$namapasien',
                username='$username',
                nohp='$nohp',
                alamat='$alamat',
                foto='$foto'
            WHERE idpasien='$idpasien'
        ");
    }

    if ($query) {

        // Hapus foto lama jika foto baru berhasil disimpan
        if ($foto != $fotolama && $fotolama != "") {
            $filelama = "../assets/images/pasien/" . $fotolama;

            if (file_exists($filelama)) {
                unlink($filelama);
            }
        }

        header("Location: ../index.php?page=pasien&status=ubahberhasil");
        exit;

    } else {

        // Hapus foto baru jika update gagal
        if ($foto != $fotolama && $foto != "") {
            $filebaru = "../assets/images/pasien/" . $foto;

            if (file_exists($filebaru)) {
                unlink($filebaru);
            }
        }

        header("Location: ../index.php?page=editpasien&idpasien=$idpasien&status=gagal");
        exit;
    }
}


// ========================================
// HAPUS PASIEN
// ========================================
elseif ($aksi == "hapus") {

    $idpasien = intval($_POST['idpasien']);

    if ($idpasien <= 0) {
        header("Location: ../index.php?page=pasien&status=gagal");
        exit;
    }

    // Cegah pasien menghapus akun yang sedang digunakan
    if (isset($_SESSION['idpasien']) && $_SESSION['idpasien'] == $idpasien) {
        header("Location: ../index.php?page=pasien&status=hapusdirisendiri");
        exit;
    }

    // Cek riwayat kunjungan
    $cekkunjungan = mysqli_query($koneksi, "
        SELECT idkunjungan FROM kunjungan
        WHERE idpasien='$idpasien'
        LIMIT 1
    ");

    if (mysqli_num_rows($cekkunjungan) > 0) {
        header("Location: ../index.php?page=pasien&status=masihdigunakan");
        exit;
    }

    // Cek pendaftaran checkup
    $cekpendaftaran = mysqli_query($koneksi, "
        SELECT idpendaftaran FROM pendaftarancheckup
        WHERE idpasien='$idpasien'
        LIMIT 1
    ");

    if (mysqli_num_rows($cekpendaftaran) > 0) {
        header("Location: ../index.php?page=pasien&status=masihdigunakan");
        exit;
    }

    // Ambil foto sebelum data dihapus
    $data = mysqli_query($koneksi, "
        SELECT foto FROM pasien WHERE idpasien='$idpasien'
    ");

    $pasien = mysqli_fetch_assoc($data);

    if (!$pasien) {
        header("Location: ../index.php?page=pasien&status=datatidakada");
        exit;
    }

    $foto = $pasien['foto'];

    $query = mysqli_query($koneksi, "
        DELETE FROM pasien WHERE idpasien='$idpasien'
    ");

    if ($query) {

        if ($foto != "") {
            $filefoto = "../assets/images/pasien/" . $foto;

            if (file_exists($filefoto)) {
                unlink($filefoto);
            }
        }

        header("Location: ../index.php?page=pasien&status=hapusberhasil");
        exit;

    } else {
        header("Location: ../index.php?page=pasien&status=gagal");
        exit;
    }
}


// ========================================
// AKSI TIDAK DIKENAL
// ========================================
else {
    header("Location: ../index.php?page=pasien");
    exit;
}
