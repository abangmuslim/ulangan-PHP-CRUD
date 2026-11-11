
<?php
// ============================================
// 1. DATA SESSION ADMIN
// ============================================
$namaUser = $_SESSION['namauser'] ?? 'Administrator';
$fotoUser = $_SESSION['foto'] ?? 'default.png';
$idUser = (int)($_SESSION['iduser'] ?? 0);

// ============================================
// 2. DAFTAR HALAMAN AKTIF
// ============================================

// PENDAFTARAN CHECKUP
$halamanPendaftaran = [
    'pendaftarancheckup',
    'creatependaftarancheckup',
    'showpendaftarancheckup',
    'setujuipendaftarancheckup',
    'tolakpendaftarancheckup'
];

// ANTRIAN
$halamanAntrian = [
    'antrian',
    'showantrian',
    'panggilanantrian'
];

// KUNJUNGAN
$halamanKunjungan = [
    'kunjungan',
    'createkunjungan',
    'editkunjungan',
    'showkunjungan'
];

// DETAIL KUNJUNGAN
$halamanDetilKunjungan = [
    'detilkunjungan',
    'createdetilkunjungan',
    'editdetilkunjungan',
    'showdetilkunjungan'
];

// KASIR
$halamanKasir = [
    'kasir',
    'showkasir',
    'cetakstruk'
];
?>

<!-- ========================================
     MAIN SIDEBAR ADMIN
========================================= -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- BRAND -->
    <a href="index.php?page=dashboardadmin"
       class="brand-link py-2">

        <i class="fas fa-clinic-medical ml-2 mr-1"></i>

        <span class="brand-text font-weight-light"
              style="font-size:14px;">

            Klinik Ahmadi

        </span>

    </a>

    <div class="sidebar">

        <!-- ====================================
             PROFIL ADMIN
        ===================================== -->
        <div class="user-panel mt-2 pb-2 mb-2 d-flex align-items-center">

            <div class="image">

                <img src="assets/images/user/<?= e($fotoUser) ?>"
                     class="img-circle elevation-1"
                     alt="Foto Admin"
                     style="width:30px;height:30px;object-fit:cover;">

            </div>

            <div class="info py-0">

                <a href="index.php?page=showuser&id=<?= $idUser ?>"
                   class="d-block"
                   style="font-size:12px;">

                    <?= e($namaUser) ?>

                </a>

                <small class="text-light" style="font-size:10px;">
                    Administrator
                </small>

            </div>

        </div>

        <!-- ====================================
             NAVIGASI
        ===================================== -->
        <nav class="mt-1">

            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false"
                style="font-size:12px;">

                <!-- =================================
                     DASHBOARD
                ================================== -->
                <li class="nav-item">

                    <a href="index.php?page=dashboardadmin"
                       class="nav-link py-2 <?= menuAktif('dashboardadmin') ?>">

                        <i class="nav-icon fas fa-tachometer-alt"
                           style="font-size:13px;"></i>

                        <p>Dashboard</p>

                    </a>

                </li>

                <!-- =================================
                     MANAJEMEN DATA
                ================================== -->
                <li class="nav-header"
                    style="font-size:9px;padding:8px 8px 4px;">

                    MANAJEMEN DATA

                </li>

                <!-- USER -->
                <li class="nav-item">

                    <a href="index.php?page=user"
                       class="nav-link py-2 <?= menuAktif('user') ?>">

                        <i class="nav-icon fas fa-users-cog"
                           style="font-size:13px;"></i>

                        <p>Data User</p>

                    </a>

                </li>

                <!-- PASIEN -->
                <li class="nav-item">

                    <a href="index.php?page=pasien"
                       class="nav-link py-2 <?= menuAktif('pasien') ?>">

                        <i class="nav-icon fas fa-user-injured"
                           style="font-size:13px;"></i>

                        <p>Data Pasien</p>

                    </a>

                </li>

                <!-- DOKTER -->
                <li class="nav-item">

                    <a href="index.php?page=dokter"
                       class="nav-link py-2 <?= menuAktif('dokter') ?>">

                        <i class="nav-icon fas fa-user-md"
                           style="font-size:13px;"></i>

                        <p>Data Dokter</p>

                    </a>

                </li>

                <!-- MERK -->
                <li class="nav-item">

                    <a href="index.php?page=merk"
                       class="nav-link py-2 <?= menuAktif('merk') ?>">

                        <i class="nav-icon fas fa-copyright"
                           style="font-size:13px;"></i>

                        <p>Merk Obat</p>

                    </a>

                </li>

                <!-- KATEGORI -->
                <li class="nav-item">

                    <a href="index.php?page=kategori"
                       class="nav-link py-2 <?= menuAktif('kategori') ?>">

                        <i class="nav-icon fas fa-tags"
                           style="font-size:13px;"></i>

                        <p>Kategori Obat</p>

                    </a>

                </li>

                <!-- OBAT -->
                <li class="nav-item">

                    <a href="index.php?page=obat"
                       class="nav-link py-2 <?= menuAktif('obat') ?>">

                        <i class="nav-icon fas fa-pills"
                           style="font-size:13px;"></i>

                        <p>Data Obat</p>

                    </a>

                </li>

                <!-- JADWAL DOKTER -->
                <li class="nav-item">

                    <a href="index.php?page=jadwaldokter"
                       class="nav-link py-2 <?= menuAktif('jadwaldokter') ?>">

                        <i class="nav-icon fas fa-calendar-alt"
                           style="font-size:13px;"></i>

                        <p>Jadwal Dokter</p>

                    </a>

                </li>

                <!-- =================================
                     PELAYANAN
                ================================== -->
                <li class="nav-header"
                    style="font-size:9px;padding:8px 8px 4px;">

                    PELAYANAN

                </li>

                <!-- PENDAFTARAN CHECKUP -->
                <li class="nav-item">

                    <a href="index.php?page=pendaftarancheckup"
                       class="nav-link py-2 <?= menuAktif($halamanPendaftaran) ?>">

                        <i class="nav-icon fas fa-clipboard-check"
                           style="font-size:13px;"></i>

                        <p>Pendaftaran Checkup</p>

                    </a>

                </li>

                <!-- ANTRIAN -->
                <li class="nav-item">

                    <a href="index.php?page=antrian"
                       class="nav-link py-2 <?= menuAktif($halamanAntrian) ?>">

                        <i class="nav-icon fas fa-list-ol"
                           style="font-size:13px;"></i>

                        <p>Antrian Pasien</p>

                    </a>

                </li>

                <!-- KUNJUNGAN -->
                <li class="nav-item">

                    <a href="index.php?page=kunjungan"
                       class="nav-link py-2 <?= menuAktif($halamanKunjungan) ?>">

                        <i class="nav-icon fas fa-notes-medical"
                           style="font-size:13px;"></i>

                        <p>Kunjungan Pasien</p>

                    </a>

                </li>

                <!-- DETAIL KUNJUNGAN -->
                <li class="nav-item">

                    <a href="index.php?page=detilkunjungan"
                       class="nav-link py-2 <?= menuAktif($halamanDetilKunjungan) ?>">

                        <i class="nav-icon fas fa-file-medical"
                           style="font-size:13px;"></i>

                        <p>Detail Kunjungan</p>

                    </a>

                </li>

                <!-- KASIR -->
                <li class="nav-item">

                    <a href="index.php?page=kasir"
                       class="nav-link py-2 <?= menuAktif($halamanKasir) ?>">

                        <i class="nav-icon fas fa-cash-register"
                           style="font-size:13px;"></i>

                        <p>Kasir</p>

                    </a>

                </li>

                <!-- =================================
                     LAPORAN
                ================================== -->
                <li class="nav-header"
                    style="font-size:9px;padding:8px 8px 4px;">

                    LAPORAN

                </li>

                <!-- LAPORAN HARIAN -->
                <li class="nav-item">

                    <a href="index.php?page=laporanharian"
                       class="nav-link py-2 <?= menuAktif('laporanharian') ?>">

                        <i class="nav-icon fas fa-calendar-day"
                           style="font-size:13px;"></i>

                        <p>Laporan Harian</p>

                    </a>

                </li>

                <!-- LAPORAN BULANAN -->
                <li class="nav-item">

                    <a href="index.php?page=laporanbulanan"
                       class="nav-link py-2 <?= menuAktif('laporanbulanan') ?>">

                        <i class="nav-icon fas fa-calendar-alt"
                           style="font-size:13px;"></i>

                        <p>Laporan Bulanan</p>

                    </a>

                </li>

                <!-- LAPORAN TAHUNAN -->
                <li class="nav-item">

                    <a href="index.php?page=laporantahunan"
                       class="nav-link py-2 <?= menuAktif('laporantahunan') ?>">

                        <i class="nav-icon fas fa-chart-line"
                           style="font-size:13px;"></i>

                        <p>Laporan Tahunan</p>

                    </a>

                </li>

                <!-- LAPORAN OBAT -->
                <li class="nav-item">

                    <a href="index.php?page=laporanobat"
                       class="nav-link py-2 <?= menuAktif('laporanobat') ?>">

                        <i class="nav-icon fas fa-capsules"
                           style="font-size:13px;"></i>

                        <p>Laporan Obat</p>

                    </a>

                </li>

                <!-- LAPORAN ANTRIAN -->
                <li class="nav-item">

                    <a href="index.php?page=laporanantrian"
                       class="nav-link py-2 <?= menuAktif('laporanantrian') ?>">

                        <i class="nav-icon fas fa-clipboard-list"
                           style="font-size:13px;"></i>

                        <p>Laporan Antrian</p>

                    </a>

                </li>

                <!-- =================================
                     AKUN
                ================================== -->
                <li class="nav-header"
                    style="font-size:9px;padding:8px 8px 4px;">

                    AKUN

                </li>

                <!-- PROFIL -->
                <li class="nav-item">

                    <a href="index.php?page=showuser&id=<?= $idUser ?>"
                       class="nav-link py-2 <?= menuAktif('showuser') ?>">

                        <i class="nav-icon fas fa-user-circle"
                           style="font-size:13px;"></i>

                        <p>Profil Saya</p>

                    </a>

                </li>

                <!-- LOGOUT -->
                <li class="nav-item">

                    <form action="index.php?page=logout"
                          method="POST"
                          class="m-0">

                        <?= csrfField() ?>

                        <button type="submit"
                                class="nav-link py-2 text-danger border-0 bg-transparent w-100 text-left"
                                onclick="return confirm('Apakah Anda yakin ingin logout?')">

                            <i class="nav-icon fas fa-sign-out-alt"
                               style="font-size:13px;"></i>

                            <p class="d-inline">Logout</p>

                        </button>

                    </form>

                </li>

            </ul>

        </nav>

    </div>

</aside>
<!-- /.main-sidebar -->
