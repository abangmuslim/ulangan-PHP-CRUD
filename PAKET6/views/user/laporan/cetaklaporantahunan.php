
<?php
// ========================================
// CETAK LAPORAN TAHUNAN
// ========================================

// Ambil tahun laporan
$tahun = $_GET['tahun'] ?? date('Y');

// Validasi tahun
if (!preg_match('/^\d{4}$/', $tahun)) {
    $tahun = date('Y');
}

$tahun = (int) $tahun;

// Tentukan periode laporan
$tanggalAwal = $tahun . '-01-01';
$tanggalAkhir = ($tahun + 1) . '-01-01';

$tanggalAwalDB = mysqli_real_escape_string($koneksi, $tanggalAwal);
$tanggalAkhirDB = mysqli_real_escape_string($koneksi, $tanggalAkhir);

// ========================================
// AMBIL DATA KUNJUNGAN
// ========================================

$query = mysqli_query($koneksi, "
    SELECT
        k.*,
        p.namapasien,
        d.namadokter,
        u.namauser
    FROM kunjungan k
    INNER JOIN pasien p ON k.idpasien = p.idpasien
    INNER JOIN dokter d ON k.iddokter = d.iddokter
    INNER JOIN user u ON k.iduser = u.iduser
    WHERE k.tanggalkunjungan >= '$tanggalAwalDB'
    AND k.tanggalkunjungan < '$tanggalAkhirDB'
    ORDER BY k.tanggalkunjungan ASC, k.idkunjungan ASC
");

// ========================================
// HITUNG RINGKASAN
// ========================================

$totalKunjungan = 0;
$totalBiaya = 0;
$totalLunas = 0;
$totalBelumBayar = 0;

$dataKunjungan = [];

while ($data = mysqli_fetch_assoc($query)) {

    $dataKunjungan[] = $data;

    $totalKunjungan++;
    $totalBiaya += $data['totalkunjungan'];

    if ($data['statuspembayaran'] == 'Lunas') {
        $totalLunas++;
    } else {
        $totalBelumBayar++;
    }
}

// ========================================
// REKAP KUNJUNGAN PER BULAN
// ========================================

$queryBulanan = mysqli_query($koneksi, "
    SELECT
        MONTH(tanggalkunjungan) AS bulan,
        COUNT(*) AS jumlahkunjungan,
        SUM(totalkunjungan) AS totalbiaya
    FROM kunjungan
    WHERE tanggalkunjungan >= '$tanggalAwalDB'
    AND tanggalkunjungan < '$tanggalAkhirDB'
    GROUP BY MONTH(tanggalkunjungan)
    ORDER BY MONTH(tanggalkunjungan) ASC
");

$rekapBulanan = [];

while ($data = mysqli_fetch_assoc($queryBulanan)) {
    $rekapBulanan[$data['bulan']] = $data;
}

$namaBulan = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
];
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Cetak Laporan Tahunan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            background: #fff;
        }

        .kop-surat {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .kop-surat h3 {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .kop-surat p {
            margin-bottom: 2px;
        }

        .judul-laporan {
            text-align: center;
            margin-bottom: 20px;
        }

        .judul-laporan h5 {
            font-weight: bold;
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #000 !important;
            padding: 5px !important;
            vertical-align: middle;
        }

        table th {
            text-align: center;
            background-color: #eee !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .judul-bagian {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 8px;
        }

        .ringkasan {
            margin-top: 20px;
            width: 350px;
        }

        .ringkasan td {
            border: none !important;
            padding: 4px !important;
        }

        .ttd {
            width: 250px;
            margin-left: auto;
            margin-top: 40px;
            text-align: center;
        }

        .ttd .nama {
            margin-top: 65px;
            font-weight: bold;
            text-decoration: underline;
        }

        @media print {

            @page {
                size: A4 landscape;
                margin: 12mm;
            }

            html,
            body {
                width: 100%;
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
                font-size: 10px;
            }

            .no-print {
                display: none !important;
            }

            .container-fluid {
                width: 100% !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            table {
                width: 100% !important;
                page-break-inside: auto;
            }

            thead {
                display: table-header-group;
            }

            tr {
                page-break-inside: avoid;
            }

            .kop-surat,
            .judul-laporan,
            .judul-bagian,
            .ringkasan,
            .ttd {
                page-break-inside: avoid;
            }

        }

    </style>

</head>

<body>

<div class="container-fluid p-4">

    <!-- TOMBOL CETAK -->
    <div class="no-print mb-4 text-end">

        <button onclick="window.print()"
                class="btn btn-primary">

            Cetak Laporan

        </button>

        <button onclick="window.close()"
                class="btn btn-secondary">

            Tutup

        </button>

    </div>

    <!-- KOP SURAT -->
    <div class="kop-surat">

        <h3>RUMAH SAKIT / KLINIK AHMADI</h3>

        <p>
            Pusat Pelayanan Kesehatan dan Pemeriksaan Pasien
        </p>

        <p>
            Laporan Administrasi Pelayanan Kesehatan
        </p>

    </div>

    <!-- JUDUL LAPORAN -->
    <div class="judul-laporan">

        <h5>LAPORAN KUNJUNGAN TAHUNAN</h5>

        <p>
            Periode Tahun:
            <strong><?= $tahun; ?></strong>
        </p>

    </div>

    <!-- TABEL LAPORAN KUNJUNGAN -->
    <div class="judul-bagian">
        A. DATA KUNJUNGAN PASIEN
    </div>

    <table>

        <thead>

            <tr>
                <th width="35">No</th>
                <th>Tanggal</th>
                <th>Pasien</th>
                <th>Dokter</th>
                <th>Petugas</th>
                <th>Keluhan</th>
                <th>Diagnosis</th>
                <th>Total Biaya</th>
                <th>Pembayaran</th>
                <th>Status Kunjungan</th>
            </tr>

        </thead>

        <tbody>

            <?php if ($totalKunjungan > 0) : ?>

                <?php $no = 1; ?>

                <?php foreach ($dataKunjungan as $row) : ?>

                    <tr>

                        <td class="text-center">
                            <?= $no++; ?>
                        </td>

                        <td class="text-center">
                            <?= date('d-m-Y', strtotime($row['tanggalkunjungan'])); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['namapasien']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['namadokter']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['namauser']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['keluhan']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['diagnosis']); ?>
                        </td>

                        <td class="text-end">
                            Rp <?= number_format($row['totalkunjungan'], 0, ',', '.'); ?>
                        </td>

                        <td class="text-center">
                            <?= htmlspecialchars($row['statuspembayaran']); ?>
                        </td>

                        <td class="text-center">
                            <?= htmlspecialchars($row['statuskunjungan']); ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else : ?>

                <tr>
                    <td colspan="10" class="text-center">
                        Tidak ada data kunjungan pada tahun ini.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

        <?php if ($totalKunjungan > 0) : ?>

            <tfoot>

                <tr>

                    <th colspan="7" class="text-end">
                        TOTAL BIAYA
                    </th>

                    <th class="text-end">
                        Rp <?= number_format($totalBiaya, 0, ',', '.'); ?>
                    </th>

                    <th colspan="2"></th>

                </tr>

            </tfoot>

        <?php endif; ?>

    </table>

    <!-- REKAP PER BULAN -->
    <div class="judul-bagian">
        B. REKAPITULASI KUNJUNGAN PER BULAN
    </div>

    <table>

        <thead>

            <tr>
                <th width="50">No</th>
                <th>Bulan</th>
                <th>Jumlah Kunjungan</th>
                <th>Total Biaya Kunjungan</th>
            </tr>

        </thead>

        <tbody>

            <?php for ($i = 1; $i <= 12; $i++) : ?>

                <?php
                $jumlah = isset($rekapBulanan[$i])
                    ? $rekapBulanan[$i]['jumlahkunjungan']
                    : 0;

                $biaya = isset($rekapBulanan[$i])
                    ? $rekapBulanan[$i]['totalbiaya']
                    : 0;
                ?>

                <tr>

                    <td class="text-center">
                        <?= $i; ?>
                    </td>

                    <td>
                        <?= $namaBulan[$i]; ?>
                    </td>

                    <td class="text-center">
                        <?= $jumlah; ?>
                    </td>

                    <td class="text-end">
                        Rp <?= number_format($biaya, 0, ',', '.'); ?>
                    </td>

                </tr>

            <?php endfor; ?>

        </tbody>

    </table>

    <!-- RINGKASAN -->
    <div class="judul-bagian">
        C. RINGKASAN TAHUNAN
    </div>

    <div class="ringkasan">

        <table>

            <tr>
                <td width="180">Total Kunjungan</td>
                <td width="15">:</td>
                <td>
                    <strong><?= $totalKunjungan; ?> Kunjungan</strong>
                </td>
            </tr>

            <tr>
                <td>Total Biaya Kunjungan</td>
                <td>:</td>
                <td>
                    <strong>
                        Rp <?= number_format($totalBiaya, 0, ',', '.'); ?>
                    </strong>
                </td>
            </tr>

            <tr>
                <td>Pembayaran Lunas</td>
                <td>:</td>
                <td><?= $totalLunas; ?> Kunjungan</td>
            </tr>

            <tr>
                <td>Belum Bayar</td>
                <td>:</td>
                <td><?= $totalBelumBayar; ?> Kunjungan</td>
            </tr>

        </table>

    </div>

    <!-- TANDA TANGAN -->
    <div class="ttd">

        <p>
            Dicetak pada:
            <?= date('d-m-Y'); ?>
        </p>

        <p>
            Mengetahui,<br>
            Admin Rumah Sakit/Klinik Ahmadi
        </p>

        <div class="nama">
            ( ................................ )
        </div>

    </div>

</div>

</body>
</html>
