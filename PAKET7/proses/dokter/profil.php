
<?php
// Pastikan dokter sudah login
if (!isset($_SESSION['iddokter'])) {
    header("Location: index.php?page=logindokter");
    exit;
}

// Proses hanya melalui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php?page=editprofil");
    exit;
}

// Validasi CSRF
csrfCheck();

$iddokter = (int) $_SESSION['iddokter'];
$namadokter = trim($_POST['namadokter'] ?? '');
$spesialis = trim($_POST['spesialis'] ?? '');
$nohp = trim($_POST['nohp'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');

// Validasi data wajib
if ($namadokter == '' || $spesialis == '') {
    header("Location: index.php?page=editprofil&pesan=kosong");
    exit;
}

// Ambil foto lama
$queryFoto = mysqli_prepare($koneksi, "SELECT foto FROM dokter WHERE iddokter=?");
mysqli_stmt_bind_param($queryFoto, "i", $iddokter);
mysqli_stmt_execute($queryFoto);
$resultFoto = mysqli_stmt_get_result($queryFoto);
$dataFoto = mysqli_fetch_assoc($resultFoto);

if (!$dataFoto) {
    header("Location: index.php?page=profil&pesan=gagal");
    exit;
}

$foto = $dataFoto['foto'];

// Jika dokter mengunggah foto baru
if (isset($_FILES['foto']) && $_FILES['foto']['error'] != 4) {

    if ($_FILES['foto']['error'] != 0) {
        header("Location: index.php?page=editprofil&pesan=fotogagal");
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
        header("Location: index.php?page=editprofil&pesan=tipefoto");
        exit;
    }

    $foto = 'dokter_' . $iddokter . '_' . time() . '.' . $ekstensi;

    $folderFoto = __DIR__ . '/../../assets/images/dokter/';
    $tujuanFoto = $folderFoto . $foto;

    if (!move_uploaded_file($fileSementara, $tujuanFoto)) {
        header("Location: index.php?page=editprofil&pesan=fotogagal");
        exit;
    }
}

// Update profil dokter
$query = mysqli_prepare($koneksi, "
    UPDATE dokter SET
        namadokter=?,
        spesialis=?,
        nohp=?,
        alamat=?,
        foto=?
    WHERE iddokter=?
");

mysqli_stmt_bind_param(
    $query,
    "sssssi",
    $namadokter,
    $spesialis,
    $nohp,
    $alamat,
    $foto,
    $iddokter
);

if (mysqli_stmt_execute($query)) {

    $_SESSION['namadokter'] = $namadokter;
    $_SESSION['foto'] = $foto;

    header("Location: index.php?page=profil&pesan=sukses");
    exit;

} else {
    header("Location: index.php?page=editprofil&pesan=gagal");
    exit;
}
?>
