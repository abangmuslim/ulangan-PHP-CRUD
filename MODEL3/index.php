<?php
include "koneksi.php";
$page = $_GET['page'] ?? 'home';
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

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">Rumah Sakit Ahmadi</a>
        <div class="navbar-nav">
            <a class="nav-link" href="index.php">Beranda</a>
            <a class="nav-link" href="index.php?page=pasien">Pasien</a>
            <a class="nav-link" href="index.php?page=kunjungan">Kunjungan</a>
        </div>
    </div>
</nav>

<div class="container py-4">
    <?php
    switch ($page) {
        case 'pasien':
            include 'views/pasien/index.php';
            break;
        case 'createpasien':
            include 'views/pasien/create.php';
            break;
        case 'editpasien':
            include 'views/pasien/edit.php';
            break;
        case 'showpasien':
            include 'views/pasien/show.php';
            break;

        case 'kunjungan':
            include 'views/kunjungan/index.php';
            break;
        case 'createkunjungan':
            include 'views/kunjungan/create.php';
            break;
        case 'editkunjungan':
            include 'views/kunjungan/edit.php';
            break;
        case 'showkunjungan':
            include 'views/kunjungan/show.php';
            break;

        default:
    ?>
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <h2>Selamat Datang</h2>
                <p>Aplikasi Pendataan Pasien dan Kunjungan Rumah Sakit Ahmadi</p>
                <a href="index.php?page=pasien" class="btn btn-primary">Data Pasien</a>
                <a href="index.php?page=kunjungan" class="btn btn-success">Data Kunjungan</a>
            </div>
        </div>
    <?php } ?>
</div>

</body>
</html>