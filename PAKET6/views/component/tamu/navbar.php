
<?php
// Mengambil halaman yang sedang dibuka.
$page = $page ?? ($_GET['page'] ?? 'home');
?>

<nav class="navbar navbar-expand-lg navbar-dark navbar-tamu">

    <div class="container">

        <!-- ========================================
             BRAND
        ========================================= -->

        <a class="navbar-brand fw-bold"
           href="index.php?page=home">

            <i class="bi bi-hospital me-2"></i>
            Rumah Sakit Ahmadi

        </a>


        <!-- ========================================
             TOGGLER MOBILE
        ========================================= -->

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#menuTamu"
                aria-controls="menuTamu"
                aria-expanded="false"
                aria-label="Buka navigasi">

            <span class="navbar-toggler-icon"></span>

        </button>


        <!-- ========================================
             MENU NAVBAR
        ========================================= -->

        <div class="collapse navbar-collapse" id="menuTamu">

            <ul class="navbar-nav ms-auto">


                <!-- ====================================
                     BERANDA
                ===================================== -->

                <li class="nav-item">

                    <a class="nav-link <?= ($page == 'home') ? 'active' : '' ?>"
                       href="index.php?page=home"
                       <?= ($page == 'home') ? 'aria-current="page"' : '' ?>>

                        <i class="bi bi-house-door me-1"></i>
                        Beranda

                    </a>

                </li>


                <!-- ====================================
                     DOKTER
                ===================================== -->

                <li class="nav-item">

                    <a class="nav-link <?= in_array($page, ['daftardokter', 'detildokter']) ? 'active' : '' ?>"
                       href="index.php?page=daftardokter"
                       <?= in_array($page, ['daftardokter', 'detildokter']) ? 'aria-current="page"' : '' ?>>

                        <i class="bi bi-person-badge me-1"></i>
                        Dokter

                    </a>

                </li>


                <!-- ====================================
                     OBAT
                ===================================== -->

                <li class="nav-item">

                    <a class="nav-link <?= in_array($page, ['daftarobat', 'detilobat']) ? 'active' : '' ?>"
                       href="index.php?page=daftarobat"
                       <?= in_array($page, ['daftarobat', 'detilobat']) ? 'aria-current="page"' : '' ?>>

                        <i class="bi bi-capsule me-1"></i>
                        Obat

                    </a>

                </li>


                <!-- ====================================
                     KATEGORI OBAT
                ===================================== -->

                <li class="nav-item">

                    <a class="nav-link <?= in_array($page, ['daftarkategori', 'detilkategori']) ? 'active' : '' ?>"
                       href="index.php?page=daftarkategori"
                       <?= in_array($page, ['daftarkategori', 'detilkategori']) ? 'aria-current="page"' : '' ?>>

                        <i class="bi bi-grid me-1"></i>
                        Kategori

                    </a>

                </li>


                <!-- ====================================
                     LAYANAN
                ===================================== -->

                <li class="nav-item">

                    <a class="nav-link <?= ($page == 'layanan') ? 'active' : '' ?>"
                       href="index.php?page=layanan"
                       <?= ($page == 'layanan') ? 'aria-current="page"' : '' ?>>

                        <i class="bi bi-heart-pulse me-1"></i>
                        Layanan

                    </a>

                </li>


                <!-- ====================================
                     TENTANG
                ===================================== -->

                <li class="nav-item">

                    <a class="nav-link <?= ($page == 'tentang') ? 'active' : '' ?>"
                       href="index.php?page=tentang"
                       <?= ($page == 'tentang') ? 'aria-current="page"' : '' ?>>

                        <i class="bi bi-info-circle me-1"></i>
                        Tentang

                    </a>

                </li>


                <!-- ====================================
                     KONTAK
                ===================================== -->

                <li class="nav-item">

                    <a class="nav-link <?= ($page == 'kontak') ? 'active' : '' ?>"
                       href="index.php?page=kontak"
                       <?= ($page == 'kontak') ? 'aria-current="page"' : '' ?>>

                        <i class="bi bi-envelope me-1"></i>
                        Kontak

                    </a>

                </li>              


                <!-- ====================================
                     LOGIN PASIEN
                ===================================== -->

                <li class="nav-item">

                    <a class="nav-link <?= ($page == 'loginpasien') ? 'active' : '' ?>"
                       href="index.php?page=loginpasien"
                       <?= ($page == 'loginpasien') ? 'aria-current="page"' : '' ?>>

                        <i class="bi bi-person-circle me-1"></i>
                        Login Pasien

                    </a>

                </li>


                <!-- ====================================
                     LOGIN ADMIN
                ===================================== -->

                <li class="nav-item">

                    <a class="nav-link <?= ($page == 'loginuser') ? 'active' : '' ?>"
                       href="index.php?page=loginuser"
                       <?= ($page == 'loginuser') ? 'aria-current="page"' : '' ?>>

                        <i class="bi bi-shield-lock me-1"></i>
                        Login Admin

                    </a>

                </li>


            </ul>

        </div>

    </div>

</nav>
