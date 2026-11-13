
<?php
// ============================================
// DISPATCHER UTAMA APLIKASI KLINIK AHMADI 7
// ============================================

// Dispatcher hanya menangani request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return;
}

// ============================================
// 1. IDENTITAS REQUEST
// ============================================

// Ambil nama proses dari POST terlebih dahulu.
// Jika tidak tersedia, gunakan parameter GET.
$proses = $_POST['page'] ?? $_GET['page'] ?? '';

// Pastikan nama proses berupa string
if (!is_string($proses)) {
    $proses = '';
}

$proses = trim($proses);

// Gunakan role yang sudah ditentukan oleh index.php
// agar admin, petugas, dokter, dan pasien dikenali dengan benar.
if (!isset($role)) {
    if (isset($_SESSION['iduser'])) {
        $role = $_SESSION['level'] ?? '';
    } elseif (isset($_SESSION['iddokter'])) {
        $role = 'dokter';
    } elseif (isset($_SESSION['idpasien'])) {
        $role = 'pasien';
    } else {
        $role = 'tamu';
    }
}

// Root folder aplikasi
$root = dirname(__DIR__, 2);

// ============================================
// 2. DAFTAR PROSES YANG DIIZINKAN
// ============================================

$daftarProses = [

    // ========================================
    // AUTENTIKASI
    // ========================================
    'loginuser' => [
        'file' => 'proses/auth/loginuser.php',
        'akses' => ['tamu']
    ],

    'loginpasien' => [
        'file' => 'proses/auth/loginpasien.php',
        'akses' => ['tamu']
    ],

    'logindokter' => [
        'file' => 'proses/auth/logindokter.php',
        'akses' => ['tamu']
    ],

    'registerpasien' => [
        'file' => 'proses/auth/registerpasien.php',
        'akses' => ['tamu']
    ],

    // ========================================
    // CRUD USER - ADMIN SAJA
    // ========================================
    'prosesuser' => [
        'file' => 'proses/user/user.php',
        'akses' => ['admin']
    ],

    // ========================================
    // CRUD PASIEN
    // ========================================
    'prosespasien' => [
        'file' => 'proses/user/pasien.php',
        'akses' => ['admin', 'petugas']
    ],

    // ========================================
    // CRUD DOKTER
    // ========================================
    'prosesdokter' => [
        'file' => 'proses/user/dokter.php',
        'akses' => ['admin', 'petugas']
    ],

    // ========================================
    // CRUD MERK
    // ========================================
    'prosesmerk' => [
        'file' => 'proses/user/merk.php',
        'akses' => ['admin', 'petugas']
    ],

    // ========================================
    // CRUD KATEGORI
    // ========================================
    'proseskategori' => [
        'file' => 'proses/user/kategori.php',
        'akses' => ['admin', 'petugas']
    ],

    // ========================================
    // CRUD OBAT
    // ========================================
    'prosesobat' => [
        'file' => 'proses/user/obat.php',
        'akses' => ['admin', 'petugas']
    ],

    // ========================================
    // JADWAL DOKTER - ADMIN/PETUGAS
    // ========================================
    'prosesjadwaldokter' => [
        'file' => 'proses/user/jadwaldokter.php',
        'akses' => ['admin', 'petugas']
    ],

    // ========================================
    // PENDAFTARAN CHECKUP
    // ========================================
    'prosespendaftarancheckup' => [
        'file' => 'proses/user/pendaftarancheckup.php',
        'akses' => ['admin', 'petugas', 'pasien']
    ],

    // ========================================
    // ANTREAN
    // ========================================
    'prosesantrian' => [
        'file' => 'proses/user/antrian.php',
        'akses' => ['admin', 'petugas', 'dokter', 'pasien']
    ],

    // ========================================
    // KUNJUNGAN
    // ========================================
    'proseskunjungan' => [
        'file' => 'proses/user/kunjungan.php',
        'akses' => ['admin', 'petugas']
    ],

    // ========================================
    // DETAIL KUNJUNGAN
    // ========================================
    'prosesdetilkunjungan' => [
        'file' => 'proses/user/detilkunjungan.php',
        'akses' => ['admin', 'petugas']
    ],

    // ========================================
    // KASIR
    // ========================================
    'proseskasir' => [
        'file' => 'proses/user/kasir.php',
        'akses' => ['admin', 'petugas']
    ],

    // ========================================
    // TRANSAKSI
    // ========================================
    'prosestransaksi' => [
        'file' => 'proses/user/transaksi.php',
        'akses' => ['admin', 'petugas']
    ],

    // ========================================
    // APOTEK
    // ========================================
    'prosesapotek' => [
        'file' => 'proses/user/apotek.php',
        'akses' => ['admin', 'petugas']
    ],

    // ========================================
    // PEMBAYARAN
    // ========================================
    'prosespembayaran' => [
        'file' => 'proses/user/pembayaran.php',
        'akses' => ['admin', 'petugas']
    ],

    // ========================================
    // PROFIL DOKTER
    // ========================================
    'profil' => [
        'file' => 'proses/dokter/profil.php',
        'akses' => ['dokter']
    ],

    // ========================================
    // JADWAL PRAKTIK DOKTER
    // ========================================
    'jadwaldokterdokter' => [
        'file' => 'proses/dokter/jadwaldokter.php',
        'akses' => ['dokter']
    ],

    // ========================================
    // PEMERIKSAAN DOKTER
    // ========================================
    'prosespemeriksaan' => [
        'file' => 'proses/dokter/pemeriksaan.php',
        'akses' => ['dokter']
    ],

    // ========================================
    // RESEP DOKTER
    // ========================================
    'prosesresep' => [
        'file' => 'proses/dokter/resep.php',
        'akses' => ['dokter']
    ],

    // ========================================
    // PROFIL PASIEN
    // ========================================
    'profilpasien' => [
        'file' => 'proses/pasien/profil.php',
        'akses' => ['pasien']
    ], 
    // ============================================
    
    ];
    
// LOGOUT
// ============================================
if (($_POST['page'] ?? $_GET['page'] ?? '') === 'logout') {

    csrfCheck();

    require dirname(__DIR__, 2) . '/proses/main/logout.php';
    exit;
}

// ============================================
// 3. VALIDASI NAMA PROSES
// ============================================

if ($proses === '' || !isset($daftarProses[$proses])) {
    http_response_code(404);
    require $root . '/views/errors/404.php';
    exit;
}

// ============================================
// 4. VALIDASI HAK AKSES
// ============================================

$akses = $daftarProses[$proses]['akses'];

if (!in_array($role, $akses, true)) {
    http_response_code(403);
    require $root . '/views/errors/403.php';
    exit;
}

// ============================================
// 5. VALIDASI FILE PROSES
// ============================================

$fileProses = $root . '/' . $daftarProses[$proses]['file'];

if (!is_file($fileProses)) {
    http_response_code(500);
    exit('File proses tidak ditemukan: ' . $daftarProses[$proses]['file']);
}

// ============================================
// 6. MENJALANKAN PROSES
// ============================================

require $fileProses;
exit;
