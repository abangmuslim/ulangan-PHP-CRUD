
<?php
// ============================================
// 1. MEMANGGIL FILE DASAR
// ============================================
require_once "proses/session.php";
require_once "proses/koneksi.php";
require_once "proses/helper.php";


// ============================================
// 2. MENENTUKAN ROLE PENGGUNA
// ============================================
if (isset($_SESSION['iduser']) && isset($_SESSION['idpasien'])) {

    http_response_code(403);
    exit("Akses ditolak. Session pengguna tidak valid.");

} elseif (isset($_SESSION['iduser'])) {

    $role = "admin";

} elseif (isset($_SESSION['idpasien'])) {

    $role = "pasien";

} else {

    $role = "tamu";
}


// ============================================
// 3. MEMBACA PARAMETER HALAMAN
// ============================================
$page = $_GET['page'] ?? 'home';

if (!is_string($page)) {
    $page = '';
}


// ============================================
// 4. MENANGANI LOGOUT
// ============================================
if ($page === 'logout') {
    require_once "proses/proseslogout.php";
    exit;
}


// ============================================
// 5. MENENTUKAN HALAMAN DENGAN SWITCH CASE
// ============================================
$view = '';
$akses = '';

switch ($page) {

    // ----------------------------------------
    // HALAMAN PUBLIK
    // ----------------------------------------
    case 'home':
        $view = "views/landing/home.php";
        $akses = "semua";
        break;

    case 'daftardokter':
        $view = "views/landing/daftardokter.php";
        $akses = "semua";
        break;

    case 'detildokter':
        $view = "views/landing/detildokter.php";
        $akses = "semua";
        break;

    case 'daftarobat':
        $view = "views/landing/daftarobat.php";
        $akses = "semua";
        break;

    case 'detilobat':
        $view = "views/landing/detilobat.php";
        $akses = "semua";
        break;

    case 'daftarkategori':
        $view = "views/landing/daftarkategori.php";
        $akses = "semua";
        break;

    case 'detilkategori':
        $view = "views/landing/detilkategori.php";
        $akses = "semua";
        break;

    case 'tentang':
        $view = "views/landing/tentang.php";
        $akses = "semua";
        break;

    case 'layanan':
        $view = "views/landing/layanan.php";
        $akses = "semua";
        break;

    case 'kontak':
        $view = "views/landing/kontak.php";
        $akses = "semua";
        break;


    // ----------------------------------------
    // HALAMAN AUTENTIKASI
    // ----------------------------------------
    case 'loginuser':
        $view = "views/auth/loginuser.php";
        $akses = "tamu";
        break;

    case 'loginpasien':
        $view = "views/auth/loginpasien.php";
        $akses = "tamu";
        break;

    case 'registerpasien':
        $view = "views/auth/registerpasien.php";
        $akses = "tamu";
        break;


    // ----------------------------------------
    // DASHBOARD ADMIN
    // ----------------------------------------
    case 'dashboarduser':
        $view = "views/user/dashboard/dashboarduser.php";
        $akses = "admin";
        break;


    // ----------------------------------------
    // CRUD ADMIN: USER
    // ----------------------------------------
    case 'user':
        $view = "views/user/user/index.php";
        $akses = "admin";
        break;

    case 'createuser':
        $view = "views/user/user/create.php";
        $akses = "admin";
        break;

    case 'edituser':
        $view = "views/user/user/edit.php";
        $akses = "admin";
        break;

    case 'showuser':
        $view = "views/user/user/show.php";
        $akses = "admin";
        break;


    // ----------------------------------------
    // CRUD ADMIN: PASIEN
    // ----------------------------------------
    case 'pasien':
        $view = "views/user/pasien/index.php";
        $akses = "admin";
        break;

    case 'createpasien':
        $view = "views/user/pasien/create.php";
        $akses = "admin";
        break;

    case 'editpasien':
        $view = "views/user/pasien/edit.php";
        $akses = "admin";
        break;

    case 'showpasien':
        $view = "views/user/pasien/show.php";
        $akses = "admin";
        break;


    // ----------------------------------------
    // CRUD ADMIN: DOKTER
    // ----------------------------------------
    case 'dokter':
        $view = "views/user/dokter/index.php";
        $akses = "admin";
        break;

    case 'createdokter':
        $view = "views/user/dokter/create.php";
        $akses = "admin";
        break;

    case 'editdokter':
        $view = "views/user/dokter/edit.php";
        $akses = "admin";
        break;

    case 'showdokter':
        $view = "views/user/dokter/show.php";
        $akses = "admin";
        break;


    // ----------------------------------------
    // CRUD ADMIN: JADWAL DOKTER
    // ----------------------------------------
    case 'jadwaldokter':
        $view = "views/user/jadwaldokter/index.php";
        $akses = "admin";
        break;

    case 'createjadwaldokter':
        $view = "views/user/jadwaldokter/create.php";
        $akses = "admin";
        break;

    case 'editjadwaldokter':
        $view = "views/user/jadwaldokter/edit.php";
        $akses = "admin";
        break;

    case 'showjadwaldokter':
        $view = "views/user/jadwaldokter/show.php";
        $akses = "admin";
        break;


    // ----------------------------------------
    // CRUD ADMIN: MERK
    // ----------------------------------------
    case 'merk':
        $view = "views/user/merk/index.php";
        $akses = "admin";
        break;

    case 'createmerk':
        $view = "views/user/merk/create.php";
        $akses = "admin";
        break;

    case 'editmerk':
        $view = "views/user/merk/edit.php";
        $akses = "admin";
        break;

    case 'showmerk':
        $view = "views/user/merk/show.php";
        $akses = "admin";
        break;


    // ----------------------------------------
    // CRUD ADMIN: KATEGORI
    // ----------------------------------------
    case 'kategori':
        $view = "views/user/kategori/index.php";
        $akses = "admin";
        break;

    case 'createkategori':
        $view = "views/user/kategori/create.php";
        $akses = "admin";
        break;

    case 'editkategori':
        $view = "views/user/kategori/edit.php";
        $akses = "admin";
        break;

    case 'showkategori':
        $view = "views/user/kategori/show.php";
        $akses = "admin";
        break;


    // ----------------------------------------
    // CRUD ADMIN: OBAT
    // ----------------------------------------
    case 'obat':
        $view = "views/user/obat/index.php";
        $akses = "admin";
        break;

    case 'createobat':
        $view = "views/user/obat/create.php";
        $akses = "admin";
        break;

    case 'editobat':
        $view = "views/user/obat/edit.php";
        $akses = "admin";
        break;

    case 'showobat':
        $view = "views/user/obat/show.php";
        $akses = "admin";
        break;


    // ----------------------------------------
    // CRUD ADMIN: pendaftarancheckup
    // ----------------------------------------
    case 'pendaftarancheckupadmin':
        $view = "views/user/pendaftarancheckup/index.php";
        $akses = "admin";
        break;

    case 'showpendaftarancheckupadmin':
        $view = "views/user/pendaftarancheckup/show.php";
        $akses = "admin";
        break;

    // ----------------------------------------
    // CRUD ADMIN: KUNJUNGAN
    // ----------------------------------------
    case 'kunjungan':
        $view = "views/user/kunjungan/index.php";
        $akses = "admin";
        break;

    case 'createkunjungan':
        $view = "views/user/kunjungan/create.php";
        $akses = "admin";
        break;

    case 'editkunjungan':
        $view = "views/user/kunjungan/edit.php";
        $akses = "admin";
        break;

    case 'showkunjungan':
        $view = "views/user/kunjungan/show.php";
        $akses = "admin";
        break;


    // ----------------------------------------
    // LAPORAN ADMIN
    // ----------------------------------------
    case 'laporanharian':
        $view = "views/user/laporan/laporanharian.php";
        $akses = "admin";
        break;

    case 'cetaklaporanharian':
        $view = "views/user/laporan/cetaklaporanharian.php";
        $akses = "admin";
        break;

    case 'laporanbulanan':
        $view = "views/user/laporan/laporanbulanan.php";
        $akses = "admin";
        break;

    case 'cetaklaporanbulanan':
        $view = "views/user/laporan/cetaklaporanbulanan.php";
        $akses = "admin";
        break;

    case 'laporantahunan':
        $view = "views/user/laporan/laporantahunan.php";
        $akses = "admin";
        break;

    case 'cetaklaporantahunan':
        $view = "views/user/laporan/cetaklaporantahunan.php";
        $akses = "admin";
        break;

    case 'laporanobat':
        $view = "views/user/laporan/laporanobat.php";
        $akses = "admin";
        break;

    case 'cetaklaporanobat':
        $view = "views/user/laporan/cetaklaporanobat.php";
        $akses = "admin";
        break;


    // ----------------------------------------
    // DASHBOARD DAN FITUR PASIEN
    // ----------------------------------------
    case 'dashboardpasien':
        $view = "views/pasien/dashboardpasien.php";
        $akses = "pasien";
        break;

    case 'profil':
        $view = "views/pasien/profil.php";
        $akses = "pasien";
        break;

    case 'editprofil':
        $view = "views/pasien/editprofil.php";
        $akses = "pasien";
        break;

    case 'pendaftarancheckuppasien':
        $view = "views/pasien/pendaftarancheckup.php";
        $akses = "pasien";
        break;

    case 'riwayatkunjungan':
        $view = "views/pasien/riwayatkunjungan.php";
        $akses = "pasien";
        break;

    case 'detilkunjungan':
        $view = "views/pasien/detilkunjungan.php";
        $akses = "pasien";
        break;

    case 'daftardokter':
        $view = "views/pasien/daftardokter.php";
        $akses = "pasien";
        break;

    case 'informasiobat':
        $view = "views/pasien/informasiobat.php";
        $akses = "pasien";
        break;


    // ----------------------------------------
    // HALAMAN TIDAK DIKENAL
    // ----------------------------------------
    default:
        $view = "views/errors/404.php";
        $akses = "semua";
        http_response_code(404);
        break;
}


// ============================================
// 6. MEMERIKSA HAK AKSES
// ============================================

// Halaman khusus admin
if ($akses === 'admin' && $role !== 'admin') {

    http_response_code(403);
    $view = "views/errors/403.php";
    $akses = "semua";

// Halaman khusus pasien
} elseif ($akses === 'pasien' && $role !== 'pasien') {

    http_response_code(403);
    $view = "views/errors/403.php";
    $akses = "semua";

// Halaman autentikasi hanya untuk tamu
} elseif ($akses === 'tamu' && $role !== 'tamu') {

    if ($role === 'admin') {
        header("Location: index.php?page=dashboarduser");
    } else {
        header("Location: index.php?page=dashboardpasien");
    }

    exit;
}


// ============================================
// 7. MEMERIKSA KEBERADAAN FILE
// ============================================
if (!file_exists($view)) {

    http_response_code(404);
    $view = "views/errors/404.php";

}


// ============================================
// 8. MEMANGGIL HEADER
// ============================================
require_once "views/component/header.php";


// ============================================
// 9. MEMANGGIL NAVBAR SESUAI ROLE
// ============================================
if ($role === 'admin') {

    require_once "views/component/user/navbar.php";

} elseif ($role === 'pasien') {

    require_once "views/component/pasien/navbar.php";

} else {

    require_once "views/component/tamu/navbar.php";

}


// ============================================
// 10. MENAMPILKAN SIDEBAR DAN ISI HALAMAN
// ============================================
?>


<div class="container-fluid">

    <?php if ($role === 'admin') { ?>

        <!-- LAYOUT ADMIN -->
        <div class="row g-0" id="adminLayout">

            <!-- SIDEBAR ADMIN -->
            <aside class="col-md-3 col-lg-2 p-0 sidebar-admin"
                   id="sidebarAdmin">

                <?php
                require_once "views/component/user/sidebar.php";
                ?>

            </aside>

            <!-- KONTEN ADMIN -->
            <main class="col-md-9 col-lg-10 main-content"
                  id="mainContent">

                <?php require $view; ?>

            </main>

        </div>

    <?php } elseif ($role === 'pasien') { ?>

        <!-- LAYOUT PASIEN -->
        <div class="row g-0" id="adminLayout">

            <!-- SIDEBAR PASIEN -->
            <aside class="col-md-3 col-lg-2 p-0 sidebar-admin"
                   id="sidebarAdmin">

                <?php
                require_once "views/component/pasien/sidebar.php";
                ?>

            </aside>

            <!-- KONTEN PASIEN -->
            <main class="col-md-9 col-lg-10 main-content"
                  id="mainContent">

                <?php require $view; ?>

            </main>

        </div>

    <?php } else { ?>

        <!-- LAYOUT TAMU -->
        <div class="row g-0">

            <main class="col-12 p-0">

                <?php require $view; ?>

            </main>

        </div>

    <?php } ?>

</div>




<?php
// ============================================
// 11. MEMANGGIL FOOTER SESUAI ROLE
// ============================================
if ($role === 'admin') {

    require_once "views/component/user/footer.php";

} elseif ($role === 'pasien') {

    require_once "views/component/pasien/footer.php";

} else {

    require_once "views/component/tamu/footer.php";

}


// ============================================
// 12. MEMANGGIL JAVASCRIPT BOOTSTRAP
// ============================================
require_once "views/component/footerjs.php";
?>
