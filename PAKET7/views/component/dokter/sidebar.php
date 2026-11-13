
<?php
$namaDokter = $_SESSION['namadokter'] ?? 'Dokter';
$usernameDokter = $_SESSION['username'] ?? '';
$fotoDokter = $_SESSION['foto'] ?? 'default.png';
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- BRAND LOGO -->
    <a href="index.php?page=dashboarddokter" class="brand-link">
        <img src="assets/images/logo.png"
             alt="Logo Klinik"
             class="brand-image img-circle elevation-3"
             style="opacity:.8">
        <span class="brand-text font-weight-light">Klinik Ahmadi</span>
    </a>

    <!-- SIDEBAR -->
    <div class="sidebar">

        <!-- IDENTITAS DOKTER -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="assets/images/dokter/<?= e($fotoDokter) ?>"
                     class="img-circle elevation-2"
                     alt="Foto Dokter"
                     style="width:35px;height:35px;object-fit:cover;">
            </div>

            <div class="info">
                <a href="index.php?page=profil" class="d-block">
                    <?= e($namaDokter) ?>
                </a>
                <small class="text-success">
                    <i class="fas fa-circle"></i> Dokter
                </small>
            </div>
        </div>

        <!-- MENU NAVIGASI -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false">

                <!-- DASHBOARD -->
                <li class="nav-item">
                    <a href="index.php?page=dashboarddokter"
                       class="nav-link <?= menuAktif('dashboarddokter') ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- AKUN SAYA -->
                <li class="nav-header">AKUN SAYA</li>

                <li class="nav-item">
                    <a href="index.php?page=profil"
                       class="nav-link <?= menuAktif(['profil','editprofil']) ?>">
                        <i class="nav-icon fas fa-user-md"></i>
                        <p>Profil Dokter</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="index.php?page=jadwaldokterdokter"
                    class="nav-link <?= menuAktif([
                        'jadwaldokterdokter',
                        'createjadwaldokterdokter',
                        'editjadwaldokterdokter',
                        'showjadwaldokterdokter'
                    ]) ?>">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Jadwal Dokter</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=jadwalkunjungan"
                       class="nav-link <?= menuAktif('jadwalkunjungan') ?>">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Jadwal Kunjungan</p>
                    </a>
                </li>

                <!-- PELAYANAN PASIEN -->
                <li class="nav-header">PELAYANAN PASIEN</li>

                <!-- ANTRIAN -->
                <li class="nav-item has-treeview <?= in_array($page, ['antriandokter','showantriandokter']) ? 'menu-open' : '' ?>">

                    <a href="#"
                       class="nav-link <?= menuAktif(['antriandokter','showantriandokter']) ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Antrian Pasien
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index.php?page=antriandokter"
                               class="nav-link <?= menuAktif('antriandokter') ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Antrian</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- PEMERIKSAAN -->
                <li class="nav-item has-treeview <?= in_array($page, ['pemeriksaan','createpemeriksaan','showpemeriksaan']) ? 'menu-open' : '' ?>">

                    <a href="#"
                       class="nav-link <?= menuAktif(['pemeriksaan','createpemeriksaan','showpemeriksaan']) ?>">
                        <i class="nav-icon fas fa-stethoscope"></i>
                        <p>
                            Pemeriksaan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index.php?page=pemeriksaan"
                               class="nav-link <?= menuAktif('pemeriksaan') ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Pemeriksaan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- RESEP OBAT -->
                <li class="nav-item has-treeview <?= in_array($page, ['resep','createresep','editresep']) ? 'menu-open' : '' ?>">

                    <a href="#"
                       class="nav-link <?= menuAktif(['resep','createresep','editresep']) ?>">
                        <i class="nav-icon fas fa-prescription"></i>
                        <p>
                            Resep Obat
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index.php?page=resep"
                               class="nav-link <?= menuAktif('resep') ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Resep</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- LOGOUT -->
                <li class="nav-header">AKUN</li>

                <li class="nav-item">
                    <form action="index.php?page=logout" method="POST">
                        <?= csrfField() ?>
                        <input type="hidden" name="page" value="logout">

                        <button type="submit"
                                class="nav-link btn btn-link text-left w-100">
                            <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                            <p>Logout</p>
                        </button>
                    </form>
                </li>

            </ul>
        </nav>
    </div>
</aside>
