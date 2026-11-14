
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// ============================================
// 1. MEMANGGIL FILE DASAR
// ============================================
require_once "proses/main/session.php";
require_once "proses/main/koneksi.php";
require_once "proses/main/helper.php";

// ============================================
// 2. MENENTUKAN ROLE PENGGUNA
// ============================================
$jumlahSession = 0;

if (isset($_SESSION['iduser'])) {
    $jumlahSession++;
}

if (isset($_SESSION['iddokter'])) {
    $jumlahSession++;
}

if (isset($_SESSION['idpasien'])) {
    $jumlahSession++;
}

// Tidak boleh memiliki lebih dari satu jenis session
if ($jumlahSession > 1) {
    http_response_code(403);
    exit("Akses ditolak. Session tidak valid.");
}

if (isset($_SESSION['iduser'])) {

    $role = $_SESSION['level'] ?? '';

    if (!in_array($role, ['admin', 'petugas'])) {
        http_response_code(403);
        exit("Level pengguna tidak valid.");
    }

} elseif (isset($_SESSION['iddokter'])) {

    $role = 'dokter';

} elseif (isset($_SESSION['idpasien'])) {

    $role = 'pasien';

} else {

    $role = 'tamu';
}


// ============================================
// 3. MEMBACA PARAMETER HALAMAN
// ============================================
$page = $_GET['page'] ?? 'home';

if (!is_string($page)) {
    $page = '';
}


// ============================================
// DISPATCHER PROSES POST
// ============================================

require_once 'proses/main/dispatcher.php';

// ============================================
// 4. MENANGANI LOGOUT
// ============================================
if ($page === 'logout') {
    require_once "proses/main/logout.php";
    exit;
}

// ============================================
// 5. MENYIAPKAN ROUTING
// ============================================
$routes = [];

// Fungsi untuk mendaftarkan halaman
function tambahRoute($page, $view, $akses, $zona) {
    global $routes;

    $routes[$page] = [
        'view'  => $view,
        'akses' => $akses,
        'zona'  => $zona
    ];
}

// ============================================
// 6. HALAMAN PUBLIC / LANDING
// ============================================
$halamanPublic = [
    'home'          => 'home.php',
    'daftardokter'  => 'daftardokter.php',
    'detildokter'   => 'detildokter.php',
    'daftarobat'    => 'daftarobat.php',
    'detilobat'     => 'detilobat.php',
    'daftarkategori'=> 'daftarkategori.php',
    'detilkategori' => 'detilkategori.php',
    'tentang'       => 'tentang.php',
    'layanan'       => 'layanan.php',
    'kontak'        => 'kontak.php'
];

foreach ($halamanPublic as $nama => $file) {
    tambahRoute(
        $nama,
        "views/landing/$file",
        ['tamu'],
        'public'
    );
}

// ============================================
// 7. HALAMAN AUTENTIKASI
// ============================================
$halamanAuth = [
    'loginuser'      => 'loginuser.php',
    'loginpasien'    => 'loginpasien.php',
    'logindokter'    => 'logindokter.php',
    'registerpasien' => 'registerpasien.php'
];

foreach ($halamanAuth as $nama => $file) {
    tambahRoute(
        $nama,
        "views/auth/$file",
        ['tamu'],
        'auth'
    );
}


// ============================================
// 8. DASHBOARD ADMIN DAN PETUGAS
// ============================================
tambahRoute(
    'dashboardadmin',
    'views/user/dashboard/dashboardadmin.php',
    ['admin'],
    'user'
);

tambahRoute(
    'dashboardpetugas',
    'views/user/dashboard/dashboardpetugas.php',
    ['petugas'],
    'user'
);

// ============================================
// 9. CRUD ZONA ADMIN DAN PETUGAS
// ============================================

// Fungsi untuk membuat routing CRUD
function tambahCRUD($modul, $akses = ['admin', 'petugas'], $aksi = ['index', 'create', 'edit', 'show']) {

    foreach ($aksi as $jenis) {

        if ($jenis === 'index') {
            $page = $modul;
        } else {
            $page = $jenis . $modul;
        }

        tambahRoute(
            $page,
            "views/user/$modul/$jenis.php",
            $akses,
            'user'
        );
    }
}

// MASTER DATA
tambahCRUD('user', ['admin']);
tambahCRUD('pasien', ['admin', 'petugas']);
tambahCRUD('dokter', ['admin']);
tambahCRUD('merk', ['admin']);
tambahCRUD('kategori', ['admin']);
tambahCRUD('obat', ['admin', 'petugas']);
tambahCRUD('jadwaldokter', ['admin']);

// ============================================
// 10. PENDAFTARAN CHECKUP
// ============================================
tambahRoute(
    'pendaftarancheckup',
    'views/user/pendaftarancheckup/index.php',
    ['admin', 'petugas'],
    'user'
);

tambahRoute(
    'creatependaftarancheckup',
    'views/user/pendaftarancheckup/create.php',
    ['admin', 'petugas'],
    'user'
);

tambahRoute(
    'showpendaftarancheckup',
    'views/user/pendaftarancheckup/show.php',
    ['admin', 'petugas'],
    'user'
);

// ============================================
// 11. ANTRIAN
// ============================================
tambahRoute(
    'antrian',
    'views/user/antrian/index.php',
    ['admin', 'petugas'],
    'user'
);

tambahRoute(
    'showantrian',
    'views/user/antrian/show.php',
    ['admin', 'petugas'],
    'user'
);

tambahRoute(
    'panggilanantrian',
    'views/user/antrian/panggilan.php',
    ['admin', 'petugas'],
    'user'
);

// ============================================
// 12. KUNJUNGAN
// ============================================
tambahCRUD('kunjungan', ['admin', 'petugas']);

tambahCRUD(
    'detilkunjungan',
    ['admin', 'petugas']
);

// ============================================
// 13. KASIR
// ============================================
tambahRoute(
    'kasir',
    'views/user/kasir/index.php',
    ['admin', 'petugas'],
    'user'
);

tambahRoute(
    'showkasir',
    'views/user/kasir/show.php',
    ['admin', 'petugas'],
    'user'
);

tambahRoute(
    'cetakstruk',
    'views/user/kasir/cetakstruk.php',
    ['admin', 'petugas'],
    'user'
);

// ============================================
// 14. LAPORAN
// ============================================
$halamanLaporan = [
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

foreach ($halamanLaporan as $nama) {

    tambahRoute(
        $nama,
        "views/user/laporan/$nama.php",
        ['admin'],
        'user'
    );
}

// ============================================
// 15. ZONA DOKTER
// ============================================
$halamanDokter = [
    'dashboarddokter' => 'dashboarddokter.php',
    'profil'          => 'profil.php',
    'editprofil'      => 'editprofil.php',
    'jadwalkunjungan' => 'jadwalkunjungan.php'
];

foreach ($halamanDokter as $nama => $file) {

    tambahRoute(
        $nama,
        "views/dokter/$file",
        ['dokter'],
        'dokter'
    );
}

// ANTRIAN DOKTER
tambahRoute(
    'antriandokter',
    'views/dokter/antrian/index.php',
    ['dokter'],
    'dokter'
);

tambahRoute(
    'showantriandokter',
    'views/dokter/antrian/show.php',
    ['dokter'],
    'dokter'
);

// PEMERIKSAAN DOKTER
tambahRoute(
    'pemeriksaan',
    'views/dokter/pemeriksaan/index.php',
    ['dokter'],
    'dokter'
);

tambahRoute(
    'createpemeriksaan',
    'views/dokter/pemeriksaan/create.php',
    ['dokter'],
    'dokter'
);

tambahRoute(
    'showpemeriksaan',
    'views/dokter/pemeriksaan/show.php',
    ['dokter'],
    'dokter'
);

// RESEP DOKTER
tambahRoute(
    'resep',
    'views/dokter/resep/index.php',
    ['dokter'],
    'dokter'
);

tambahRoute(
    'createresep',
    'views/dokter/resep/create.php',
    ['dokter'],
    'dokter'
);

tambahRoute(
    'editresep',
    'views/dokter/resep/edit.php',
    ['dokter'],
    'dokter'
);


// ============================================
// JADWAL PRAKTIK DOKTER
// ============================================

tambahRoute(
    'jadwaldokterdokter',
    'views/dokter/jadwaldokter/index.php',
    ['dokter'],
    'dokter'
);

tambahRoute(
    'createjadwaldokterdokter',
    'views/dokter/jadwaldokter/create.php',
    ['dokter'],
    'dokter'
);

tambahRoute(
    'editjadwaldokterdokter',
    'views/dokter/jadwaldokter/edit.php',
    ['dokter'],
    'dokter'
);

tambahRoute(
    'showjadwaldokterdokter',
    'views/dokter/jadwaldokter/show.php',
    ['dokter'],
    'dokter'
);


// ============================================
// 16. ZONA PASIEN
// ============================================
$halamanPasien = [
    'dashboardpasien'     => 'dashboardpasien.php',
    'profilpasien'        => 'profil.php',
    'editprofilpasien'    => 'editprofil.php',
    'riwayatkunjungan'    => 'riwayatkunjungan.php',
    'detilkunjunganpasien'=> 'detilkunjungan.php',
    'daftardokterpasien'  => 'daftardokter.php',
    'informasiobat'       => 'informasiobat.php'
];

foreach ($halamanPasien as $nama => $file) {

    tambahRoute(
        $nama,
        "views/pasien/$file",
        ['pasien'],
        'pasien'
    );
}

// PENDAFTARAN PASIEN
tambahRoute(
    'pendaftaranpasien',
    'views/pasien/pendaftaran/index.php',
    ['pasien'],
    'pasien'
);

tambahRoute(
    'creatependaftaranpasien',
    'views/pasien/pendaftaran/create.php',
    ['pasien'],
    'pasien'
);

tambahRoute(
    'showpendaftaranpasien',
    'views/pasien/pendaftaran/show.php',
    ['pasien'],
    'pasien'
);

// ANTRIAN PASIEN
tambahRoute(
    'antrianpasien',
    'views/pasien/antrian/index.php',
    ['pasien'],
    'pasien'
);

tambahRoute(
    'showantrianpasien',
    'views/pasien/antrian/show.php',
    ['pasien'],
    'pasien'
);

// ============================================
// 17. MENENTUKAN HALAMAN YANG DIPANGGIL
// ============================================
if (isset($routes[$page])) {

    $view  = $routes[$page]['view'];
    $akses = $routes[$page]['akses'];
    $zona  = $routes[$page]['zona'];

} else {

    $view  = 'views/errors/404.php';
    $akses = ['tamu', 'admin', 'petugas', 'dokter', 'pasien'];
    $zona  = 'public';

    http_response_code(404);
}

// ============================================
// 18. MEMERIKSA HAK AKSES
// ============================================
if (!in_array($role, $akses)) {

    http_response_code(403);

    $view = 'views/errors/403.php';

    // Halaman error mengikuti layout role yang sedang login
    if ($role === 'admin' || $role === 'petugas') {
        $zona = 'user';
    } elseif ($role === 'dokter') {
        $zona = 'dokter';
    } elseif ($role === 'pasien') {
        $zona = 'pasien';
    } else {
        $zona = 'public';
    }
}

// ============================================
// 19. MEMERIKSA KEBERADAAN FILE
// ============================================
if (!file_exists($view)) {

    http_response_code(404);

    $view = 'views/errors/404.php';
}

// ============================================
// 20. PENGALIHAN JIKA SUDAH LOGIN
// ============================================
if ($zona === 'auth' && $role !== 'tamu') {

    if ($role === 'admin') {
        header("Location: index.php?page=dashboardadmin");
    } elseif ($role === 'petugas') {
        header("Location: index.php?page=dashboardpetugas");
    } elseif ($role === 'dokter') {
        header("Location: index.php?page=dashboarddokter");
    } elseif ($role === 'pasien') {
        header("Location: index.php?page=dashboardpasien");
    }

    exit;
}
// ============================================
// 20A. PROSES DASHBOARD ADMIN DAN PETUGAS
// ============================================

if (
    $zona === 'user' &&
    in_array($page, ['dashboardadmin', 'dashboardpetugas'], true) &&
    in_array($role, $akses, true)
) {
    require_once "proses/dashboard/dashboarduser.php";
}

// ============================================
// 21. MEMANGGIL HEADER
// ============================================
require_once "views/component/header.php";

// ============================================
// 22. MEMANGGIL NAVBAR SESUAI ZONA
// ============================================
if ($zona === 'public' || $zona === 'auth') {

    require_once "views/component/tamu/navbar.php";

} elseif ($role === 'admin') {

    require_once "views/component/user/navbaradmin.php";

} elseif ($role === 'petugas') {

    require_once "views/component/user/navbarpetugas.php";

} elseif ($role === 'dokter') {

    require_once "views/component/dokter/navbar.php";

} elseif ($role === 'pasien') {

    require_once "views/component/pasien/navbar.php";
}

// ============================================
// 23. MEMANGGIL SIDEBAR DAN KONTEN
// ============================================

// ZONA PUBLIC DAN AUTH
if ($zona === 'public' || $zona === 'auth') {
?>

    <div class="content-wrapper">
        <main class="content">
            <?php require $view; ?>
        </main>
    </div>

<?php
}

// ZONA ADMIN DAN PETUGAS
elseif ($zona === 'user') {

    if ($role === 'admin') {
        require_once "views/component/user/sidebaradmin.php";
    } else {
        require_once "views/component/user/sidebarpetugas.php";
    }
?>

    <div class="content-wrapper">
        <main class="content">
            <div class="container-fluid pt-3">
                <?php require $view; ?>
            </div>
        </main>
    </div>

<?php
}

// ZONA DOKTER
elseif ($zona === 'dokter') {

    require_once "views/component/dokter/sidebar.php";
?>

    <div class="content-wrapper">
        <main class="content">
            <div class="container-fluid pt-3">
                <?php require $view; ?>
            </div>
        </main>
    </div>

<?php
}

// ZONA PASIEN
elseif ($zona === 'pasien') {

    require_once "views/component/pasien/sidebar.php";
?>

    <div class="content-wrapper">
        <main class="content">
            <div class="container-fluid pt-3">
                <?php require $view; ?>
            </div>
        </main>
    </div>

<?php
}

// ============================================
// 24. MEMANGGIL FOOTER SESUAI ZONA
// ============================================
if ($zona === 'public' || $zona === 'auth') {

    require_once "views/component/tamu/footer.php";

} elseif ($zona === 'user') {

    require_once "views/component/user/footer.php";

} elseif ($zona === 'dokter') {

    require_once "views/component/dokter/footer.php";

} elseif ($zona === 'pasien') {

    require_once "views/component/pasien/footer.php";
}

// ============================================
// 25. MEMANGGIL JAVASCRIPT
// ============================================
require_once "views/component/footerjs.php";
?>