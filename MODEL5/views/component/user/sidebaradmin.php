<!-- Main Sidebar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="index.php?page=dashboardadmin" class="brand-link py-2">
        <i class="fas fa-hospital ml-2 mr-1"></i>
        <span class="brand-text font-weight-light" style="font-size:14px;">
            Rumah Sakit Ahmadi
        </span>
    </a>

    <div class="sidebar">

        <!-- Profil Admin -->
        <div class="user-panel mt-2 pb-2 mb-2 d-flex align-items-center">
            <div class="image">
                <img
                    src="assets/images/user/<?= htmlspecialchars($_SESSION['foto'] ?? 'default.png'); ?>"
                    class="img-circle elevation-1"
                    alt="Foto Admin"
                    style="width:30px;height:30px;object-fit:cover;">
            </div>
            <div class="info py-0">
                <a href="index.php?page=showuser&id=<?= (int)($_SESSION['iduser'] ?? 0); ?>"
                   class="d-block"
                   style="font-size:12px;">
                    <?= htmlspecialchars($_SESSION['namauser'] ?? 'Administrator'); ?>
                </a>
                <small class="text-light" style="font-size:10px;">
                    <?= ucfirst(htmlspecialchars($_SESSION['role'] ?? 'admin')); ?>
                </small>
            </div>
        </div>

        <!-- Navigasi -->
        <nav class="mt-1">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false"
                style="font-size:12px;">

                <li class="nav-item">
                    <a href="index.php?page=dashboardadmin"
                       class="nav-link py-2 <?= menuAktif('dashboardadmin'); ?>">
                        <i class="nav-icon fas fa-tachometer-alt" style="font-size:13px;"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header" style="font-size:9px;padding:8px 8px 4px;">
                    MANAJEMEN DATA
                </li>

                <li class="nav-item">
                    <a href="index.php?page=user"
                       class="nav-link py-2 <?= menuAktif('user'); ?>">
                        <i class="nav-icon fas fa-users-cog" style="font-size:13px;"></i>
                        <p>Data User</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=pasien"
                       class="nav-link py-2 <?= menuAktif('pasien'); ?>">
                        <i class="nav-icon fas fa-user-injured" style="font-size:13px;"></i>
                        <p>Data Pasien</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=dokter"
                       class="nav-link py-2 <?= menuAktif('dokter'); ?>">
                        <i class="nav-icon fas fa-user-md" style="font-size:13px;"></i>
                        <p>Data Dokter</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=merk"
                       class="nav-link py-2 <?= menuAktif('merk'); ?>">
                        <i class="nav-icon fas fa-copyright" style="font-size:13px;"></i>
                        <p>Merk Obat</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=kategori"
                       class="nav-link py-2 <?= menuAktif('kategori'); ?>">
                        <i class="nav-icon fas fa-tags" style="font-size:13px;"></i>
                        <p>Kategori Obat</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=obat"
                       class="nav-link py-2 <?= menuAktif('obat'); ?>">
                        <i class="nav-icon fas fa-pills" style="font-size:13px;"></i>
                        <p>Data Obat</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=jadwaldokter"
                       class="nav-link py-2 <?= menuAktif('jadwaldokter'); ?>">
                        <i class="nav-icon fas fa-calendar-alt" style="font-size:13px;"></i>
                        <p>Jadwal Dokter</p>
                    </a>
                </li>

                <li class="nav-header" style="font-size:9px;padding:8px 8px 4px;">
                    PELAYANAN
                </li>

                <li class="nav-item">
                    <a href="index.php?page=kunjungan"
                       class="nav-link py-2 <?= menuAktif('kunjungan'); ?>">
                        <i class="nav-icon fas fa-notes-medical" style="font-size:13px;"></i>
                        <p>Kunjungan Pasien</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=detilkunjungan"
                       class="nav-link py-2 <?= menuAktif('detilkunjungan'); ?>">
                        <i class="nav-icon fas fa-file-medical" style="font-size:13px;"></i>
                        <p>Detail Kunjungan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=antrian"
                       class="nav-link py-2 <?= menuAktif('antrian'); ?>">
                        <i class="nav-icon fas fa-list-ol" style="font-size:13px;"></i>
                        <p>Antrian Pasien</p>
                    </a>
                </li>

                <li class="nav-header" style="font-size:9px;padding:8px 8px 4px;">
                    LAPORAN
                </li>

                <li class="nav-item">
                    <a href="index.php?page=laporanharian"
                       class="nav-link py-2 <?= menuAktif('laporanharian'); ?>">
                        <i class="nav-icon fas fa-calendar-day" style="font-size:13px;"></i>
                        <p>Laporan Harian</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=laporanbulanan"
                       class="nav-link py-2 <?= menuAktif('laporanbulanan'); ?>">
                        <i class="nav-icon fas fa-calendar-alt" style="font-size:13px;"></i>
                        <p>Laporan Bulanan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=laporantahunan"
                       class="nav-link py-2 <?= menuAktif('laporantahunan'); ?>">
                        <i class="nav-icon fas fa-chart-line" style="font-size:13px;"></i>
                        <p>Laporan Tahunan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=laporanobat"
                       class="nav-link py-2 <?= menuAktif('laporanobat'); ?>">
                        <i class="nav-icon fas fa-capsules" style="font-size:13px;"></i>
                        <p>Laporan Obat</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=laporanantrian"
                       class="nav-link py-2 <?= menuAktif('laporanantrian'); ?>">
                        <i class="nav-icon fas fa-clipboard-list" style="font-size:13px;"></i>
                        <p>Laporan Antrian</p>
                    </a>
                </li>

                <li class="nav-header" style="font-size:9px;padding:8px 8px 4px;">
                    AKUN
                </li>

                <li class="nav-item">
                    <a href="index.php?page=logout" class="nav-link py-2 text-danger">
                        <i class="nav-icon fas fa-sign-out-alt" style="font-size:13px;"></i>
                        <p>Logout</p>
                    </a>
                </li>

            </ul>
        </nav>

    </div>
</aside>
<!-- /.main-sidebar -->