
<?php
// ========================================
// AMBIL ID KUNJUNGAN
// ========================================
$idkunjungan = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// ========================================
// QUERY DATA KUNJUNGAN
// ========================================
$query = mysqli_query($koneksi, "
    SELECT
        kunjungan.*,
        pasien.namapasien,
        pasien.username AS usernamepasien,
        pasien.nohp AS nohppasien,
        pasien.alamat AS alamatpasien,
        dokter.namadokter,
        dokter.spesialis,
        user.namauser,
        jadwaldokter.haripraktik,
        jadwaldokter.jam_mulai,
        jadwaldokter.jam_selesai
    FROM kunjungan
    INNER JOIN pasien
        ON kunjungan.idpasien = pasien.idpasien
    INNER JOIN dokter
        ON kunjungan.iddokter = dokter.iddokter
    INNER JOIN user
        ON kunjungan.iduser = user.iduser
    INNER JOIN jadwaldokter
        ON kunjungan.idjadwal = jadwaldokter.idjadwal
    WHERE kunjungan.idkunjungan = '$idkunjungan'
");

$data = mysqli_fetch_assoc($query);

// ========================================
// JIKA DATA TIDAK DITEMUKAN
// ========================================
if (!$data) {
?>

    <div class="container-fluid">

        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Data kunjungan tidak ditemukan.
        </div>

        <a href="index.php?page=kunjungan" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

    </div>

<?php
    return;
}

// ========================================
// QUERY DETAIL OBAT KUNJUNGAN
// ========================================
$queryobat = mysqli_query($koneksi, "
    SELECT
        detilkunjungan.*,
        obat.namaobat
    FROM detilkunjungan
    INNER JOIN obat
        ON detilkunjungan.idobat = obat.idobat
    WHERE detilkunjungan.idkunjungan = '$idkunjungan'
    ORDER BY detilkunjungan.iddetilkunjungan ASC
");
?>

<div class="container-fluid">

    <!-- JUDUL HALAMAN -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-clipboard2-pulse text-primary"></i>
                Detail Kunjungan
            </h4>

            <p class="text-muted mb-0">
                Informasi lengkap transaksi pemeriksaan pasien.
            </p>
        </div>

        <a href="index.php?page=kunjungan" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

    </div>

    <!-- IDENTITAS TRANSAKSI -->
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-primary text-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-receipt me-2"></i>
                    KJ-<?= str_pad($data['idkunjungan'], 4, '0', STR_PAD_LEFT); ?>
                </h5>

                <span class="badge bg-light text-dark">
                    <?= date('d-m-Y', strtotime($data['tanggalkunjungan'])); ?>
                </span>

            </div>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <!-- DATA PASIEN -->
                <div class="col-md-6">

                    <h6 class="fw-bold text-primary mb-3">
                        <i class="bi bi-person-circle me-2"></i>
                        Informasi Pasien
                    </h6>

                    <table class="table table-sm table-borderless">

                        <tr>
                            <td width="130">Nama Pasien</td>
                            <td width="10">:</td>
                            <td class="fw-semibold">
                                <?= htmlspecialchars($data['namapasien']); ?>
                            </td>
                        </tr>

                        <tr>
                            <td>Username</td>
                            <td>:</td>
                            <td>
                                <?= htmlspecialchars($data['usernamepasien'] ?? '-'); ?>
                            </td>
                        </tr>

                        <tr>
                            <td>No. HP</td>
                            <td>:</td>
                            <td>
                                <?= htmlspecialchars($data['nohppasien'] ?? '-'); ?>
                            </td>
                        </tr>

                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>
                                <?= htmlspecialchars($data['alamatpasien'] ?? '-'); ?>
                            </td>
                        </tr>

                    </table>

                </div>

                <!-- DATA DOKTER -->
                <div class="col-md-6">

                    <h6 class="fw-bold text-primary mb-3">
                        <i class="bi bi-person-badge me-2"></i>
                        Informasi Pemeriksaan
                    </h6>

                    <table class="table table-sm table-borderless">

                        <tr>
                            <td width="130">Dokter</td>
                            <td width="10">:</td>
                            <td class="fw-semibold">
                                <?= htmlspecialchars($data['namadokter']); ?>
                            </td>
                        </tr>

                        <tr>
                            <td>Spesialis</td>
                            <td>:</td>
                            <td>
                                <?= htmlspecialchars($data['spesialis'] ?? '-'); ?>
                            </td>
                        </tr>

                        <tr>
                            <td>Hari Praktik</td>
                            <td>:</td>
                            <td>
                                <?= htmlspecialchars($data['haripraktik']); ?>
                            </td>
                        </tr>

                        <tr>
                            <td>Jam Praktik</td>
                            <td>:</td>
                            <td>
                                <?= date('H:i', strtotime($data['jam_mulai'])); ?>
                                -
                                <?= date('H:i', strtotime($data['jam_selesai'])); ?>
                            </td>
                        </tr>

                        <tr>
                            <td>Petugas</td>
                            <td>:</td>
                            <td>
                                <?= htmlspecialchars($data['namauser']); ?>
                            </td>
                        </tr>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <!-- KELUHAN DAN DIAGNOSIS -->
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="fw-bold mb-0">
                <i class="bi bi-journal-medical text-primary me-2"></i>
                Hasil Pemeriksaan
            </h5>

        </div>

        <div class="card-body">

            <div class="mb-4">

                <label class="fw-bold mb-2">
                    Keluhan Pasien
                </label>

                <div class="p-3 bg-light rounded">
                    <?= nl2br(htmlspecialchars($data['keluhan'] ?? '-')); ?>
                </div>

            </div>

            <div>

                <label class="fw-bold mb-2">
                    Diagnosis Dokter
                </label>

                <div class="p-3 bg-light rounded">
                    <?= nl2br(htmlspecialchars($data['diagnosis'] ?? '-')); ?>
                </div>

            </div>

        </div>

    </div>

    <!-- DAFTAR OBAT -->
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="fw-bold mb-0">
                <i class="bi bi-capsule text-primary me-2"></i>
                Daftar Obat Kunjungan
            </h5>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr class="text-center">
                            <th width="50">No</th>
                            <th>Nama Obat</th>
                            <th width="100">Jumlah</th>
                            <th width="150">Harga</th>
                            <th width="150">Subtotal</th>
                            <th>Aturan Pakai</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php
                        $no = 1;
                        $totalobat = 0;

                        if (mysqli_num_rows($queryobat) > 0) {

                            while ($obat = mysqli_fetch_assoc($queryobat)) {

                                $totalobat += $obat['subtotal'];
                        ?>

                            <tr>

                                <td class="text-center">
                                    <?= $no++; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($obat['namaobat']); ?>
                                </td>

                                <td class="text-center">
                                    <?= $obat['jumlah']; ?>
                                </td>

                                <td class="text-end">
                                    Rp <?= number_format($obat['harga'], 0, ',', '.'); ?>
                                </td>

                                <td class="text-end">
                                    Rp <?= number_format($obat['subtotal'], 0, ',', '.'); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($obat['aturanpakai'] ?? '-'); ?>
                                </td>

                            </tr>

                        <?php
                            }

                        } else {
                        ?>

                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Belum ada obat yang diberikan.
                                </td>
                            </tr>

                        <?php } ?>

                    </tbody>

                    <?php if (mysqli_num_rows($queryobat) > 0) { ?>

                        <tfoot class="table-light">

                            <tr>
                                <th colspan="4" class="text-end">
                                    Total Obat
                                </th>

                                <th class="text-end">
                                    Rp <?= number_format($totalobat, 0, ',', '.'); ?>
                                </th>

                                <th></th>
                            </tr>

                        </tfoot>

                    <?php } ?>

                </table>

            </div>

        </div>

    </div>

    <!-- INFORMASI PEMBAYARAN -->
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="fw-bold mb-0">
                <i class="bi bi-cash-coin text-primary me-2"></i>
                Informasi Pembayaran
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-6">

                    <table class="table table-borderless">

                        <tr>
                            <td>Total Kunjungan</td>
                            <td class="text-end fw-bold">
                                Rp <?= number_format($data['totalkunjungan'], 0, ',', '.'); ?>
                            </td>
                        </tr>

                        <tr>
                            <td>Jumlah Pembayaran</td>
                            <td class="text-end">
                                Rp <?= number_format($data['jumlahbayar'], 0, ',', '.'); ?>
                            </td>
                        </tr>

                        <tr>
                            <td>Kembalian</td>
                            <td class="text-end">
                                Rp <?= number_format($data['kembalian'], 0, ',', '.'); ?>
                            </td>
                        </tr>

                    </table>

                </div>

                <div class="col-md-6">

                    <div class="p-3 bg-light rounded mb-3">

                        <div class="mb-2">
                            <span class="text-muted">Status Pembayaran</span>
                        </div>

                        <?php if ($data['statuspembayaran'] == 'Lunas') { ?>

                            <span class="badge bg-success fs-6">
                                <i class="bi bi-check-circle me-1"></i>
                                Lunas
                            </span>

                        <?php } else { ?>

                            <span class="badge bg-warning text-dark fs-6">
                                <i class="bi bi-clock me-1"></i>
                                Belum Bayar
                            </span>

                        <?php } ?>

                    </div>

                    <div class="p-3 bg-light rounded">

                        <div class="mb-2">
                            <span class="text-muted">Status Kunjungan</span>
                        </div>

                        <?php if ($data['statuskunjungan'] == 'Selesai') { ?>

                            <span class="badge bg-success fs-6">
                                <i class="bi bi-check-circle me-1"></i>
                                Selesai
                            </span>

                        <?php } else { ?>

                            <span class="badge bg-primary fs-6">
                                <i class="bi bi-arrow-repeat me-1"></i>
                                Proses
                            </span>

                        <?php } ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- TOMBOL AKSI -->
    <div class="d-flex justify-content-end gap-2 mb-4">

        <a href="index.php?page=kunjungan" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>

        <a href="index.php?page=editkunjungan&id=<?= $data['idkunjungan']; ?>"
           class="btn btn-warning">

            <i class="bi bi-pencil-square me-1"></i>
            Edit Kunjungan

        </a>

    </div>