
<?php
// Mengambil halaman yang sedang dibuka.
$page = $page ?? ($_GET['page'] ?? 'home');
?>

<nav class="navbar navbar-expand-lg navbar-dark navbar-tamu">
    <div class="container">

        <a class="navbar-brand fw-bold"
           href="index.php?page=home">
            Rumah Sakit Ahmadi
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#menuTamu"
                aria-controls="menuTamu"
                aria-expanded="false"
                aria-label="Buka navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuTamu">

            <ul class="navbar-nav ms-auto">

                <!-- BERANDA -->
                <li class="nav-item">
                    <a class="nav-link <?= ($page == 'home') ? 'active' : '' ?>"
                       href="index.php?page=home"
                       <?= ($page == 'home') ? 'aria-current="page"' : '' ?>>
                        Beranda
                    </a>
                </li>

                <!-- DOKTER -->
                <li class="nav-item">
                    <a class="nav-link <?= in_array($page, ['daftardokter', 'detildokter']) ? 'active' : '' ?>"
                       href="index.php?page=daftardokter"
                       <?= in_array($page, ['daftardokter', 'detildokter']) ? 'aria-current="page"' : '' ?>>
                        Dokter
                    </a>
                </li>

                <!-- OBAT -->
                <li class="nav-item">
                    <a class="nav-link <?= in_array($page, ['daftarobat', 'detilobat', 'daftarkategori', 'detilkategori']) ? 'active' : '' ?>"
                       href="index.php?page=daftarobat"
                       <?= in_array($page, ['daftarobat', 'detilobat', 'daftarkategori', 'detilkategori']) ? 'aria-current="page"' : '' ?>>
                        Obat
                    </a>
                </li>

                <!-- TENTANG -->
                <li class="nav-item">
                    <a class="nav-link <?= ($page == 'tentang') ? 'active' : '' ?>"
                       href="index.php?page=tentang"
                       <?= ($page == 'tentang') ? 'aria-current="page"' : '' ?>>
                        Tentang
                    </a>
                </li>

                <!-- KONTAK -->
                <li class="nav-item">
                    <a class="nav-link <?= ($page == 'kontak') ? 'active' : '' ?>"
                       href="index.php?page=kontak"
                       <?= ($page == 'kontak') ? 'aria-current="page"' : '' ?>>
                        Kontak
                    </a>
                </li>

                <!-- LOGIN PASIEN -->
                <li class="nav-item">
                    <a class="nav-link <?= ($page == 'loginpasien') ? 'active' : '' ?>"
                       href="index.php?page=loginpasien">
                        Login Pasien
                    </a>
                </li>

                <!-- LOGIN ADMIN -->
                <li class="nav-item">
                    <a class="nav-link <?= ($page == 'loginuser') ? 'active' : '' ?>"
                       href="index.php?page=loginuser">
                        Login Admin
                    </a>
                </li>

            </ul>

        </div>
    </div>
</nav>
