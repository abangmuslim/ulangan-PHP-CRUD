
<?php
require_once __DIR__ . "/koneksi.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// ========================================
// CEK LOGIN USER / ADMIN
// ========================================
if (!isset($_SESSION['iduser'])) {
    header("location:../index.php?page=login");
    exit;
}

// ========================================
// CEK AKSI
// ========================================
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

$iduser = (int) $_SESSION['iduser'];

// ========================================
// AMBIL DATA FORM
// ========================================
$tanggalkunjungan = isset($_POST['tanggalkunjungan'])
    ? trim($_POST['tanggalkunjungan']) : '';

$keluhan = isset($_POST['keluhan'])
    ? trim($_POST['keluhan']) : '';

$diagnosis = isset($_POST['diagnosis'])
    ? trim($_POST['diagnosis']) : '';

$jumlahbayar = isset($_POST['jumlahbayar'])
    ? (int) $_POST['jumlahbayar'] : 0;

$idobat = isset($_POST['idobat']) && is_array($_POST['idobat'])
    ? $_POST['idobat'] : [];

$jumlah = isset($_POST['jumlah']) && is_array($_POST['jumlah'])
    ? $_POST['jumlah'] : [];

$aturanpakai = isset($_POST['aturanpakai']) && is_array($_POST['aturanpakai'])
    ? $_POST['aturanpakai'] : [];


// ========================================
// VALIDASI TANGGAL DAN PEMBAYARAN
// ========================================
function validasiTanggalKunjungan($tanggal)
{
    $hasil = DateTime::createFromFormat('Y-m-d', $tanggal);

    return $hasil && $hasil->format('Y-m-d') === $tanggal;
}


// ======================================================
// ======================================================
// PROSES TAMBAH KUNJUNGAN
// ======================================================
// ======================================================

if ($aksi == "tambah") {

    // ========================================
    // AMBIL ID PENDAFTARAN
    // ========================================
    $idpendaftaran = isset($_POST['idpendaftaran'])
        ? (int) $_POST['idpendaftaran'] : 0;

    // ========================================
    // VALIDASI DATA AWAL
    // ========================================
    if ($idpendaftaran <= 0) {
        header("location:../index.php?page=kunjungan&status=pendaftarantidakvalid");
        exit;
    }

    if ($jumlahbayar < 0) {
        header("location:../index.php?page=createkunjungan&idpendaftaran="
            . $idpendaftaran . "&status=jumlahbayartidakvalid");
        exit;
    }

    if (!validasiTanggalKunjungan($tanggalkunjungan)) {
        header("location:../index.php?page=createkunjungan&idpendaftaran="
            . $idpendaftaran . "&status=tanggaltidakvalid");
        exit;
    }

    // ========================================
    // PROSES TRANSAKSI DATABASE
    // ========================================
    try {

        mysqli_begin_transaction($koneksi);

        // ========================================
        // 1. AMBIL DATA PENDAFTARAN
        // ========================================
        $queryPendaftaran = "
            SELECT
                p.idpendaftaran,
                p.idpasien,
                p.idjadwal,
                p.keluhan,
                p.status,
                p.idkunjungan,
                j.iddokter
            FROM pendaftarancheckup p
            INNER JOIN jadwaldokter j
                ON p.idjadwal = j.idjadwal
            WHERE p.idpendaftaran = ?
            FOR UPDATE
        ";

        $stmtPendaftaran = mysqli_prepare(
            $koneksi,
            $queryPendaftaran
        );

        mysqli_stmt_bind_param(
            $stmtPendaftaran,
            "i",
            $idpendaftaran
        );

        mysqli_stmt_execute($stmtPendaftaran);

        $resultPendaftaran = mysqli_stmt_get_result(
            $stmtPendaftaran
        );

        $dataPendaftaran = mysqli_fetch_assoc(
            $resultPendaftaran
        );

        mysqli_stmt_close($stmtPendaftaran);

        if (!$dataPendaftaran) {
            throw new Exception("pendaftarantidakditemukan");
        }

        // Pendaftaran harus sudah disetujui
        if ($dataPendaftaran['status'] != "Disetujui") {
            throw new Exception("pendaftaranbelumdisetujui");
        }

        // Satu pendaftaran hanya boleh menjadi satu kunjungan
        if (!empty($dataPendaftaran['idkunjungan'])) {
            throw new Exception("kunjungansudahdibuat");
        }

        $idpasien = (int) $dataPendaftaran['idpasien'];
        $idjadwal = (int) $dataPendaftaran['idjadwal'];
        $iddokter = (int) $dataPendaftaran['iddokter'];

        // ========================================
        // 2. SIMPAN DATA UTAMA KUNJUNGAN
        // ========================================
        $queryKunjungan = "
            INSERT INTO kunjungan
            (
                iduser,
                idpasien,
                iddokter,
                idjadwal,
                tanggalkunjungan,
                keluhan,
                diagnosis,
                totalkunjungan,
                jumlahbayar,
                kembalian,
                statuspembayaran,
                statuskunjungan
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, 0, 0, 0, 'Belum Bayar', 'Proses')
        ";

        $stmtKunjungan = mysqli_prepare(
            $koneksi,
            $queryKunjungan
        );

        mysqli_stmt_bind_param(
            $stmtKunjungan,
            "iiiisss",
            $iduser,
            $idpasien,
            $iddokter,
            $idjadwal,
            $tanggalkunjungan,
            $keluhan,
            $diagnosis
        );

        mysqli_stmt_execute($stmtKunjungan);

        $idkunjungan = mysqli_insert_id($koneksi);

        mysqli_stmt_close($stmtKunjungan);

        // ========================================
        // 3. SIMPAN DETAIL OBAT
        // ========================================
        $totalKunjungan = 0;
        $obatTerpilih = [];

        $jumlahBaris = count($idobat);

        for ($i = 0; $i < $jumlahBaris; $i++) {

            $idObatBaris = (int) $idobat[$i];

            // Baris obat yang tidak dipilih dilewati
            if ($idObatBaris <= 0) {
                continue;
            }

            $jumlahObat = isset($jumlah[$i])
                ? (int) $jumlah[$i] : 0;

            $aturan = isset($aturanpakai[$i])
                ? trim($aturanpakai[$i]) : '';

            if ($jumlahObat <= 0) {
                throw new Exception("jumlahobattidakvalid");
            }

            // Cegah obat yang sama dimasukkan dua kali
            if (in_array($idObatBaris, $obatTerpilih)) {
                throw new Exception("obatduplikat");
            }

            $obatTerpilih[] = $idObatBaris;

            // ========================================
            // CEK HARGA DAN STOK OBAT
            // ========================================
            $queryObat = "
                SELECT harga, stok
                FROM obat
                WHERE idobat = ?
                FOR UPDATE
            ";

            $stmtObat = mysqli_prepare(
                $koneksi,
                $queryObat
            );

            mysqli_stmt_bind_param(
                $stmtObat,
                "i",
                $idObatBaris
            );

            mysqli_stmt_execute($stmtObat);

            $resultObat = mysqli_stmt_get_result($stmtObat);
            $dataObat = mysqli_fetch_assoc($resultObat);

            mysqli_stmt_close($stmtObat);

            if (!$dataObat) {
                throw new Exception("obattidakditemukan");
            }

            $harga = (int) $dataObat['harga'];
            $stok = (int) $dataObat['stok'];

            if ($jumlahObat > $stok) {
                throw new Exception("stokobattidakcukup");
            }

            $subtotal = $harga * $jumlahObat;

            // ========================================
            // INSERT DETAIL KUNJUNGAN
            // ========================================
            $queryDetail = "
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
            ";

            $stmtDetail = mysqli_prepare(
                $koneksi,
                $queryDetail
            );

            mysqli_stmt_bind_param(
                $stmtDetail,
                "iiiiis",
                $idkunjungan,
                $idObatBaris,
                $jumlahObat,
                $harga,
                $subtotal,
                $aturan
            );

            mysqli_stmt_execute($stmtDetail);

            mysqli_stmt_close($stmtDetail);

            // ========================================
            // KURANGI STOK OBAT
            // ========================================
            $queryStok = "
                UPDATE obat
                SET stok = stok - ?
                WHERE idobat = ?
                AND stok >= ?
            ";

            $stmtStok = mysqli_prepare(
                $koneksi,
                $queryStok
            );

            mysqli_stmt_bind_param(
                $stmtStok,
                "iii",
                $jumlahObat,
                $idObatBaris,
                $jumlahObat
            );

            mysqli_stmt_execute($stmtStok);

            if (mysqli_stmt_affected_rows($stmtStok) != 1) {
                mysqli_stmt_close($stmtStok);
                throw new Exception("stokobattidakcukup");
            }

            mysqli_stmt_close($stmtStok);

            // Tambahkan subtotal ke total kunjungan
            $totalKunjungan += $subtotal;
        }

        // ========================================
        // 4. HITUNG PEMBAYARAN
        // ========================================
        $kembalian = 0;

        if ($jumlahbayar > $totalKunjungan) {
            $kembalian = $jumlahbayar - $totalKunjungan;
        }

        if ($jumlahbayar >= $totalKunjungan) {
            $statuspembayaran = "Lunas";
        } else {
            $statuspembayaran = "Belum Bayar";
        }

        // ========================================
        // 5. UPDATE TOTAL KUNJUNGAN
        // ========================================
        $queryUpdateKunjungan = "
            UPDATE kunjungan
            SET
                totalkunjungan = ?,
                jumlahbayar = ?,
                kembalian = ?,
                statuspembayaran = ?
            WHERE idkunjungan = ?
        ";

        $stmtUpdateKunjungan = mysqli_prepare(
            $koneksi,
            $queryUpdateKunjungan
        );

        mysqli_stmt_bind_param(
            $stmtUpdateKunjungan,
            "iiisi",
            $totalKunjungan,
            $jumlahbayar,
            $kembalian,
            $statuspembayaran,
            $idkunjungan
        );

        mysqli_stmt_execute($stmtUpdateKunjungan);

        mysqli_stmt_close($stmtUpdateKunjungan);

        // ========================================
        // 6. HUBUNGKAN PENDAFTARAN DENGAN KUNJUNGAN
        // ========================================
        $queryUpdatePendaftaran = "
            UPDATE pendaftarancheckup
            SET idkunjungan = ?
            WHERE idpendaftaran = ?
            AND idkunjungan IS NULL
        ";

        $stmtUpdatePendaftaran = mysqli_prepare(
            $koneksi,
            $queryUpdatePendaftaran
        );

        mysqli_stmt_bind_param(
            $stmtUpdatePendaftaran,
            "ii",
            $idkunjungan,
            $idpendaftaran
        );

        mysqli_stmt_execute($stmtUpdatePendaftaran);

        if (mysqli_stmt_affected_rows($stmtUpdatePendaftaran) != 1) {
            mysqli_stmt_close($stmtUpdatePendaftaran);
            throw new Exception("pendaftarantelahdiproses");
        }

        mysqli_stmt_close($stmtUpdatePendaftaran);

        // ========================================
        // 7. SELESAIKAN TRANSAKSI
        // ========================================
        mysqli_commit($koneksi);

        header("location:../index.php?page=kunjungan&status=tambah");
        exit;

    } catch (Throwable $e) {

        mysqli_rollback($koneksi);

        error_log($e->getMessage());

        $statusError = $e->getMessage();

        $daftarError = [
            "pendaftarantidakditemukan",
            "pendaftaranbelumdisetujui",
            "kunjungansudahdibuat",
            "jumlahobattidakvalid",
            "obatduplikat",
            "obattidakditemukan",
            "stokobattidakcukup",
            "pendaftarantelahdiproses"
        ];

        if (!in_array($statusError, $daftarError)) {
            $statusError = "gagal";
        }

        header(
            "location:../index.php?page=createkunjungan&idpendaftaran="
            . $idpendaftaran
            . "&status="
            . $statusError
        );

        exit;
    }


// ======================================================
// ======================================================
// PROSES EDIT KUNJUNGAN
// ======================================================
// ======================================================

} elseif ($aksi == "edit") {

    // ========================================
    // AMBIL ID KUNJUNGAN
    // ========================================
    $idkunjungan = isset($_POST['idkunjungan'])
        ? (int) $_POST['idkunjungan'] : 0;

    // ========================================
    // VALIDASI DATA AWAL
    // ========================================
    if ($idkunjungan <= 0) {
        header("location:../index.php?page=kunjungan&status=idkunjungantidakvalid");
        exit;
    }

    if ($jumlahbayar < 0) {
        header("location:../index.php?page=editkunjungan&idkunjungan="
            . $idkunjungan . "&status=jumlahbayartidakvalid");
        exit;
    }

    if (!validasiTanggalKunjungan($tanggalkunjungan)) {
        header("location:../index.php?page=editkunjungan&idkunjungan="
            . $idkunjungan . "&status=tanggaltidakvalid");
        exit;
    }

    // ========================================
    // PROSES TRANSAKSI DATABASE
    // ========================================
    try {

        mysqli_begin_transaction($koneksi);

        // ========================================
        // 1. AMBIL DAN KUNCI DATA KUNJUNGAN
        // ========================================
        $queryKunjungan = "
            SELECT idkunjungan, statuskunjungan
            FROM kunjungan
            WHERE idkunjungan = ?
            FOR UPDATE
        ";

        $stmtKunjungan = mysqli_prepare(
            $koneksi,
            $queryKunjungan
        );

        mysqli_stmt_bind_param(
            $stmtKunjungan,
            "i",
            $idkunjungan
        );

        mysqli_stmt_execute($stmtKunjungan);

        $resultKunjungan = mysqli_stmt_get_result(
            $stmtKunjungan
        );

        $dataKunjungan = mysqli_fetch_assoc(
            $resultKunjungan
        );

        mysqli_stmt_close($stmtKunjungan);

        if (!$dataKunjungan) {
            throw new Exception("kunjungantidakditemukan");
        }

        // Kunjungan yang sudah selesai tidak boleh diedit
        if ($dataKunjungan['statuskunjungan'] != "Proses") {
            throw new Exception("kunjungantidakbisadiedit");
        }

        // ========================================
        // 2. AMBIL DETAIL OBAT LAMA
        // ========================================
        $queryDetailLama = "
            SELECT idobat, jumlah
            FROM detilkunjungan
            WHERE idkunjungan = ?
            FOR UPDATE
        ";

        $stmtDetailLama = mysqli_prepare(
            $koneksi,
            $queryDetailLama
        );

        mysqli_stmt_bind_param(
            $stmtDetailLama,
            "i",
            $idkunjungan
        );

        mysqli_stmt_execute($stmtDetailLama);

        $resultDetailLama = mysqli_stmt_get_result(
            $stmtDetailLama
        );

        $detailLama = mysqli_fetch_all(
            $resultDetailLama,
            MYSQLI_ASSOC
        );

        mysqli_stmt_close($stmtDetailLama);

        // ========================================
        // 3. KEMBALIKAN STOK OBAT LAMA
        // ========================================
        foreach ($detailLama as $detail) {

            $idObatLama = (int) $detail['idobat'];
            $jumlahLama = (int) $detail['jumlah'];

            $queryKembalikanStok = "
                UPDATE obat
                SET stok = stok + ?
                WHERE idobat = ?
            ";

            $stmtKembalikanStok = mysqli_prepare(
                $koneksi,
                $queryKembalikanStok
            );

            mysqli_stmt_bind_param(
                $stmtKembalikanStok,
                "ii",
                $jumlahLama,
                $idObatLama
            );

            mysqli_stmt_execute($stmtKembalikanStok);

            if (mysqli_stmt_affected_rows($stmtKembalikanStok) != 1) {
                mysqli_stmt_close($stmtKembalikanStok);
                throw new Exception("gagalmengembalikanstok");
            }

            mysqli_stmt_close($stmtKembalikanStok);
        }

        // ========================================
        // 3A. HAPUS DETAIL OBAT LAMA
        // ========================================
        $queryHapusDetail = "
            DELETE FROM detilkunjungan
            WHERE idkunjungan = ?
        ";

        $stmtHapusDetail = mysqli_prepare(
            $koneksi,
            $queryHapusDetail
        );

        mysqli_stmt_bind_param(
            $stmtHapusDetail,
            "i",
            $idkunjungan
        );

        mysqli_stmt_execute($stmtHapusDetail);

        mysqli_stmt_close($stmtHapusDetail);

        
        // ========================================
        // 4. VALIDASI DAN SIMPAN DETAIL OBAT BARU
        // ========================================
        $totalKunjungan = 0;
        $obatTerpilih = [];

        $jumlahBaris = count($idobat);

        for ($i = 0; $i < $jumlahBaris; $i++) {

            $idObatBaris = (int) $idobat[$i];

            // Baris obat yang tidak dipilih dilewati
            if ($idObatBaris <= 0) {
                continue;
            }

            $jumlahObat = isset($jumlah[$i])
                ? (int) $jumlah[$i] : 0;

            $aturan = isset($aturanpakai[$i])
                ? trim($aturanpakai[$i]) : '';

            if ($jumlahObat <= 0) {
                throw new Exception("jumlahobattidakvalid");
            }

            // Cegah obat yang sama dimasukkan dua kali
            if (in_array($idObatBaris, $obatTerpilih)) {
                throw new Exception("obatduplikat");
            }

            $obatTerpilih[] = $idObatBaris;

            // ========================================
            // CEK HARGA DAN STOK OBAT
            // ========================================
            $queryObat = "
                SELECT harga, stok
                FROM obat
                WHERE idobat = ?
                FOR UPDATE
            ";

            $stmtObat = mysqli_prepare(
                $koneksi,
                $queryObat
            );

            mysqli_stmt_bind_param(
                $stmtObat,
                "i",
                $idObatBaris
            );

            mysqli_stmt_execute($stmtObat);

            $resultObat = mysqli_stmt_get_result($stmtObat);

            $dataObat = mysqli_fetch_assoc($resultObat);

            mysqli_stmt_close($stmtObat);

            if (!$dataObat) {
                throw new Exception("obattidakditemukan");
            }

            $harga = (int) $dataObat['harga'];
            $stok = (int) $dataObat['stok'];

            // Stok sudah termasuk pengembalian obat lama
            if ($jumlahObat > $stok) {
                throw new Exception("stokobattidakcukup");
            }

            $subtotal = $harga * $jumlahObat;

            // ========================================
            // SIMPAN DETAIL OBAT BARU
            // ========================================
            $queryDetailBaru = "
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
            ";

            $stmtDetailBaru = mysqli_prepare(
                $koneksi,
                $queryDetailBaru
            );

            mysqli_stmt_bind_param(
                $stmtDetailBaru,
                "iiiiis",
                $idkunjungan,
                $idObatBaris,
                $jumlahObat,
                $harga,
                $subtotal,
                $aturan
            );

            mysqli_stmt_execute($stmtDetailBaru);

            mysqli_stmt_close($stmtDetailBaru);

            // ========================================
            // KURANGI STOK OBAT BARU
            // ========================================
            $queryKurangiStok = "
                UPDATE obat
                SET stok = stok - ?
                WHERE idobat = ?
                AND stok >= ?
            ";

            $stmtKurangiStok = mysqli_prepare(
                $koneksi,
                $queryKurangiStok
            );

            mysqli_stmt_bind_param(
                $stmtKurangiStok,
                "iii",
                $jumlahObat,
                $idObatBaris,
                $jumlahObat
            );

            mysqli_stmt_execute($stmtKurangiStok);

            if (mysqli_stmt_affected_rows($stmtKurangiStok) != 1) {
                mysqli_stmt_close($stmtKurangiStok);
                throw new Exception("stokobattidakcukup");
            }

            mysqli_stmt_close($stmtKurangiStok);

            // Hitung total biaya
            $totalKunjungan += $subtotal;
        }

        // ========================================
        // 5. HITUNG ULANG PEMBAYARAN
        // ========================================
        $kembalian = 0;

        if ($jumlahbayar > $totalKunjungan) {
            $kembalian = $jumlahbayar - $totalKunjungan;
        }

        if ($jumlahbayar >= $totalKunjungan) {
            $statuspembayaran = "Lunas";
        } else {
            $statuspembayaran = "Belum Bayar";
        }

        // ========================================
        // 6. UPDATE DATA UTAMA KUNJUNGAN
        // ========================================
        $queryUpdateKunjungan = "
            UPDATE kunjungan
            SET
                tanggalkunjungan = ?,
                keluhan = ?,
                diagnosis = ?,
                totalkunjungan = ?,
                jumlahbayar = ?,
                kembalian = ?,
                statuspembayaran = ?
            WHERE idkunjungan = ?
            AND statuskunjungan = 'Proses'
        ";

        $stmtUpdateKunjungan = mysqli_prepare(
            $koneksi,
            $queryUpdateKunjungan
        );

        mysqli_stmt_bind_param(
            $stmtUpdateKunjungan,
            "sssiiisi",
            $tanggalkunjungan,
            $keluhan,
            $diagnosis,
            $totalKunjungan,
            $jumlahbayar,
            $kembalian,
            $statuspembayaran,
            $idkunjungan
        );

        mysqli_stmt_execute($stmtUpdateKunjungan);

        if (mysqli_stmt_affected_rows($stmtUpdateKunjungan) < 0) {
            mysqli_stmt_close($stmtUpdateKunjungan);
            throw new Exception("gagalmemperbaruidatakunjungan");
        }

        mysqli_stmt_close($stmtUpdateKunjungan);

        // ========================================
        // 7. SELESAIKAN TRANSAKSI
        // ========================================
        mysqli_commit($koneksi);

        header("location:../index.php?page=kunjungan&status=edit");
        exit;

    } catch (Throwable $e) {

        // Batalkan seluruh perubahan jika terjadi kesalahan
        mysqli_rollback($koneksi);

        // Catat kesalahan untuk pemeriksaan
        error_log($e->getMessage());

        $statusError = $e->getMessage();

        $daftarError = [
            "kunjungantidakditemukan",
            "kunjungantidakbisadiedit",
            "jumlahobattidakvalid",
            "obatduplikat",
            "obattidakditemukan",
            "stokobattidakcukup",
            "gagalmengembalikanstok",
            "gagalmemperbaruidatakunjungan"
        ];

        if (!in_array($statusError, $daftarError)) {
            $statusError = "gagal";
        }

        header(
            "location:../index.php?page=editkunjungan&idkunjungan="
            . $idkunjungan
            . "&status="
            . $statusError
        );

        exit;
    }


// ======================================================
// AKSI TIDAK VALID
// ======================================================

} else {

    header("location:../index.php?page=kunjungan&status=aksitidakvalid");
    exit;
}
?>
