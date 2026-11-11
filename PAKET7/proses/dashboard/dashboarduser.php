
<?php
// =====================================================
// PROSES DASHBOARD ADMIN DAN PETUGAS
// File: proses/dashboard/dashboarduser.php
// =====================================================

// Proses hanya boleh dipanggil melalui index.php
if (basename($_SERVER['SCRIPT_NAME']) !== 'index.php') {
    http_response_code(403);
    exit('Akses langsung tidak diizinkan.');
}

// =====================================================
// FUNGSI MENGHITUNG JUMLAH DATA
// =====================================================

$ambilJumlah = function ($sql) use ($koneksi) {
    try {
        $hasil = mysqli_query($koneksi, $sql);

        if (!$hasil) {
            error_log('Query jumlah dashboard gagal: ' . mysqli_error($koneksi));
            return 0;
        }

        $data = mysqli_fetch_assoc($hasil);
        return (int) ($data['jumlah'] ?? 0);

    } catch (Throwable $e) {
        error_log('Error jumlah dashboard: ' . $e->getMessage());
        return 0;
    }
};

// =====================================================
// FUNGSI MENGAMBIL DATA TABEL
// Hasil berupa mysqli_result agar dapat digunakan
// dengan mysqli_num_rows() dan mysqli_fetch_assoc()
// =====================================================

$ambilHasil = function ($sql) use ($koneksi) {
    try {
        $hasil = mysqli_query($koneksi, $sql);

        if (!$hasil) {
            error_log('Query data dashboard gagal: ' . mysqli_error($koneksi));
            return false;
        }

        return $hasil;

    } catch (Throwable $e) {
        error_log('Error data dashboard: ' . $e->getMessage());
        return false;
    }
};

// =====================================================
// 1. RINGKASAN DATA KLINIK
// =====================================================

$totalAdmin = $ambilJumlah("
    SELECT COUNT(*) AS jumlah
    FROM user
    WHERE level = 'admin'
");

$totalPetugas = $ambilJumlah("
    SELECT COUNT(*) AS jumlah
    FROM user
    WHERE level = 'petugas'
");

$totalPasien = $ambilJumlah("
    SELECT COUNT(*) AS jumlah
    FROM pasien
");

$totalDokter = $ambilJumlah("
    SELECT COUNT(*) AS jumlah
    FROM dokter
");

$totalObat = $ambilJumlah("
    SELECT COUNT(*) AS jumlah
    FROM obat
");

$totalJadwal = $ambilJumlah("
    SELECT COUNT(*) AS jumlah
    FROM jadwaldokter
");

$totalPendaftaran = $ambilJumlah("
    SELECT COUNT(*) AS jumlah
    FROM pendaftarancheckup
");

$totalKunjungan = $ambilJumlah("
    SELECT COUNT(*) AS jumlah
    FROM kunjungan
");

// =====================================================
// 2. RINGKASAN STATUS PENDAFTARAN
// =====================================================

$totalMenunggu = $ambilJumlah("
    SELECT COUNT(*) AS jumlah
    FROM pendaftarancheckup
    WHERE status = 'Menunggu'
");

$totalDisetujui = $ambilJumlah("
    SELECT COUNT(*) AS jumlah
    FROM pendaftarancheckup
    WHERE status = 'Disetujui'
");

$totalDitolak = $ambilJumlah("
    SELECT COUNT(*) AS jumlah
    FROM pendaftarancheckup
    WHERE status = 'Ditolak'
");

// =====================================================
// 3. RINGKASAN ANTRIAN DAN PEMBAYARAN
// =====================================================

// Jumlah pendaftaran yang sudah mendapatkan nomor
// antrian untuk tanggal periksa hari ini
$totalAntrian = $ambilJumlah("
    SELECT COUNT(*) AS jumlah
    FROM pendaftarancheckup
    WHERE tanggalperiksa = CURDATE()
    AND nomorantrian IS NOT NULL
");

// Jumlah antrian yang sudah mendapatkan nomor
// dan masih menunggu berdasarkan statusantrian
$antrianMenunggu = $ambilJumlah("
    SELECT COUNT(*) AS jumlah
    FROM pendaftarancheckup
    WHERE tanggalperiksa = CURDATE()
    AND nomorantrian IS NOT NULL
    AND LOWER(statusantrian) = 'menunggu'
");

// Jumlah kunjungan yang belum lunas
$totalBelumLunas = $ambilJumlah("
    SELECT COUNT(*) AS jumlah
    FROM kunjungan
    WHERE statuspembayaran = 'Belum Bayar'
");

// =====================================================
// 4. STATISTIK KUNJUNGAN HARI INI
// =====================================================

$kunjunganHariIni = $ambilJumlah("
    SELECT COUNT(*) AS jumlah
    FROM kunjungan
    WHERE DATE(tanggalkunjungan) = CURDATE()
");

// =====================================================
// 5. DATA STOK OBAT MENIPIS
// =====================================================

$stokMenipis = $ambilJumlah("
    SELECT COUNT(*) AS jumlah
    FROM obat
    WHERE stok <= 10
");

$daftarStokMenipis = $ambilHasil("
    SELECT
        idobat,
        namaobat,
        stok
    FROM obat
    WHERE stok <= 10
    ORDER BY stok ASC, namaobat ASC
    LIMIT 6
");

// =====================================================
// 6. JADWAL DOKTER MINGGU INI
// =====================================================

$daftarHari = [
    'senin',
    'selasa',
    'rabu',
    'kamis',
    'jumat',
    'sabtu',
    'minggu'
];

$posisiHariIni = (int) date('N') - 1;

$hariMingguIni = array_merge(
    array_slice($daftarHari, $posisiHariIni),
    array_slice($daftarHari, 0, $posisiHariIni)
);

$hariSQL = "'" . implode("','", $hariMingguIni) . "'";

$queryJadwalMinggu = $ambilHasil("
    SELECT
        dokter.namadokter,
        jadwaldokter.haripraktik,
        jadwaldokter.jam_mulai,
        jadwaldokter.jam_selesai
    FROM jadwaldokter
    INNER JOIN dokter
        ON jadwaldokter.iddokter = dokter.iddokter
    WHERE LOWER(jadwaldokter.haripraktik) IN ($hariSQL)
    ORDER BY
        FIELD(LOWER(jadwaldokter.haripraktik), $hariSQL),
        jadwaldokter.jam_mulai
");

// =====================================================
// 7. PENDAFTARAN CHECKUP TERBARU
// =====================================================

$queryPendaftaran = $ambilHasil("
    SELECT
        pendaftarancheckup.idpendaftaran,
        pendaftarancheckup.tanggalperiksa,
        pendaftarancheckup.jenispendaftaran,
        pendaftarancheckup.nomorantrian,
        pendaftarancheckup.status,
        pendaftarancheckup.statusantrian,
        pasien.namapasien,
        dokter.namadokter
    FROM pendaftarancheckup
    INNER JOIN pasien
        ON pendaftarancheckup.idpasien = pasien.idpasien
    INNER JOIN jadwaldokter
        ON pendaftarancheckup.idjadwal = jadwaldokter.idjadwal
    INNER JOIN dokter
        ON jadwaldokter.iddokter = dokter.iddokter
    ORDER BY
        pendaftarancheckup.idpendaftaran DESC
    LIMIT 10
");

// =====================================================
// 8. 10 KUNJUNGAN TERAKHIR
// =====================================================

$queryKunjungan = $ambilHasil("
    SELECT
        kunjungan.idkunjungan,
        kunjungan.tanggalkunjungan,
        kunjungan.totalkunjungan,
        kunjungan.statuskunjungan,
        kunjungan.statuspembayaran,
        pasien.namapasien,
        dokter.namadokter,
        user.namauser
    FROM kunjungan
    INNER JOIN pasien
        ON kunjungan.idpasien = pasien.idpasien
    INNER JOIN dokter
        ON kunjungan.iddokter = dokter.iddokter
    INNER JOIN user
        ON kunjungan.iduser = user.iduser
    ORDER BY
        kunjungan.idkunjungan DESC
    LIMIT 10
");

// =====================================================
// 9. KUNJUNGAN TERBARU DALAM BENTUK ARRAY
// Digunakan jika diperlukan oleh komponen dashboard
// =====================================================

$kunjunganTerbaru = [];

if ($queryKunjungan) {
    while ($baris = mysqli_fetch_assoc($queryKunjungan)) {
        $kunjunganTerbaru[] = $baris;
    }

    // Jalankan ulang query agar tetap tersedia
    // untuk tampilan dashboard yang menggunakan mysqli_fetch_assoc()
    $queryKunjungan = $ambilHasil("
        SELECT
            kunjungan.idkunjungan,
            kunjungan.tanggalkunjungan,
            kunjungan.totalkunjungan,
            kunjungan.statuskunjungan,
            kunjungan.statuspembayaran,
            pasien.namapasien,
            dokter.namadokter,
            user.namauser
        FROM kunjungan
        INNER JOIN pasien
            ON kunjungan.idpasien = pasien.idpasien
        INNER JOIN dokter
            ON kunjungan.iddokter = dokter.iddokter
        INNER JOIN user
            ON kunjungan.iduser = user.iduser
        ORDER BY
            kunjungan.idkunjungan DESC
        LIMIT 10
    ");
}

?>
