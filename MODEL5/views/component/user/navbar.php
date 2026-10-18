<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Tombol Sidebar -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-md-inline-block">
            <a href="index.php?page=dashboard<?= ($_SESSION['role'] ?? '') == 'admin' ? 'admin' : 'petugas'; ?>" class="nav-link">
                <i class="fas fa-hospital mr-1"></i>
                Rumah Sakit Ahmadi
            </a>
        </li>
    </ul>

    <!-- Menu Cepat -->
    <ul class="navbar-nav">
        <li class="nav-item d-none d-md-inline-block">
            <a href="index.php?page=pasien" class="nav-link">
                <i class="fas fa-user-injured mr-1"></i>
                Pasien
            </a>
        </li>
        <li class="nav-item d-none d-md-inline-block">
            <a href="index.php?page=kunjungan" class="nav-link">
                <i class="fas fa-notes-medical mr-1"></i>
                Kunjungan
            </a>
        </li>
        <li class="nav-item d-none d-md-inline-block">
            <a href="index.php?page=antrian" class="nav-link">
                <i class="fas fa-list-ol mr-1"></i>
                Antrian
            </a>
        </li>
        <li class="nav-item dropdown d-none d-md-inline-block">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                <i class="fas fa-file-medical-alt mr-1"></i>
                Laporan
            </a>
            <div class="dropdown-menu">
                <a href="index.php?page=laporanharian" class="dropdown-item">
                    <i class="fas fa-calendar-day mr-2"></i>
                    Laporan Harian
                </a>
                <a href="index.php?page=laporanbulanan" class="dropdown-item">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Laporan Bulanan
                </a>
                <a href="index.php?page=laporantahunan" class="dropdown-item">
                    <i class="fas fa-chart-line mr-2"></i>
                    Laporan Tahunan
                </a>
                <div class="dropdown-divider"></div>
                <a href="index.php?page=laporanobat" class="dropdown-item">
                    <i class="fas fa-pills mr-2"></i>
                    Laporan Obat
                </a>
                <a href="index.php?page=laporanantrian" class="dropdown-item">
                    <i class="fas fa-clipboard-list mr-2"></i>
                    Laporan Antrian
                </a>
            </div>
        </li>
    </ul>

    <!-- User Menu -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <img
                    src="assets/images/user/<?= htmlspecialchars($_SESSION['foto'] ?? 'default.png'); ?>"
                    class="user-image img-circle elevation-2"
                    alt="User">
                <span class="d-none d-md-inline">
                    <?= htmlspecialchars($_SESSION['namauser'] ?? 'User'); ?>
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <li class="user-header bg-primary">
                    <img
                        src="assets/images/user/<?= htmlspecialchars($_SESSION['foto'] ?? 'default.png'); ?>"
                        class="img-circle elevation-2"
                        alt="User">
                    <p>
                        <?= htmlspecialchars($_SESSION['namauser'] ?? 'User'); ?>
                        <small>
                            <?= ucfirst(htmlspecialchars($_SESSION['role'] ?? 'User')); ?>
                            - Rumah Sakit Ahmadi
                        </small>
                    </p>
                </li>
                <li class="user-body">
                    <div class="row">
                        <div class="col-12 text-center">
                            <a href="index.php?page=showuser&id=<?= (int)($_SESSION['iduser'] ?? 0); ?>">
                                <i class="fas fa-user-circle mr-1"></i>
                                Profil Saya
                            </a>
                        </div>
                    </div>
                </li>
                <li class="user-footer">
                    <span class="btn btn-default btn-flat">
                        <?= ucfirst(htmlspecialchars($_SESSION['role'] ?? 'User')); ?>
                    </span>
                    <a href="index.php?page=logout" class="btn btn-danger btn-flat float-right"
                        onclick="return confirm('Apakah Anda yakin ingin logout?');">
                        Logout
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<!-- /.navbar -->