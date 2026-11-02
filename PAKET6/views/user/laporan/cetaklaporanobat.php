
<?php
// ========================================
// CETAK LAPORAN PENGGUNAAN OBAT
// ========================================

// Ambil tahun dari URL
$tahun = $_GET['tahun'] ?? date('Y');

// Validasi tahun
if (!preg_match('/^\d{4}$/', (string) $tahun) || $tahun < 2000 || $tahun > 2100) {
    $tahun = date('Y');
}

$tahun = (int) $tahun;

// Batas tanggal laporan
$tanggalAwalDB = $tahun . "-01-01";
$tanggalAkhirDB = ($tahun + 1) . "-01-01";

// ========================================
// AMBIL DATA LAPORAN OBAT
// ========================================

$query = mysqli_query($koneksi, "
    SELECT
        o.idobat,
        o.namaobat,
        m.namamerk,
        k.namakategori,
        SUM(d.jumlah) AS totaljumlah,
        COUNT(DISTINCT kun.idkunjungan) AS totaltransaksi,
        SUM(d.subtotal) AS totalnilai,
        CASE
            WHEN SUM(d.jumlah) > 0
            THEN SUM(d.subtotal) / SUM(d.jumlah)
            ELSE 0
        END AS hargarata
    FROM detilkunjungan d
    INNER JOIN kunjungan kun
        ON d.idkunjungan = kun.idkunjungan
    INNER JOIN obat o
        ON d.idobat = o.idobat
    LEFT JOIN merk m
        ON o.idmerk = m.idmerk
    LEFT JOIN kategori k
        ON o.idkategori = k.idkategori
    WHERE kun.tanggalkunjungan >= '$tanggalAwalDB'
      AND kun.tanggalkunjungan < '$tanggalAkhirDB'
    GROUP BY
        o.idobat,
        o.namaobat,
        m.namamerk,
        k.namakategori
    ORDER BY totaljumlah DESC
");

// ========================================
// HITUNG TOTAL LAPORAN
// ========================================

$dataObat = [];
$totalJenisObat = 0;
$totalJumlahObat = 0;
$totalNilaiObat = 0;

if ($query) {
    while ($data = mysqli_fetch_assoc($query)) {
        $dataObat[] = $data;

        $totalJenisObat++;
        $totalJumlahObat += (int) $data['totaljumlah'];
        $totalNilaiObat += (float) $data['totalnilai'];
    }
}

// Hitung jumlah kunjungan yang menggunakan obat
$queryTransaksi = mysqli_query($koneksi, "
    SELECT COUNT(DISTINCT kun.idkunjungan) AS totaltransaksi
    FROM kunjungan kun
    INNER JOIN detilkunjungan d
        ON kun.idkunjungan = d.idkunjungan
    WHERE kun.tanggalkunjungan >= '$tanggalAwalDB'
      AND kun.tanggalkunjungan < '$tanggalAkhirDB'
");

$totalTransaksi = 0;

if ($queryTransaksi) {
    $dataTransaksi = mysqli_fetch_assoc($queryTransaksi);
    $totalTransaksi = (int) $dataTransaksi['totaltransaksi'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cetak Laporan Obat <?= $tahun; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .kop-surat {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .kop-surat h4 {
            font-weight: bold;
            margin-bottom: 4px;
        }

        .kop-surat p {
            margin: 0;
        }

        .judul-laporan {
            text-align: center;
            margin-bottom: 20px;
        }

        .judul-laporan h5 {
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .table th,
        .table td {
            vertical-align: middle;
            padding: 7px;
        }

        .table th {
            text-align: center;
        }

        .ringkasan {
            margin-bottom: 20px;
        }

        .ringkasan td {
            padding: 5px 8px;
        }

        .tanda-tangan {
            width: 250px;
            margin-left: auto;
            margin-top: 40px;
            text-align: center;
        }

        .area-tombol {
            margin-bottom: 20px;
        }

        @page {
            size: A4 landscape;
            margin: 12mm;
        }

        @media print {
            body {
                font-size: 11px;
            }

            .area-tombol {
                display: none !important;
            }

            .table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>

</head>

<body>

<div class="container-fluid">

    <!-- TOMBOL CETAK -->
    <div class="area-tombol d-flex justify-content-between">

        <a href="index.php?page=laporanobat&tahun=<?= $tahun; ?>"
           class="btn btn-secondary btn-sm">

            Kembali

        </a>

        <button onclick="window.print()"
                class="btn btn-primary btn-sm">

            <i class="bi bi-printer"></i>
            Cetak Laporan

        </button>

    </div>

    <!-- KOP SURAT -->
    <div class="kop-surat">

        <h4>RUMAH SAKIT / KLINIK AHMADI</h4>

        <p>LAPORAN DATA DAN PENGGUNAAN OBAT</p>

    </div>

    <!-- JUDUL LAPORAN -->
    <div class="judul-laporan">

        <h5>Laporan Penggunaan Obat</h5>

        <p>
            Periode Tahun <?= $tahun; ?>
        </p>

    </div>

    <!-- RINGKASAN -->
    <table class="table table-bordered ringkasan">

        <tr>
            <th width="25%">Total Jenis Obat</th>
            <td width="25%">
                <?= number_format($totalJenisObat, 0, ',', '.'); ?> jenis
            </td>

            <th width="25%">Total Pemakaian Obat</th>
            <td width="25%">
                <?= number_format($totalJumlahObat, 0, ',', '.'); ?> unit
            </td>
        </tr>

        <tr>
            <th>Total Transaksi Kunjungan</th>
            <td>
                <?= number_format($totalTransaksi, 0, ',', '.'); ?> transaksi
            </td>

            <th>Total Nilai Obat</th>
            <td>
                Rp <?= number_format($totalNilaiObat, 0, ',', '.'); ?>
            </td>
        </tr>

    </table>

    <!-- TABEL LAPORAN -->
    <table class="table table-bordered table-striped">

        <thead class="table-light">

            <tr>
                <th width="4%">No.</th>
                <th>Nama Obat</th>
                <th>Merek</th>
                <th>Kategori</th>
                <th>Total Pemakaian</th>
                <th>Jumlah Transaksi</th>
                <th>Harga Rata-rata</th>
                <th>Total Nilai</th>
            </tr>

        </thead>

        <tbody>

            <?php if (count($dataObat) > 0): ?>

                <?php $no = 1; ?>

                <?php foreach ($dataObat as $obat): ?>

                    <tr>

                        <td class="text-center">
                            <?= $no++; ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($obat['namaobat']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($obat['namamerk'] ?? '-'); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($obat['namakategori'] ?? '-'); ?>
                        </td>

                        <td class="text-center">
                            <?= number_format($obat['totaljumlah'], 0, ',', '.'); ?>
                        </td>

                        <td class="text-center">
                            <?= number_format($obat['totaltransaksi'], 0, ',', '.'); ?>
                        </td>

                        <td class="text-end">
                            Rp <?= number_format($obat['hargarata'], 0, ',', '.'); ?>
                        </td>

                        <td class="text-end">
                            Rp <?= number_format($obat['totalnilai'], 0, ',', '.'); ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="8" class="text-center">
                        Tidak ada data penggunaan obat pada tahun <?= $tahun; ?>.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

        <?php if (count($dataObat) > 0): ?>

            <tfoot>

                <tr class="fw-bold">

                    <td colspan="4" class="text-center">
                        TOTAL
                    </td>

                    <td class="text-center">
                        <?= number_format($totalJumlahObat, 0, ',', '.'); ?>
                    </td>

                    <td class="text-center">
                        -
                    </td>

                    <td></td>

                    <td class="text-end">
                        Rp <?= number_format($totalNilaiObat, 0, ',', '.'); ?>
                    </td>

                </tr>

            </tfoot>

        <?php endif; ?>

    </table>

    <!-- TANDA TANGAN -->
    <div class="tanda-tangan">

        <p>
            Mengetahui,<br>
            Pimpinan Rumah Sakit / Klinik Ahmadi
        </p>

        <br><br><br>

        <p>
            <strong>(........................................)</strong>
        </p>

    </div>

</div>

</body>
</html>
