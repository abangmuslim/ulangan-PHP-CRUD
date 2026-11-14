
<?php
// ============================================
// PROSES PENDAFTARAN CHECKUP ONLINE PASIEN
// File: proses/pasien/pendaftarancheckup.php
// ============================================

// Pastikan hanya menerima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php?page=pendaftaranpasien");
    exit;
}

// Pastikan pasien sudah login
if (!isset($_SESSION['idpasien'])) {
    http_response_code(403);
    exit('Akses ditolak. Silakan login sebagai pasien.');
}

// Ambil ID pasien dari session
$idpasien = (int) $_SESSION['idpasien'];

// Pastikan aksi yang dikirim adalah daftar
$aksi = $_POST['aksi'] ?? '';

if ($aksi !== 'daftar') {
    http_response_code(403);
    exit('Aksi tidak diizinkan.');
}

// Periksa CSRF jika helper tersedia
if (function_exists('csrfCheck')) {
    if (csrfCheck() === false) {
        http_response_code(403);
        exit('Token keamanan tidak valid. Silakan muat ulang halaman.');
    }
}

// ============================================
// AMBIL DATA FORM
// ============================================

$tanggalperiksa = trim($_POST['tanggalperiksa'] ?? '');
$idjadwal = (int) ($_POST['idjadwal'] ?? 0);
$keluhan = trim($_POST['keluhan'] ?? '');

// ============================================
// VALIDASI INPUT
// ============================================

// Periksa tanggal
$tanggal = DateTime::createFromFormat('!Y-m-d', $tanggalperiksa);

if (!$tanggal || $tanggal->format('Y-m-d') !== $tanggalperiksa) {
    exit('Tanggal pemeriksaan tidak valid.');
}

// Tanggal pemeriksaan tidak boleh sebelum hari ini
$hariini = date('Y-m-d');

if ($tanggalperiksa < $hariini) {
    exit('Tanggal pemeriksaan tidak boleh sudah berlalu.');
}

// Periksa jadwal
if ($idjadwal <= 0) {
    exit('Silakan pilih jadwal dokter terlebih dahulu.');
}

// Periksa keluhan
if ($keluhan === '') {
    exit('Keluhan pasien wajib diisi.');
}

// ============================================
// VALIDASI JADWAL DOKTER
// ============================================

// Konversi hari ke bahasa Indonesia
$daftarHari = [
    1 => 'Senin',
    2 => 'Selasa',
    3 => 'Rabu',
    4 => 'Kamis',
    5 => 'Jumat',
    6 => 'Sabtu',
    7 => 'Minggu'
];

$hariPeriksa = $daftarHari[(int) $tanggal->format('N')];

// Pastikan jadwal benar-benar tersedia
// dan sesuai dengan hari pemeriksaan
$queryJadwal = mysqli_prepare($koneksi, "
    SELECT idjadwal
    FROM jadwaldokter
    WHERE idjadwal = ?
    AND haripraktik = ?
");

if (!$queryJadwal) {
    exit('Terjadi kesalahan saat memeriksa jadwal dokter.');
}

mysqli_stmt_bind_param(
    $queryJadwal,
    "is",
    $idjadwal,
    $hariPeriksa
);

mysqli_stmt_execute($queryJadwal);

$hasilJadwal = mysqli_stmt_get_result($queryJadwal);
$jadwalTersedia = mysqli_num_rows($hasilJadwal);

mysqli_stmt_close($queryJadwal);

if ($jadwalTersedia === 0) {
    exit('Jadwal dokter tidak sesuai dengan tanggal pemeriksaan yang dipilih.');
}

// ============================================
// CEK PENDAFTARAN GANDA
// ============================================

// Pasien tidak boleh mengajukan jadwal yang sama
// pada tanggal yang sama selama pengajuan sebelumnya
// belum ditolak.
$queryCek = mysqli_prepare($koneksi, "
    SELECT idpendaftaran
    FROM pendaftarancheckup
    WHERE idpasien = ?
    AND idjadwal = ?
    AND tanggalperiksa = ?
    AND status != 'Ditolak'
");

if (!$queryCek) {
    exit('Terjadi kesalahan saat memeriksa pendaftaran.');
}

mysqli_stmt_bind_param(
    $queryCek,
    "iis",
    $idpasien,
    $idjadwal,
    $tanggalperiksa
);

mysqli_stmt_execute($queryCek);

$hasilCek = mysqli_stmt_get_result($queryCek);
$pendaftaranAda = mysqli_num_rows($hasilCek);

mysqli_stmt_close($queryCek);

if ($pendaftaranAda > 0) {
    exit('Anda sudah memiliki pengajuan pendaftaran pada jadwal dan tanggal tersebut.');
}

// ============================================
// SIMPAN PENDAFTARAN CHECKUP ONLINE
// ============================================

$querySimpan = mysqli_prepare($koneksi, "
    INSERT INTO pendaftarancheckup
    (
        idpasien,
        idjadwal,
        idkunjungan,
        tanggalperiksa,
        keluhan,
        jenispendaftaran,
        nomorantrian,
        status,
        statusantrian,
        tanggalpengajuan
    )
    VALUES
    (
        ?,
        ?,
        NULL,
        ?,
        ?,
        'Online',
        NULL,
        'Menunggu',
        'Belum Antri',
        NOW()
    )
");

if (!$querySimpan) {
    exit('Terjadi kesalahan saat menyiapkan penyimpanan pendaftaran.');
}

mysqli_stmt_bind_param(
    $querySimpan,
    "iiss",
    $idpasien,
    $idjadwal,
    $tanggalperiksa,
    $keluhan
);

if (mysqli_stmt_execute($querySimpan)) {

    mysqli_stmt_close($querySimpan);

    // Berhasil mendaftar
    $_SESSION['pesanpendaftaran'] = 'Pendaftaran checkup online berhasil dikirim. Silakan menunggu persetujuan admin atau petugas.';

    header("Location: index.php?page=pendaftaranpasien");
    exit;

} else {

    mysqli_stmt_close($querySimpan);

    exit('Pendaftaran gagal disimpan. Silakan coba kembali.');
}
?>
