
<?php
// ============================================
// DASHBOARD ADMIN DAN PETUGAS
// ============================================

// TANGGAL DAN WAKTU
$tanggalDashboard = date('d-m-Y');
$hariSekarang = date('l');

$daftarHari = [
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu',
    'Sunday' => 'Minggu'
];

$hariIni = $daftarHari[$hariSekarang];

// ============================================
// STATISTIK PENGGUNA
// ============================================
$queryAdmin = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM user WHERE level='admin'");
$totalAdmin = mysqli_fetch_assoc($queryAdmin)['total'];

$queryPetugas = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM user WHERE level='petugas'");
$totalPetugas = mysqli_fetch_assoc($queryPetugas)['total'];

$queryPasien = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pasien");
$totalPasien = mysqli_fetch_assoc($queryPasien)['total'];

$queryDokter = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM dokter");
$totalDokter = mysqli_fetch_assoc($queryDokter)['total'];

// ============================================
// STATISTIK OBAT DAN JADWAL
// ============================================
$queryObat = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM obat");
$totalObat = mysqli_fetch_assoc($queryObat)['total'];

$queryJadwal = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM jadwaldokter");
$totalJadwal = mysqli_fetch_assoc($queryJadwal)['total'];

// ============================================
// STATISTIK PENDAFTARAN
// ============================================
$queryPendaftaranTotal = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pendaftarancheckup");
$totalPendaftaran = mysqli_fetch_assoc($queryPendaftaranTotal)['total'];

$queryMenunggu = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pendaftarancheckup WHERE status='Menunggu'");
$totalMenunggu = mysqli_fetch_assoc($queryMenunggu)['total'];

$queryDisetujui = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pendaftarancheckup WHERE status='Disetujui'");
$totalDisetujui = mysqli_fetch_assoc($queryDisetujui)['total'];

$queryDitolak = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pendaftarancheckup WHERE status='Ditolak'");
$totalDitolak = mysqli_fetch_assoc($queryDitolak)['total'];

// ============================================
// STATISTIK KUNJUNGAN
// ============================================
$queryKunjunganTotal = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM kunjungan");
$totalKunjungan = mysqli_fetch_assoc($queryKunjunganTotal)['total'];

// ============================================
// JADWAL DOKTER MINGGU INI
// ============================================
$queryJadwalMinggu = mysqli_query($koneksi, "
    SELECT
        jadwaldokter.*,
        dokter.namadokter,
        dokter.spesialis
    FROM jadwaldokter
    INNER JOIN dokter ON jadwaldokter.iddokter = dokter.iddokter
    ORDER BY FIELD(
        haripraktik,
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu',
        'Minggu'
    ), jam_mulai ASC
");

// ============================================
// PENDAFTARAN TERBARU
// ============================================
$queryPendaftaran = mysqli_query($koneksi, "
    SELECT
        pendaftarancheckup.*,
        pasien.namapasien,
        dokter.namadokter,
        jadwaldokter.haripraktik,
        jadwaldokter.jam_mulai,
        jadwaldokter.jam_selesai
    FROM pendaftarancheckup
    INNER JOIN pasien ON pendaftarancheckup.idpasien = pasien.idpasien
    INNER JOIN jadwaldokter ON pendaftarancheckup.idjadwal = jadwaldokter.idjadwal
    INNER JOIN dokter ON jadwaldokter.iddokter = dokter.iddokter
    ORDER BY pendaftarancheckup.tanggalpengajuan DESC
    LIMIT 10
");

// ============================================
// KUNJUNGAN TERBARU
// ============================================
$queryKunjungan = mysqli_query($koneksi, "
    SELECT
        kunjungan.*,
        pasien.namapasien,
        dokter.namadokter
    FROM kunjungan
    INNER JOIN pasien ON kunjungan.idpasien = pasien.idpasien
    INNER JOIN dokter ON kunjungan.iddokter = dokter.iddokter
    ORDER BY kunjungan.tanggalkunjungan DESC
    LIMIT 10
");
