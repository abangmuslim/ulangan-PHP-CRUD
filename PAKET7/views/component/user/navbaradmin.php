
<?php
// ============================================
// 1. DATA SESSION ADMIN
// ============================================
$roleAktif = $_SESSION['level'] ?? 'admin';
$namaUser = $_SESSION['namauser'] ?? 'Administrator';
$fotoUser = $_SESSION['foto'] ?? 'default.png';
$idUser = (int)($_SESSION['iduser'] ?? 0);

// ============================================
// 2. DAFTAR HALAMAN DROPDOWN
// ============================================
$halamanAntrian = [
    'antrian',
    'showantrian',
    'panggilanantrian'
];

$halamanLaporan = [
    'laporanharian',
    'cetaklaporanharian',
    'laporanbulanan',
    'cetaklaporanbulanan',
    'laporantahunan',
    'cetaklaporantahunan',
    'laporanobat',
    'cetaklaporanobat',
    'laporanantrian',
    'cetaklaporanantrian'
];
?>

<!-- ========================================
     NAVBAR ADMIN KLINIK AHMADI
========================================= -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- MENU KIRI -->
    <ul class="navbar-nav">

        <!-- TOGGLE SIDEBAR -->
        <li class="nav-item">
            <a class="nav-link"
               data-widget="pushmenu"
               href="#"
               role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>

        <!-- BRAND -->
        <li class="nav-item d-none d-md-inline-block">
            <a href="index.php?page=dashboardadmin"
               class="nav-link <?= menuAktif('dashboardadmin') ?>">

                <i class="fas fa-clinic-medical mr-1"></i>
                Klinik Ahmadi

            </a>
        </li>

    </ul>

    <!-- ========================================
         MENU CEPAT
    ========================================= -->
    <ul class="navbar-nav">

        <!-- PASIEN -->
        <li class="nav-item d-none d-md-inline-block">
            <a href="index.php?page=pasien"
               class="nav-link <?= menuAktif('pasien') ?>">

                <i class="fas fa-user-injured mr-1"></i>
                Pasien

            </a>
        </li>

        <!-- KUNJUNGAN -->
        <li class="nav-item d-none d-md-inline-block">
            <a href="index.php?page=kunjungan"
               class="nav-link <?= menuAktif('kunjungan') ?>">

                <i class="fas fa-notes-medical mr-1"></i>
                Kunjungan

            </a>
        </li>

        <!-- DROPDOWN ANTRIAN -->
        <li class="nav-item dropdown d-none d-md-inline-block">

            <a href="#"
               class="nav-link dropdown-toggle <?= menuAktif($halamanAntrian) ?>"
               data-toggle="dropdown">

                <i class="fas fa-list-ol mr-1"></i>
                Antrian

            </a>

            <div class="dropdown-menu">

                <a href="index.php?page=antrian"
                   class="dropdown-item <?= menuAktif('antrian') ?>">

                    <i class="fas fa-clipboard-list mr-2"></i>
                    Daftar Antrian

                </a>

                <a href="index.php?page=panggilanantrian"
                   class="dropdown-item <?= menuAktif('panggilanantrian') ?>">

                    <i class="fas fa-bullhorn mr-2"></i>
                    Panggilan Antrian

                </a>

            </div>

        </li>

        <!-- DROPDOWN LAPORAN -->
        <li class="nav-item dropdown d-none d-md-inline-block">

            <a href="#"
               class="nav-link dropdown-toggle <?= menuAktif($halamanLaporan) ?>"
               data-toggle="dropdown">

                <i class="fas fa-file-medical-alt mr-1"></i>
                Laporan

            </a>

            <div class="dropdown-menu">

                <a href="index.php?page=laporanharian"
                   class="dropdown-item <?= menuAktif('laporanharian') ?>">

                    <i class="fas fa-calendar-day mr-2"></i>
                    Laporan Harian

                </a>

                <a href="index.php?page=laporanbulanan"
                   class="dropdown-item <?= menuAktif('laporanbulanan') ?>">

                    <i class="fas fa-calendar-alt mr-2"></i>
                    Laporan Bulanan

                </a>

                <a href="index.php?page=laporantahunan"
                   class="dropdown-item <?= menuAktif('laporantahunan') ?>">

                    <i class="fas fa-chart-line mr-2"></i>
                    Laporan Tahunan

                </a>

                <div class="dropdown-divider"></div>

                <a href="index.php?page=laporanobat"
                   class="dropdown-item <?= menuAktif('laporanobat') ?>">

                    <i class="fas fa-pills mr-2"></i>
                    Laporan Obat

                </a>

                <a href="index.php?page=laporanantrian"
                   class="dropdown-item <?= menuAktif('laporanantrian') ?>">

                    <i class="fas fa-clipboard-list mr-2"></i>
                    Laporan Antrian

                </a>

            </div>

        </li>

    </ul>

    <!-- ========================================
         USER MENU KANAN
    ========================================= -->
    <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown user-menu">

            <a href="#"
               class="nav-link dropdown-toggle"
               data-toggle="dropdown">

                <img src="assets/images/user/<?= e($fotoUser) ?>"
                     class="user-image img-circle elevation-2"
                     alt="Foto Admin">

                <span class="d-none d-md-inline">
                    <?= e($namaUser) ?>
                </span>

            </a>

            <!-- DROPDOWN PROFIL -->
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                <!-- HEADER USER -->
                <li class="user-header bg-primary">

                    <img src="assets/images/user/<?= e($fotoUser) ?>"
                         class="img-circle elevation-2"
                         alt="Foto Admin">

                    <p>
                        <?= e($namaUser) ?>

                        <small>
                            Administrator - Klinik Ahmadi
                        </small>
                    </p>

                </li>

                <!-- BODY USER -->
                <li class="user-body">

                    <div class="row">

                        <div class="col-12 text-center">

                            <a href="index.php?page=showuser&id=<?= $idUser ?>"
                               class="<?= menuAktif('showuser') ?>">

                                <i class="fas fa-user-circle mr-1"></i>
                                Profil Saya

                            </a>

                        </div>

                    </div>

                </li>

                <!-- FOOTER USER -->
                <li class="user-footer">

                    <span class="btn btn-default btn-flat">
                        <?= e(ucfirst($roleAktif)) ?>
                    </span>
                        <form action="index.php?page=logout" method="POST">
                            <?= csrfField(); ?>
                            <button type="submit"
                                    class="btn btn-danger btn-flat float-right"
                                    onclick="return confirm('Apakah Anda yakin ingin logout?')">
                                Logout
                            </button>
                        </form>
                </li>

            </ul>

        </li>

    </ul>

</nav>
<!-- /.navbar -->
