<?php
// Pastikan pasien sudah login
if (!isset($_SESSION['idpasien'])) {
    header("Location: index.php?page=loginpasien");
    exit;
}

// Proses hanya melalui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php?page=editprofilpasien");
    exit;
}

// Validasi CSRF
csrfCheck();

$idpasien = (int) $_SESSION['idpasien'];
$namapasien = trim($_POST['namapasien'] ?? '');
$nohp = trim($_POST['nohp'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');

// Validasi data wajib
if ($namapasien == '') {
    header("Location: index.php?page=editprofilpasien&pesan=kosong");
    exit;
}

// Ambil foto lama
$queryFoto = mysqli_prepare($koneksi, "
    SELECT foto
    FROM pasien
    WHERE idpasien = ?
");

mysqli_stmt_bind_param($queryFoto, "i", $idpasien);
mysqli_stmt_execute($queryFoto);
$resultFoto = mysqli_stmt_get_result($queryFoto);
$dataFoto = mysqli_fetch_assoc($resultFoto);

if (!$dataFoto) {
    header("Location: index.php?page=profilpasien&pesan=gagal");
    exit;
}

$foto = $dataFoto['foto'];

// Jika pasien mengunggah foto baru
if (isset($_FILES['foto']) && $_FILES['foto']['error'] != 4) {

    if ($_FILES['foto']['error'] != 0) {
        header("Location: index.php?page=editprofilpasien&pesan=fotogagal");
        exit;
    }

    $namaFile = $_FILES['foto']['name'];
    $ekstensi = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

    $ekstensiDiizinkan = ['jpg', 'jpeg', 'png', 'webp'];
    $tipeDiizinkan = ['image/jpeg', 'image/png', 'image/webp'];

    $fileSementara = $_FILES['foto']['tmp_name'];

    $infoFile = finfo_open(FILEINFO_MIME_TYPE);
    $mimeFile = finfo_file($infoFile, $fileSementara);
    finfo_close($infoFile);

    if (!in_array($ekstensi, $ekstensiDiizinkan) || !in_array($mimeFile, $tipeDiizinkan)) {
        header("Location: index.php?page=editprofilpasien&pesan=tipefoto");
        exit;
    }

    $foto = 'pasien_' . $idpasien . '_' . time() . '.' . $ekstensi;

    $folderFoto = __DIR__ . '/../../assets/images/pasien/';
    $tujuanFoto = $folderFoto . $foto;

    if (!is_dir($folderFoto) || !move_uploaded_file($fileSementara, $tujuanFoto)) {
        header("Location: index.php?page=editprofilpasien&pesan=fotogagal");
        exit;
    }
}

// Update profil pasien
$query = mysqli_prepare($koneksi, "
    UPDATE pasien SET
        namapasien = ?,
        nohp = ?,
        alamat = ?,
        foto = ?
    WHERE idpasien = ?
");

mysqli_stmt_bind_param(
    $query,
    "ssssi",
    $namapasien,
    $nohp,
    $alamat,
    $foto,
    $idpasien
);

if (mysqli_stmt_execute($query)) {

    // Perbarui session pasien
    $_SESSION['namapasien'] = $namapasien;
    $_SESSION['foto'] = $foto;

    header("Location: index.php?page=profilpasien&pesan=sukses");
    exit;

} else {
    header("Location: index.php?page=editprofilpasien&pesan=gagal");
    exit;
}
?>