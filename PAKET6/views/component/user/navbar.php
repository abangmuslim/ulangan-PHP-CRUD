
<?php
$namauser = htmlspecialchars($_SESSION['namauser'] ?? 'Admin', ENT_QUOTES, 'UTF-8');
?>

<nav class="navbar navbar-expand-lg navbar-dark navbar-admin shadow-sm">

    <div class="container-fluid">

        <!-- TOMBOL PUSH MENU -->
        <button type="button"
            class="btn btn-pushmenu"
            id="toggleSidebar"
            title="Tampilkan / sembunyikan sidebar"
            aria-label="Tampilkan atau sembunyikan sidebar">

            <i class="bi bi-list"></i>

        </button>


        <!-- BRAND -->
        <a class="navbar-brand fw-bold me-3"
            href="index.php?page=dashboarduser">

            <i class="bi bi-hospital me-2"></i>
            Rumah Sakit Ahmadi

        </a>


        <!-- TOGGLER NAVBAR MOBILE -->
        <button class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarAdmin"
            aria-controls="navbarAdmin"
            aria-expanded="false"
            aria-label="Buka navigasi">

            <span class="navbar-toggler-icon"></span>

        </button>


        <!-- ISI NAVBAR -->
        <div class="collapse navbar-collapse" id="navbarAdmin">


            <!-- MENU UTAMA -->
            <ul class="navbar-nav me-auto">


                <!-- MENU OBAT -->
                <li class="nav-item">

                    <a class="nav-link"
                        href="index.php?page=obat">

                        <i class="bi bi-capsule me-1"></i>
                        Obat

                    </a>

                </li>


                <!-- MENU KUNJUNGAN -->
                <li class="nav-item">

                    <a class="nav-link"
                        href="index.php?page=kunjungan">

                        <i class="bi bi-clipboard2-pulse me-1"></i>
                        Kunjungan

                    </a>

                </li>


                <!-- DROPDOWN LAPORAN -->
                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle"
                        href="#"
                        id="menuLaporan"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">

                        <i class="bi bi-file-earmark-bar-graph me-1"></i>
                        Laporan

                    </a>


                    <ul class="dropdown-menu shadow"
                        aria-labelledby="menuLaporan">

                        <li>
                            <a class="dropdown-item"
                                href="index.php?page=laporanharian">

                                <i class="bi bi-calendar-day me-2"></i>
                                Laporan Harian

                            </a>
                        </li>


                        <li>
                            <a class="dropdown-item"
                                href="index.php?page=laporanbulanan">

                                <i class="bi bi-calendar-month me-2"></i>
                                Laporan Bulanan

                            </a>
                        </li>


                        <li>
                            <a class="dropdown-item"
                                href="index.php?page=laporantahunan">

                                <i class="bi bi-calendar3 me-2"></i>
                                Laporan Tahunan

                            </a>
                        </li>


                        <li>
                            <hr class="dropdown-divider">
                        </li>


                        <li>
                            <a class="dropdown-item"
                                href="index.php?page=laporanobat">

                                <i class="bi bi-capsule me-2"></i>
                                Laporan Obat

                            </a>
                        </li>

                    </ul>

                </li>


            </ul>


            <!-- MENU PENGGUNA -->
            <ul class="navbar-nav ms-auto align-items-lg-center">


                <!-- SAPAAN PENGGUNA -->
                <li class="nav-item">

                    <span class="nav-link user-greeting">

                        <i class="bi bi-person-circle me-1"></i>
                        Halo, <?= $namauser; ?>

                    </span>

                </li>


                <!-- LOGOUT -->
                <li class="nav-item ms-lg-2">

                    <a href="index.php?page=logout"
                        class="btn btn-outline-light btn-logout"
                        onclick="return confirm('Apakah Anda yakin ingin logout?')">

                        <i class="bi bi-box-arrow-right"></i>
                        Logout

                    </a>

                </li>


            </ul>


        </div>

    </div>

</nav>
