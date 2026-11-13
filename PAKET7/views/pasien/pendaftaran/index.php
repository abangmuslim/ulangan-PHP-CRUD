
<?php
$idpasien = $_SESSION['idpasien'];

// Ambil seluruh riwayat pendaftaran pasien.
$querypendaftaran = mysqli_query($koneksi, "
    SELECT
        pendaftarancheckup.*,
        dokter.namadokter,
        dokter.spesialis,
        jadwaldokter.haripraktik,
        jadwaldokter.jam_mulai,
        jadwaldokter.jam_selesai
    FROM pendaftarancheckup
    INNER JOIN jadwaldokter
        ON pendaftarancheckup.idjadwal = jadwaldokter.idjadwal
    INNER JOIN dokter
        ON jadwaldokter.iddokter = dokter.iddokter
    WHERE pendaftarancheckup.idpasien = '$idpasien'
    ORDER BY pendaftarancheckup.tanggalpengajuan DESC
");

// Hitung jumlah pendaftaran berdasarkan status.
$total = mysqli_num_rows($querypendaftaran);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Pendaftaran Checkup</h4>
        <p class="text-muted mb-0">
            Riwayat permohonan pemeriksaan kesehatan Anda.
        </p>
    </div>

    <a href="index.php?page=creatependaftaranpasien"
       class="btn btn-success">
        <i class="fas fa-plus-circle me-1"></i>
        Daftar Checkup
    </a>
</div>

<div class="row mb-4">

    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1">Total Pendaftaran</p>
                <h3 class="fw-bold mb-0"><?= $total; ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1">Menunggu Konfirmasi</p>
                <?php
                $menunggu = mysqli_query($koneksi, "
                    SELECT idpendaftaran
                    FROM pendaftarancheckup
                    WHERE idpasien = '$idpasien'
                    AND status = 'Menunggu'
                ");
                ?>
                <h3 class="fw-bold text-warning mb-0">
                    <?= mysqli_num_rows($menunggu); ?>
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1">Disetujui</p>
                <?php
                $disetujui = mysqli_query($koneksi, "
                    SELECT idpendaftaran
                    FROM pendaftarancheckup
                    WHERE idpasien = '$idpasien'
                    AND status = 'Disetujui'
                ");
                ?>
                <h3 class="fw-bold text-success mb-0">
                    <?= mysqli_num_rows($disetujui); ?>
                </h3>
            </div>
        </div>
    </div>

</div>

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">
            <i class="fas fa-history me-2"></i>
            Riwayat Permohonan Saya
        </h5>
    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover table-bordered align-middle">

                <thead class="table-light">
                    <tr>
                        <th width="5%">No.</th>
                        <th>Dokter</th>
                        <th>Tanggal Periksa</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Antrean</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if ($total > 0) { ?>

                        <?php $no = 1; ?>

                        <?php while ($data = mysqli_fetch_assoc($querypendaftaran)) { ?>

                            <tr>

                                <td><?= $no++; ?></td>

                                <td>
                                    <strong><?= e($data['namadokter']); ?></strong>
                                    <br>
                                    <small class="text-muted">
                                        <?= e($data['spesialis']); ?>
                                    </small>
                                </td>

                                <td>
                                    <?= date('d-m-Y', strtotime($data['tanggalperiksa'])); ?>
                                </td>

                                <td>
                                    <?= e($data['haripraktik']); ?>
                                    <br>
                                    <small class="text-muted">
                                        <?= e($data['jam_mulai']); ?>
                                        -
                                        <?= e($data['jam_selesai']); ?>
                                    </small>
                                </td>

                                <td>
                                    <?php if ($data['status'] == 'Menunggu') { ?>
                                        <span class="badge bg-warning text-dark">
                                            Menunggu
                                        </span>
                                    <?php } elseif ($data['status'] == 'Disetujui') { ?>
                                        <span class="badge bg-success">
                                            Disetujui
                                        </span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger">
                                            Ditolak
                                        </span>
                                    <?php } ?>
                                </td>

                                <td>
                                    <?php if (!empty($data['nomorantrian'])) { ?>
                                        <span class="badge bg-primary">
                                            No. <?= $data['nomorantrian']; ?>
                                        </span>
                                    <?php } elseif ($data['status'] == 'Disetujui') { ?>
                                        <span class="badge bg-info text-dark">
                                            Belum Check-in
                                        </span>
                                    <?php } else { ?>
                                        <span class="text-muted">
                                            Belum Antri
                                        </span>
                                    <?php } ?>
                                </td>

                                <td>
                                    <a href="index.php?page=showpendaftaranpasien&id=<?= $data['idpendaftaran']; ?>"
                                       class="btn btn-sm btn-info text-white"
                                       title="Detail Pendaftaran">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>

                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                <p class="mb-0">
                                    Anda belum memiliki permohonan checkup.
                                </p>
                            </td>
                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>
</div>
