<?php
require_once __DIR__ . '/proses/koneksi.php';
require_once __DIR__ . '/proses/helper.php';
require_once __DIR__ . '/proses/session.php';

// ==============================
// PENGATURAN ROUTER
// ==============================
$page = isset($_GET['page']) && is_string($_GET['page'])
    ? strtolower(trim($_GET['page']))
    : 'home';

$aksi = isset($_GET['aksi']) && is_string($_GET['aksi'])
    ? strtolower(trim($_GET['aksi']))
    : 'index';

$aksiDiizinkan = ['index', 'create', 'edit', 'show'];
if (!in_array($aksi, $aksiDiizinkan, true)) {
    $aksi = 'index';
}

$zona = 'tamu';
$fileHalaman = '';
$judul = 'Klinik';
$roleDiizinkan = [];
$halamanProses = '';

// ==============================
// DAFTAR HALAMAN PUBLIK
// ==============================
$halamanPublik = [
    'home' => ['landing/home.php', 'Beranda'],
    'daftardokter' => ['landing/daftardokter.php', 'Daftar Dokter'],
    'detildokter' => ['landing/detildokter.php', 'Detail Dokter'],
    'daftarobat' => ['landing/daftarobat.php', 'Daftar Obat'],
    'detilobat' => ['landing/detilobat.php', 'Detail Obat'],
    'daftarkategori' => ['landing/daftarkategori.php', 'Daftar Kategori'],
    'detilkategori' => ['landing/detilkategori.php', 'Detail Kategori'],
    'tentang' => ['landing/tentang.php', 'Tentang Kami'],
    'layanan' => ['landing/layanan.php', 'Layanan'],
    'kontak' => ['landing/kontak.php', 'Kontak'],
];

// ==============================
// DAFTAR HALAMAN AUTENTIKASI
// ==============================
$halamanAuth = [
    'loginuser' => ['auth/loginuser.php', 'Login Admin/Petugas'],
    'loginpasien' => ['auth/loginpasien.php', 'Login Pasien'],
    'logindokter' => ['auth/logindokter.php', 'Login Dokter'],
    'registerpasien' => ['auth/registerpasien.php', 'Registrasi Pasien'],
];

// ==============================
// DAFTAR HALAMAN ERROR
// ==============================
$halamanError = [
    '403' => ['errors/403.php', 'Akses Ditolak'],
    '404' => ['errors/404.php', 'Halaman Tidak Ditemukan'],
    '500' => ['errors/500.php', 'Kesalahan Server'],
];

// ==============================
// ROUTE PUBLIK
// ==============================
if (isset($halamanPublik[$page])) {
    [$path, $judul] = $halamanPublik[$page];
    $fileHalaman = __DIR__ . '/views/' . $path;
}

// ==============================
// ROUTE AUTENTIKASI
// ==============================
elseif (isset($halamanAuth[$page])) {
    [$path, $judul] = $halamanAuth[$page];
    $fileHalaman = __DIR__ . '/views/' . $path;

    if (sudahLogin()) {
        if (loginAdmin()) {
            redirect('index.php?page=dashboardadmin');
            exit;
        } elseif (loginPetugas()) {
            redirect('index.php?page=dashboardpetugas');
            exit;
        } elseif (loginPasien()) {
            redirect('index.php?page=dashboardpasien');
            exit;
        } elseif (loginDokter()) {
            redirect('index.php?page=dashboarddokter');
            exit;
        }
    }
}

// ==============================
// ROUTE ERROR
// ==============================
elseif (isset($halamanError[$page])) {
    [$path, $judul] = $halamanError[$page];
    $fileHalaman = __DIR__ . '/views/' . $path;
}

// ==============================
// ROUTE DASHBOARD ADMIN/PETUGAS
// ==============================
elseif (in_array($page, ['dashboardadmin', 'dashboardpetugas'], true)) {
    $zona = 'user';

    if ($page === 'dashboardadmin') {
        $judul = 'Dashboard Admin';
        $roleDiizinkan = ['admin'];
        $fileHalaman = __DIR__ . '/views/user/dashboard/dashboardadmin.php';
    } else {
        $judul = 'Dashboard Petugas';
        $roleDiizinkan = ['petugas'];
        $fileHalaman = __DIR__ . '/views/user/dashboard/dashboardpetugas.php';
    }
}

// ==============================
// ROUTE DASHBOARD PASIEN/DOKTER
// ==============================
elseif ($page === 'dashboardpasien') {
    $zona = 'pasien';
    $judul = 'Dashboard Pasien';
    $roleDiizinkan = ['pasien'];
    $fileHalaman = __DIR__ . '/views/pasien/dashboardpasien.php';
}
elseif ($page === 'dashboarddokter') {
    $zona = 'dokter';
    $judul = 'Dashboard Dokter';
    $roleDiizinkan = ['dokter'];
    $fileHalaman = __DIR__ . '/views/dokter/dashboarddokter.php';
}

// ==============================
// ROUTE MODUL ADMIN/PETUGAS
// ==============================
elseif (in_array($page, [
    'user', 'pasien', 'dokter', 'merk', 'kategori', 'obat',
    'jadwaldokter', 'kunjungan', 'detilkunjungan', 'antrian'
], true)) {
    $zona = 'user';
    $judul = ucfirst($page);
    $roleDiizinkan = ['admin', 'petugas'];

    if ($page === 'user') {
        $roleDiizinkan = ['admin'];
    }

    $folderModul = $page;
    $namaFile = $aksi === 'index' ? 'index.php' : $aksi . '.php';
    $fileHalaman = __DIR__ . '/views/user/' . $folderModul . '/' . $namaFile;
}

// ==============================
// ROUTE LAPORAN
// ==============================
elseif (in_array($page, [
    'laporanharian', 'laporanbulanan', 'laporantahunan',
    'laporanobat', 'laporanantrian'
], true)) {
    $zona = 'user';
    $judul = ucfirst($page);
    $roleDiizinkan = ['admin'];
    $fileHalaman = __DIR__ . '/views/user/laporan/' . $page . '.php';
}
elseif (in_array($page, [
    'cetaklaporanharian', 'cetaklaporanbulanan',
    'cetaklaporantahunan', 'cetaklaporanobat',
    'cetaklaporanantrian'
], true)) {
    $zona = 'cetak';
    $judul = ucfirst($page);
    $roleDiizinkan = ['admin'];
    $fileHalaman = __DIR__ . '/views/user/laporan/' . $page . '.php';
}

// ==============================
// ROUTE KHUSUS PASIEN
// ==============================
elseif (in_array($page, [
    'profil', 'editprofil', 'antrian', 'riwayatkunjungan',
    'detailkunjungan'
], true)) {
    $zona = 'pasien';
    $judul = ucfirst($page);
    $roleDiizinkan = ['pasien'];

    $daftarFilePasien = [
        'profil' => 'profil.php',
        'editprofil' => 'editprofil.php',
        'antrian' => 'antrian.php',
        'riwayatkunjungan' => 'riwayatkunjungan.php',
        'detailkunjungan' => 'detailkunjungan.php',
    ];

    $fileHalaman = __DIR__ . '/views/pasien/' . $daftarFilePasien[$page];
}

// ==============================
// ROUTE KHUSUS DOKTER
// ==============================
elseif (in_array($page, [
    'profildokter', 'jadwalkunjungan', 'antrianpasien',
    'detailkunjungandokter'
], true)) {
    $zona = 'dokter';
    $judul = ucfirst($page);
    $roleDiizinkan = ['dokter'];

    $daftarFileDokter = [
        'profildokter' => 'profil.php',
        'jadwalkunjungan' => 'jadwalkunjungan.php',
        'antrianpasien' => 'antrianpasien.php',
        'detailkunjungandokter' => 'detailkunjungan.php',
    ];

    $fileHalaman = __DIR__ . '/views/dokter/' . $daftarFileDokter[$page];
}

// ==============================
// HALAMAN TIDAK DIKENAL
// ==============================
else {
    http_response_code(404);
    $zona = 'tamu';
    $judul = 'Halaman Tidak Ditemukan';
    $fileHalaman = __DIR__ . '/views/errors/404.php';
}

// ==============================
// PEMROSESAN LOGIN
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $daftarProsesLogin = [
        'loginuser' => __DIR__ . '/proses/prosesloginuser.php',
        'loginpasien' => __DIR__ . '/proses/prosesloginpasien.php',
        'logindokter' => __DIR__ . '/proses/proseslogindokter.php',
    ];

    if (isset($daftarProsesLogin[$page])) {
        require $daftarProsesLogin[$page];
        exit;
    }
}

// ==============================
// PEMERIKSAAN AKSES
// ==============================
if (!empty($roleDiizinkan)) {
    if (!sudahLogin()) {
        $tujuanLogin = 'loginuser';

        if (in_array('pasien', $roleDiizinkan, true)) {
            $tujuanLogin = 'loginpasien';
        } elseif (in_array('dokter', $roleDiizinkan, true)) {
            $tujuanLogin = 'logindokter';
        }

        redirect('index.php?page=' . $tujuanLogin);
        exit;
    }

    $roleSekarang = '';

    if (loginAdmin()) {
        $roleSekarang = 'admin';
    } elseif (loginPetugas()) {
        $roleSekarang = 'petugas';
    } elseif (loginPasien()) {
        $roleSekarang = 'pasien';
    } elseif (loginDokter()) {
        $roleSekarang = 'dokter';
    }

    if (!in_array($roleSekarang, $roleDiizinkan, true)) {
        http_response_code(403);
        $zona = 'tamu';
        $judul = 'Akses Ditolak';
        $fileHalaman = __DIR__ . '/views/errors/403.php';
    }
}

// ==============================
// PEMROSESAN LOGOUT
// ==============================
if ($page === 'logout') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrfValid()) {
        http_response_code(403);
        exit('Permintaan logout tidak valid.');
    }

    require __DIR__ . '/proses/proseslogout.php';
    exit;
}

// ==============================
// HEADER DOKUMEN
// ==============================
require __DIR__ . '/views/component/header.php';

// ==============================
// LAYOUT DAN KONTEN
// ==============================
if ($zona === 'user') {
    echo '<div class="wrapper">';

    if (loginAdmin()) {
        require __DIR__ . '/views/component/user/navbaradmin.php';
        require __DIR__ . '/views/component/user/sidebaradmin.php';
    } else {
        require __DIR__ . '/views/component/user/navbarpetugas.php';
        require __DIR__ . '/views/component/user/sidebarpetugas.php';
    }

    echo '<div class="content-wrapper">';
    echo '<section class="content"><div class="container-fluid pt-3">';
    

    if (is_file($fileHalaman)) {
        require $fileHalaman;
    } else {
        http_response_code(404);
        require __DIR__ . '/views/errors/404.php';
    }

    echo '</div></section></div>';

    require __DIR__ . '/views/component/user/footer.php';
    echo '</div>';
}
elseif ($zona === 'pasien') {
    echo '<div class="wrapper">';
    require __DIR__ . '/views/component/pasien/navbar.php';
    require __DIR__ . '/views/component/pasien/sidebar.php';

    echo '<div class="content-wrapper">';
    echo '<section class="content"><div class="container-fluid pt-3">';

    if (is_file($fileHalaman)) {
        require $fileHalaman;
    } else {
        http_response_code(404);
        require __DIR__ . '/views/errors/404.php';
    }

    echo '</div></section></div>';

    require __DIR__ . '/views/component/pasien/footer.php';
    echo '</div>';
}
elseif ($zona === 'dokter') {
    echo '<div class="wrapper">';
    require __DIR__ . '/views/component/dokter/navbar.php';
    require __DIR__ . '/views/component/dokter/sidebar.php';

    echo '<div class="content-wrapper">';
    echo '<section class="content"><div class="container-fluid pt-3">';

    if (is_file($fileHalaman)) {
        require $fileHalaman;
    } else {
        http_response_code(404);
        require __DIR__ . '/views/errors/404.php';
    }

    echo '</div></section></div>';

    require __DIR__ . '/views/component/dokter/footer.php';
    echo '</div>';
}
elseif ($zona === 'cetak') {
    if (is_file($fileHalaman)) {
        require $fileHalaman;
    } else {
        http_response_code(404);
        require __DIR__ . '/views/errors/404.php';
    }
}
else {
    echo '<div class="wrapper">';
    require __DIR__ . '/views/component/tamu/navbar.php';

    echo '<div class="content-wrapper">';
    echo '<section class="content p-0">';

    if (is_file($fileHalaman)) {
        require $fileHalaman;
    } else {
        http_response_code(404);
        require __DIR__ . '/views/errors/404.php';
    }

    echo '</section></div>';

    require __DIR__ . '/views/component/tamu/footer.php';
    echo '</div>';
}

// ==============================
// JAVASCRIPT DAN PENUTUP HTML
// ==============================
require __DIR__ . '/views/component/footerjs.php';