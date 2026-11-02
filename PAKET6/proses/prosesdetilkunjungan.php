
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
// AMBIL AKSI
// ========================================
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

$idkunjungan = isset($_POST['idkunjungan'])
    ? (int) $_POST['idkunjungan'] : 0;

if ($idkunjungan <= 0) {
    $idkunjungan = isset($_GET['idkunjungan'])
        ? (int) $_GET['idkunjungan'] : 0;
}

if ($idkunjungan <= 0) {
    header("location:../index.php?page=kunjungan&status=kunjungantidakvalid");
    exit;
}

// ========================================
// PROSES DETAIL KUNJUNGAN
// ========================================
try {

    mysqli_begin_transaction($koneksi);

    // ========================================
    // CEK DATA KUNJUNGAN
    // ========================================
    $queryKunjungan = "
        SELECT idkunjungan, statuskunjungan
        FROM kunjungan
        WHERE idkunjungan = ?
        FOR UPDATE
    ";

    $stmtKunjungan = mysqli_prepare($koneksi, $queryKunjungan);
    mysqli_stmt_bind_param($stmtKunjungan, "i", $idkunjungan);
    mysqli_stmt_execute($stmtKunjungan);

    $resultKunjungan = mysqli_stmt_get_result($stmtKunjungan);
    $dataKunjungan = mysqli_fetch_assoc($resultKunjungan);

    mysqli_stmt_close($stmtKunjungan);

    if (!$dataKunjungan) {
        throw new Exception("kunjungantidakditemukan");
    }

    // Detail hanya boleh diubah selama kunjungan masih berlangsung
    if ($dataKunjungan['statuskunjungan'] != "Proses") {
        throw new Exception("kunjungansudahselesai");
    }

    // ========================================
    // AKSI TAMBAH DETAIL OBAT
    // ========================================
    if ($aksi == "tambah") {

        $idobat = isset($_POST['idobat'])
            ? (int) $_POST['idobat'] : 0;

        $jumlah = isset($_POST['jumlah'])
            ? (int) $_POST['jumlah'] : 0;

        $aturanpakai = isset($_POST['aturanpakai'])
            ? trim($_POST['aturanpakai']) : '';

        if ($idobat <= 0 || $jumlah <= 0) {
            throw new Exception("datadetailtidakvalid");
        }

        // Cek apakah obat sudah ada pada detail kunjungan
        $queryCekDetail = "
            SELECT iddetilkunjungan
            FROM detilkunjungan
            WHERE idkunjungan = ?
            AND idobat = ?
        ";

        $stmtCekDetail = mysqli_prepare($koneksi, $queryCekDetail);
        mysqli_stmt_bind_param($stmtCekDetail, "ii", $idkunjungan, $idobat);
        mysqli_stmt_execute($stmtCekDetail);

        $resultCekDetail = mysqli_stmt_get_result($stmtCekDetail);
        $detailAda = mysqli_fetch_assoc($resultCekDetail);

        mysqli_stmt_close($stmtCekDetail);

        if ($detailAda) {
            throw new Exception("obatsudahada");
        }

        // Ambil harga dan stok obat
        $queryObat = "
            SELECT harga, stok
            FROM obat
            WHERE idobat = ?
            FOR UPDATE
        ";

        $stmtObat = mysqli_prepare($koneksi, $queryObat);
        mysqli_stmt_bind_param($stmtObat, "i", $idobat);
        mysqli_stmt_execute($stmtObat);

        $resultObat = mysqli_stmt_get_result($stmtObat);
        $dataObat = mysqli_fetch_assoc($resultObat);

        mysqli_stmt_close($stmtObat);

        if (!$dataObat) {
            throw new Exception("obattidakditemukan");
        }

        $harga = (int) $dataObat['harga'];
        $stok = (int) $dataObat['stok'];

        if ($jumlah > $stok) {
            throw new Exception("stokobattidakcukup");
        }

        $subtotal = $harga * $jumlah;

        // Simpan detail obat
        $queryTambah = "
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

        $stmtTambah = mysqli_prepare($koneksi, $queryTambah);

        mysqli_stmt_bind_param(
            $stmtTambah,
            "iiiiis",
            $idkunjungan,
            $idobat,
            $jumlah,
            $harga,
            $subtotal,
            $aturanpakai
        );

        mysqli_stmt_execute($stmtTambah);
        mysqli_stmt_close($stmtTambah);

        // Kurangi stok
        $queryStok = "
            UPDATE obat
            SET stok = stok - ?
            WHERE idobat = ?
            AND stok >= ?
        ";

        $stmtStok = mysqli_prepare($koneksi, $queryStok);

        mysqli_stmt_bind_param(
            $stmtStok,
            "iii",
            $jumlah,
            $idobat,
            $jumlah
        );

        mysqli_stmt_execute($stmtStok);

        if (mysqli_stmt_affected_rows($stmtStok) != 1) {
            mysqli_stmt_close($stmtStok);
            throw new Exception("stokobattidakcukup");
        }

        mysqli_stmt_close($stmtStok);

    // ========================================
    // AKSI UBAH DETAIL OBAT
    // ========================================
    } elseif ($aksi == "ubah") {

        $iddetilkunjungan = isset($_POST['iddetilkunjungan'])
            ? (int) $_POST['iddetilkunjungan'] : 0;

        $idobatBaru = isset($_POST['idobat'])
            ? (int) $_POST['idobat'] : 0;

        $jumlahBaru = isset($_POST['jumlah'])
            ? (int) $_POST['jumlah'] : 0;

        $aturanpakai = isset($_POST['aturanpakai'])
            ? trim($_POST['aturanpakai']) : '';

        if (
            $iddetilkunjungan <= 0 ||
            $idobatBaru <= 0 ||
            $jumlahBaru <= 0
        ) {
            throw new Exception("datadetailtidakvalid");
        }

        // Ambil detail lama
        $queryDetailLama = "
            SELECT idobat, jumlah
            FROM detilkunjungan
            WHERE iddetilkunjungan = ?
            AND idkunjungan = ?
            FOR UPDATE
        ";

        $stmtDetailLama = mysqli_prepare($koneksi, $queryDetailLama);

        mysqli_stmt_bind_param(
            $stmtDetailLama,
            "ii",
            $iddetilkunjungan,
            $idkunjungan
        );

        mysqli_stmt_execute($stmtDetailLama);

        $resultDetailLama = mysqli_stmt_get_result($stmtDetailLama);
        $dataDetailLama = mysqli_fetch_assoc($resultDetailLama);

        mysqli_stmt_close($stmtDetailLama);

        if (!$dataDetailLama) {
            throw new Exception("detailtidakditemukan");
        }

        $idobatLama = (int) $dataDetailLama['idobat'];
        $jumlahLama = (int) $dataDetailLama['jumlah'];

        // Jika obat diganti, pastikan tidak terjadi duplikasi
        if ($idobatBaru != $idobatLama) {

            $queryCekObat = "
                SELECT iddetilkunjungan
                FROM detilkunjungan
                WHERE idkunjungan = ?
                AND idobat = ?
                AND iddetilkunjungan != ?
            ";

            $stmtCekObat = mysqli_prepare($koneksi, $queryCekObat);

            mysqli_stmt_bind_param(
                $stmtCekObat,
                "iii",
                $idkunjungan,
                $idobatBaru,
                $iddetilkunjungan
            );

            mysqli_stmt_execute($stmtCekObat);

            $resultCekObat = mysqli_stmt_get_result($stmtCekObat);
            $obatDuplikat = mysqli_fetch_assoc($resultCekObat);

            mysqli_stmt_close($stmtCekObat);

            if ($obatDuplikat) {
                throw new Exception("obatsudahada");
            }
        }

        // Kembalikan stok obat lama terlebih dahulu
        $queryKembalikanStok = "
            UPDATE obat
            SET stok = stok + ?
            WHERE idobat = ?
        ";

        $stmtKembalikanStok = mysqli_prepare($koneksi, $queryKembalikanStok);

        mysqli_stmt_bind_param(
            $stmtKembalikanStok,
            "ii",
            $jumlahLama,
            $idobatLama
        );

        mysqli_stmt_execute($stmtKembalikanStok);
        mysqli_stmt_close($stmtKembalikanStok);

        // Ambil harga dan stok obat baru
        $queryObatBaru = "
            SELECT harga, stok
            FROM obat
            WHERE idobat = ?
            FOR UPDATE
        ";

        $stmtObatBaru = mysqli_prepare($koneksi, $queryObatBaru);
        mysqli_stmt_bind_param($stmtObatBaru, "i", $idobatBaru);
        mysqli_stmt_execute($stmtObatBaru);

        $resultObatBaru = mysqli_stmt_get_result($stmtObatBaru);
        $dataObatBaru = mysqli_fetch_assoc($resultObatBaru);

        mysqli_stmt_close($stmtObatBaru);

        if (!$dataObatBaru) {
            throw new Exception("obattidakditemukan");
        }

        $hargaBaru = (int) $dataObatBaru['harga'];
        $stokBaru = (int) $dataObatBaru['stok'];

        if ($jumlahBaru > $stokBaru) {
            throw new Exception("stokobattidakcukup");
        }

        $subtotalBaru = $hargaBaru * $jumlahBaru;

        // Update detail
        $queryUbah = "
            UPDATE detilkunjungan
            SET
                idobat = ?,
                jumlah = ?,
                harga = ?,
                subtotal = ?,
                aturanpakai = ?
            WHERE iddetilkunjungan = ?
            AND idkunjungan = ?
        ";

        $stmtUbah = mysqli_prepare($koneksi, $queryUbah);

        mysqli_stmt_bind_param(
            $stmtUbah,
            "iiiisii",
            $idobatBaru,
            $jumlahBaru,
            $hargaBaru,
            $subtotalBaru,
            $aturanpakai,
            $iddetilkunjungan,
            $idkunjungan
        );

        mysqli_stmt_execute($stmtUbah);
        mysqli_stmt_close($stmtUbah);

        // Kurangi stok obat baru
        $queryKurangiStok = "
            UPDATE obat
            SET stok = stok - ?
            WHERE idobat = ?
            AND stok >= ?
        ";

        $stmtKurangiStok = mysqli_prepare($koneksi, $queryKurangiStok);

        mysqli_stmt_bind_param(
            $stmtKurangiStok,
            "iii",
            $jumlahBaru,
            $idobatBaru,
            $jumlahBaru
        );

        mysqli_stmt_execute($stmtKurangiStok);

        if (mysqli_stmt_affected_rows($stmtKurangiStok) != 1) {
            mysqli_stmt_close($stmtKurangiStok);
            throw new Exception("stokobattidakcukup");
        }

        mysqli_stmt_close($stmtKurangiStok);

    // ========================================
    // AKSI HAPUS DETAIL OBAT
    // ========================================
    } elseif ($aksi == "hapus") {

        $iddetilkunjungan = isset($_POST['iddetilkunjungan'])
            ? (int) $_POST['iddetilkunjungan'] : 0;

        if ($iddetilkunjungan <= 0) {
            $iddetilkunjungan = isset($_GET['iddetilkunjungan'])
                ? (int) $_GET['iddetilkunjungan'] : 0;
        }

        if ($iddetilkunjungan <= 0) {
            throw new Exception("detailtidakvalid");
        }

        // Ambil detail yang akan dihapus
        $queryDetail = "
            SELECT idobat, jumlah
            FROM detilkunjungan
            WHERE iddetilkunjungan = ?
            AND idkunjungan = ?
            FOR UPDATE
        ";

        $stmtDetail = mysqli_prepare($koneksi, $queryDetail);

        mysqli_stmt_bind_param(
            $stmtDetail,
            "ii",
            $iddetilkunjungan,
            $idkunjungan
        );

        mysqli_stmt_execute($stmtDetail);

        $resultDetail = mysqli_stmt_get_result($stmtDetail);
        $dataDetail = mysqli_fetch_assoc($resultDetail);

        mysqli_stmt_close($stmtDetail);

        if (!$dataDetail) {
            throw new Exception("detailtidakditemukan");
        }

        $idobat = (int) $dataDetail['idobat'];
        $jumlah = (int) $dataDetail['jumlah'];

        // Kembalikan stok obat
        $queryStok = "
            UPDATE obat
            SET stok = stok + ?
            WHERE idobat = ?
        ";

        $stmtStok = mysqli_prepare($koneksi, $queryStok);

        mysqli_stmt_bind_param(
            $stmtStok,
            "ii",
            $jumlah,
            $idobat
        );

        mysqli_stmt_execute($stmtStok);
        mysqli_stmt_close($stmtStok);

        // Hapus detail
        $queryHapus = "
            DELETE FROM detilkunjungan
            WHERE iddetilkunjungan = ?
            AND idkunjungan = ?
        ";

        $stmtHapus = mysqli_prepare($koneksi, $queryHapus);

        mysqli_stmt_bind_param(
            $stmtHapus,
            "ii",
            $iddetilkunjungan,
            $idkunjungan
        );

        mysqli_stmt_execute($stmtHapus);
        mysqli_stmt_close($stmtHapus);

    } else {
        throw new Exception("aksitidakvalid");
    }

    // ========================================
    // HITUNG ULANG TOTAL KUNJUNGAN
    // ========================================
    $queryTotal = "
        SELECT COALESCE(SUM(subtotal), 0) AS total
        FROM detilkunjungan
        WHERE idkunjungan = ?
    ";

    $stmtTotal = mysqli_prepare($koneksi, $queryTotal);
    mysqli_stmt_bind_param($stmtTotal, "i", $idkunjungan);
    mysqli_stmt_execute($stmtTotal);

    $resultTotal = mysqli_stmt_get_result($stmtTotal);
    $dataTotal = mysqli_fetch_assoc($resultTotal);

    mysqli_stmt_close($stmtTotal);

    $totalKunjungan = (int) $dataTotal['total'];

    // Ambil jumlah pembayaran yang sudah tercatat
    $queryBayar = "
        SELECT jumlahbayar
        FROM kunjungan
        WHERE idkunjungan = ?
    ";

    $stmtBayar = mysqli_prepare($koneksi, $queryBayar);
    mysqli_stmt_bind_param($stmtBayar, "i", $idkunjungan);
    mysqli_stmt_execute($stmtBayar);

    $resultBayar = mysqli_stmt_get_result($stmtBayar);
    $dataBayar = mysqli_fetch_assoc($resultBayar);

    mysqli_stmt_close($stmtBayar);

    $jumlahbayar = (int) $dataBayar['jumlahbayar'];

    $kembalian = 0;

    if ($jumlahbayar > $totalKunjungan) {
        $kembalian = $jumlahbayar - $totalKunjungan;
    }

    if ($jumlahbayar >= $totalKunjungan) {
        $statuspembayaran = "Lunas";
    } else {
        $statuspembayaran = "Belum Bayar";
    }

    // Update total dan status pembayaran
    $queryUpdateTotal = "
        UPDATE kunjungan
        SET
            totalkunjungan = ?,
            kembalian = ?,
            statuspembayaran = ?
        WHERE idkunjungan = ?
    ";

    $stmtUpdateTotal = mysqli_prepare($koneksi, $queryUpdateTotal);

    mysqli_stmt_bind_param(
        $stmtUpdateTotal,
        "iisi",
        $totalKunjungan,
        $kembalian,
        $statuspembayaran,
        $idkunjungan
    );

    mysqli_stmt_execute($stmtUpdateTotal);
    mysqli_stmt_close($stmtUpdateTotal);

    // ========================================
    // COMMIT TRANSAKSI
    // ========================================
    mysqli_commit($koneksi);

    header(
        "location:../index.php?page=showkunjungan&idkunjungan="
        . $idkunjungan
        . "&status="
        . $aksi
    );
    exit;

} catch (Throwable $e) {

    mysqli_rollback($koneksi);

    $statusError = $e->getMessage();

    $daftarError = [
        "kunjungantidakditemukan",
        "kunjungansudahselesai",
        "datadetailtidakvalid",
        "detailtidakvalid",
        "detailtidakditemukan",
        "obatsudahada",
        "obattidakditemukan",
        "stokobattidakcukup",
        "aksitidakvalid"
    ];

    if (!in_array($statusError, $daftarError)) {
        $statusError = "gagal";
    }

    header(
        "location:../index.php?page=showkunjungan&idkunjungan="
        . $idkunjungan
        . "&status="
        . $statusError
    );
    exit;
}
