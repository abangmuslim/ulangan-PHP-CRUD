
<?php
$page = $_GET['page'] ?? 'home';

$menuAktif = function ($halaman) use ($page) {
    return in_array($page, (array) $halaman, true) ? 'active' : '';
};
?>
<style>
.main-header.navbar{position:sticky;top:0;z-index:1030;min-height:66px;padding:8px 0;background:rgba(255,255,255,.96)!important;border-bottom:1px solid #dceee9;box-shadow:0 4px 18px rgba(15,23,42,.07)!important;backdrop-filter:blur(12px)}
.main-header .navbar-brand{display:flex;align-items:center;gap:9px;margin-right:22px;padding:4px 0;font-size:19px;font-weight:700;color:#075c45!important}
.main-header .brand-icon{display:flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#087f5b,#0f766e);color:#fff;font-size:22px}
.main-header .navbar-nav{gap:4px}
.main-header .navbar-nav .nav-link{display:flex;align-items:center;gap:7px;padding:10px 12px!important;border:1px solid transparent;border-radius:9px;color:#475569!important;font-size:13px;font-weight:600;white-space:nowrap;transition:all .2s}
.main-header .navbar-nav .nav-link i{font-size:14px}
.main-header .navbar-nav .nav-link:hover{color:#087f5b!important;background:#ecfdf5;border-color:#d1fae5}
.main-header .navbar-nav .nav-link.active{color:#fff!important;background:linear-gradient(135deg,#087f5b,#0f766e);box-shadow:0 4px 12px rgba(8,127,91,.2)}
.main-header .navbar-nav .nav-link.nav-login,
.main-header .navbar-nav .nav-link.nav-login:link,
.main-header .navbar-nav .nav-link.nav-login:visited,
.main-header .navbar-nav .nav-link.nav-login:hover,
.main-header .navbar-nav .nav-link.nav-login:focus,
.main-header .navbar-nav .nav-link.nav-login:active,
.main-header .navbar-nav .nav-link.nav-login.active{color:#fff!important;font-weight:700!important}
.main-header .navbar-nav .nav-link.nav-login i{color:#fff!important}
.main-header .nav-login{justify-content:center;text-shadow:0 1px 1px rgba(0,0,0,.12)}
.main-header .nav-login-pasien{background:#087f5b!important;border-color:#087f5b!important}
.main-header .nav-login-dokter{background:#0f766e!important;border-color:#0f766e!important}
.main-header .nav-login-user{background:#334155!important;border-color:#334155!important}
.main-header .navbar-nav .nav-link.nav-login:hover{filter:brightness(1.1);transform:translateY(-1px)}
.main-header .navbar-nav .nav-link.nav-login.active{box-shadow:0 0 0 2px rgba(8,127,91,.18),0 4px 12px rgba(15,23,42,.12)}
.main-header .navbar-toggler{padding:7px 10px;border:1px solid #cde7df;border-radius:9px}
@media(max-width:1199.98px){.main-header .navbar-brand{margin-right:10px}.main-header .navbar-nav{gap:2px}.main-header .navbar-nav .nav-link{padding:9px 8px!important;font-size:12px}}
@media(max-width:991.98px){.main-header.navbar{padding:8px 0}.main-header .navbar-collapse{margin-top:10px;padding:10px;background:#fff;border:1px solid #dceee9;border-radius:12px;box-shadow:0 8px 20px rgba(15,23,42,.08)}.main-header .navbar-nav .nav-link{justify-content:flex-start;padding:11px 12px!important;font-size:13px}.main-header .navbar-nav.ml-auto{margin-top:8px;padding-top:8px;border-top:1px solid #e2e8f0}.main-header .nav-login{justify-content:flex-start;width:100%}}
@media(prefers-reduced-motion:reduce){.main-header *{transition:none!important}}
</style>

<nav class="main-header navbar navbar-expand-lg navbar-white navbar-light">
    <div class="container">
        <a href="index.php?page=home" class="navbar-brand">
            <span class="brand-icon"><i class="fas fa-hospital"></i></span>
            <span>RumahSakit<span style="color:#087f5b">Ahmadi</span></span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTamu" aria-controls="navbarTamu" aria-expanded="false" aria-label="Buka menu navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTamu">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="index.php?page=home" class="nav-link <?= $menuAktif('home') ?>">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=daftardokter" class="nav-link <?= $menuAktif(['daftardokter', 'detildokter']) ?>">
                        <i class="fas fa-user-md"></i> Dokter
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=daftarobat" class="nav-link <?= $menuAktif(['daftarobat', 'detilobat']) ?>">
                        <i class="fas fa-pills"></i> Obat
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=daftarkategori" class="nav-link <?= $menuAktif(['daftarkategori', 'detilkategori']) ?>">
                        <i class="fas fa-th-large"></i> Kategori
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=layanan" class="nav-link <?= $menuAktif('layanan') ?>">
                        <i class="fas fa-heartbeat"></i> Layanan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=tentang" class="nav-link <?= $menuAktif('tentang') ?>">
                        <i class="fas fa-hospital-user"></i> Tentang
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=kontak" class="nav-link <?= $menuAktif('kontak') ?>">
                        <i class="fas fa-headset"></i> Kontak
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto align-items-lg-center">
                <li class="nav-item">
                    <a href="index.php?page=loginpasien" class="nav-link nav-login nav-login-pasien <?= $menuAktif('loginpasien') ?>">
                        <i class="fas fa-user"></i> Login Pasien
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=logindokter" class="nav-link nav-login nav-login-dokter <?= $menuAktif('logindokter') ?>">
                        <i class="fas fa-user-md"></i> Login Dokter
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=loginuser" class="nav-link nav-login nav-login-user <?= $menuAktif('loginuser') ?>">
                        <i class="fas fa-user-shield"></i> Login User
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
