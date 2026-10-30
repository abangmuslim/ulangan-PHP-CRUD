
<?php
$namapasien = htmlspecialchars(
    $_SESSION['namapasien'] ?? 'Pasien',
    ENT_QUOTES,
    'UTF-8'
);
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
            href="index.php?page=dashboardpasien">

            <i class="bi bi-hospital me-2"></i>
            Rumah Sakit Ahmadi

        </a>


        <!-- TOGGLER NAVBAR MOBILE -->
        <button class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarPasien"
            aria-controls="navbarPasien"
            aria-expanded="false"
            aria-label="Buka navigasi">

            <span class="navbar-toggler-icon"></span>

        </button>


        <!-- ISI NAVBAR -->
        <div class="collapse navbar-collapse" id="navbarPasien">


            <!-- MENU UTAMA -->
            <ul class="navbar-nav me-auto">


                <!-- JADWAL DOKTER -->
                <li class="nav-item">

                    <a class="nav-link"
                        href="index.php?page=daftardokter">

                        <i class="bi bi-calendar-week me-1"></i>
                        Dokter

                    </a>

                </li>


                <!-- INFORMASI OBAT -->
                <li class="nav-item">

                    <a class="nav-link"
                        href="index.php?page=daftarobat">

                        <i class="bi bi-capsule me-1"></i>
                        Obat

                    </a>

                </li>


                <!-- PENDAFTARAN CHECKUP -->
                <li class="nav-item">

                    <a class="nav-link"
                        href="index.php?page=pendaftarancheckup">

                        <i class="bi bi-calendar2-plus me-1"></i>
                        Pendaftaran Checkup

                    </a>

                </li>


                <!-- RIWAYAT KUNJUNGAN -->
                <li class="nav-item">

                    <a class="nav-link"
                        href="index.php?page=riwayatkunjungan">

                        <i class="bi bi-clock-history me-1"></i>
                        Riwayat

                    </a>

                </li>


            </ul>


            <!-- MENU PENGGUNA -->
            <ul class="navbar-nav ms-auto align-items-lg-center">


                <!-- SAPAAN PASIEN -->
                <li class="nav-item">

                    <span class="nav-link user-greeting">

                        <i class="bi bi-person-circle me-1"></i>
                        Halo, <?= $namapasien; ?>

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
