
<?php
$namaPasien = $_SESSION['namapasien'] ?? 'Pasien';
$fotoPasien = $_SESSION['foto'] ?? 'default.png';
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- BRAND LOGO -->
    <a href="index.php?page=dashboardpasien" class="brand-link">
        <img src="assets/images/logo.png"
             alt="Logo Klinik"
             class="brand-image img-circle elevation-3"
             style="opacity:.8">

        <span class="brand-text font-weight-light">
            Klinik Ahmadi
        </span>
    </a>

    <!-- SIDEBAR -->
    <div class="sidebar">

        <!-- IDENTITAS PASIEN -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">

            <div class="image">
                <img src="assets/images/pasien/<?= e($fotoPasien) ?>"
                     class="img-circle elevation-2"
                     alt="Foto Pasien"
                     style="width:35px;height:35px;object-fit:cover;">
            </div>

            <div class="info">
                <a href="index.php?page=profilpasien" class="d-block">
                    <?= e($namaPasien) ?>
                </a>

                <small class="text-success">
                    <i class="fas fa-circle"></i> Pasien
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
                    <a href="index.php?page=dashboardpasien"
                       class="nav-link <?= menuAktif('dashboardpasien') ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- AKUN SAYA -->
                <li class="nav-header">AKUN SAYA</li>

                <li class="nav-item">
                    <a href="index.php?page=profilpasien"
                       class="nav-link <?= menuAktif(['profilpasien','editprofilpasien']) ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profil Saya</p>
                    </a>
                </li>

                <!-- LAYANAN KLINIK -->
                <li class="nav-header">LAYANAN KLINIK</li>

                <!-- PENDAFTARAN CHECKUP -->
                <li class="nav-item has-treeview <?= in_array($page, ['pendaftaranpasien','creatependaftaranpasien','showpendaftaranpasien']) ? 'menu-open' : '' ?>">

                    <a href="#"
                       class="nav-link <?= menuAktif(['pendaftaranpasien','creatependaftaranpasien','showpendaftaranpasien']) ?>">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>
                            Pendaftaran Checkup
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="index.php?page=pendaftaranpasien"
                               class="nav-link <?= menuAktif('pendaftaranpasien') ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Pendaftaran</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="index.php?page=creatependaftaranpasien"
                               class="nav-link <?= menuAktif('creatependaftaranpasien') ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Checkup</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- ANTRIAN -->
                <li class="nav-item">
                    <a href="index.php?page=antrianpasien"
                       class="nav-link <?= menuAktif(['antrianpasien','showantrianpasien']) ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Antrian Saya</p>
                    </a>
                </li>

                <!-- RIWAYAT KUNJUNGAN -->
                <li class="nav-item">
                    <a href="index.php?page=riwayatkunjungan"
                       class="nav-link <?= menuAktif(['riwayatkunjungan','detilkunjunganpasien']) ?>">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Riwayat Kunjungan</p>
                    </a>
                </li>

                <!-- INFORMASI KLINIK -->
                <li class="nav-header">INFORMASI KLINIK</li>

                <li class="nav-item">
                    <a href="index.php?page=daftardokterpasien"
                       class="nav-link <?= menuAktif('daftardokterpasien') ?>">
                        <i class="nav-icon fas fa-user-md"></i>
                        <p>Daftar Dokter</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=informasiobat"
                       class="nav-link <?= menuAktif('informasiobat') ?>">
                        <i class="nav-icon fas fa-pills"></i>
                        <p>Informasi Obat</p>
                    </a>
                </li>

                <!-- AKUN -->
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
