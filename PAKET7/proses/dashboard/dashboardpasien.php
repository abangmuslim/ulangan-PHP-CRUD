
<?php
// =====================================================
// PROSES DASHBOARD PASIEN
// =====================================================


// =====================================================
// 1. IDENTITAS DAN VALIDASI PASIEN
// =====================================================

$idpasien = (int) ($_SESSION['idpasien'] ?? 0);

if ($idpasien <= 0) {
    $_SESSION['error_login'] = 'Silakan login sebagai pasien terlebih dahulu.';
    header('Location: index.php?page=loginpasien');
    exit;
}


// =====================================================
// 2. DATA PROFIL PASIEN
// =====================================================

$queryProfil = mysqli_query($koneksi, "
    SELECT *
    FROM pasien
    WHERE idpasien = '$idpasien'
    LIMIT 1
");

$dataPasien = $queryProfil
    ? mysqli_fetch_assoc($queryProfil)
    : null;

if (!$dataPasien) {
    $_SESSION['error_login'] = 'Data pasien tidak ditemukan. Silakan login kembali.';
    header('Location: index.php?page=loginpasien');
    exit;
}

$namapasien = $dataPasien['namapasien'] ?? 'Pasien';

$fotoPasien = !empty($dataPasien['foto'])
    ? $dataPasien['foto']
    : 'default.png';


// =====================================================
// 3. SAPAAN BERDASARKAN WAKTU
// =====================================================

$jam = (int) date('H');

if ($jam >= 4 && $jam < 11) {
    $sapaan = 'Selamat Pagi';
} elseif ($jam >= 11 && $jam < 15) {
    $sapaan = 'Selamat Siang';
} elseif ($jam >= 15 && $jam < 18) {
    $sapaan = 'Selamat Sore';
} else {
    $sapaan = 'Selamat Malam';
}


// =====================================================
// 4. RINGKASAN KUNJUNGAN PASIEN
// =====================================================

$queryRingkasan = mysqli_query($koneksi, "
    SELECT
        COUNT(*) AS total_kunjungan,
        COALESCE(SUM(totalkunjungan), 0) AS total_biaya
    FROM kunjungan
    WHERE idpasien = '$idpasien'
");

$dataRingkasan = $queryRingkasan
    ? mysqli_fetch_assoc($queryRingkasan)
    : null;

$totalKunjungan = (int) ($dataRingkasan['total_kunjungan'] ?? 0);
$totalBiaya = (float) ($dataRingkasan['total_biaya'] ?? 0);


// =====================================================
// 5. RINGKASAN PENDAFTARAN PASIEN
// =====================================================

$queryPendaftaran = mysqli_query($koneksi, "
    SELECT
        COUNT(*) AS total_pendaftaran,
        SUM(CASE WHEN status = 'Menunggu' THEN 1 ELSE 0 END) AS menunggu,
        SUM(CASE WHEN status = 'Disetujui' THEN 1 ELSE 0 END) AS disetujui,
        SUM(CASE WHEN status = 'Ditolak' THEN 1 ELSE 0 END) AS ditolak
    FROM pendaftarancheckup
    WHERE idpasien = '$idpasien'
");

$dataPendaftaran = $queryPendaftaran
    ? mysqli_fetch_assoc($queryPendaftaran)
    : null;

$totalPendaftaran = (int) ($dataPendaftaran['total_pendaftaran'] ?? 0);
$totalMenunggu = (int) ($dataPendaftaran['menunggu'] ?? 0);
$totalDisetujui = (int) ($dataPendaftaran['disetujui'] ?? 0);
$totalDitolak = (int) ($dataPendaftaran['ditolak'] ?? 0);


// =====================================================
// 6. RINGKASAN ANTREAN AKTIF HARI INI
// =====================================================

$queryAntreanAktif = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM pendaftarancheckup
    WHERE idpasien = '$idpasien'
    AND tanggalperiksa = CURDATE()
    AND status = 'Disetujui'
    AND statusantrian IN ('Menunggu', 'Dipanggil', 'Diperiksa')
");

$dataAntreanAktif = $queryAntreanAktif
    ? mysqli_fetch_assoc($queryAntreanAktif)
    : null;

$totalAntreanAktif = (int) ($dataAntreanAktif['total'] ?? 0);


// =====================================================
// 7. DAFTAR HARI
// =====================================================

$daftarHari = [
    1 => 'senin',
    2 => 'selasa',
    3 => 'rabu',
    4 => 'kamis',
    5 => 'jumat',
    6 => 'sabtu',
    7 => 'minggu'
];

$namaHari = [
    'senin' => 'Senin',
    'selasa' => 'Selasa',
    'rabu' => 'Rabu',
    'kamis' => 'Kamis',
    'jumat' => 'Jumat',
    'sabtu' => 'Sabtu',
    'minggu' => 'Minggu'
];


// =====================================================
// 8. DATA SELURUH JADWAL DOKTER
// =====================================================

$querySemuaJadwal = mysqli_query($koneksi, "
    SELECT
        j.idjadwal,
        j.haripraktik,
        j.jam_mulai,
        j.jam_selesai,
        d.namadokter,
        d.spesialis
    FROM jadwaldokter j
    INNER JOIN dokter d ON j.iddokter = d.iddokter
    ORDER BY j.jam_mulai ASC
");

$jadwalDokter = [];

if ($querySemuaJadwal) {
    while ($dataJadwal = mysqli_fetch_assoc($querySemuaJadwal)) {
        $hari = strtolower(trim($dataJadwal['haripraktik']));
        $jadwalDokter[$hari][] = $dataJadwal;
    }
}


// =====================================================
// 9. JADWAL DOKTER 7 HARI KE DEPAN
// =====================================================

$jadwalTujuhHari = [];

for ($i = 0; $i < 7; $i++) {

    $tanggal = date('Y-m-d', strtotime("+$i day"));

    $nomorHari = (int) date('N', strtotime($tanggal));

    $hari = $daftarHari[$nomorHari];

    $jadwalTujuhHari[] = [
        'tanggal' => $tanggal,
        'hari' => $hari,
        'jadwal' => $jadwalDokter[$hari] ?? []
    ];
}


// =====================================================
// 10. JADWAL DOKTER HARI INI
// =====================================================

$hariSekarang = $daftarHari[(int) date('N')];

$jamSekarang = date('H:i:s');

$jadwalHariIni = $jadwalDokter[$hariSekarang] ?? [];

$statusLayanan = false;

foreach ($jadwalHariIni as $jadwal) {

    if (
        $jamSekarang >= $jadwal['jam_mulai'] &&
        $jamSekarang <= $jadwal['jam_selesai']
    ) {
        $statusLayanan = true;
        break;
    }
}


// =====================================================
// 11. LIMA PENDAFTARAN TERAKHIR
// =====================================================

$queryPendaftaranTerakhir = mysqli_query($koneksi, "
    SELECT
        pc.idpendaftaran,
        pc.tanggalperiksa,
        pc.jenispendaftaran,
        pc.nomorantrian,
        pc.status,
        pc.statusantrian,
        d.namadokter,
        d.spesialis
    FROM pendaftarancheckup pc
    INNER JOIN jadwaldokter j ON pc.idjadwal = j.idjadwal
    INNER JOIN dokter d ON j.iddokter = d.iddokter
    WHERE pc.idpasien = '$idpasien'
    ORDER BY
        pc.tanggalpengajuan DESC,
        pc.idpendaftaran DESC
    LIMIT 5
");


// =====================================================
// 12. LIMA RIWAYAT KUNJUNGAN TERAKHIR
// =====================================================

$queryRiwayat = mysqli_query($koneksi, "
    SELECT
        k.idkunjungan,
        k.tanggalkunjungan,
        k.keluhan,
        k.diagnosis,
        k.totalkunjungan,
        d.namadokter
    FROM kunjungan k
    INNER JOIN dokter d ON k.iddokter = d.iddokter
    WHERE k.idpasien = '$idpasien'
    ORDER BY
        k.tanggalkunjungan DESC,
        k.idkunjungan DESC
    LIMIT 5
");

?>
