
<?php
// ========================================
// AMBIL ID KUNJUNGAN
// ========================================

$idkunjungan = isset($_GET['idkunjungan'])
    ? (int) $_GET['idkunjungan']
    : 0;

// ========================================
// QUERY DATA KUNJUNGAN
// ========================================

$querykunjungan = mysqli_query($koneksi, "
    SELECT
        kunjungan.*,
        pasien.namapasien,
        pasien.username,
        pasien.nohp,
        pasien.alamat,
        dokter.namadokter,
        dokter.spesialis,
        jadwaldokter.haripraktik,
        jadwaldokter.jam_mulai,
        jadwaldokter.jam_selesai
    FROM kunjungan
    INNER JOIN pasien
        ON kunjungan.idpasien = pasien.idpasien
    INNER JOIN dokter
        ON kunjungan.iddokter = dokter.iddokter
    INNER JOIN jadwaldokter
        ON kunjungan.idjadwal = jadwaldokter.idjadwal
    WHERE kunjungan.idkunjungan = '$idkunjungan'
    LIMIT 1
");

if (!$querykunjungan) {
    die("Gagal mengambil data kunjungan: " . mysqli_error($koneksi));
}

$dataKunjungan = mysqli_fetch_assoc($querykunjungan);

// ========================================
// CEK STATUS KUNJUNGAN
// ========================================

$bolehEdit = false;

if ($dataKunjungan) {
    if ($dataKunjungan['statuskunjungan'] == 'Proses') {
        $bolehEdit = true;
    }
}

// ========================================
// QUERY DETAIL OBAT KUNJUNGAN
// ========================================

$dataDetailObat = [];

if ($bolehEdit) {

    $querydetail = mysqli_query($koneksi, "
        SELECT
            detilkunjungan.*,
            obat.namaobat
        FROM detilkunjungan
        INNER JOIN obat
            ON detilkunjungan.idobat = obat.idobat
        WHERE detilkunjungan.idkunjungan = '$idkunjungan'
        ORDER BY detilkunjungan.iddetilkunjungan ASC
    ");

    if (!$querydetail) {
        die("Gagal mengambil detail obat: " . mysqli_error($koneksi));
    }

    $dataDetailObat = mysqli_fetch_all($querydetail, MYSQLI_ASSOC);
}

// ========================================
// QUERY MASTER OBAT
// STOK DITAMBAH JUMLAH OBAT LAMA
// ========================================

$dataObat = [];

if ($bolehEdit) {

    $queryobat = mysqli_query($koneksi, "
        SELECT
            obat.idobat,
            obat.namaobat,
            obat.harga,
            obat.stok,
            COALESCE(detail.jumlah_lama, 0) AS jumlah_lama
        FROM obat
        LEFT JOIN (
            SELECT
                idobat,
                SUM(jumlah) AS jumlah_lama
            FROM detilkunjungan
            WHERE idkunjungan = '$idkunjungan'
            GROUP BY idobat
        ) AS detail
            ON obat.idobat = detail.idobat
        ORDER BY obat.namaobat ASC
    ");

    if (!$queryobat) {
        die("Gagal mengambil data obat: " . mysqli_error($koneksi));
    }

    $dataObat = mysqli_fetch_all($queryobat, MYSQLI_ASSOC);
}
?>

<style>
    .kunjungan-label {
        font-size: 12px;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .kunjungan-panel {
        border-radius: 10px;
        overflow: hidden;
    }

    .kunjungan-panel .card-header {
        padding: 16px 20px;
    }

    .kunjungan-panel .card-body {
        padding: 20px;
    }

    .kunjungan-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .kunjungan-input {
        min-height: 42px;
    }

    .kunjungan-table thead th {
        background: #f4f6f9;
        color: #495057;
        font-size: 12px;
        text-transform: uppercase;
        white-space: nowrap;
        vertical-align: middle;
    }

    .kunjungan-table td {
        vertical-align: middle;
    }

    .kunjungan-table .pilih-obat {
        min-width: 220px;
    }

    .kunjungan-table .qty-obat {
        min-width: 75px;
        text-align: center;
    }

    .kunjungan-table .aturan-obat {
        min-width: 160px;
    }

    .kunjungan-table .harga-obat,
    .kunjungan-table .subtotal-obat {
        white-space: nowrap;
        font-size: 13px;
    }

    .kunjungan-summary {
        position: sticky;
        top: 15px;
    }

    .kunjungan-total-box {
        padding: 20px;
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: #fff;
        border-radius: 10px;
    }

    .kunjungan-total-box .total-value {
        font-size: clamp(24px, 2.2vw, 32px);
        font-weight: 800;
        overflow-wrap: anywhere;
    }

    .kunjungan-payment-box {
        border: 1px solid #dee2e6;
        padding: 16px;
        border-radius: 10px;
        background: #fff;
    }

    .kunjungan-change-box {
        padding: 16px;
        background: #e8f7ee;
        border-radius: 10px;
        border: 1px solid #b8e5c8;
    }

    .kunjungan-change-box.kurang {
        background: #fff3cd;
        border-color: #ffe69c;
    }

    .kunjungan-change-value {
        font-size: 24px;
        font-weight: 800;
        overflow-wrap: anywhere;
    }

    .kunjungan-action .btn {
        min-height: 44px;
        font-weight: 600;
    }

    .kunjungan-hint {
        font-size: 12px;
        color: #6c757d;
    }

    .info-pasien {
        background: #f4f8ff;
        border: 1px solid #dbe7ff;
        border-radius: 8px;
        padding: 15px;
    }

    @media (max-width: 767.98px) {
        .kunjungan-panel .card-body {
            padding: 14px;
        }

        .kunjungan-summary {
            position: static;
        }

        .kunjungan-table .pilih-obat {
            min-width: 190px;
        }
    }
</style>


<?php if (!$dataKunjungan) { ?>

    <div class="container-fluid">

        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Data kunjungan tidak ditemukan.
        </div>

        <a href="index.php?page=kunjungan"
           class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

    </div>

<?php } elseif (!$bolehEdit) { ?>

    <div class="container-fluid">

        <div class="alert alert-warning">

            <h5>
                <i class="bi bi-exclamation-triangle me-2"></i>
                Kunjungan Tidak Dapat Diedit
            </h5>

            Kunjungan ini sudah selesai sehingga tidak dapat
            diubah melalui halaman edit.

        </div>

        <a href="index.php?page=showkunjungan&idkunjungan=<?= $idkunjungan; ?>"
           class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Detail Kunjungan
        </a>

    </div>

<?php } else { ?>


<!-- ========================================
     JUDUL HALAMAN
======================================== -->

<section class="content-header">

    <div class="container-fluid">

        <div class="row mb-3 align-items-center">

            <div class="col-md-7">

                <h4 class="fw-bold mb-1">
                    <i class="bi bi-pencil-square text-primary"></i>
                    Edit Kunjungan
                </h4>

                <p class="text-muted mb-0">
                    Perbarui hasil pemeriksaan, obat,
                    dan pembayaran kunjungan pasien.
                </p>

            </div>

            <div class="col-md-5">

                <nav aria-label="breadcrumb">

                    <ol class="breadcrumb justify-content-md-end mb-0">

                        <li class="breadcrumb-item">
                            <a href="index.php">Dashboard</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="index.php?page=kunjungan">
                                Kunjungan
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Edit Kunjungan
                        </li>

                    </ol>

                </nav>

            </div>

        </div>

    </div>

</section>


<!-- ========================================
     FORM EDIT KUNJUNGAN
======================================== -->

<section class="content">

    <div class="container-fluid">

        <form
            action="proses/proseskunjungan.php?aksi=edit"
            method="POST"
            id="formKunjungan"
        >

            <input
                type="hidden"
                name="idkunjungan"
                value="<?= (int) $dataKunjungan['idkunjungan']; ?>"
            >

            <div class="row">


                <!-- ========================================
                     KOLOM INFORMASI DAN OBAT
                ======================================== -->

                <div class="col-lg-8">


                    <!-- INFORMASI KUNJUNGAN -->

                    <div class="card border-0 shadow-sm kunjungan-panel mb-4">

                        <div class="card-header bg-white">

                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-receipt text-primary me-2"></i>
                                Informasi Kunjungan
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label class="kunjungan-label">
                                        Nomor Kunjungan
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control kunjungan-input"
                                        value="KJ-<?= str_pad($dataKunjungan['idkunjungan'], 4, '0', STR_PAD_LEFT); ?>"
                                        readonly
                                    >

                                </div>

                                <div class="col-md-6">

                                    <label class="kunjungan-label">
                                        Status Kunjungan
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control kunjungan-input"
                                        value="<?= htmlspecialchars($dataKunjungan['statuskunjungan']); ?>"
                                        readonly
                                    >

                                </div>

                                <div class="col-md-6">

                                    <label for="tanggalkunjungan"
                                           class="kunjungan-label">
                                        Tanggal Kunjungan
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="date"
                                        name="tanggalkunjungan"
                                        id="tanggalkunjungan"
                                        class="form-control kunjungan-input"
                                        value="<?= htmlspecialchars($dataKunjungan['tanggalkunjungan']); ?>"
                                        required
                                    >

                                </div>

                                <div class="col-md-6">

                                    <label class="kunjungan-label">
                                        Tanggal Pendaftaran
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control kunjungan-input"
                                        value="<?= date('d-m-Y', strtotime($dataKunjungan['tanggalkunjungan'])); ?>"
                                        readonly
                                    >

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- INFORMASI PASIEN DAN DOKTER -->

                    <div class="card border-0 shadow-sm kunjungan-panel mb-4">

                        <div class="card-header bg-white">

                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-person-lines-fill text-primary me-2"></i>
                                Informasi Pasien dan Dokter
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row g-3">

                                <!-- PASIEN -->

                                <div class="col-md-6">

                                    <div class="info-pasien h-100">

                                        <div class="d-flex align-items-center mb-3">

                                            <i class="bi bi-person-circle fs-3 text-primary me-2"></i>

                                            <div>

                                                <small class="text-muted">
                                                    Pasien
                                                </small>

                                                <div class="fw-bold">
                                                    <?= htmlspecialchars($dataKunjungan['namapasien']); ?>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="small mb-2">
                                            <strong>Username:</strong>
                                            <?= htmlspecialchars($dataKunjungan['username'] ?? '-'); ?>
                                        </div>

                                        <div class="small mb-2">
                                            <strong>No. HP:</strong>
                                            <?= htmlspecialchars($dataKunjungan['nohp'] ?? '-'); ?>
                                        </div>

                                        <div class="small">
                                            <strong>Alamat:</strong>
                                            <?= htmlspecialchars($dataKunjungan['alamat'] ?? '-'); ?>
                                        </div>

                                    </div>

                                </div>


                                <!-- DOKTER -->

                                <div class="col-md-6">

                                    <div class="info-pasien h-100">

                                        <div class="d-flex align-items-center mb-3">

                                            <i class="bi bi-person-badge fs-3 text-primary me-2"></i>

                                            <div>

                                                <small class="text-muted">
                                                    Dokter Pemeriksa
                                                </small>

                                                <div class="fw-bold">
                                                    <?= htmlspecialchars($dataKunjungan['namadokter']); ?>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="small mb-2">
                                            <strong>Spesialis:</strong>
                                            <?= htmlspecialchars($dataKunjungan['spesialis'] ?? '-'); ?>
                                        </div>

                                        <div class="small mb-2">
                                            <strong>Hari Praktik:</strong>
                                            <?= htmlspecialchars($dataKunjungan['haripraktik']); ?>
                                        </div>

                                        <div class="small">
                                            <strong>Jam Praktik:</strong>
                                            <?= date('H:i', strtotime($dataKunjungan['jam_mulai'])); ?>
                                            -
                                            <?= date('H:i', strtotime($dataKunjungan['jam_selesai'])); ?>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="alert alert-info mt-3 mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Data pasien dan dokter mengikuti
                                kunjungan yang sudah tersimpan.
                            </div>

                        </div>

                    </div>


                    <!-- HASIL PEMERIKSAAN -->

                    <div class="card border-0 shadow-sm kunjungan-panel mb-4">

                        <div class="card-header bg-white">

                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-journal-medical text-primary me-2"></i>
                                Hasil Pemeriksaan
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="mb-3">

                                <label for="keluhan" class="form-label fw-semibold">
                                    Keluhan Pasien
                                </label>

                                <textarea
                                    name="keluhan"
                                    id="keluhan"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Masukkan keluhan pasien..."
                                ><?= htmlspecialchars($dataKunjungan['keluhan'] ?? ''); ?></textarea>

                            </div>

                            <div>

                                <label for="diagnosis" class="form-label fw-semibold">
                                    Diagnosis Dokter
                                </label>

                                <textarea
                                    name="diagnosis"
                                    id="diagnosis"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Masukkan hasil diagnosis pemeriksaan..."
                                ><?= htmlspecialchars($dataKunjungan['diagnosis'] ?? ''); ?></textarea>

                            </div>

                        </div>

                    </div>


                    <!-- DAFTAR OBAT -->

                    <div class="card border-0 shadow-sm kunjungan-panel mb-4">

                        <div class="card-header bg-white">

                            <div class="kunjungan-toolbar">

                                <h5 class="fw-bold mb-0">
                                    <i class="bi bi-capsule text-primary me-2"></i>
                                    Daftar Obat Kunjungan
                                </h5>

                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm"
                                    id="tambahBaris"
                                    <?= empty($dataObat) ? 'disabled' : ''; ?>
                                >
                                    <i class="bi bi-plus-lg me-1"></i>
                                    Tambah Obat
                                </button>

                            </div>

                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered table-hover kunjungan-table mb-0">

                                    <thead>

                                        <tr>
                                            <th width="4%">No</th>
                                            <th width="32%">Obat</th>
                                            <th>Harga</th>
                                            <th>Stok</th>
                                            <th width="10%">Jumlah</th>
                                            <th>Subtotal</th>
                                            <th>Aturan Pakai</th>
                                            <th width="6%">Aksi</th>
                                        </tr>

                                    </thead>

                                    <tbody id="daftarObat">

                                        <?php if (!empty($dataDetailObat)) { ?>

                                            <?php foreach ($dataDetailObat as $index => $d) { ?>

                                                <tr class="baris-obat">

                                                    <td class="nomor-baris text-center">
                                                        <?= $index + 1; ?>
                                                    </td>

                                                    <td>

                                                        <select
                                                            name="idobat[]"
                                                            class="form-select form-select-sm pilih-obat"
                                                        >

                                                            <option value="">
                                                                -- Pilih Obat --
                                                            </option>

                                                            <?php foreach ($dataObat as $o) { ?>

                                                                <option
                                                                    value="<?= (int) $o['idobat']; ?>"
                                                                    data-harga="<?= (int) $o['harga']; ?>"
                                                                    data-stok="<?= (int) $o['stok'] + (int) $o['jumlah_lama']; ?>"
                                                                    <?= (int) $o['idobat'] == (int) $d['idobat'] ? 'selected' : ''; ?>
                                                                    <?= ((int) $o['stok'] + (int) $o['jumlah_lama']) <= 0 ? 'disabled' : ''; ?>
                                                                >

                                                                    <?= htmlspecialchars($o['namaobat']); ?>
                                                                    — Rp <?= number_format($o['harga'], 0, ',', '.'); ?>
                                                                    (Stok: <?= (int) $o['stok'] + (int) $o['jumlah_lama']; ?>)

                                                                </option>

                                                            <?php } ?>

                                                        </select>

                                                    </td>

                                                    <td class="harga-obat text-end">
                                                        Rp <?= number_format($d['harga'], 0, ',', '.'); ?>
                                                    </td>

                                                    <td class="stok-obat text-center">
                                                        -
                                                    </td>

                                                    <td>

                                                        <input
                                                            type="number"
                                                            name="jumlah[]"
                                                            class="form-control form-control-sm qty-obat"
                                                            min="1"
                                                            step="1"
                                                            value="<?= (int) $d['jumlah']; ?>"
                                                        >

                                                    </td>

                                                    <td class="subtotal-obat text-end fw-bold">
                                                        Rp <?= number_format($d['subtotal'], 0, ',', '.'); ?>
                                                    </td>

                                                    <td>

                                                        <input
                                                            type="text"
                                                            name="aturanpakai[]"
                                                            class="form-control form-control-sm aturan-obat"
                                                            value="<?= htmlspecialchars($d['aturanpakai'] ?? ''); ?>"
                                                            placeholder="Contoh: 3x sehari"
                                                        >

                                                    </td>

                                                    <td class="text-center">

                                                        <button
                                                            type="button"
                                                            class="btn btn-outline-danger btn-sm hapus-baris"
                                                            title="Hapus obat"
                                                        >
                                                            <i class="bi bi-trash"></i>
                                                        </button>

                                                    </td>

                                                </tr>

                                            <?php } ?>

                                        <?php } else { ?>

                                            <tr class="baris-obat">

                                                <td class="nomor-baris text-center">
                                                    1
                                                </td>

                                                <td>

                                                    <select
                                                        name="idobat[]"
                                                        class="form-select form-select-sm pilih-obat"
                                                    >

                                                        <option value="">
                                                            -- Pilih Obat --
                                                        </option>

                                                        <?php foreach ($dataObat as $o) { ?>

                                                            <option
                                                                value="<?= (int) $o['idobat']; ?>"
                                                                data-harga="<?= (int) $o['harga']; ?>"
                                                                data-stok="<?= (int) $o['stok']; ?>"
                                                                <?= (int) $o['stok'] <= 0 ? 'disabled' : ''; ?>
                                                            >
                                                                <?= htmlspecialchars($o['namaobat']); ?>
                                                                — Rp <?= number_format($o['harga'], 0, ',', '.'); ?>
                                                                (Stok: <?= (int) $o['stok']; ?>)
                                                            </option>

                                                        <?php } ?>

                                                    </select>

                                                </td>

                                                <td class="harga-obat text-end">Rp 0</td>

                                                <td class="stok-obat text-center">-</td>

                                                <td>
                                                    <input
                                                        type="number"
                                                        name="jumlah[]"
                                                        class="form-control form-control-sm qty-obat"
                                                        min="1"
                                                        step="1"
                                                        value="1"
                                                    >
                                                </td>

                                                <td class="subtotal-obat text-end fw-bold">Rp 0</td>

                                                <td>
                                                    <input
                                                        type="text"
                                                        name="aturanpakai[]"
                                                        class="form-control form-control-sm aturan-obat"
                                                        placeholder="Contoh: 3x sehari"
                                                    >
                                                </td>

                                                <td class="text-center">
                                                    <button
                                                        type="button"
                                                        class="btn btn-outline-danger btn-sm hapus-baris"
                                                        disabled
                                                    >
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>

                                            </tr>

                                        <?php } ?>

                                    </tbody>

                                </table>

                            </div>

                            <div class="d-flex justify-content-between align-items-center flex-wrap mt-3">

                                <small class="kunjungan-hint">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Perbarui obat, jumlah, dan aturan pakai.
                                </small>

                                <strong class="mt-2 mt-sm-0">
                                    Total jenis obat:
                                    <span id="totalJenis">0</span>
                                </strong>

                            </div>

                            <?php if (empty($dataObat)) { ?>

                                <div class="alert alert-warning mt-3 mb-0">
                                    Belum tersedia data obat.
                                </div>

                            <?php } ?>

                        </div>

                    </div>


                </div>


                <!-- ========================================
                     KOLOM RINGKASAN PEMBAYARAN
                ======================================== -->

                <div class="col-lg-4">

                    <div class="card border-0 shadow-sm kunjungan-panel kunjungan-summary">

                        <div class="card-header bg-white">

                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-wallet2 text-success me-2"></i>
                                Ringkasan Pembayaran
                            </h5>

                        </div>

                        <div class="card-body">


                            <!-- TOTAL -->

                            <div class="kunjungan-total-box mb-4">

                                <div class="text-uppercase small">
                                    Total Kunjungan
                                </div>

                                <div class="total-value mt-2" id="totalKunjungan">
                                    Rp 0
                                </div>

                                <div class="small mt-2">
                                    <i class="bi bi-capsule me-1"></i>
                                    <span id="totalUnit">0</span>
                                    unit obat
                                </div>

                            </div>


                            <!-- DETAIL TOTAL -->

                            <div class="d-flex justify-content-between mb-2">

                                <span class="text-muted">
                                    Jenis obat
                                </span>

                                <strong id="ringkasanJenis">0</strong>

                            </div>

                            <div class="d-flex justify-content-between mb-3">

                                <span class="text-muted">
                                    Total unit
                                </span>

                                <strong id="ringkasanUnit">
                                    0 unit
                                </strong>

                            </div>

                            <hr>


                            <!-- PEMBAYARAN -->

                            <div class="kunjungan-payment-box mb-3">

                                <label for="jumlahbayar" class="fw-semibold">

                                    <i class="bi bi-cash-stack text-success me-1"></i>
                                    Jumlah Pembayaran (Rp)

                                </label>

                                <input
                                    type="number"
                                    name="jumlahbayar"
                                    id="jumlahbayar"
                                    class="form-control form-control-lg text-end fw-bold"
                                    min="0"
                                    step="1"
                                    value="<?= (int) $dataKunjungan['jumlahbayar']; ?>"
                                >

                                <small class="text-muted">
                                    Pembayaran dapat disesuaikan dengan
                                    total biaya terbaru.
                                </small>

                                <div class="d-flex flex-wrap mt-2" style="gap: 6px;">

                                    <button type="button"
                                            class="btn btn-outline-secondary btn-sm nominal-cepat"
                                            data-nominal="10000">
                                        10 rb
                                    </button>

                                    <button type="button"
                                            class="btn btn-outline-secondary btn-sm nominal-cepat"
                                            data-nominal="20000">
                                        20 rb
                                    </button>

                                    <button type="button"
                                            class="btn btn-outline-secondary btn-sm nominal-cepat"
                                            data-nominal="50000">
                                        50 rb
                                    </button>

                                    <button type="button"
                                            class="btn btn-outline-secondary btn-sm nominal-cepat"
                                            data-nominal="100000">
                                        100 rb
                                    </button>

                                    <button type="button"
                                            class="btn btn-outline-success btn-sm"
                                            id="uangPas">
                                        Uang Pas
                                    </button>

                                </div>

                            </div>


                            <!-- KEMBALIAN -->

                            <div class="kunjungan-change-box mb-4"
                                 id="kotakKembalian">

                                <div class="text-muted small"
                                     id="labelKembalian">
                                    Kembalian
                                </div>

                                <div class="kunjungan-change-value mt-1"
                                     id="nilaiKembalian">
                                    Rp 0
                                </div>

                                <small id="statusPembayaran">
                                    Belum ada pembayaran.
                                </small>

                            </div>


                            <!-- STATUS PEMBAYARAN -->

                            <div class="mb-3">

                                <label class="fw-semibold">
                                    Status Pembayaran
                                </label>

                                <div id="statusLunas"></div>

                                <small class="text-muted">
                                    Status dihitung kembali berdasarkan
                                    jumlah pembayaran.
                                </small>

                            </div>

                            <hr>


                            <!-- TOMBOL AKSI -->

                            <div class="kunjungan-action">

                                <button
                                    type="submit"
                                    class="btn btn-success w-100 mb-2"
                                    id="btnSimpan"
                                >
                                    <i class="bi bi-save me-1"></i>
                                    Simpan Perubahan
                                </button>

                                <button
                                    type="reset"
                                    class="btn btn-outline-warning w-100 mb-2"
                                    id="btnReset"
                                >
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                                    Reset Perubahan
                                </button>

                                <a
                                    href="index.php?page=showkunjungan&idkunjungan=<?= $idkunjungan; ?>"
                                    class="btn btn-outline-secondary w-100"
                                >
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Kembali ke Detail
                                </a>

                            </div>


                            <div class="text-muted text-center mt-3"
                                 style="font-size: 11px;">

                                <i class="bi bi-shield-check me-1"></i>
                                Pastikan hasil pemeriksaan,
                                obat, dan pembayaran sudah benar.

                            </div>

                        </div>

                    </div>

                </div>


            </div>

        </form>

    </div>

</section>


<!-- ========================================
     JAVASCRIPT EDIT KUNJUNGAN
======================================== -->

<script>
(function () {

    'use strict';

    const form = document.getElementById('formKunjungan');
    const daftarObat = document.getElementById('daftarObat');
    const tombolTambah = document.getElementById('tambahBaris');
    const inputBayar = document.getElementById('jumlahbayar');

    const formatter = new Intl.NumberFormat('id-ID', {
        maximumFractionDigits: 0
    });

    function rupiah(nilai) {
        return 'Rp ' + formatter.format(nilai);
    }

    function angka(input) {
        const nilai = Number(input);
        return Number.isFinite(nilai) && nilai > 0 ? nilai : 0;
    }

    // ========================================
    // SEMUA BARIS OBAT
    // ========================================

    function semuaBaris() {
        return Array.from(
            daftarObat.querySelectorAll('.baris-obat')
        );
    }

    // ========================================
    // PILIHAN OBAT
    // ========================================

    function opsiObat() {

        return `
            <option value="">
                -- Pilih Obat --
            </option>

            <?php foreach ($dataObat as $o) { ?>

                <option
                    value="<?= (int) $o['idobat']; ?>"
                    data-harga="<?= (int) $o['harga']; ?>"
                    data-stok="<?= (int) $o['stok'] + (int) $o['jumlah_lama']; ?>"
                    <?= ((int) $o['stok'] + (int) $o['jumlah_lama']) <= 0 ? 'disabled' : ''; ?>
                >
                    <?= htmlspecialchars($o['namaobat'], ENT_QUOTES, 'UTF-8'); ?>
                    — Rp <?= number_format($o['harga'], 0, ',', '.'); ?>
                    (Stok: <?= (int) $o['stok'] + (int) $o['jumlah_lama']; ?>)
                </option>

            <?php } ?>
        `;
    }

    // ========================================
    // MEMBUAT BARIS BARU
    // ========================================

    function buatBaris() {

        const tr = document.createElement('tr');

        tr.className = 'baris-obat';

        tr.innerHTML = `

            <td class="nomor-baris text-center"></td>

            <td>
                <select
                    name="idobat[]"
                    class="form-select form-select-sm pilih-obat"
                >
                    ${opsiObat()}
                </select>
            </td>

            <td class="harga-obat text-end">Rp 0</td>

            <td class="stok-obat text-center">-</td>

            <td>
                <input
                    type="number"
                    name="jumlah[]"
                    class="form-control form-control-sm qty-obat"
                    min="1"
                    step="1"
                    value="1"
                >
            </td>

            <td class="subtotal-obat text-end fw-bold">Rp 0</td>

            <td>
                <input
                    type="text"
                    name="aturanpakai[]"
                    class="form-control form-control-sm aturan-obat"
                    placeholder="Contoh: 3x sehari"
                >
            </td>

            <td class="text-center">
                <button
                    type="button"
                    class="btn btn-outline-danger btn-sm hapus-baris"
                    title="Hapus obat"
                >
                    <i class="bi bi-trash"></i>
                </button>
            </td>

        `;

        return tr;
    }

    // ========================================
    // NOMOR BARIS
    // ========================================

    function perbaruiNomor() {

        const baris = semuaBaris();

        baris.forEach(function (tr, index) {

            tr.querySelector('.nomor-baris').textContent = index + 1;

            tr.querySelector('.hapus-baris').disabled =
                baris.length === 1;

        });

    }

    // ========================================
    // HITUNG TOTAL
    // ========================================

    function hitung() {

        let total = 0;
        let totalUnit = 0;
        let jenis = 0;

        semuaBaris().forEach(function (tr) {

            const select = tr.querySelector('.pilih-obat');
            const qty = tr.querySelector('.qty-obat');

            const opsi = select.options[select.selectedIndex];

            const harga = opsi && select.value
                ? angka(opsi.dataset.harga)
                : 0;

            const stok = opsi && select.value
                ? Number(opsi.dataset.stok)
                : 0;

            let jumlah = parseInt(qty.value, 10);

            if (!Number.isFinite(jumlah) || jumlah < 1) {
                jumlah = 1;
            }

            if (select.value) {

                qty.max = stok;

                tr.querySelector('.harga-obat').textContent =
                    rupiah(harga);

                tr.querySelector('.stok-obat').textContent =
                    stok;

                const subtotal = harga * jumlah;

                tr.querySelector('.subtotal-obat').textContent =
                    rupiah(subtotal);

                total += subtotal;
                totalUnit += jumlah;
                jenis++;

            } else {

                qty.removeAttribute('max');

                tr.querySelector('.harga-obat').textContent = 'Rp 0';
                tr.querySelector('.stok-obat').textContent = '-';
                tr.querySelector('.subtotal-obat').textContent = 'Rp 0';

            }

            tr.classList.toggle(
                'table-danger',
                Boolean(select.value) && jumlah > stok
            );

        });


        // ========================================
        // HITUNG PEMBAYARAN
        // ========================================

        const uang = angka(inputBayar.value);
        const selisih = uang - total;

        document.getElementById('totalKunjungan').textContent =
            rupiah(total);

        document.getElementById('totalJenis').textContent =
            jenis;

        document.getElementById('ringkasanJenis').textContent =
            jenis;

        document.getElementById('totalUnit').textContent =
            totalUnit;

        document.getElementById('ringkasanUnit').textContent =
            totalUnit + ' unit';


        const kotak = document.getElementById('kotakKembalian');
        const label = document.getElementById('labelKembalian');
        const nilai = document.getElementById('nilaiKembalian');
        const status = document.getElementById('statusPembayaran');
        const statusLunas = document.getElementById('statusLunas');


        if (total === 0) {

            kotak.classList.remove('kurang');

            label.textContent = 'Kembalian';
            nilai.textContent = 'Rp 0';
            status.textContent = 'Belum ada biaya obat.';

            statusLunas.innerHTML = `
                <span class="badge bg-warning text-dark fs-6">
                    Belum Bayar
                </span>
            `;

        } else if (selisih < 0) {

            kotak.classList.add('kurang');

            label.textContent = 'Kekurangan Pembayaran';
            nilai.textContent = rupiah(Math.abs(selisih));
            status.textContent = 'Pembayaran belum mencukupi.';

            statusLunas.innerHTML = `
                <span class="badge bg-warning text-dark fs-6">
                    Belum Bayar
                </span>
            `;

        } else {

            kotak.classList.remove('kurang');

            label.textContent = 'Kembalian';
            nilai.textContent = rupiah(selisih);
            status.textContent = 'Pembayaran mencukupi.';

            statusLunas.innerHTML = `
                <span class="badge bg-success fs-6">
                    <i class="bi bi-check-circle me-1"></i>
                    Lunas
                </span>
            `;

        }

        return {
            total: total,
            uang: uang,
            jenis: jenis,
            totalUnit: totalUnit,
            selisih: selisih
        };

    }

    // ========================================
    // CEK OBAT DUPLIKAT
    // ========================================

    function obatDuplikat(selectSaatIni) {

        const nilai = selectSaatIni.value;

        if (!nilai) {
            return false;
        }

        return semuaBaris().some(function (tr) {

            const select = tr.querySelector('.pilih-obat');

            return select !== selectSaatIni &&
                select.value === nilai;

        });

    }

    // ========================================
    // VALIDASI TRANSAKSI
    // ========================================

    function validasiTransaksi() {

        const hasil = hitung();
        const baris = semuaBaris();

        for (const tr of baris) {

            const select = tr.querySelector('.pilih-obat');
            const qty = tr.querySelector('.qty-obat');

            if (!select.value) {
                continue;
            }

            if (obatDuplikat(select)) {

                alert(
                    'Obat yang sama dipilih lebih dari satu kali. ' +
                    'Gabungkan jumlahnya dalam satu baris.'
                );

                select.focus();

                return false;

            }

            const opsi = select.options[select.selectedIndex];

            const stok = Number(opsi.dataset.stok);
            const jumlah = Number(qty.value);

            if (!Number.isInteger(jumlah) || jumlah < 1) {

                alert('Jumlah obat harus berupa bilangan bulat minimal 1.');

                qty.focus();

                return false;

            }

            if (jumlah > stok) {

                alert(
                    'Jumlah obat melebihi stok yang tersedia: ' +
                    opsi.text
                );

                qty.focus();

                return false;

            }

        }

        if (hasil.uang < 0) {

            alert('Jumlah pembayaran tidak valid.');

            inputBayar.focus();

            return false;

        }

        return true;

    }

    // ========================================
    // TAMBAH BARIS OBAT
    // ========================================

    tombolTambah.addEventListener('click', function () {

        daftarObat.appendChild(buatBaris());

        perbaruiNomor();
        hitung();

        const baris = semuaBaris();

        baris[baris.length - 1]
            .querySelector('.pilih-obat')
            .focus();

    });

    // ========================================
    // PILIH OBAT
    // ========================================

    daftarObat.addEventListener('change', function (event) {

        if (event.target.classList.contains('pilih-obat')) {

            if (obatDuplikat(event.target)) {

                alert(
                    'Obat tersebut sudah ada dalam daftar. ' +
                    'Silakan ubah jumlah pada baris yang tersedia.'
                );

                event.target.value = '';

            }

            hitung();

        }

    });

    // ========================================
    // UBAH JUMLAH
    // ========================================

    daftarObat.addEventListener('input', function (event) {

        if (event.target.classList.contains('qty-obat')) {
            hitung();
        }

    });

    // ========================================
    // HAPUS BARIS
    // ========================================

    daftarObat.addEventListener('click', function (event) {

        const tombol = event.target.closest('.hapus-baris');

        if (!tombol) {
            return;
        }

        const baris = semuaBaris();

        if (baris.length <= 1) {
            return;
        }

        tombol.closest('.baris-obat').remove();

        perbaruiNomor();
        hitung();

    });

    // ========================================
    // PEMBAYARAN
    // ========================================

    inputBayar.addEventListener('input', hitung);

    document.querySelectorAll('.nominal-cepat').forEach(function (tombol) {

        tombol.addEventListener('click', function () {

            const nominal = Number(tombol.dataset.nominal);

            inputBayar.value = angka(inputBayar.value) + nominal;

            hitung();

        });

    });

    // ========================================
    // UANG PAS
    // ========================================

    document.getElementById('uangPas').addEventListener('click', function () {

        let total = 0;

        semuaBaris().forEach(function (tr) {

            const select = tr.querySelector('.pilih-obat');
            const qty = tr.querySelector('.qty-obat');
            const opsi = select.options[select.selectedIndex];

            if (select.value) {

                const harga = angka(opsi.dataset.harga);

                const jumlah = Math.max(
                    1,
                    parseInt(qty.value, 10) || 1
                );

                total += harga * jumlah;

            }

        });

        inputBayar.value = total;

        hitung();

    });

    // ========================================
    // RESET FORM
    // ========================================

    form.addEventListener('reset', function () {

        setTimeout(function () {

            const baris = semuaBaris();

            for (let i = 1; i < baris.length; i++) {
                baris[i].remove();
            }

            perbaruiNomor();
            hitung();

        }, 0);

    });

    // ========================================
    // VALIDASI SEBELUM SIMPAN
    // ========================================

    form.addEventListener('submit', function (event) {

        if (!validasiTransaksi()) {

            event.preventDefault();

            return;

        }

        const tombolSubmit = document.getElementById('btnSimpan');

        tombolSubmit.disabled = true;

        tombolSubmit.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1"></span>
            Memperbarui Kunjungan...
        `;

    });

    // ========================================
    // INISIALISASI
    // ========================================

    perbaruiNomor();
    hitung();

})();
</script>

<?php } ?>
