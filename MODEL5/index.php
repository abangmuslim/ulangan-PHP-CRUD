
<?php
session_start();
include "proses/koneksi.php";

$page = $_GET['page'] ?? 'home';

// Halaman yang boleh dibuka tanpa login
$halamanPublik = [
    'home', 'daftardokter', 'detildokter', 'daftarobat', 'detilobat', 'daftarkategori', 'detilkategori',
    'tentang', 'layanan', 'kontak',
    'loginuser', 'loginpasien', 'logindokter', 'registerpasien'
];

// Tentukan role yang sedang login
$role = '';

if (isset($_SESSION['iduser'])) {
    $role = $_SESSION['role'] ?? '';
} elseif (isset($_SESSION['idpasien'])) {
    $role = 'pasien';
} elseif (isset($_SESSION['iddokter'])) {
    $role = 'dokter';
}

// Jika belum login, hanya halaman publik yang boleh dibuka
if ($role == '' && !in_array($page, $halamanPublik)) {
    header("location:index.php?page=loginuser");
    exit;
}

// Jika sudah login, arahkan halaman login ke dashboard masing-masing
if ($role != '' && in_array($page, [
    'loginuser', 'loginpasien', 'logindokter', 'registerpasien'
])) {
    if ($role == 'admin') {
        header("location:index.php?page=dashboardadmin");
    } elseif ($role == 'petugas') {
        header("location:index.php?page=dashboardpetugas");
    } elseif ($role == 'pasien') {
        header("location:index.php?page=dashboardpasien");
    } elseif ($role == 'dokter') {
        header("location:index.php?page=dashboarddokter");
    } else {
        header("location:index.php?page=403");
    }
    exit;
}

// Tentukan zona halaman berdasarkan role
$halamanUser = [
    'dashboardadmin', 'dashboardpetugas',
    'user', 'createuser', 'edituser', 'showuser',
    'pasien', 'createpasien', 'editpasien', 'showpasien',
    'dokter', 'createdokter', 'editdokter', 'showdokter',
    'merk', 'createmerk', 'editmerk', 'showmerk',
    'kategori', 'createkategori', 'editkategori', 'showkategori',
    'obat', 'createobat', 'editobat', 'showobat',
    'jadwaldokter', 'createjadwaldokter',
    'editjadwaldokter', 'showjadwaldokter',
    'kunjungan', 'createkunjungan', 'editkunjungan', 'showkunjungan',
    'detilkunjungan', 'createdetilkunjungan',
    'editdetilkunjungan', 'showdetilkunjungan',
    'antrian', 'createantrian', 'editantrian', 'showantrian',
    'laporanharian', 'cetaklaporanharian',
    'laporanbulanan', 'cetaklaporanbulanan',
    'laporantahunan', 'cetaklaporantahunan',
    'laporanobat', 'cetaklaporanobat',
    'laporanantrian', 'cetaklaporanantrian'
];

$halamanPasien = [
    'dashboardpasien', 'profilpasien', 'editprofilpasien',
    'antrianpasien', 'riwayatkunjunganpasien',
    'detailkunjunganpasien'
];

$halamanDokter = [
    'dashboarddokter', 'profildokter',
    'jadwalkunjungan', 'antrianpasiendokter',
    'detailkunjungandokter'
];

// Tentukan class body berdasarkan jenis halaman
$isPublic =
    in_array($page, $halamanPublik) ||
    $page === 'logout';

if ($isPublic) {
    $bodyClass = 'hold-transition layout-top-nav';
} else {
    $bodyClass = 'hold-transition sidebar-mini layout-fixed';
}

// Batasi akses halaman zona user
if (in_array($page, $halamanUser)) {
    if (!in_array($role, ['admin', 'petugas'])) {
        header("location:index.php?page=403");
        exit;
    }

    // Manajemen user hanya boleh diakses admin
    if (in_array($page, [
        'user', 'createuser', 'edituser', 'showuser'
    ]) && $role != 'admin') {
        header("location:index.php?page=403");
        exit;
    }
}

// Batasi akses zona pasien
if (in_array($page, $halamanPasien) && $role != 'pasien') {
    header("location:index.php?page=403");
    exit;
}

// Batasi akses zona dokter
if (in_array($page, $halamanDokter) && $role != 'dokter') {
    header("location:index.php?page=403");
    exit;
}

// Tentukan komponen tampilan
if ($role == 'admin' || $role == 'petugas') {
    include "views/component/header.php";
    include "views/component/user/navbar.php";

    if ($role == 'admin') {
        include "views/component/user/sidebaradmin.php";
    } else {
        include "views/component/user/sidebarpetugas.php";
    }
} elseif ($role == 'pasien') {
    include "views/component/header.php";
    include "views/component/pasien/navbar.php";
    include "views/component/pasien/sidebar.php";
} elseif ($role == 'dokter') {
    include "views/component/header.php";
    include "views/component/dokter/navbar.php";
    include "views/component/dokter/sidebar.php";
} else {
    include "views/component/header.php";
    include "views/component/tamu/navbar.php";
}
?>

<?php
switch ($page) {

    // HALAMAN PUBLIK
    case 'home':
        include "views/landing/home.php";
        break;
    case 'daftardokter':
        include "views/landing/daftardokter.php";
        break;
    case 'detildokter':
        include "views/landing/detildokter.php";
        break;
    case 'daftarobat':
        include "views/landing/daftarobat.php";
        break;
    case 'detilobat':
        include "views/landing/detilobat.php";
        break;
    case 'daftarkategori':
        include "views/landing/daftarkategori.php";
        break;
    case 'detilkategori':
        include "views/landing/detilkategori.php";
        break;
    case 'tentang':
        include "views/landing/tentang.php";
        break;
    case 'layanan':
        include "views/landing/layanan.php";
        break;
    case 'kontak':
        include "views/landing/kontak.php";
        break;

    // AUTENTIKASI
    case 'loginuser':
        include "views/auth/loginuser.php";
        break;
    case 'loginpasien':
        include "views/auth/loginpasien.php";
        break;
    case 'logindokter':
        include "views/auth/logindokter.php";
        break;
    case 'registerpasien':
        include "views/auth/registerpasien.php";
        break;
    case 'logout':
        include "views/auth/logout.php";
        break;

    // DASHBOARD ADMIN DAN PETUGAS
    case 'dashboardadmin':
        include "views/user/dashboard/dashboardadmin.php";
        break;
    case 'dashboardpetugas':
        include "views/user/dashboard/dashboardpetugas.php";
        break;

    // CRUD USER
    case 'user':
        include "views/user/user/index.php";
        break;
    case 'createuser':
        include "views/user/user/create.php";
        break;
    case 'edituser':
        include "views/user/user/edit.php";
        break;
    case 'showuser':
        include "views/user/user/show.php";
        break;

    // CRUD PASIEN
    case 'pasien':
        include "views/user/pasien/index.php";
        break;
    case 'createpasien':
        include "views/user/pasien/create.php";
        break;
    case 'editpasien':
        include "views/user/pasien/edit.php";
        break;
    case 'showpasien':
        include "views/user/pasien/show.php";
        break;

    // CRUD DOKTER
    case 'dokter':
        include "views/user/dokter/index.php";
        break;
    case 'createdokter':
        include "views/user/dokter/create.php";
        break;
    case 'editdokter':
        include "views/user/dokter/edit.php";
        break;
    case 'showdokter':
        include "views/user/dokter/show.php";
        break;

    // CRUD MERK
    case 'merk':
        include "views/user/merk/index.php";
        break;
    case 'createmerk':
        include "views/user/merk/create.php";
        break;
    case 'editmerk':
        include "views/user/merk/edit.php";
        break;
    case 'showmerk':
        include "views/user/merk/show.php";
        break;

    // CRUD KATEGORI
    case 'kategori':
        include "views/user/kategori/index.php";
        break;
    case 'createkategori':
        include "views/user/kategori/create.php";
        break;
    case 'editkategori':
        include "views/user/kategori/edit.php";
        break;
    case 'showkategori':
        include "views/user/kategori/show.php";
        break;

    // CRUD OBAT
    case 'obat':
        include "views/user/obat/index.php";
        break;
    case 'createobat':
        include "views/user/obat/create.php";
        break;
    case 'editobat':
        include "views/user/obat/edit.php";
        break;
    case 'showobat':
        include "views/user/obat/show.php";
        break;

    // CRUD JADWAL DOKTER
    case 'jadwaldokter':
        include "views/user/jadwaldokter/index.php";
        break;
    case 'createjadwaldokter':
        include "views/user/jadwaldokter/create.php";
        break;
    case 'editjadwaldokter':
        include "views/user/jadwaldokter/edit.php";
        break;
    case 'showjadwaldokter':
        include "views/user/jadwaldokter/show.php";
        break;

    // CRUD KUNJUNGAN
    case 'kunjungan':
        include "views/user/kunjungan/index.php";
        break;
    case 'createkunjungan':
        include "views/user/kunjungan/create.php";
        break;
    case 'editkunjungan':
        include "views/user/kunjungan/edit.php";
        break;
    case 'showkunjungan':
        include "views/user/kunjungan/show.php";
        break;

    // CRUD DETAIL KUNJUNGAN
    case 'detilkunjungan':
        include "views/user/detilkunjungan/index.php";
        break;
    case 'createdetilkunjungan':
        include "views/user/detilkunjungan/create.php";
        break;
    case 'editdetilkunjungan':
        include "views/user/detilkunjungan/edit.php";
        break;
    case 'showdetilkunjungan':
        include "views/user/detilkunjungan/show.php";
        break;

    // CRUD ANTRIAN
    case 'antrian':
        include "views/user/antrian/index.php";
        break;
    case 'createantrian':
        include "views/user/antrian/create.php";
        break;
    case 'editantrian':
        include "views/user/antrian/edit.php";
        break;
    case 'showantrian':
        include "views/user/antrian/show.php";
        break;

    // LAPORAN
    case 'laporanharian':
        include "views/user/laporan/laporanharian.php";
        break;
    case 'cetaklaporanharian':
        include "views/user/laporan/cetaklaporanharian.php";
        break;
    case 'laporanbulanan':
        include "views/user/laporan/laporanbulanan.php";
        break;
    case 'cetaklaporanbulanan':
        include "views/user/laporan/cetaklaporanbulanan.php";
        break;
    case 'laporantahunan':
        include "views/user/laporan/laporantahunan.php";
        break;
    case 'cetaklaporantahunan':
        include "views/user/laporan/cetaklaporantahunan.php";
        break;
    case 'laporanobat':
        include "views/user/laporan/laporanobat.php";
        break;
    case 'cetaklaporanobat':
        include "views/user/laporan/cetaklaporanobat.php";
        break;
    case 'laporanantrian':
        include "views/user/laporan/laporanantrian.php";
        break;
    case 'cetaklaporanantrian':
        include "views/user/laporan/cetaklaporanantrian.php";
        break;

    // ZONA PASIEN
    case 'dashboardpasien':
        include "views/pasien/dashboardpasien.php";
        break;
    case 'profilpasien':
        include "views/pasien/profil.php";
        break;
    case 'editprofilpasien':
        include "views/pasien/editprofil.php";
        break;
    case 'antrianpasien':
        include "views/pasien/antrian.php";
        break;
    case 'riwayatkunjunganpasien':
        include "views/pasien/riwayatkunjungan.php";
        break;
    case 'detailkunjunganpasien':
        include "views/pasien/detailkunjungan.php";
        break;

    // ZONA DOKTER
    case 'dashboarddokter':
        include "views/dokter/dashboarddokter.php";
        break;
    case 'profildokter':
        include "views/dokter/profil.php";
        break;
    case 'jadwalkunjungan':
        include "views/dokter/jadwalkunjungan.php";
        break;
    case 'antrianpasiendokter':
        include "views/dokter/antrianpasien.php";
        break;
    case 'detailkunjungandokter':
        include "views/dokter/detailkunjungan.php";
        break;

    // HALAMAN ERROR
    case '403':
        include "views/errors/403.php";
        break;
    case '404':
        include "views/errors/404.php";
        break;
    case '500':
        include "views/errors/500.php";
        break;

    default:
        include "views/errors/404.php";
        break;
}
?>

<?php
// Komponen penutup berdasarkan zona
if ($role == 'admin' || $role == 'petugas') {
    include "views/component/user/footer.php";
} elseif ($role == 'pasien') {
    include "views/component/pasien/footer.php";
} elseif ($role == 'dokter') {
    include "views/component/dokter/footer.php";
} else {
    include "views/component/tamu/footer.php";
}

include "views/component/footerjs.php";
?>
