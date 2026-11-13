
<?php
// =====================================================
// PROSES PEMERIKSAAN DOKTER
// =====================================================

// 1. CEK LOGIN DOKTER
if (!isset($_SESSION['iddokter'])) {
    header("Location: index.php?page=logindokter");
    exit;
}

$iddokter = (int) $_SESSION['iddokter'];

// 2. CEK METODE REQUEST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php?page=pemeriksaan");
    exit;
}

// 3. CEK CSRF
if (!csrfCheck()) {
    header("Location: index.php?page=pemeriksaan&pesan=csrf");
    exit;
}

// 4. AMBIL DATA FORM
$idkunjungan = (int) ($_POST['idkunjungan'] ?? 0);
$diagnosis = trim($_POST['diagnosis'] ?? '');
$aksi = $_POST['aksi'] ?? '';

// 5. VALIDASI INPUT
if ($idkunjungan <= 0 || $diagnosis == '') {
    header("Location: index.php?page=createpemeriksaan&idkunjungan=$idkunjungan&pesan=kosong");
    exit;
}

if (!in_array($aksi, ['simpan', 'selesai'])) {
    header("Location: index.php?page=pemeriksaan&pesan=aksi");
    exit;
}

// 6. MULAI TRANSAKSI DATABASE
mysqli_begin_transaction($koneksi);

try {

    // 7. CEK KUNJUNGAN DAN KEPEMILIKAN DOKTER
    $stmt = mysqli_prepare($koneksi, "
        SELECT idkunjungan, statuskunjungan
        FROM kunjungan
        WHERE idkunjungan = ?
        AND iddokter = ?
        LIMIT 1
        FOR UPDATE
    ");

    mysqli_stmt_bind_param($stmt, "ii", $idkunjungan, $iddokter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$data) {
        throw new Exception('Kunjungan tidak ditemukan.');
    }

    // 8. CEK STATUS PEMERIKSAAN
    if ($data['statuskunjungan'] == 'Selesai') {
        throw new Exception('Pemeriksaan sudah selesai dan tidak dapat diubah.');
    }

    // 9. TENTUKAN STATUS PEMERIKSAAN DAN ANTREAN
    if ($aksi == 'selesai') {
        $statuskunjungan = 'Selesai';
        $statusantrian = 'Selesai';
    } else {
        $statuskunjungan = 'Proses';
        $statusantrian = 'Sedang Dilayani';
    }

    // 10. SIMPAN DIAGNOSIS DAN STATUS KUNJUNGAN
    $stmt = mysqli_prepare($koneksi, "
        UPDATE kunjungan
        SET diagnosis = ?,
            statuskunjungan = ?
        WHERE idkunjungan = ?
        AND iddokter = ?
    ");

    mysqli_stmt_bind_param(
        $stmt,
        "ssii",
        $diagnosis,
        $statuskunjungan,
        $idkunjungan,
        $iddokter
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Gagal menyimpan hasil pemeriksaan.');
    }

    mysqli_stmt_close($stmt);

    // 11. PERBARUI STATUS ANTREAN
    $stmt = mysqli_prepare($koneksi, "
        UPDATE pendaftarancheckup
        SET statusantrian = ?
        WHERE idkunjungan = ?
    ");

    mysqli_stmt_bind_param(
        $stmt,
        "si",
        $statusantrian,
        $idkunjungan
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Gagal memperbarui status antrean.');
    }

    mysqli_stmt_close($stmt);

    // 12. SIMPAN PERUBAHAN DATABASE
    mysqli_commit($koneksi);

    // 13. KEMBALI KE HALAMAN DETAIL
    if ($aksi == 'selesai') {
        header("Location: index.php?page=showpemeriksaan&idkunjungan=$idkunjungan&pesan=selesai");
    } else {
        header("Location: index.php?page=showpemeriksaan&idkunjungan=$idkunjungan&pesan=tersimpan");
    }
    exit;

} catch (Exception $e) {

    // 14. BATALKAN PERUBAHAN JIKA TERJADI ERROR
    mysqli_rollback($koneksi);

    header("Location: index.php?page=createpemeriksaan&idkunjungan=$idkunjungan&pesan=gagal");
    exit;
}
?>
