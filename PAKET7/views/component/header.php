
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Klinik Ahmadi</title>

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">

    <!-- ADMINLTE 3.2 -->
    <link rel="stylesheet" href="assets/css/adminlte.min.css">

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <?php
    // MENENTUKAN BODY CLASS SESUAI ZONA
    if ($zona === 'public' || $zona === 'auth') {
        $bodyClass = 'hold-transition layout-top-nav';
    } else {
        $bodyClass = 'hold-transition sidebar-mini layout-fixed';
    }
    ?>
</head>

<body class="<?= $bodyClass; ?>">

<div class="wrapper">
