
<?php
// ============================================
// DATA RINGKASAN JADWAL DOKTER
// ============================================
$queryTotal = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total FROM jadwaldokter
");
$totalJadwal = mysqli_fetch_assoc($queryTotal)['total'] ?? 0;

$queryDokter = mysqli_query($koneksi, "
    SELECT COUNT(DISTINCT iddokter) AS total
    FROM jadwaldokter
");
$totalDokter = mysqli_fetch_assoc($queryDokter)['total'] ?? 0;

// ============================================
// DATA JADWAL DOKTER
// ============================================
$query = mysqli_query($koneksi, "
    SELECT
        j.idjadwal,
        j.iddokter,
        j.haripraktik,
        j.jam_mulai,
        j.jam_selesai,
        d.namadokter,
        d.spesialis
    FROM jadwaldokter j
    INNER JOIN dokter d ON j.iddokter = d.iddokter
    ORDER BY
        d.namadokter,
        FIELD(j.haripraktik, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'),
        j.jam_mulai
");
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Jadwal Dokter</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="index.php?page=dashboarduser">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Jadwal Dokter</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <!-- RINGKASAN -->
        <div class="row">
            <div class="col-md-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= (int) $totalJadwal ?></h3>
                        <p>Total Jadwal Praktik</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= (int) $totalDokter ?></h3>
                        <p>Dokter Memiliki Jadwal</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- PESAN -->
        <?php if (isset($_SESSION['success_jadwaldokter'])) : ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle mr-1"></i>
                <?= e($_SESSION['success_jadwaldokter']) ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['success_jadwaldokter']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_jadwaldokter'])) : ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle mr-1"></i>
                <?= e($_SESSION['error_jadwaldokter']) ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['error_jadwaldokter']); ?>
        <?php endif; ?>

        <!-- TABEL JADWAL -->
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Daftar Jadwal Praktik Dokter
                </h3>
                <div class="card-tools">
                    <a href="index.php?page=createjadwaldokter" class="btn btn-success btn-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Jadwal
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="text-center">
                            <tr>
                                <th width="55">No.</th>
                                <th>Nama Dokter</th>
                                <th>Spesialis</th>
                                <th>Hari Praktik</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th width="130">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($query && mysqli_num_rows($query) > 0) : ?>
                                <?php $no = 1; ?>
                                <?php while ($jadwal = mysqli_fetch_assoc($query)) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= e($jadwal['namadokter']) ?></td>
                                        <td><?= e($jadwal['spesialis'] ?: '-') ?></td>
                                        <td><?= e($jadwal['haripraktik']) ?></td>
                                        <td class="text-center">
                                            <?= e(substr($jadwal['jam_mulai'], 0, 5)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?= e(substr($jadwal['jam_selesai'], 0, 5)) ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="index.php?page=showjadwaldokter&idjadwal=<?= (int) $jadwal['idjadwal'] ?>"
                                               class="btn btn-info btn-xs" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="index.php?page=editjadwaldokter&idjadwal=<?= (int) $jadwal['idjadwal'] ?>"
                                               class="btn btn-warning btn-xs" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        Belum ada jadwal praktik dokter.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer text-muted">
                <i class="fas fa-info-circle mr-1"></i>
                Halaman ini menampilkan seluruh jadwal praktik dokter yang terdaftar di klinik.
            </div>
        </div>

    </div>
</section>
