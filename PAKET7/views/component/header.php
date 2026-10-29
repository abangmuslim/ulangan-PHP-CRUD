<?php
if (!isset($judul)) {
    $judul = 'Klinik';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#087f5b">
    <title><?= e($judul) ?> | Klinik</title>

    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/css/adminlte.min.css">
    <link rel="stylesheet" href="assets/css/style.css">



</head>

<?php
$classBody = 'hold-transition';

if (isset($zona) && $zona === 'tamu') {
    $classBody .= ' layout-top-nav';
}
?>
<body class="<?= e($classBody) ?>">