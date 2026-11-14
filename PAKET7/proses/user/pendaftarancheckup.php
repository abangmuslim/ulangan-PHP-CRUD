
<?php
// ======================================================
// PROSES PENDAFTARAN CHECKUP
// ZONA ADMIN DAN PETUGAS
// ======================================================

// ======================================================
// 1. PEMERIKSAAN REQUEST
// ======================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php?page=pendaftarancheckup");
    exit;
}

// ======================================================
// 2. PEMERIKSAAN SESSION ADMIN / PETUGAS
// ======================================================

if (
    !isset($_SESSION['iduser']) ||
    !isset($_SESSION['level']) ||
    !in_array($_SESSION['level'], ['admin', 'petugas'], true)
) {
    header("Location: index.php?page=loginuser");
    exit;
}

$iduser = (int)$_SESSION['iduser'];
$level = $_SESSION['level'];

// ======================================================
// 3. PEMERIKSAAN CSRF
// ======================================================

csrfCheck();

// ======================================================
// 4. FUNGSI BANTU
// ======================================================

// Kembali ke halaman pendaftaran
$kembali = function ($pesan) {
    header("Location: index.php?page=pendaftarancheckup&pesan=" . urlencode($pesan));
    exit;
};

// Mengubah nama hari bahasa Inggris menjadi Indonesia
$hariIndonesia = function ($tanggal) {

    $hari = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];

    $namaHari = date('l', strtotime($tanggal));

    return $hari[$namaHari] ?? '';
};

// Memeriksa format tanggal
$tanggalValid = function ($tanggal) {

    $format = DateTime::createFromFormat('!Y-m-d', $tanggal);

    return $format && $format->format('Y-m-d') === $tanggal;
};

// ======================================================
// 5. AMBIL DATA POST
// ======================================================

$aksi = $_POST['aksi'] ?? '';

$idpendaftaran = isset($_POST['idpendaftaran'])
    ? (int)$_POST['idpendaftaran']
    : 0;

// ======================================================
// 6. PROSES TAMBAH PENDAFTARAN LANGSUNG
// ======================================================

if ($aksi === 'tambah') {

    // ----------------------------------------------
    // Ambil input
    // ----------------------------------------------

    $idpasien = isset($_POST['idpasien'])
        ? (int)$_POST['idpasien']
        : 0;

    $idjadwal = isset($_POST['idjadwal'])
        ? (int)$_POST['idjadwal']
        : 0;

    $tanggalperiksa = trim($_POST['tanggalperiksa'] ?? '');
    $keluhan = trim($_POST['keluhan'] ?? '');

    // ----------------------------------------------
    // Validasi input
    // ----------------------------------------------

    if (
        $idpasien <= 0 ||
        $idjadwal <= 0 ||
        $tanggalperiksa === '' ||
        $keluhan === ''
    ) {
        $kembali('gagal');
    }

    if (!$tanggalValid($tanggalperiksa)) {
        $kembali('gagal');
    }

    if ($tanggalperiksa < date('Y-m-d')) {
        $kembali('gagal');
    }

    // ----------------------------------------------
    // Periksa pasien
    // ----------------------------------------------

    $stmtPasien = mysqli_prepare($koneksi, "
        SELECT idpasien
        FROM pasien
        WHERE idpasien = ?
        LIMIT 1
    ");

    mysqli_stmt_bind_param($stmtPasien, "i", $idpasien);
    mysqli_stmt_execute($stmtPasien);

    $hasilPasien = mysqli_stmt_get_result($stmtPasien);
    $pasien = mysqli_fetch_assoc($hasilPasien);

    mysqli_stmt_close($stmtPasien);

    if (!$pasien) {
        $kembali('gagal');
    }

    // ----------------------------------------------
    // Periksa jadwal dokter
    // ----------------------------------------------

    $stmtJadwal = mysqli_prepare($koneksi, "
        SELECT
            idjadwal,
            haripraktik
        FROM jadwaldokter
        WHERE idjadwal = ?
        LIMIT 1
    ");

    mysqli_stmt_bind_param($stmtJadwal, "i", $idjadwal);
    mysqli_stmt_execute($stmtJadwal);

    $hasilJadwal = mysqli_stmt_get_result($stmtJadwal);
    $jadwal = mysqli_fetch_assoc($hasilJadwal);

    mysqli_stmt_close($stmtJadwal);

    if (!$jadwal) {
        $kembali('gagal');
    }

    // ----------------------------------------------
    // Validasi hari praktik
    // ----------------------------------------------

    $hariPeriksa = $hariIndonesia($tanggalperiksa);

    if ($hariPeriksa !== $jadwal['haripraktik']) {
        $kembali('gagal');
    }

    // ----------------------------------------------
    // Cegah pendaftaran ganda
    // Pasien, jadwal, dan tanggal yang sama
    // ----------------------------------------------

    $stmtDuplikat = mysqli_prepare($koneksi, "
        SELECT idpendaftaran
        FROM pendaftarancheckup
        WHERE idpasien = ?
          AND idjadwal = ?
          AND tanggalperiksa = ?
          AND status IN ('Menunggu', 'Disetujui')
        LIMIT 1
    ");

    mysqli_stmt_bind_param(
        $stmtDuplikat,
        "iis",
        $idpasien,
        $idjadwal,
        $tanggalperiksa
    );

    mysqli_stmt_execute($stmtDuplikat);

    $hasilDuplikat = mysqli_stmt_get_result($stmtDuplikat);
    $duplikat = mysqli_fetch_assoc($hasilDuplikat);

    mysqli_stmt_close($stmtDuplikat);

    if ($duplikat) {
        $kembali('sudah diproses');
    }

    // ----------------------------------------------
    // Simpan pendaftaran langsung
    // ----------------------------------------------

    $jenispendaftaran = 'Langsung';
    $status = 'Menunggu';
    $statusantrian = 'Belum Antri';

    $stmtTambah = mysqli_prepare($koneksi, "
        INSERT INTO pendaftarancheckup
        (
            idpasien,
            idjadwal,
            tanggalperiksa,
            keluhan,
            jenispendaftaran,
            status,
            statusantrian
        )
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    mysqli_stmt_bind_param(
        $stmtTambah,
        "iisssss",
        $idpasien,
        $idjadwal,
        $tanggalperiksa,
        $keluhan,
        $jenispendaftaran,
        $status,
        $statusantrian
    );

    $berhasil = mysqli_stmt_execute($stmtTambah);

    mysqli_stmt_close($stmtTambah);

    if ($berhasil) {
        $kembali('berhasil');
    }

    $kembali('gagal');
}

// ======================================================
// 7. PROSES MENYETUJUI PENDAFTARAN
// ======================================================

elseif ($aksi === 'setujui') {

    if ($idpendaftaran <= 0) {
        $kembali('data tidak ditemukan');
    }

    mysqli_begin_transaction($koneksi);

    try {

        // ------------------------------------------
        // Ambil dan kunci data pendaftaran
        // ------------------------------------------

        $stmtDaftar = mysqli_prepare($koneksi, "
            SELECT
                idpendaftaran,
                idpasien,
                idjadwal,
                idkunjungan,
                tanggalperiksa,
                keluhan,
                status
            FROM pendaftarancheckup
            WHERE idpendaftaran = ?
            FOR UPDATE
        ");

        mysqli_stmt_bind_param(
            $stmtDaftar,
            "i",
            $idpendaftaran
        );

        mysqli_stmt_execute($stmtDaftar);

        $hasilDaftar = mysqli_stmt_get_result($stmtDaftar);
        $pendaftaran = mysqli_fetch_assoc($hasilDaftar);

        mysqli_stmt_close($stmtDaftar);

        if (!$pendaftaran) {
            throw new Exception('Data pendaftaran tidak ditemukan.');
        }

        // ------------------------------------------
        // Pastikan belum pernah diproses
        // ------------------------------------------

        if (
            $pendaftaran['status'] !== 'Menunggu' ||
            !empty($pendaftaran['idkunjungan'])
        ) {
            throw new Exception('Pendaftaran sudah diproses.');
        }

        $idpasien = (int)$pendaftaran['idpasien'];
        $idjadwal = (int)$pendaftaran['idjadwal'];
        $tanggalperiksa = $pendaftaran['tanggalperiksa'];
        $keluhan = $pendaftaran['keluhan'];

        // ------------------------------------------
        // Validasi tanggal pemeriksaan
        // ------------------------------------------

        if (
            !$tanggalValid($tanggalperiksa) ||
            $tanggalperiksa < date('Y-m-d')
        ) {
            throw new Exception('Tanggal pemeriksaan tidak valid.');
        }

        // ------------------------------------------
        // Kunci jadwal dokter
        // ------------------------------------------

        $stmtJadwal = mysqli_prepare($koneksi, "
            SELECT
                idjadwal,
                iddokter,
                haripraktik
            FROM jadwaldokter
            WHERE idjadwal = ?
            FOR UPDATE
        ");

        mysqli_stmt_bind_param(
            $stmtJadwal,
            "i",
            $idjadwal
        );

        mysqli_stmt_execute($stmtJadwal);

        $hasilJadwal = mysqli_stmt_get_result($stmtJadwal);
        $jadwal = mysqli_fetch_assoc($hasilJadwal);

        mysqli_stmt_close($stmtJadwal);

        if (!$jadwal) {
            throw new Exception('Jadwal dokter tidak ditemukan.');
        }

        $iddokter = (int)$jadwal['iddokter'];

        // ------------------------------------------
        // Pastikan tanggal sesuai hari praktik
        // ------------------------------------------

        $hariPeriksa = $hariIndonesia($tanggalperiksa);

        if ($hariPeriksa !== $jadwal['haripraktik']) {
            throw new Exception('Tanggal tidak sesuai jadwal praktik dokter.');
        }

        // ------------------------------------------
        // Tentukan nomor antrean berikutnya
        // Berdasarkan jadwal dan tanggal periksa
        // ------------------------------------------

        $stmtAntrian = mysqli_prepare($koneksi, "
            SELECT COALESCE(MAX(nomorantrian), 0) + 1 AS berikutnya
            FROM pendaftarancheckup
            WHERE idjadwal = ?
              AND tanggalperiksa = ?
              AND status = 'Disetujui'
        ");

        mysqli_stmt_bind_param(
            $stmtAntrian,
            "is",
            $idjadwal,
            $tanggalperiksa
        );

        mysqli_stmt_execute($stmtAntrian);

        $hasilAntrian = mysqli_stmt_get_result($stmtAntrian);
        $dataAntrian = mysqli_fetch_assoc($hasilAntrian);

        mysqli_stmt_close($stmtAntrian);

        $nomorantrian = (int)$dataAntrian['berikutnya'];

        // ------------------------------------------
        // Buat data kunjungan
        // ------------------------------------------

        $stmtKunjungan = mysqli_prepare($koneksi, "
            INSERT INTO kunjungan
            (
                iduser,
                idpasien,
                iddokter,
                idjadwal,
                tanggalkunjungan,
                keluhan,
                totalkunjungan,
                jumlahbayar,
                kembalian,
                statuspembayaran,
                statuskunjungan
            )
            VALUES
            (
                ?, ?, ?, ?, ?, ?,
                0, 0, 0,
                'Belum Bayar',
                'Proses'
            )
        ");

        mysqli_stmt_bind_param(
            $stmtKunjungan,
            "iiiiss",
            $iduser,
            $idpasien,
            $iddokter,
            $idjadwal,
            $tanggalperiksa,
            $keluhan
        );

        if (!mysqli_stmt_execute($stmtKunjungan)) {
            throw new Exception('Gagal membuat data kunjungan.');
        }

        $idkunjungan = mysqli_insert_id($koneksi);

        mysqli_stmt_close($stmtKunjungan);

        if ($idkunjungan <= 0) {
            throw new Exception('ID kunjungan tidak berhasil dibuat.');
        }

        // ------------------------------------------
        // Perbarui pendaftaran
        // ------------------------------------------

        $status = 'Disetujui';
        $statusantrian = 'Menunggu';

        $stmtUpdate = mysqli_prepare($koneksi, "
            UPDATE pendaftarancheckup
            SET
                idkunjungan = ?,
                nomorantrian = ?,
                status = ?,
                statusantrian = ?
            WHERE idpendaftaran = ?
        ");

        mysqli_stmt_bind_param(
            $stmtUpdate,
            "iissi",
            $idkunjungan,
            $nomorantrian,
            $status,
            $statusantrian,
            $idpendaftaran
        );

        if (!mysqli_stmt_execute($stmtUpdate)) {
            throw new Exception('Gagal memperbarui status pendaftaran.');
        }

        mysqli_stmt_close($stmtUpdate);

        // ------------------------------------------
        // Simpan seluruh perubahan
        // ------------------------------------------

        mysqli_commit($koneksi);

        $kembali('disetujui');

    } catch (Throwable $e) {

        mysqli_rollback($koneksi);

        // Jika terjadi kesalahan, kembalikan pesan umum
        $kembali('gagal');
    }
}

// ======================================================
// 8. PROSES MENOLAK PENDAFTARAN
// ======================================================

elseif ($aksi === 'tolak') {

    if ($idpendaftaran <= 0) {
        $kembali('data tidak ditemukan');
    }

    $catatanadmin = trim($_POST['catatanadmin'] ?? '');

    if ($catatanadmin === '') {
        $kembali('gagal');
    }

    mysqli_begin_transaction($koneksi);

    try {

        // ------------------------------------------
        // Kunci data pendaftaran
        // ------------------------------------------

        $stmtDaftar = mysqli_prepare($koneksi, "
            SELECT
                idpendaftaran,
                status,
                idkunjungan
            FROM pendaftarancheckup
            WHERE idpendaftaran = ?
            FOR UPDATE
        ");

        mysqli_stmt_bind_param(
            $stmtDaftar,
            "i",
            $idpendaftaran
        );

        mysqli_stmt_execute($stmtDaftar);

        $hasilDaftar = mysqli_stmt_get_result($stmtDaftar);
        $pendaftaran = mysqli_fetch_assoc($hasilDaftar);

        mysqli_stmt_close($stmtDaftar);

        if (!$pendaftaran) {
            throw new Exception('Data pendaftaran tidak ditemukan.');
        }

        // ------------------------------------------
        // Pastikan masih menunggu
        // ------------------------------------------

        if (
            $pendaftaran['status'] !== 'Menunggu' ||
            !empty($pendaftaran['idkunjungan'])
        ) {
            throw new Exception('Pendaftaran sudah diproses.');
        }

        // ------------------------------------------
        // Perbarui status menjadi ditolak
        // ------------------------------------------

        $status = 'Ditolak';
        $statusantrian = 'Belum Antri';

        $stmtTolak = mysqli_prepare($koneksi, "
            UPDATE pendaftarancheckup
            SET
                status = ?,
                statusantrian = ?,
                nomorantrian = NULL,
                catatanadmin = ?
            WHERE idpendaftaran = ?
        ");

        mysqli_stmt_bind_param(
            $stmtTolak,
            "sssi",
            $status,
            $statusantrian,
            $catatanadmin,
            $idpendaftaran
        );

        if (!mysqli_stmt_execute($stmtTolak)) {
            throw new Exception('Gagal menolak pendaftaran.');
        }

        mysqli_stmt_close($stmtTolak);

        // ------------------------------------------
        // Simpan perubahan
        // ------------------------------------------

        mysqli_commit($koneksi);

        $kembali('ditolak');

    } catch (Throwable $e) {

        mysqli_rollback($koneksi);

        $kembali('gagal');
    }
}

// ======================================================
// 9. AKSI TIDAK DIKENALI
// ======================================================

else {

    $kembali('gagal');

}
