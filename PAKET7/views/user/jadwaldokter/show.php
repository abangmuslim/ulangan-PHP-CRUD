
<?php
$idjadwal = (int) ($_GET['idjadwal'] ?? 0);

$stmt = mysqli_prepare($koneksi, "
    SELECT
        j.idjadwal,
        j.iddokter,
        j.haripraktik,
        j.jam_mulai,
        j.jam_selesai,
        d.namadokter,
        d.spesialis,
        d.nohp
    FROM jadwaldokter j
    INNER JOIN dokter d ON j.iddokter = d.iddokter
    WHERE j.idjadwal = ?
");

mysqli_stmt_bind_param($stmt, "i", $idjadwal);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$jadwal = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Jadwal Dokter</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="index.php?page=dashboarduser">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="index.php?page=jadwaldokter">Jadwal Dokter</a>
                    </li>
                    <li class="breadcrumb-item active">Detail Jadwal</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <?php if (!$jadwal) : ?>

            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Jadwal dokter tidak ditemukan.
            </div>

            <a href="index.php?page=jadwaldokter" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>

        <?php else : ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="card card-success card-outline">

                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Informasi Jadwal Praktik
                            </h3>
                        </div>

                        <div class="card-body">

                            <table class="table table-bordered">
                                <tr>
                                    <th width="35%">ID Jadwal</th>
                                    <td><?= (int) $jadwal['idjadwal'] ?></td>
                                </tr>
                                <tr>
                                    <th>Nama Dokter</th>
                                    <td><?= e($jadwal['namadokter']) ?></td>
                                </tr>
                                <tr>
                                    <th>Spesialis</th>
                                    <td><?= e($jadwal['spesialis'] ?: '-') ?></td>
                                </tr>
                                <tr>
                                    <th>Nomor HP</th>
                                    <td><?= e($jadwal['nohp'] ?: '-') ?></td>
                                </tr>
                                <tr>
                                    <th>Hari Praktik</th>
                                    <td><?= e($jadwal['haripraktik']) ?></td>
                                </tr>
                                <tr>
                                    <th>Jam Mulai</th>
                                    <td><?= e(substr($jadwal['jam_mulai'], 0, 5)) ?></td>
                                </tr>
                                <tr>
                                    <th>Jam Selesai</th>
                                    <td><?= e(substr($jadwal['jam_selesai'], 0, 5)) ?></td>
                                </tr>
                            </table>

                        </div>

                        <div class="card-footer">

                            <a href="index.php?page=editjadwaldokter&idjadwal=<?= (int) $jadwal['idjadwal'] ?>"
                               class="btn btn-warning">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>

                            <form action="index.php?page=jadwaldokter"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus jadwal praktik ini?')">

                                <?= csrfField() ?>

                                <input type="hidden" name="page" value="jadwaldokter">
                                <input type="hidden" name="aksi" value="hapus">
                                <input type="hidden" name="idjadwal"
                                    value="<?= (int) $jadwal['idjadwal'] ?>">

                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>

                            <a href="index.php?page=jadwaldokter" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>

                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </div>
</section>
