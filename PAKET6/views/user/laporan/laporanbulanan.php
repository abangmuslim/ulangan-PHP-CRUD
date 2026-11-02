
<?php
// ========================================
// LAPORAN BULANAN
// ========================================

// Ambil periode bulan
$bulan = $_GET['bulan'] ?? date('Y-m');

// Validasi format bulan
if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $bulan)) {
    $bulan = date('Y-m');
}

// Tentukan tanggal awal dan akhir bulan
$periode = DateTime::createFromFormat('!Y-m', $bulan);

$tanggalAwal = $periode->format('Y-m-01');
$tanggalAkhir = (clone $periode)->modify('+1 month')->format('Y-m-01');

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

$bulanAngka = (int) $periode->format('n');
$tahun = $periode->format('Y');

$periodeLaporan = $namaBulan[$bulanAngka] . ' ' . $tahun;

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
// HITUNG RINGKASAN LAPORAN
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
?>

<div class="container-fluid">

    <!-- JUDUL HALAMAN -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">Laporan Bulanan</h4>

            <p class="text-muted mb-0">
                Laporan transaksi kunjungan pasien setiap bulan.
            </p>
        </div>

    </div>

    <!-- FILTER BULAN -->
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="index.php"
                  class="row g-3 align-items-end">

                <input type="hidden"
                       name="page"
                       value="laporanbulanan">

                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Pilih Bulan dan Tahun
                    </label>

                    <input type="month"
                           name="bulan"
                           class="form-control"
                           value="<?= htmlspecialchars($bulan); ?>"
                           required>

                </div>

                <div class="col-md-3">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-search"></i>
                        Tampilkan

                    </button>

                    <a href="index.php?page=laporanbulanan"
                       class="btn btn-secondary">

                        Reset

                    </a>

                </div>

                <div class="col-md-5 text-md-end">

                    <a href="index.php?page=cetaklaporanbulanan&bulan=<?= urlencode($bulan); ?>"
                       target="_blank"
                       class="btn btn-danger">

                        <i class="bi bi-printer"></i>
                        Cetak Laporan

                    </a>

                </div>

            </form>

        </div>

    </div>

    <!-- RINGKASAN LAPORAN -->
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

    <!-- TABEL LAPORAN -->
    <div class="card shadow-sm">

        <div class="card-header bg-white py-3">

            <h6 class="fw-bold mb-0">
                Data Kunjungan Bulan <?= $periodeLaporan; ?>
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

                                    <td>
                                        <?= $no++; ?>
                                    </td>

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

                                    Tidak ada data kunjungan pada bulan ini.

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
