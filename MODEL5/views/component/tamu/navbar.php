<?php
// Menentukan menu aktif berdasarkan parameter halaman.
$halamanAktif = $_GET['halaman'] ?? 'home';

$menuAktif = function ($halaman) use ($halamanAktif) {
    return $halamanAktif === $halaman ? 'active' : '';
};
?>

<style>
    /* =========================================
   FUTURISTIC NAVBAR - KOPSEKAM
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

    /* Brand / logo */
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

    /* Menu utama */
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
        transition:
            color .22s ease,
            background .22s ease,
            border-color .22s ease,
            box-shadow .22s ease,
            transform .22s ease;
    }

    /* Ikon menu */
    .main-header .navbar-nav .nav-link i {
        font-size: 14px;
        transition: transform .22s ease;
    }

    /* Hover */
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

    /* Menu aktif: tetap terlihat walaupun kursor berpindah */
    .main-header .navbar-nav .nav-link.active {
        color: #ffffff !important;
        background: linear-gradient(135deg, #059669, #0f766e);
        border-color: transparent;
        box-shadow: 0 4px 13px rgba(5, 150, 105, .24);
    }

    /* Garis glow tipis pada menu aktif */
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

    /* Hover pada menu aktif */
    .main-header .navbar-nav .nav-link.active:hover {
        color: #ffffff !important;
        background: linear-gradient(135deg, #047857, #115e59);
        box-shadow: 0 6px 18px rgba(5, 150, 105, .30);
    }

    /* Efek ketika menu ditekan */
    .main-header .navbar-nav .nav-link:active,
    .main-header .navbar-nav .nav-link.btn:active {
        transform: scale(.96);
    }

    /* Tombol login */
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

    .main-header .nav-login:hover {
        color: #ffffff !important;
        transform: translateY(-2px);
        filter: brightness(1.08);
    }

    .main-header .nav-login:active {
        transform: scale(.96);
    }

    .main-header .nav-login-pelanggan {
        background: linear-gradient(135deg, #059669, #0f766e) !important;
        box-shadow: 0 4px 12px rgba(5, 150, 105, .20);
    }

    .main-header .nav-login-user {
        background: linear-gradient(135deg, #334155, #0f172a) !important;
        box-shadow: 0 4px 12px rgba(15, 23, 42, .18);
    }

    .main-header .nav-login-user:hover {
        box-shadow: 0 6px 18px rgba(15, 23, 42, .27);
    }

    .main-header .navbar-nav .nav-item-login {
        display: flex;
        align-items: center;
        margin-left: 5px;
    }

    /* =========================================
   FIX: TOMBOL LOGIN PELANGGAN & USER
========================================= */

    /* Kondisi normal untuk seluruh teks dan ikon */
    .main-header .navbar-nav .nav-link.nav-login,
    .main-header .navbar-nav .nav-link.nav-login i,
    .main-header .navbar-nav .nav-link.nav-login span {
        color: #ffffff !important;
        opacity: 1 !important;
        text-decoration: none !important;
    }

    /* Login Pelanggan */
    .main-header .navbar-nav .nav-link.nav-login-pelanggan {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%) !important;
        border: 1px solid #059669 !important;
        box-shadow: 0 5px 14px rgba(5, 150, 105, 0.22) !important;
    }

    /* Login User */
    .main-header .navbar-nav .nav-link.nav-login-user {
        background: linear-gradient(135deg, #f04452 0%, #c81e3a 100%) !important;
        border: 1px solid #dc3545 !important;
        box-shadow: 0 5px 14px rgba(220, 53, 69, 0.22) !important;
    }

    /* Hover: warna lebih terang, naik sedikit */
    .main-header .navbar-nav .nav-link.nav-login-pelanggan:hover {
        color: #ffffff !important;
        background: linear-gradient(135deg, #34d399 0%, #059669 100%) !important;
        border-color: #34d399 !important;
        box-shadow: 0 8px 22px rgba(5, 150, 105, 0.35) !important;
        transform: translateY(-2px);
    }

    .main-header .navbar-nav .nav-link.nav-login-user:hover {
        color: #ffffff !important;
        background: linear-gradient(135deg, #fb7185 0%, #e11d48 100%) !important;
        border-color: #fb7185 !important;
        box-shadow: 0 8px 22px rgba(225, 29, 72, 0.32) !important;
        transform: translateY(-2px);
    }

    /* Paksa ikon tetap putih dalam semua kondisi */
    .main-header .navbar-nav .nav-link.nav-login:hover i,
    .main-header .navbar-nav .nav-link.nav-login:focus i,
    .main-header .navbar-nav .nav-link.nav-login:active i,
    .main-header .navbar-nav .nav-link.nav-login:hover,
    .main-header .navbar-nav .nav-link.nav-login:focus,
    .main-header .navbar-nav .nav-link.nav-login:active {
        color: #ffffff !important;
        opacity: 1 !important;
    }

    /* Fokus keyboard */
    .main-header .navbar-nav .nav-link.nav-login:focus-visible {
        outline: 3px solid rgba(16, 185, 129, 0.35);
        outline-offset: 3px;
    }

    /* Halaman login yang sedang aktif */
    .main-header .navbar-nav .nav-link.nav-login.active {
        color: #ffffff !important;
        filter: brightness(0.88);
        box-shadow:
            0 0 0 3px rgba(16, 185, 129, 0.16),
            0 7px 20px rgba(15, 23, 42, 0.18) !important;
    }

    /* Penanda aktif kecil */
    .main-header .navbar-nav .nav-link.nav-login.active::after {
        content: "";
        position: absolute;
        width: 6px;
        height: 6px;
        top: 5px;
        right: 6px;
        background: #ffffff;
        border-radius: 50%;
        box-shadow: 0 0 8px rgba(255, 255, 255, 0.9);
    }

    /* Efek tekan */
    .main-header .navbar-nav .nav-link.nav-login:active {
        transform: scale(0.97);
    }

    /* Tombol hamburger */
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

    /* Tablet dan laptop kecil */
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

    /* Navbar mobile */
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

        .main-header .navbar-nav.ml-auto {
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

    /* Hormati pengaturan pengguna yang mengurangi animasi */
    @media (prefers-reduced-motion: reduce) {

        .main-header *,
        .main-header *::before,
        .main-header *::after {
            transition: none !important;
            animation: none !important;
        }
    }
</style>

<nav class="main-header navbar navbar-expand-lg navbar-white navbar-light">
    <div class="container">

        <!-- Brand -->
        <a href="index.php?halaman=home" class="navbar-brand">
            <img
                src="assets/images/logo.png"
                alt="Logo KOPSEKAM"
                class="brand-image elevation-2">

            <span class="brand-text font-weight-bold">
                <span class="text-primary">KOP</span><span class="text-danger">SEK</span><span class="text-success">AM</span>
            </span>
        </a>

        <!-- Toggle mobile -->
        <button
            class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarCollapse"
            aria-controls="navbarCollapse"
            aria-expanded="false"
            aria-label="Buka atau tutup menu navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">

            <!-- Menu utama -->
            <ul class="navbar-nav">

                <li class="nav-item">
                    <a
                        href="index.php?halaman=home"
                        class="nav-link <?= $menuAktif('home') ?>">
                        <i class="fas fa-home"></i>
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        href="index.php?halaman=daftarbarang"
                        class="nav-link <?= $menuAktif('daftarbarang') ?>">
                        <i class="fas fa-box"></i>
                        Daftar Barang
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        href="index.php?halaman=daftarkategori"
                        class="nav-link <?= $menuAktif('daftarkategori') ?>">
                        <i class="fas fa-layer-group"></i>
                        Kategori
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        href="index.php?halaman=tentang"
                        class="nav-link <?= $menuAktif('tentang') ?>">
                        <i class="fas fa-circle-info"></i>
                        Tentang
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        href="index.php?halaman=kontak"
                        class="nav-link <?= $menuAktif('kontak') ?>">
                        <i class="fas fa-headset"></i>
                        Kontak
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        href="index.php?halaman=daftarisi"
                        class="nav-link <?= $menuAktif('daftarisi') ?>">
                        <i class="fas fa-list-ul"></i>
                        Daftar Isi
                    </a>
                </li>

            </ul>

            <!-- Login -->
            <ul class="navbar-nav ml-auto align-items-lg-center">

                <ul class="navbar-nav ml-auto align-items-lg-center">

                    <!-- Login Pelanggan -->
                    <li class="nav-item nav-item-login">
                        <a
                            href="index.php?halaman=loginpelanggan"
                            class="nav-link nav-login nav-login-pelanggan <?= $menuAktif('loginpelanggan') ?>">
                            <i class="fas fa-user"></i>
                            <span>Login Pelanggan</span>
                        </a>
                    </li>

                    <!-- Login User -->
                    <li class="nav-item nav-item-login">
                        <a
                            href="index.php?halaman=loginuser"
                            class="nav-link nav-login nav-login-user <?= $menuAktif('loginuser') ?>">
                            <i class="fas fa-user-shield"></i>
                            <span>Login User</span>
                        </a>
                    </li>

                </ul>

            </ul>


        </div>
    </div>
</nav>