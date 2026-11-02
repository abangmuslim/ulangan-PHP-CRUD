
<?php
// ========================================
// LAPORAN TAHUNAN
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

// Nama bulan Indonesia
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
?>

<div class="container-fluid">

    <!-- JUDUL HALAMAN -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Laporan Tahunan
            </h4>

            <p class="text-muted mb-0">
                Rekapitulasi kunjungan pasien selama satu tahun.
            </p>
        </div>

    </div>

    <!-- FILTER TAHUN -->
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="index.php"
                  class="row g-3 align-items-end">

                <input type="hidden"
                       name="page"
                       value="laporantahunan">

                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Pilih Tahun Laporan
                    </label>

                    <input type="number"
                           name="tahun"
                           class="form-control"
                           min="2000"
                           max="2100"
                           value="<?= htmlspecialchars((string) $tahun); ?>"
                           required>

                </div>

                <div class="col-md-3">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-search"></i>
                        Tampilkan

                    </button>

                    <a href="index.php?page=laporantahunan"
                       class="btn btn-secondary">

                        Reset

                    </a>

                </div>

                <div class="col-md-5 text-md-end">

                    <a href="index.php?page=cetaklaporantahunan&tahun=<?= urlencode((string) $tahun); ?>"
                       target="_blank"
                       class="btn btn-danger">

                        <i class="bi bi-printer"></i>
                        Cetak Laporan

                    </a>

                </div>

            </form>

        </div>

    </div>

    <!-- RINGKASAN -->
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="text-muted small">
                        Total Kunjungan
                    </div>

                    <h3 class="fw-bold text-primary mb-0">
                        <?= $totalKunjungan; ?>
                    </h3>

                </div>
            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="text-muted small">
                        Total Biaya
                    </div>

                    <h5 class="fw-bold text-success mb-0">
                        Rp <?= number_format($totalBiaya, 0, ',', '.'); ?>
                    </h5>

                </div>
            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="text-muted small">
                        Pembayaran Lunas
                    </div>

                    <h3 class="fw-bold text-success mb-0">
                        <?= $totalLunas; ?>
                    </h3>

                </div>
            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="text-muted small">
                        Belum Bayar
                    </div>

                    <h3 class="fw-bold text-danger mb-0">
                        <?= $totalBelumBayar; ?>
                    </h3>

                </div>
            </div>

        </div>

    </div>

    <!-- REKAP PER BULAN -->
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h6 class="fw-bold mb-0">
                Rekapitulasi Kunjungan Per Bulan Tahun <?= $tahun; ?>
            </h6>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped align-middle">

                    <thead class="table-primary">

                        <tr>
                            <th width="60">No</th>
                            <th>Bulan</th>
                            <th>Jumlah Kunjungan</th>
                            <th>Total Biaya</th>
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

                                <td><?= $i; ?></td>

                                <td><?= $namaBulan[$i]; ?></td>

                                <td class="text-center">
                                    <?= number_format($jumlah, 0, ',', '.'); ?>
                                </td>

                                <td class="text-end">
                                    Rp <?= number_format($biaya, 0, ',', '.'); ?>
                                </td>

                            </tr>

                        <?php endfor; ?>

                    </tbody>

                    <tfoot class="table-light">

                        <tr>

                            <th colspan="2" class="text-end">
                                TOTAL
                            </th>

                            <th class="text-center">
                                <?= number_format($totalKunjungan, 0, ',', '.'); ?>
                            </th>

                            <th class="text-end">
                                Rp <?= number_format($totalBiaya, 0, ',', '.'); ?>
                            </th>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

    <!-- DETAIL KUNJUNGAN -->
    <div class="card shadow-sm">

        <div class="card-header bg-white py-3">

            <h6 class="fw-bold mb-0">
                Detail Kunjungan Tahun <?= $tahun; ?>
            </h6>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped
                              table-hover align-middle">

                    <thead class="table-primary">

                        <tr>
                            <th width="45">No</th>
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

                                    <td><?= $no++; ?></td>

                                    <td>
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

                                    <td>

                                        <?php if ($row['statuspembayaran'] == 'Lunas') : ?>

                                            <span class="badge bg-success">
                                                Lunas
                                            </span>

                                        <?php else : ?>

                                            <span class="badge bg-danger">
                                                Belum Bayar
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <?php if ($row['statuskunjungan'] == 'Selesai') : ?>

                                            <span class="badge bg-success">
                                                Selesai
                                            </span>

                                        <?php else : ?>

                                            <span class="badge bg-warning text-dark">
                                                Proses
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else : ?>

                            <tr>

                                <td colspan="10"
                                    class="text-center text-muted py-4">

                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>

                                    Tidak ada data kunjungan pada tahun ini.

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                    <?php if ($totalKunjungan > 0) : ?>

                        <tfoot class="table-light">

                            <tr>

                                <th colspan="7" class="text-end">
                                    TOTAL
                                </th>

                                <th class="text-end">
                                    Rp <?= number_format($totalBiaya, 0, ',', '.'); ?>
                                </th>

                                <th colspan="2"></th>

                            </tr>

                        </tfoot>

                    <?php endif; ?>

                </table>

            </div>

        </div>

    </div>

</div>
