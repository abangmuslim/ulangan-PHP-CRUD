
<?php
require_once __DIR__ . "/koneksi.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ========================================
// CEK LOGIN ADMIN
// ========================================

if (!isset($_SESSION['iduser'])) {
    header("location: ../index.php?page=loginuser");
    exit;
}

// ========================================
// CEK AKSI
// ========================================

$aksi = $_GET['aksi'] ?? '';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("location: ../index.php?page=pendaftarancheckup");
    exit;
}

// ========================================
// AMBIL ID PENDAFTARAN
// ========================================

$idpendaftaran = isset($_POST['idpendaftaran'])
    ? (int) $_POST['idpendaftaran']
    : 0;

if ($idpendaftaran <= 0) {
    header("location: ../index.php?page=pendaftarancheckup&status=datatidakada");
    exit;
}

// ========================================
// CEK DATA PENDAFTARAN
// ========================================

$query = mysqli_prepare($koneksi, "
    SELECT
        pendaftarancheckup.idpendaftaran,
        pendaftarancheckup.idpasien,
        pendaftarancheckup.idjadwal,
        pendaftarancheckup.tanggalperiksa,
        pendaftarancheckup.status,
        pendaftarancheckup.idkunjungan,
        jadwaldokter.haripraktik
    FROM pendaftarancheckup
    INNER JOIN jadwaldokter
        ON pendaftarancheckup.idjadwal = jadwaldokter.idjadwal
    WHERE pendaftarancheckup.idpendaftaran = ?
");

mysqli_stmt_bind_param($query, "i", $idpendaftaran);
mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($query);

// ========================================
// VALIDASI DATA PENDAFTARAN
// ========================================

if (!$data) {
    header("location: ../index.php?page=pendaftarancheckup&status=datatidakada");
    exit;
}

// Pendaftaran harus masih menunggu
if ($data['status'] != 'Menunggu') {
    header("location: ../index.php?page=showpendaftarancheckup&idpendaftaran=$idpendaftaran&status=sudahdiproses");
    exit;
}

// Pendaftaran belum boleh memiliki kunjungan
if (!empty($data['idkunjungan'])) {
    header("location: ../index.php?page=showpendaftarancheckup&idpendaftaran=$idpendaftaran&status=sudahdiproses");
    exit;
}

// ========================================
// AKSI MENYETUJUI PENDAFTARAN
// ========================================

if ($aksi == 'setujui') {

    // ========================================
    // VALIDASI TANGGAL PEMERIKSAAN
    // ========================================

    $tanggalperiksa = $data['tanggalperiksa'];

    // Tanggal pemeriksaan tidak boleh lewat
    if ($tanggalperiksa < date('Y-m-d')) {

        header("location: ../index.php?page=showpendaftarancheckup&idpendaftaran=$idpendaftaran&status=tanggallewat");
        exit;
    }

    // ========================================
    // VALIDASI HARI PRAKTIK DOKTER
    // ========================================

    $hariIndonesia = [
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu',
        'Sunday'    => 'Minggu'
    ];

    $hariTanggal = date(
        'l',
        strtotime($tanggalperiksa)
    );

    $hariPeriksa = $hariIndonesia[$hariTanggal];

    if (strcasecmp(trim($data['haripraktik']), $hariPeriksa) != 0) {

        header("location: ../index.php?page=showpendaftarancheckup&idpendaftaran=$idpendaftaran&status=jadwaltidakcocok");
        exit;
    }

    // ========================================
    // UPDATE STATUS PENDAFTARAN
    // ========================================

    $catatanadmin = "Pendaftaran checkup disetujui oleh admin.";

    $stmt = mysqli_prepare($koneksi, "
        UPDATE pendaftarancheckup
        SET
            status = 'Disetujui',
            catatanadmin = ?
        WHERE
            idpendaftaran = ?
            AND status = 'Menunggu'
            AND idkunjungan IS NULL
    ");

    mysqli_stmt_bind_param(
        $stmt,
        "si",
        $catatanadmin,
        $idpendaftaran
    );

    $berhasil = mysqli_stmt_execute($stmt);

    $jumlahubah = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    // ========================================
    // HASIL PERSETUJUAN
    // ========================================

    if ($berhasil && $jumlahubah > 0) {

        header("location: ../index.php?page=showpendaftarancheckup&idpendaftaran=$idpendaftaran&status=disetujui");

    } else {

        header("location: ../index.php?page=showpendaftarancheckup&idpendaftaran=$idpendaftaran&status=gagal");

    }

    exit;
}

// ========================================
// AKSI MENOLAK PENDAFTARAN
// ========================================

elseif ($aksi == 'tolak') {

    // ========================================
    // AMBIL CATATAN ADMIN
    // ========================================

    $catatanadmin = trim($_POST['catatanadmin'] ?? '');

    // Catatan wajib diisi
    if ($catatanadmin == '') {

        header("location: ../index.php?page=showpendaftarancheckup&idpendaftaran=$idpendaftaran&status=catatankosong#form-penolakan");
        exit;
    }

    // ========================================
    // UPDATE STATUS PENDAFTARAN
    // ========================================

    $stmt = mysqli_prepare($koneksi, "
        UPDATE pendaftarancheckup
        SET
            status = 'Ditolak',
            catatanadmin = ?
        WHERE
            idpendaftaran = ?
            AND status = 'Menunggu'
            AND idkunjungan IS NULL
    ");

    mysqli_stmt_bind_param(
        $stmt,
        "si",
        $catatanadmin,
        $idpendaftaran
    );

    $berhasil = mysqli_stmt_execute($stmt);

    $jumlahubah = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    // ========================================
    // HASIL PENOLAKAN
    // ========================================

    if ($berhasil && $jumlahubah > 0) {

        header("location: ../index.php?page=showpendaftarancheckup&idpendaftaran=$idpendaftaran&status=ditolak");

    } else {

        header("location: ../index.php?page=showpendaftarancheckup&idpendaftaran=$idpendaftaran&status=gagal");

    }

    exit;
}

// ========================================
// AKSI TIDAK DIKENALI
// ========================================

else {

    header("location: ../index.php?page=pendaftarancheckup&status=aksiinvalid");
    exit;

}
