<?php
// ========================================
// PENCARIAN KUNJUNGAN
// ========================================
$cari = "";

if (isset($_GET['cari'])) {
    $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);
}

// ========================================
// QUERY DATA KUNJUNGAN
// ========================================
$query = mysqli_query($koneksi, "
    SELECT
        kunjungan.*,
        pasien.namapasien,
        dokter.namadokter,
        user.namauser
    FROM kunjungan
    INNER JOIN pasien
        ON kunjungan.idpasien = pasien.idpasien
    INNER JOIN dokter
        ON kunjungan.iddokter = dokter.iddokter
    INNER JOIN user
        ON kunjungan.iduser = user.iduser
    WHERE
        pasien.namapasien LIKE '%$cari%'
        OR dokter.namadokter LIKE '%$cari%'
        OR kunjungan.keluhan LIKE '%$cari%'
        OR kunjungan.idkunjungan LIKE '%$cari%'
    ORDER BY kunjungan.idkunjungan DESC
");
?>

<div class="container-fluid">

    <!-- JUDUL HALAMAN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-clipboard2-pulse text-primary"></i>
                Data Kunjungan
            </h4>
            <p class="text-muted mb-0">
                Kelola data pemeriksaan dan transaksi kunjungan pasien.
            </p>
        </div>
    </div>

    <!-- INFORMASI -->
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        Data kunjungan dibuat melalui proses pendaftaran checkup
        yang telah disetujui ketika pasien datang untuk melakukan pemeriksaan.
    </div>

    <!-- CARD DATA -->
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <div class="row align-items-center">

                <div class="col-md-6">
                    <h5 class="fw-bold mb-0">
                        Daftar Kunjungan Pasien
                    </h5>
                </div>

                <div class="col-md-6">
                    <form method="GET" action="index.php">
                        <input type="hidden" name="page" value="kunjungan">

                        <div class="input-group">
                            <input
                                type="text"
                                name="cari"
                                class="form-control"
                                placeholder="Cari pasien, dokter, keluhan..."
                                value="<?= htmlspecialchars($cari); ?>"
                            >

                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i>
                                Cari
                            </button>

                            <?php if ($cari != "") { ?>
                                <a href="index.php?page=kunjungan"
                                   class="btn btn-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            <?php } ?>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr class="text-center">
                            <th width="50">No</th>
                            <th width="100">ID Kunjungan</th>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th width="120">Tanggal</th>
                            <th>Keluhan</th>
                            <th width="130">Total Biaya</th>
                            <th width="120">Pembayaran</th>
                            <th width="110">Kunjungan</th>
                            <th width="130">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $no = 1;

                        if (mysqli_num_rows($query) > 0) {

                            while ($data = mysqli_fetch_assoc($query)) {
                        ?>

                            <tr>

                                <td class="text-center">
                                    <?= $no++; ?>
                                </td>

                                <td class="text-center fw-bold">
                                    KJ-<?= str_pad($data['idkunjungan'], 4, '0', STR_PAD_LEFT); ?>
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($data['namapasien']); ?>
                                    </div>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['namadokter']); ?>
                                </td>

                                <td class="text-center">
                                    <?= date('d-m-Y', strtotime($data['tanggalkunjungan'])); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['keluhan'] ?? '-'); ?>
                                </td>

                                <td class="text-end">
                                    Rp <?= number_format($data['totalkunjungan'], 0, ',', '.'); ?>
                                </td>

                                <td class="text-center">
                                    <?php if ($data['statuspembayaran'] == 'Lunas') { ?>

                                        <span class="badge bg-success">
                                            Lunas
                                        </span>

                                    <?php } else { ?>

                                        <span class="badge bg-warning text-dark">
                                            Belum Bayar
                                        </span>

                                    <?php } ?>
                                </td>

                                <td class="text-center">
                                    <?php if ($data['statuskunjungan'] == 'Selesai') { ?>

                                        <span class="badge bg-success">
                                            Selesai
                                        </span>

                                    <?php } else { ?>

                                        <span class="badge bg-primary">
                                            Proses
                                        </span>

                                    <?php } ?>
                                </td>

                                <td class="text-center">

                                    <div class="btn-group">
                                        <a href="index.php?page=showkunjungan&id=<?= $data['idkunjungan']; ?>"
                                           class="btn btn-sm btn-info text-white"
                                           title="Detail">
                                            <i class="bi bi-eye"> </i>
                                        </a>                                        
                                        <a href="index.php?page=editkunjungan&idkunjungan=<?= $data['idkunjungan']; ?>"
                                        class="btn btn-sm btn-warning"
                                        title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </div>

                                </td>

                            </tr>

                        <?php
                            }

                        } else {
                        ?>

                            <tr>
                                <td colspan="10" class="text-center py-4">

                                    <i class="bi bi-inbox fs-1 text-muted"></i>

                                    <p class="text-muted mb-0 mt-2">
                                        Data kunjungan belum tersedia.
                                    </p>

                                </td>
                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

        <div class="card-footer bg-white">

            <small class="text-muted">
                Menampilkan <?= mysqli_num_rows($query); ?> data kunjungan.
            </small>

        </div>

    </div>

</div>
