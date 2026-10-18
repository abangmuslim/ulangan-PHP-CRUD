<?php
// Pastikan koneksi database tersedia
if (!isset($koneksi)) {
    include_once "koneksi.php";
}

// Fungsi menghitung jumlah data tabel
function hitungDataDashboard($koneksi, $tabel, $kondisi = '') {
    $tabelDiizinkan = [
        'user', 'pasien', 'dokter', 'merk',
        'kategori', 'obat', 'jadwaldokter',
        'kunjungan', 'detilkunjungan', 'antrian'
    ];

    if (!in_array($tabel, $tabelDiizinkan, true)) {
        return 0;
    }

    $sql = "SELECT COUNT(*) AS total FROM `$tabel`";

    if ($kondisi !== '') {
        $sql .= " WHERE $kondisi";
    }

    $query = mysqli_query($koneksi, $sql);

    if (!$query) {
        return 0;
    }

    $data = mysqli_fetch_assoc($query);

    return (int)($data['total'] ?? 0);
}

// Statistik data utama
$totalUser = hitungDataDashboard($koneksi, 'user');
$totalPasien = hitungDataDashboard($koneksi, 'pasien');
$totalDokter = hitungDataDashboard($koneksi, 'dokter');
$totalObat = hitungDataDashboard($koneksi, 'obat');
$totalKunjungan = hitungDataDashboard($koneksi, 'kunjungan');
$totalAntrian = hitungDataDashboard($koneksi, 'antrian');

// Statistik kunjungan hari ini
$totalKunjunganHariIni = hitungDataDashboard(
    $koneksi,
    'kunjungan',
    "tanggalkunjungan = CURDATE()"
);

// Statistik antrian yang masih aktif
$totalAntrianAktif = hitungDataDashboard(
    $koneksi,
    'antrian',
    "statusantrian IN ('menunggu', 'dipanggil', 'dilayani')"
);

// Statistik obat dengan stok menipis
$totalObatMenipis = hitungDataDashboard(
    $koneksi,
    'obat',
    'stok <= 10'
);

// Statistik antrian yang menunggu
$totalAntrianMenunggu = hitungDataDashboard(
    $koneksi,
    'antrian',
    "statusantrian = 'menunggu'"
);

// Statistik kunjungan berdasarkan bulan berjalan
$totalKunjunganBulanIni = hitungDataDashboard(
    $koneksi,
    'kunjungan',
    "YEAR(tanggalkunjungan) = YEAR(CURDATE())
     AND MONTH(tanggalkunjungan) = MONTH(CURDATE())"
);