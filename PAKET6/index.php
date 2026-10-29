
<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
ini_set('log_errors', '1');

session_start();

/* =========================================================
   1. KONFIGURASI DASAR
   ========================================================= */

define('APP_ROOT', __DIR__);
define('APP_NAME', 'RumahSakitAhmadi5');

function catatError($pesan)
{
    error_log('[' . APP_NAME . '] ' . $pesan);
}

function redirectHalaman($halaman)
{
    header('Location: index.php?page=' . rawurlencode($halaman));
    exit;
}

function tampilkanErrorHalaman($kode)
{
    http_response_code($kode);
    $file = APP_ROOT . '/views/errors/' . $kode . '.php';

    if (is_file($file)) {
        include $file;
    } else {
        echo '<div class="container mt-4">';
        echo '<div class="alert alert-warning">';
        echo '<h4>Halaman ' . (int) $kode . '</h4>';
        echo '<p>Halaman tidak dapat ditampilkan.</p>';
        echo '<a href="index.php?page=home">Kembali ke Beranda</a>';
        echo '</div></div>';
    }
}

/* =========================================================
   2. MEMUAT SESSION, KONEKSI, HELPER, DAN AUTH
   ========================================================= */

if (is_file(APP_ROOT . '/proses/session.php')) {
    require_once APP_ROOT . '/proses/session.php';
} else {
    catatError('File proses/session.php tidak ditemukan.');
}

if (is_file(APP_ROOT . '/proses/koneksi.php')) {
    require_once APP_ROOT . '/proses/koneksi.php';
} else {
    catatError('File proses/koneksi.php tidak ditemukan.');
}

if (is_file(APP_ROOT . '/proses/helper.php')) {
    require_once APP_ROOT . '/proses/helper.php';
} else {
    catatError('File proses/helper.php tidak ditemukan.');
}

if (is_file(APP_ROOT . '/proses/auth.php')) {
    require_once APP_ROOT . '/proses/auth.php';
} else {
    catatError('File proses/auth.php tidak ditemukan.');
}

/* =========================================================
   3. MEMBACA PARAMETER PAGE
   ========================================================= */

$page = 'home';

if (isset($_GET['page']) && is_string($_GET['page'])) {
    $inputPage = trim($_GET['page']);

    if (preg_match('/^[a-zA-Z0-9_-]{1,80}$/', $inputPage)) {
        $page = strtolower($inputPage);
    } else {
        $page = '404';
    }
}

/* =========================================================
   4. MENENTUKAN ROLE SESSION
   ========================================================= */

$role = '';

if (!empty($_SESSION['iduser'])) {
    $roleSesi = strtolower((string) ($_SESSION['role'] ?? ''));

    if (in_array($roleSesi, ['admin', 'petugas'], true)) {
        $role = $roleSesi;
    }
} elseif (!empty($_SESSION['idpasien'])) {
    $role = 'pasien';
} elseif (!empty($_SESSION['iddokter'])) {
    $role = 'dokter';
}

$sedangLogin = ($role !== '');

/* =========================================================
   5. DAFTAR HALAMAN PUBLIK
   ========================================================= */

$halamanPublik = [
    'home',
    'daftardokter',
    'detildokter',
    'daftarobat',
    'detilobat',
    'daftarkategori',
    'detilkategori',
    'tentang',
    'layanan',
    'kontak',
    'loginuser',
    'loginpasien',
    'logindokter',
    'registerpasien',
    '403',
    '404',
    '500'
];

/* =========================================================
   6. DAFTAR HALAMAN ADMIN DAN PETUGAS
   ========================================================= */

$halamanUser = [
    'dashboardadmin',
    'dashboardpetugas',
    'user',
    'createuser',
    'edituser',
    'showuser',
    'pasien',
    'createpasien',
    'editpasien',
    'showpasien',
    'dokter',
    'createdokter',
    'editdokter',
    'showdokter',
    'merk',
    'createmerk',
    'editmerk',
    'showmerk',
    'kategori',
    'createkategori',
    'editkategori',
    'showkategori',
    'obat',
    'createobat',
    'editobat',
    'showobat',
    'jadwaldokter',
    'createjadwaldokter',
    'editjadwaldokter',
    'showjadwaldokter',
    'kunjungan',
    'createkunjungan',
    'editkunjungan',
    'showkunjungan',
    'detilkunjungan',
    'createdetilkunjungan',
    'editdetilkunjungan',
    'showdetilkunjungan',
    'antrian',
    'createantrian',
    'editantrian',
    'showantrian',
    'laporanharian',
    'cetaklaporanharian',
    'laporanbulanan',
    'cetaklaporanbulanan',
    'laporantahunan',
    'cetaklaporantahunan',
    'laporanobat',
    'cetaklaporanobat',
    'laporanantrian',
    'cetaklaporanantrian'
];

/* Hanya admin yang boleh mengelola akun user */
$halamanKhususAdmin = [
    'user',
    'createuser',
    'edituser',
    'showuser'
];

/* =========================================================
   7. DAFTAR HALAMAN PASIEN DAN DOKTER
   ========================================================= */

$halamanPasien = [
    'dashboardpasien',
    'profilpasien',
    'editprofilpasien',
    'antrianpasien',
    'riwayatkunjunganpasien',
    'detailkunjunganpasien'
];

$halamanDokter = [
    'dashboarddokter',
    'profildokter',
    'jadwalkunjungan',
    'antrianpasiendokter',
    'detailkunjungandokter'
];

/* =========================================================
   8. PENGALIHAN DASHBOARD SESUAI ROLE
   ========================================================= */

if ($sedangLogin) {
    if ($page === 'dashboardadmin' && $role === 'petugas') {
        redirectHalaman('dashboardpetugas');
    }

    if ($page === 'dashboardpetugas' && $role === 'admin') {
        redirectHalaman('dashboardadmin');
    }

    if (
        in_array($page, [
            'loginuser',
            'loginpasien',
            'logindokter',
            'registerpasien'
        ], true)
    ) {
        if ($role === 'admin') {
            redirectHalaman('dashboardadmin');
        } elseif ($role === 'petugas') {
            redirectHalaman('dashboardpetugas');
        } elseif ($role === 'pasien') {
            redirectHalaman('dashboardpasien');
        } elseif ($role === 'dokter') {
            redirectHalaman('dashboarddokter');
        }
    }
}

/* =========================================================
   9. VALIDASI HALAMAN DAN HAK AKSES
   ========================================================= */

/* Halaman tidak dikenal diarahkan ke 404 */
$semuaHalamanTerdaftar = array_merge(
    $halamanPublik,
    $halamanUser,
    $halamanPasien,
    $halamanDokter,
    ['logout']
);

if (!in_array($page, $semuaHalamanTerdaftar, true)) {
    $page = '404';
}

/* Pengguna yang belum login tidak boleh membuka halaman privat */
$semuaHalamanPrivat = array_merge(
    $halamanUser,
    $halamanPasien,
    $halamanDokter
);

if (!$sedangLogin && in_array($page, $semuaHalamanPrivat, true)) {
    redirectHalaman('loginuser');
}

/* Hak akses admin dan petugas */
if (in_array($page, $halamanUser, true)) {
    if (!in_array($role, ['admin', 'petugas'], true)) {
        $page = '403';
    } elseif (
        in_array($page, $halamanKhususAdmin, true) &&
        $role !== 'admin'
    ) {
        $page = '403';
    }
}

/* Hak akses pasien */
if (in_array($page, $halamanPasien, true) && $role !== 'pasien') {
    $page = '403';
}

/* Hak akses dokter */
if (in_array($page, $halamanDokter, true) && $role !== 'dokter') {
    $page = '403';
}

/* =========================================================
   10. DAFTAR ROUTING
   ========================================================= */

$routes = [
    /* Landing page */
    'home' => 'views/landing/home.php',
    'daftardokter' => 'views/landing/daftardokter.php',
    'detildokter' => 'views/landing/detildokter.php',
    'daftarobat' => 'views/landing/daftarobat.php',
    'detilobat' => 'views/landing/detilobat.php',
    'daftarkategori' => 'views/landing/daftarkategori.php',
    'detilkategori' => 'views/landing/detilkategori.php',
    'tentang' => 'views/landing/tentang.php',
    'layanan' => 'views/landing/layanan.php',
    'kontak' => 'views/landing/kontak.php',

    /* Autentikasi */
    'loginuser' => 'views/auth/loginuser.php',
    'loginpasien' => 'views/auth/loginpasien.php',
    'logindokter' => 'views/auth/logindokter.php',
    'registerpasien' => 'views/auth/registerpasien.php',
    'logout' => 'views/auth/logout.php',

    /* Dashboard */
    'dashboardadmin' => 'views/user/dashboard/dashboardadmin.php',
    'dashboardpetugas' => 'views/user/dashboard/dashboardpetugas.php',
    'dashboardpasien' => 'views/pasien/dashboardpasien.php',
    'dashboarddokter' => 'views/dokter/dashboarddokter.php',

    /* CRUD User */
    'user' => 'views/user/user/index.php',
    'createuser' => 'views/user/user/create.php',
    'edituser' => 'views/user/user/edit.php',
    'showuser' => 'views/user/user/show.php',

    /* CRUD Pasien */
    'pasien' => 'views/user/pasien/index.php',
    'createpasien' => 'views/user/pasien/create.php',
    'editpasien' => 'views/user/pasien/edit.php',
    'showpasien' => 'views/user/pasien/show.php',

    /* CRUD Dokter */
    'dokter' => 'views/user/dokter/index.php',
    'createdokter' => 'views/user/dokter/create.php',
    'editdokter' => 'views/user/dokter/edit.php',
    'showdokter' => 'views/user/dokter/show.php',

    /* CRUD Merk */
    'merk' => 'views/user/merk/index.php',
    'createmerk' => 'views/user/merk/create.php',
    'editmerk' => 'views/user/merk/edit.php',
    'showmerk' => 'views/user/merk/show.php',

    /* CRUD Kategori */
    'kategori' => 'views/user/kategori/index.php',
    'createkategori' => 'views/user/kategori/create.php',
    'editkategori' => 'views/user/kategori/edit.php',
    'showkategori' => 'views/user/kategori/show.php',

    /* CRUD Obat */
    'obat' => 'views/user/obat/index.php',
    'createobat' => 'views/user/obat/create.php',
    'editobat' => 'views/user/obat/edit.php',
    'showobat' => 'views/user/obat/show.php',

    /* CRUD Jadwal Dokter */
    'jadwaldokter' => 'views/user/jadwaldokter/index.php',
    'createjadwaldokter' => 'views/user/jadwaldokter/create.php',
    'editjadwaldokter' => 'views/user/jadwaldokter/edit.php',
    'showjadwaldokter' => 'views/user/jadwaldokter/show.php',

    /* CRUD Kunjungan */
    'kunjungan' => 'views/user/kunjungan/index.php',
    'createkunjungan' => 'views/user/kunjungan/create.php',
    'editkunjungan' => 'views/user/kunjungan/edit.php',
    'showkunjungan' => 'views/user/kunjungan/show.php',

    /* CRUD Detail Kunjungan */
    'detilkunjungan' => 'views/user/detilkunjungan/index.php',
    'createdetilkunjungan' => 'views/user/detilkunjungan/create.php',
    'editdetilkunjungan' => 'views/user/detilkunjungan/edit.php',
    'showdetilkunjungan' => 'views/user/detilkunjungan/show.php',

    /* CRUD Antrian */
    'antrian' => 'views/user/antrian/index.php',
    'createantrian' => 'views/user/antrian/create.php',
    'editantrian' => 'views/user/antrian/edit.php',
    'showantrian' => 'views/user/antrian/show.php',

    /* Laporan */
    'laporanharian' => 'views/user/laporan/laporanharian.php',
    'cetaklaporanharian' => 'views/user/laporan/cetaklaporanharian.php',
    'laporanbulanan' => 'views/user/laporan/laporanbulanan.php',
    'cetaklaporanbulanan' => 'views/user/laporan/cetaklaporanbulanan.php',
    'laporantahunan' => 'views/user/laporan/laporantahunan.php',
    'cetaklaporantahunan' => 'views/user/laporan/cetaklaporantahunan.php',
    'laporanobat' => 'views/user/laporan/laporanobat.php',
    'cetaklaporanobat' => 'views/user/laporan/cetaklaporanobat.php',
    'laporanantrian' => 'views/user/laporan/laporanantrian.php',
    'cetaklaporanantrian' => 'views/user/laporan/cetaklaporanantrian.php',

    /* Zona pasien */
    'profilpasien' => 'views/pasien/profil.php',
    'editprofilpasien' => 'views/pasien/editprofil.php',
    'antrianpasien' => 'views/pasien/antrian.php',
    'riwayatkunjunganpasien' => 'views/pasien/riwayatkunjungan.php',
    'detailkunjunganpasien' => 'views/pasien/detailkunjungan.php',

    /* Zona dokter */
    'profildokter' => 'views/dokter/profil.php',
    'jadwalkunjungan' => 'views/dokter/jadwalkunjungan.php',
    'antrianpasiendokter' => 'views/dokter/antrianpasien.php',
    'detailkunjungandokter' => 'views/dokter/detailkunjungan.php',

    /* Halaman error */
    '403' => 'views/errors/403.php',
    '404' => 'views/errors/404.php',
    '500' => 'views/errors/500.php'
];

/* =========================================================
   11. MEMERIKSA FILE TUJUAN
   ========================================================= */

if (!isset($routes[$page])) {
    $page = '404';
}

$fileHalaman = APP_ROOT . '/' . $routes[$page];

if (!is_file($fileHalaman)) {
    catatError('File halaman tidak ditemukan: ' . $routes[$page]);
    $page = '404';
    $fileHalaman = APP_ROOT . '/' . $routes[$page];
}

/* =========================================================
   12. MENENTUKAN LAYOUT
   ========================================================= */

$isPublic = (
    in_array($page, $halamanPublik, true) ||
    $page === 'logout'
);

$bodyClass = $isPublic
    ? 'hold-transition layout-top-nav'
    : 'hold-transition sidebar-mini layout-fixed';

$navbarFile = 'views/component/tamu/navbar.php';
$sidebarFile = null;
$footerFile = 'views/component/tamu/footer.php';

if ($role === 'admin' || $role === 'petugas') {
    $navbarFile = 'views/component/user/navbar.php';
    $sidebarFile = $role === 'admin'
        ? 'views/component/user/sidebaradmin.php'
        : 'views/component/user/sidebarpetugas.php';
    $footerFile = 'views/component/user/footer.php';
} elseif ($role === 'pasien') {
    $navbarFile = 'views/component/pasien/navbar.php';
    $sidebarFile = 'views/component/pasien/sidebar.php';
    $footerFile = 'views/component/pasien/footer.php';
} elseif ($role === 'dokter') {
    $navbarFile = 'views/component/dokter/navbar.php';
    $sidebarFile = 'views/component/dokter/sidebar.php';
    $footerFile = 'views/component/dokter/footer.php';
}

/* Halaman cetak dibuat tanpa layout utama */
$halamanCetak = [
    'cetaklaporanharian',
    'cetaklaporanbulanan',
    'cetaklaporantahunan',
    'cetaklaporanobat',
    'cetaklaporanantrian'
];

$tanpaLayout = in_array($page, $halamanCetak, true);

/* =========================================================
   13. MEMUAT KOMPONEN DAN HALAMAN
   ========================================================= */

if (!$tanpaLayout) {
    $fileHeader = APP_ROOT . '/views/component/header.php';

    if (is_file($fileHeader)) {
        try {
            include $fileHeader;
        } catch (Throwable $e) {
            catatError('Header gagal dimuat: ' . $e->getMessage());
        }
    } else {
        catatError('views/component/header.php tidak ditemukan.');
    }

    $fileNavbar = APP_ROOT . '/' . $navbarFile;

    if (is_file($fileNavbar)) {
        try {
            include $fileNavbar;
        } catch (Throwable $e) {
            catatError('Navbar gagal dimuat: ' . $e->getMessage());
        }
    } else {
        catatError('File navbar tidak ditemukan: ' . $navbarFile);
    }

    if ($sidebarFile !== null) {
        $fileSidebar = APP_ROOT . '/' . $sidebarFile;

        if (is_file($fileSidebar)) {
            try {
                include $fileSidebar;
            } catch (Throwable $e) {
                catatError('Sidebar gagal dimuat: ' . $e->getMessage());
            }
        } else {
            catatError('File sidebar tidak ditemukan: ' . $sidebarFile);
        }
    }

    echo '<div class="content-wrapper">';

    try {
        include $fileHalaman;
    } catch (Throwable $e) {
        catatError(
            'Halaman ' . $page . ' gagal dimuat: ' . $e->getMessage()
        );

        http_response_code(500);
        echo '<section class="content"><div class="container-fluid pt-3">';
        echo '<div class="alert alert-danger">';
        echo '<h5>Halaman tidak dapat ditampilkan.</h5>';
        echo 'Terjadi kesalahan saat memuat halaman. Periksa log PHP.';
        echo '</div></div></section>';
    }

    echo '</div>';

    $fileFooter = APP_ROOT . '/' . $footerFile;

    if (is_file($fileFooter)) {
        try {
            include $fileFooter;
        } catch (Throwable $e) {
            catatError('Footer gagal dimuat: ' . $e->getMessage());
        }
    } else {
        catatError('File footer tidak ditemukan: ' . $footerFile);
    }

    $fileFooterJS = APP_ROOT . '/views/component/footerjs.php';

    if (is_file($fileFooterJS)) {
        try {
            include $fileFooterJS;
        } catch (Throwable $e) {
            catatError('Footer JS gagal dimuat: ' . $e->getMessage());
        }
    } else {
        catatError('views/component/footerjs.php tidak ditemukan.');
    }
} else {
    try {
        include $fileHalaman;
    } catch (Throwable $e) {
        catatError(
            'Halaman cetak ' . $page . ' gagal dimuat: ' . $e->getMessage()
        );

        http_response_code(500);
        echo 'Halaman cetak gagal dimuat. Periksa log PHP.';
    }
}

/* =========================================================
   14. SELESAI
   ========================================================= */

ob_end_flush();
