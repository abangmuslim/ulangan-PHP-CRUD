
<?php
// Menentukan menu aktif berdasarkan parameter halaman.
$halamanAktif = $_GET['page'] ?? 'home';

$menuAktif = function ($halaman) use ($halamanAktif) {
    return $halamanAktif === $halaman ? 'active' : '';
};
?>

<style>
/* =========================================
   FUTURISTIC NAVBAR - KLINIK AHMADI
========================================= */

.main-header.navbar {
    position: sticky;
    top: 0;
    z-index: 1030;
    min-height: 68px;
    padding: 9px 0;
    background: rgba(255, 255, 255, 0.94) !important;
    border: 0;
    border-bottom: 1px solid rgba(16, 185, 129, 0.18);
    box-shadow: 0 5px 24px rgba(15, 23, 42, 0.07) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
}

/* BRAND DAN LOGO */
.main-header .navbar-brand {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    margin-right: 22px;
    padding: 4px 10px 4px 0;
    transition: transform .25s ease;
}

.main-header .navbar-brand:hover {
    transform: translateY(-1px);
}

.main-header .brand-image {
    float: none !important;
    width: 38px;
    height: 38px;
    margin: 0 !important;
    object-fit: contain;
    border-radius: 11px !important;
}

.main-header .brand-text {
    font-size: 19px;
    letter-spacing: 1px;
    line-height: 1;
}

/* MENU UTAMA */
.main-header .navbar-nav {
    gap: 5px;
}

.main-header .navbar-nav .nav-link {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 10px 13px !important;
    border: 1px solid transparent;
    border-radius: 10px;
    color: #475569 !important;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
    transition: all .22s ease;
}

/* IKON MENU */
.main-header .navbar-nav .nav-link i {
    font-size: 14px;
    transition: transform .22s ease;
}

/* HOVER MENU */
.main-header .navbar-nav .nav-link:hover {
    color: #047857 !important;
    background: linear-gradient(135deg,
        rgba(16, 185, 129, .12),
        rgba(20, 184, 166, .07));
    border-color: rgba(16, 185, 129, .20);
    box-shadow: 0 4px 13px rgba(16, 185, 129, .10);
    transform: translateY(-2px);
}

.main-header .navbar-nav .nav-link:hover i {
    transform: scale(1.12);
}

/* MENU AKTIF */
.main-header .navbar-nav .nav-link.active {
    color: #ffffff !important;
    background: linear-gradient(135deg, #059669, #0f766e);
    border-color: transparent;
    box-shadow: 0 4px 13px rgba(5, 150, 105, .24);
}

.main-header .navbar-nav .nav-link.active::after {
    content: "";
    position: absolute;
    left: 18%;
    right: 18%;
    bottom: 3px;
    height: 2px;
    border-radius: 10px;
    background: rgba(255, 255, 255, .85);
}

.main-header .navbar-nav .nav-link.active:hover {
    color: #ffffff !important;
    background: linear-gradient(135deg, #047857, #115e59);
    box-shadow: 0 6px 18px rgba(5, 150, 105, .30);
}

.main-header .navbar-nav .nav-link:active {
    transform: scale(.96);
}

/* =========================================
   TOMBOL LOGIN
========================================= */

.main-header .nav-login {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 10px 14px !important;
    border: 1px solid transparent !important;
    border-radius: 10px !important;
    color: #ffffff !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    letter-spacing: .1px;
    box-shadow: 0 4px 10px rgba(15, 23, 42, .10);
    transition: all .22s ease !important;
}

.main-header .nav-login,
.main-header .nav-login i,
.main-header .nav-login span {
    color: #ffffff !important;
    opacity: 1 !important;
    text-decoration: none !important;
}

.main-header .nav-login:hover {
    color: #ffffff !important;
    transform: translateY(-2px);
    filter: brightness(1.08);
}

.main-header .nav-login:active {
    transform: scale(.96);
}

/* LOGIN user */
.main-header .nav-login-user {
    background: linear-gradient(135deg, #334155, #0f172a) !important;
    border-color: #334155 !important;
    box-shadow: 0 4px 12px rgba(15, 23, 42, .18);
}

/* LOGIN PASIEN */
.main-header .nav-login-pasien {
    background: linear-gradient(135deg, #10b981, #047857) !important;
    border-color: #059669 !important;
    box-shadow: 0 5px 14px rgba(5, 150, 105, .22) !important;
}

/* LOGIN DOKTER */
.main-header .nav-login-dokter {
    background: linear-gradient(135deg, #0ea5e9, #0369a1) !important;
    border-color: #0284c7 !important;
    box-shadow: 0 5px 14px rgba(2, 132, 199, .22) !important;
}

/* HOVER LOGIN */
.main-header .nav-login-user:hover {
    background: linear-gradient(135deg, #475569, #1e293b) !important;
    box-shadow: 0 8px 22px rgba(15, 23, 42, .27) !important;
}

.main-header .nav-login-pasien:hover {
    background: linear-gradient(135deg, #34d399, #059669) !important;
    box-shadow: 0 8px 22px rgba(5, 150, 105, .35) !important;
}

.main-header .nav-login-dokter:hover {
    background: linear-gradient(135deg, #38bdf8, #0284c7) !important;
    box-shadow: 0 8px 22px rgba(2, 132, 199, .32) !important;
}

/* POSISI LOGIN */
.main-header .navbar-nav .nav-item-login {
    display: flex;
    align-items: center;
    margin-left: 5px;
}

/* FOKUS KEYBOARD */
.main-header .nav-login:focus-visible {
    outline: 3px solid rgba(16, 185, 129, .35);
    outline-offset: 3px;
}

/* HALAMAN LOGIN AKTIF */
.main-header .nav-login.active {
    filter: brightness(.88);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, .16),
                0 7px 20px rgba(15, 23, 42, .18) !important;
}

.main-header .nav-login.active::after {
    content: "";
    position: absolute;
    width: 6px;
    height: 6px;
    top: 5px;
    right: 6px;
    background: #ffffff;
    border-radius: 50%;
    box-shadow: 0 0 8px rgba(255, 255, 255, .9);
}

/* HAMBURGER */
.main-header .navbar-toggler {
    padding: 7px 10px;
    border: 1px solid #d1fae5;
    border-radius: 9px;
    outline: none;
    box-shadow: none;
}

.main-header .navbar-toggler:hover,
.main-header .navbar-toggler:focus {
    background: #ecfdf5;
    border-color: #6ee7b7;
}

/* LAPTOP KECIL */
@media (max-width: 1199.98px) {
    .main-header .navbar-brand {
        margin-right: 12px;
    }

    .main-header .navbar-nav {
        gap: 3px;
    }

    .main-header .navbar-nav .nav-link {
        padding: 9px 10px !important;
        font-size: 12px;
    }

    .main-header .nav-login {
        padding: 9px 10px !important;
        font-size: 11px !important;
    }
}

/* TABLET DAN MOBILE */
@media (max-width: 991.98px) {
    .main-header.navbar {
        padding: 8px 0;
    }

    .main-header .navbar-collapse {
        margin-top: 12px;
        padding: 10px;
        background: rgba(255, 255, 255, .98);
        border: 1px solid #d1fae5;
        border-radius: 14px;
        box-shadow: 0 12px 28px rgba(15, 23, 42, .09);
    }

    .main-header .navbar-nav {
        gap: 5px;
    }

    .main-header .navbar-nav .nav-link {
        justify-content: flex-start;
        padding: 11px 13px !important;
        font-size: 13px;
    }

    .main-header .navbar-nav .nav-link.active::after {
        left: auto;
        right: 12px;
        bottom: 50%;
        width: 5px;
        height: 5px;
        border-radius: 50%;
        transform: translateY(50%);
    }

    .main-header .navbar-nav.ms-auto {
        margin-top: 9px;
        padding-top: 10px;
        border-top: 1px solid #e2e8f0;
    }

    .main-header .navbar-nav .nav-item-login {
        margin: 4px 0;
    }

    .main-header .nav-login {
        width: 100%;
        justify-content: flex-start;
        padding: 11px 13px !important;
        font-size: 12px !important;
    }
}

/* REDUCED MOTION */
@media (prefers-reduced-motion: reduce) {
    .main-header *,
    .main-header *::before,
    .main-header *::after {
        transition: none !important;
        animation: none !important;
    }
}
</style>


<!-- =========================================
     NAVBAR KLINIK AHMADI
========================================= -->

<nav class="main-header navbar navbar-expand-lg navbar-white navbar-light">

    <div class="container">

        <!-- BRAND -->
        <a href="index.php?page=home" class="navbar-brand">

            <img
                src="assets/images/logo.png"
                alt="Logo Klinik Ahmadi"
                class="brand-image elevation-2">

            <span class="brand-text font-weight-bold">
                <span class="text-success">KLINIK</span>
                <span class="text-dark">AHMADI</span>
            </span>

        </a>


        <!-- TOGGLE MOBILE -->
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarCollapse"
            aria-controls="navbarCollapse"
            aria-expanded="false"
            aria-label="Buka atau tutup menu navigasi">

            <span class="navbar-toggler-icon"></span>

        </button>


        <div class="collapse navbar-collapse" id="navbarCollapse">


            <!-- =====================================
                 MENU UTAMA (KIRI)
            ====================================== -->

            <ul class="navbar-nav">

                <!-- BERANDA -->
                <li class="nav-item">
                    <a href="index.php?page=home"
                       class="nav-link <?= $menuAktif('home') ?>">
                        <i class="fas fa-home"></i>
                        Beranda
                    </a>
                </li>

                <!-- DOKTER -->
                <li class="nav-item">
                    <a href="index.php?page=daftardokter"
                       class="nav-link <?= in_array($halamanAktif, ['daftardokter', 'detildokter']) ? 'active' : '' ?>">
                        <i class="fas fa-user-md"></i>
                        Dokter
                    </a>
                </li>

                <!-- OBAT -->
                <li class="nav-item">
                    <a href="index.php?page=daftarobat"
                       class="nav-link <?= in_array($halamanAktif, ['daftarobat', 'detilobat']) ? 'active' : '' ?>">
                        <i class="fas fa-capsules"></i>
                        Obat
                    </a>
                </li>

                <!-- KATEGORI -->
                <li class="nav-item">
                    <a href="index.php?page=daftarkategori"
                       class="nav-link <?= in_array($halamanAktif, ['daftarkategori', 'detilkategori']) ? 'active' : '' ?>">
                        <i class="fas fa-th-large"></i>
                        Kategori
                    </a>
                </li>

                <!-- LAYANAN -->
                <li class="nav-item">
                    <a href="index.php?page=layanan"
                       class="nav-link <?= $menuAktif('layanan') ?>">
                        <i class="fas fa-heartbeat"></i>
                        Layanan
                    </a>
                </li>

                <!-- TENTANG -->
                <li class="nav-item">
                    <a href="index.php?page=tentang"
                       class="nav-link <?= $menuAktif('tentang') ?>">
                        <i class="fas fa-info-circle"></i>
                        Tentang
                    </a>
                </li>

                <!-- KONTAK -->
                <li class="nav-item">
                    <a href="index.php?page=kontak"
                       class="nav-link <?= $menuAktif('kontak') ?>">
                        <i class="fas fa-headset"></i>
                        Kontak
                    </a>
                </li>

            </ul>


            <!-- =====================================
                 LOGIN (KANAN)
            ====================================== -->

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <!-- LOGIN user -->
                <li class="nav-item nav-item-login">
                    <a href="index.php?page=loginuser"
                       class="nav-link nav-login nav-login-user <?= $menuAktif('loginuser') ?>">
                        <i class="fas fa-user-shield"></i>
                        <span>Login User</span>
                    </a>
                </li>

                <!-- LOGIN PASIEN -->
                <li class="nav-item nav-item-login">
                    <a href="index.php?page=loginpasien"
                       class="nav-link nav-login nav-login-pasien <?= $menuAktif('loginpasien') ?>">
                        <i class="fas fa-user"></i>
                        <span>Login Pasien</span>
                    </a>
                </li>

                <!-- LOGIN DOKTER -->
                <li class="nav-item nav-item-login">
                    <a href="index.php?page=logindokter"
                       class="nav-link nav-login nav-login-dokter <?= $menuAktif('logindokter') ?>">
                        <i class="fas fa-user-md"></i>
                        <span>Login Dokter</span>
                    </a>
                </li>

            </ul>

        </div>

    </div>

</nav>
