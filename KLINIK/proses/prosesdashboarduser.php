
<?php
// Proses dashboard hanya boleh dipanggil melalui index.php
if (basename($_SERVER['SCRIPT_NAME']) !== 'index.php') {
    http_response_code(403);
    exit('Akses langsung tidak diizinkan.');
}

$ambilJumlah = function ($sql) use ($koneksi) {
    $hasil = mysqli_query($koneksi, $sql);
    if (!$hasil) {
        error_log('Query statistik dashboard gagal: ' . mysqli_error($koneksi));
        return 0;
    }
    $data = mysqli_fetch_assoc($hasil);
    return (int) ($data['jumlah'] ?? 0);
};

$ambilData = function ($sql) use ($koneksi) {
    $hasil = mysqli_query($koneksi, $sql);
    if (!$hasil) {
        error_log('Query data dashboard gagal: ' . mysqli_error($koneksi));
        return [];
    }
    $data = [];
    while ($baris = mysqli_fetch_assoc($hasil)) {
        $data[] = $baris;
    }
    return $data;
};

// Statistik utama
$totalPasien = $ambilJumlah("SELECT COUNT(*) AS jumlah FROM pasien");
$totalDokter = $ambilJumlah("SELECT COUNT(*) AS jumlah FROM dokter");
$totalObat = $ambilJumlah("SELECT COUNT(*) AS jumlah FROM obat");
$kunjunganHariIni = $ambilJumlah("SELECT COUNT(*) AS jumlah FROM kunjungan WHERE DATE(tanggalkunjungan) = CURDATE()");
$antrianMenunggu = $ambilJumlah("SELECT COUNT(*) AS jumlah FROM antrian WHERE tanggalantrian = CURDATE() AND statusantrian = 'menunggu'");
$stokMenipis = $ambilJumlah("SELECT COUNT(*) AS jumlah FROM obat WHERE stok <= 10");

// Kunjungan terbaru
$kunjunganTerbaru = $ambilData("
    SELECT k.idkunjungan, k.tanggalkunjungan, k.keluhan, k.diagnosis,
           p.namapasien, d.namadokter
    FROM kunjungan k
    LEFT JOIN pasien p ON k.idpasien = p.idpasien
    LEFT JOIN dokter d ON k.iddokter = d.iddokter
    ORDER BY k.idkunjungan DESC
    LIMIT 6
");

// Obat dengan stok menipis
$daftarStokMenipis = $ambilData("
    SELECT idobat, namaobat, stok
    FROM obat
    WHERE stok <= 10
    ORDER BY stok ASC, namaobat ASC
    LIMIT 6
");
?>
