
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Aplikasi Rumah Sakit Ahmadi">
    <meta name="author" content="Ahmadi Muslim, M.P">
    <title><?= htmlspecialchars($judul ?? 'Rumah Sakit Ahmadi'); ?></title>
    <link rel="icon" type="image/png" href="assets/images/logo.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/css/adminlte.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @media (min-width: 1200px) {
            .container {
                max-width: 1360px !important;
                width: 90% !important;
            }
        }
    </style>
</head>
<body class="<?= htmlspecialchars($bodyClass ?? 'hold-transition layout-top-nav') ?>">
<div class="wrapper">
