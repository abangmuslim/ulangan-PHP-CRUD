<?php
// ============================================
// 1. VALIDASI SESSION DOKTER
// ============================================
if (!isset($_SESSION['iddokter'])) {
    http_response_code(403);
    exit("Akses ditolak.");
}

$iddokter = (int) $_SESSION['iddokter'];

// ============================================
// 2. VALIDASI REQUEST
// ============================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php?page=resep");
    exit;
}

csrfCheck();

// ============================================
// 3. MEMBACA DATA FORM
// ============================================
$aksi = $_POST['aksi'] ?? '';
$idkunjungan = (int) ($_POST['idkunjungan'] ?? 0);
$iddetilkunjungan = (int) ($_POST['iddetilkunjungan'] ?? 0);
$idobat = (int) ($_POST['idobat'] ?? 0);
$jumlah = (int) ($_POST['jumlah'] ?? 0);
$aturanpakai = trim($_POST['aturanpakai'] ?? '');

if (
    !in_array($aksi, ['tambah', 'ubah'], true) ||
    $idkunjungan <= 0 ||
    $idobat <= 0 ||
    $jumlah <= 0 ||
    $aturanpakai == '' ||
    strlen($aturanpakai) > 255
) {
    header("Location: index.php?page=createresep&idkunjungan=$idkunjungan&pesan=gagal");
    exit;
}

if ($aksi == 'ubah' && $iddetilkunjungan <= 0) {
    header("Location: index.php?page=resep&pesan=gagal");
    exit;
}

// ============================================
// 4. MEMULAI TRANSAKSI DATABASE
// ============================================
mysqli_begin_transaction($koneksi);

try {

    // ========================================
    // 5. MEMASTIKAN KUNJUNGAN MILIK DOKTER
    // ========================================
    $stmt = mysqli_prepare($koneksi, "
        SELECT statuskunjungan
        FROM kunjungan
        WHERE idkunjungan = ?
        AND iddokter = ?
        FOR UPDATE
    ");

    mysqli_stmt_bind_param($stmt, "ii", $idkunjungan, $iddokter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $kunjungan = mysqli_fetch_assoc($result);

    if (!$kunjungan) {
        throw new Exception("Kunjungan tidak ditemukan.");
    }

    // ========================================
    // 6. MEMASTIKAN PEMERIKSAAN BELUM SELESAI
    // ========================================
    if ($kunjungan['statuskunjungan'] != 'Proses') {
        throw new Exception("Pemeriksaan sudah selesai.");
    }

    // ========================================
    // 7. MENGAMBIL HARGA OBAT DARI DATABASE
    // ========================================
    $stmt = mysqli_prepare($koneksi, "
        SELECT harga
        FROM obat
        WHERE idobat = ?
    ");

    mysqli_stmt_bind_param($stmt, "i", $idobat);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $obat = mysqli_fetch_assoc($result);

    if (!$obat) {
        throw new Exception("Obat tidak ditemukan.");
    }

    $harga = (int) $obat['harga'];
    $subtotal = $harga * $jumlah;

    // ========================================
    // 8. MENAMBAH RESEP
    // ========================================
    if ($aksi == 'tambah') {

        $stmt = mysqli_prepare($koneksi, "
            INSERT INTO detilkunjungan
            (
                idkunjungan,
                idobat,
                jumlah,
                harga,
                subtotal,
                aturanpakai
            )
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        mysqli_stmt_bind_param(
            $stmt,
            "iiiiis",
            $idkunjungan,
            $idobat,
            $jumlah,
            $harga,
            $subtotal,
            $aturanpakai
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Gagal menambahkan resep.");
        }
    }

    // ========================================
    // 9. MENGUBAH RESEP
    // ========================================
    if ($aksi == 'ubah') {

        // Pastikan detail resep terkait kunjungan tersebut
        $stmt = mysqli_prepare($koneksi, "
            SELECT iddetilkunjungan
            FROM detilkunjungan
            WHERE iddetilkunjungan = ?
            AND idkunjungan = ?
            FOR UPDATE
        ");

        mysqli_stmt_bind_param(
            $stmt,
            "ii",
            $iddetilkunjungan,
            $idkunjungan
        );

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $detail = mysqli_fetch_assoc($result);

        if (!$detail) {
            throw new Exception("Detail resep tidak ditemukan.");
        }

        $stmt = mysqli_prepare($koneksi, "
            UPDATE detilkunjungan
            SET
                idobat = ?,
                jumlah = ?,
                harga = ?,
                subtotal = ?,
                aturanpakai = ?
            WHERE iddetilkunjungan = ?
            AND idkunjungan = ?
        ");

        mysqli_stmt_bind_param(
            $stmt,
            "iiiisii",
            $idobat,
            $jumlah,
            $harga,
            $subtotal,
            $aturanpakai,
            $iddetilkunjungan,
            $idkunjungan
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Gagal mengubah resep.");
        }
    }

    // ========================================
    // 10. SIMPAN TRANSAKSI
    // ========================================
    mysqli_commit($koneksi);

    header("Location: index.php?page=showresep&idkunjungan=$idkunjungan&pesan=berhasil");
    exit;

} catch (Throwable $e) {

    mysqli_rollback($koneksi);

    header("Location: index.php?page=createresep&idkunjungan=$idkunjungan&pesan=gagal");
    exit;
}