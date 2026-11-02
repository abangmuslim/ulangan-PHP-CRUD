
<?php
// ========================================
// LAPORAN PEMAKAIAN OBAT
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
// AMBIL DATA PEMAKAIAN OBAT
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
// HITUNG RINGKASAN
// ========================================

$totalJenisObat = 0;
$totalPemakaian = 0;
$totalTransaksi = 0;
$totalNilaiObat = 0;

$dataObat = [];

while ($data = mysqli_fetch_assoc($query)) {

    $dataObat[] = $data;

    $totalJenisObat++;

    $totalPemakaian += $data['totaljumlah'];

    $totalNilaiObat += $data['totalnilai'];

    $totalTransaksi += $data['totaltransaksi'];
}

// ========================================
// HITUNG TAHUN LAPORAN
// ========================================

$periodeLaporan = $tahun;
?>

<div class="container-fluid">

    <!-- JUDUL HALAMAN -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                Laporan Pemakaian Obat
            </h4>

            <p class="text-muted mb-0">
                Laporan penggunaan obat berdasarkan transaksi kunjungan pasien.
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
                    value="laporanobat">

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

                <div class="col-md-8">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-search"></i>
                        Tampilkan

                    </button>

                    <a href="index.php?page=laporanobat"
                    class="btn btn-secondary">

                        <i class="bi bi-arrow-counterclockwise"></i>
                        Reset

                    </a>

                    <a href="index.php?page=cetaklaporanobat&tahun=<?= urlencode((string) $tahun); ?>"
                    target="_blank"
                    class="btn btn-success">

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
                        Jenis Obat Digunakan
                    </div>

                    <h3 class="fw-bold text-primary mb-0">
                        <?= $totalJenisObat; ?>
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Pemakaian Obat
                    </div>

                    <h3 class="fw-bold text-success mb-0">
                        <?= number_format($totalPemakaian, 0, ',', '.'); ?>
                    </h3>

                    <small class="text-muted">
                        Satuan obat
                    </small>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Transaksi Kunjungan
                    </div>

                    <h3 class="fw-bold text-info mb-0">
                        <?= $totalTransaksi; ?>
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Nilai Pemakaian
                    </div>

                    <h5 class="fw-bold text-danger mb-0">
                        Rp <?= number_format($totalNilaiObat, 0, ',', '.'); ?>
                    </h5>

                </div>

            </div>

        </div>

    </div>

    <!-- TABEL LAPORAN OBAT -->
    <div class="card shadow-sm">

        <div class="card-header bg-white py-3">

            <h6 class="fw-bold mb-0">

                Data Pemakaian Obat Tahun <?= $periodeLaporan; ?>

            </h6>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped
                              table-hover align-middle">

                    <thead class="table-primary">

                        <tr>

                            <th width="50">No</th>

                            <th>Nama Obat</th>

                            <th>Merek</th>

                            <th>Kategori</th>

                            <th>Jumlah Pemakaian</th>

                            <th>Transaksi Kunjungan</th>

                            <th>Rata-rata Harga</th>

                            <th>Total Nilai Pemakaian</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if ($totalJenisObat > 0) : ?>

                            <?php $no = 1; ?>

                            <?php foreach ($dataObat as $row) : ?>

                                <tr>

                                    <td>
                                        <?= $no++; ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['namaobat']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['namamerk'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['namakategori'] ?? '-'); ?>
                                    </td>

                                    <td class="text-center">
                                        <?= number_format($row['totaljumlah'], 0, ',', '.'); ?>
                                    </td>

                                    <td class="text-center">
                                        <?= number_format($row['totaltransaksi'], 0, ',', '.'); ?>
                                    </td>

                                    <td class="text-end">
                                        Rp <?= number_format($row['hargarata'], 0, ',', '.'); ?>
                                    </td>

                                    <td class="text-end fw-semibold">
                                        Rp <?= number_format($row['totalnilai'], 0, ',', '.'); ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else : ?>

                            <tr>

                                <td colspan="8"
                                    class="text-center text-muted py-4">

                                    <i class="bi bi-capsule fs-2 d-block mb-2"></i>

                                    Belum ada pemakaian obat pada tahun ini.

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                    <?php if ($totalJenisObat > 0) : ?>

                        <tfoot class="table-light">

                            <tr>

                                <th colspan="4" class="text-end">
                                    TOTAL
                                </th>

                                <th class="text-center">
                                    <?= number_format($totalPemakaian, 0, ',', '.'); ?>
                                </th>

                                <th></th>

                                <th></th>

                                <th class="text-end">
                                    Rp <?= number_format($totalNilaiObat, 0, ',', '.'); ?>
                                </th>

                            </tr>

                        </tfoot>

                    <?php endif; ?>

                </table>

            </div>

        </div>

    </div>

</div>
