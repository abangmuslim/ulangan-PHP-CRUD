<?php
session_start();
include "proses/koneksi.php";

$page = $_GET['page'] ?? 'home';

$halamanPublik = ['login'];

if (!isset($_SESSION['iduser']) && !in_array($page, $halamanPublik)) {
    header("location:index.php?page=login");
    exit;
}

if (isset($_SESSION['iduser']) && $page == 'login') {
    header("location:index.php?page=home");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rumah Sakit Ahmadi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php if (isset($_SESSION['iduser'])) { ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php?page=home">Rumah Sakit Ahmadi</a>
        <div class="navbar-nav">
            <a class="nav-link" href="index.php?page=home">Beranda</a>
            <a class="nav-link" href="index.php?page=user">User</a>
            <a class="nav-link" href="index.php?page=pasien">Pasien</a>
            <a class="nav-link" href="index.php?page=dokter">Dokter</a>
            <a class="nav-link" href="index.php?page=obat">Obat</a>
            <a class="nav-link" href="index.php?page=kunjungan">Kunjungan</a>
            <a class="nav-link" href="proses/logout.php">Logout</a>
        </div>
    </div>
</nav>
<?php } ?>

<div class="container py-4">
<?php
switch ($page) {
    case 'login':
        include 'views/Auth/login.php';
        break;

    case 'home':
        echo '<div class="card"><div class="card-body">';
        echo '<h3>Selamat Datang</h3>';
        echo '<p>Aplikasi Rumah Sakit Ahmadi</p>';
        echo '</div></div>';
        break;

    case 'user':
        include 'views/User/index.php';
        break;
    case 'createuser':
        include 'views/User/create.php';
        break;
    case 'edituser':
        include 'views/User/edit.php';
        break;
    case 'showuser':
        include 'views/User/show.php';
        break;

    case 'pasien':
        include 'views/Pasien/index.php';
        break;
    case 'createpasien':
        include 'views/Pasien/create.php';
        break;
    case 'editpasien':
        include 'views/Pasien/edit.php';
        break;
    case 'showpasien':
        include 'views/Pasien/show.php';
        break;

    case 'dokter':
        include 'views/Dokter/index.php';
        break;
    case 'createdokter':
        include 'views/Dokter/create.php';
        break;
    case 'editdokter':
        include 'views/Dokter/edit.php';
        break;
    case 'showdokter':
        include 'views/Dokter/show.php';
        break;

    case 'obat':
        include 'views/Obat/index.php';
        break;
    case 'createobat':
        include 'views/Obat/create.php';
        break;
    case 'editobat':
        include 'views/Obat/edit.php';
        break;
    case 'showobat':
        include 'views/Obat/show.php';
        break;

    case 'kunjungan':
        include 'views/Kunjungan/index.php';
        break;
    case 'createkunjungan':
        include 'views/Kunjungan/create.php';
        break;
    case 'editkunjungan':
        include 'views/Kunjungan/edit.php';
        break;
    case 'showkunjungan':
        include 'views/Kunjungan/show.php';
        break;

    default:
        echo '<div class="alert alert-warning">Halaman tidak ditemukan.</div>';
        break;
}
?>
</div>
</body>
</html>