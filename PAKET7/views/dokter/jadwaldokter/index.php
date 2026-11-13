
<?php
$iddokter = (int) ($_SESSION['iddokter'] ?? 0);

$query = mysqli_query($koneksi, "
    SELECT idjadwal, haripraktik, jam_mulai, jam_selesai
    FROM jadwaldokter
    WHERE iddokter = $iddokter
    ORDER BY FIELD(haripraktik, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), jam_mulai
");
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Jadwal Praktik Saya</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboarddokter">Dashboard</a></li>
                    <li class="breadcrumb-item active">Jadwal Dokter</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Daftar Jadwal Praktik
                </h3>
                <div class="card-tools">
                    <a href="index.php?page=createjadwaldokterdokter" class="btn btn-success btn-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Jadwal
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="text-center">
                            <tr>
                                <th width="60">No.</th>
                                <th>Hari Praktik</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($query && mysqli_num_rows($query) > 0) : ?>
                                <?php $no = 1; ?>
                                <?php while ($jadwal = mysqli_fetch_assoc($query)) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= e($jadwal['haripraktik']) ?></td>
                                        <td class="text-center"><?= e(substr($jadwal['jam_mulai'], 0, 5)) ?></td>
                                        <td class="text-center"><?= e(substr($jadwal['jam_selesai'], 0, 5)) ?></td>
                                        <td class="text-center">
                                            <a href="index.php?page=showjadwaldokterdokter&id=<?= (int) $jadwal['idjadwal'] ?>"
                                               class="btn btn-info btn-xs" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="index.php?page=editjadwaldokterdokter&idjadwal=<?= (int) $jadwal['idjadwal'] ?>"
                                                class="btn btn-warning btn-xs" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Belum ada jadwal praktik yang ditambahkan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer text-muted">
                <i class="fas fa-info-circle mr-1"></i>
                Halaman ini hanya menampilkan jadwal praktik milik Anda.
            </div>
        </div>

    </div>
</section>
