
<?php
// ============================================
// DAFTAR ANTRIAN PASIEN DOKTER
// ============================================

$iddokter = (int) ($_SESSION['iddokter'] ?? 0);

// Pastikan dokter sudah login
if ($iddokter <= 0) {
    echo '<div class="alert alert-danger">Sesi dokter tidak ditemukan. Silakan login kembali.</div>';
    return;
}

// Ambil daftar pendaftaran yang disetujui untuk dokter hari ini
$queryAntrian = mysqli_query($koneksi, "
    SELECT
        pc.idpendaftaran,
        pc.idpasien,
        pc.idjadwal,
        pc.tanggalperiksa,
        pc.keluhan,
        pc.jenispendaftaran,
        pc.nomorantrian,
        pc.status,
        pc.statusantrian,
        p.namapasien,
        p.nohp,
        j.haripraktik,
        j.jam_mulai,
        j.jam_selesai
    FROM pendaftarancheckup pc
    INNER JOIN pasien p ON pc.idpasien = p.idpasien
    INNER JOIN jadwaldokter j ON pc.idjadwal = j.idjadwal
    WHERE j.iddokter = '$iddokter'
    AND pc.status = 'Disetujui'
    AND pc.tanggalperiksa = CURDATE()
    ORDER BY
        pc.nomorantrian IS NULL,
        pc.nomorantrian ASC,
        pc.tanggalpengajuan ASC
");

if (!$queryAntrian) {
    echo '<div class="alert alert-danger">Gagal mengambil data antrean: ' . e(mysqli_error($koneksi)) . '</div>';
    return;
}

$totalAntrian = mysqli_num_rows($queryAntrian);
?>

<div class="container-fluid">

    <!-- Judul Halaman -->
    <div class="row mb-3">
        <div class="col-md-8">
            <h4 class="mb-1">
                <i class="fas fa-users text-primary mr-2"></i>
                Antrean Pasien
            </h4>
            <p class="text-muted mb-0">
                Daftar pasien yang terdaftar pada jadwal praktik Anda hari ini.
            </p>
        </div>
        <div class="col-md-4 text-md-right">
            <span class="badge badge-primary p-2">
                <?= date('d-m-Y'); ?>
            </span>
        </div>
    </div>

    <!-- Informasi Jumlah Antrean -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= $totalAntrian; ?></h3>
                    <p>Total Pendaftaran Hari Ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Antrean -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list mr-1"></i>
                Daftar Antrean Pasien
            </h3>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="bg-light">
                    <tr>
                        <th width="50">No.</th>
                        <th width="90">Antrean</th>
                        <th>Nama Pasien</th>
                        <th>No. HP</th>
                        <th>Jam Praktik</th>
                        <th>Jenis Pendaftaran</th>
                        <th>Status Antrean</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($totalAntrian > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($antrian = mysqli_fetch_assoc($queryAntrian)): ?>

                            <?php
                            $statusAntrian = $antrian['statusantrian'] ?: 'Belum Antri';

                            $warnaStatus = [
                                'Belum Antri' => 'secondary',
                                'Menunggu'    => 'warning',
                                'Dipanggil'   => 'info',
                                'Diperiksa'   => 'primary',
                                'Selesai'     => 'success',
                                'Batal'       => 'danger'
                            ];

                            $badgeStatus = $warnaStatus[$statusAntrian] ?? 'secondary';
                            ?>

                            <tr>
                                <td><?= $no++; ?></td>

                                <td class="text-center">
                                    <?php if (!empty($antrian['nomorantrian'])): ?>
                                        <span class="badge badge-dark p-2">
                                            <?= e($antrian['nomorantrian']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <strong><?= e($antrian['namapasien']); ?></strong>
                                </td>

                                <td><?= e($antrian['nohp']); ?></td>

                                <td>
                                    <?= e(substr($antrian['jam_mulai'], 0, 5)); ?>
                                    -
                                    <?= e(substr($antrian['jam_selesai'], 0, 5)); ?>
                                </td>

                                <td>
                                    <?= e($antrian['jenispendaftaran']); ?>
                                </td>

                                <td>
                                    <span class="badge badge-<?= $badgeStatus; ?>">
                                        <?= e($statusAntrian); ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <a href="index.php?page=showantriandokter&idpendaftaran=<?= (int) $antrian['idpendaftaran']; ?>"
                                       class="btn btn-sm btn-info"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>

                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted p-4">
                                <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                <br>
                                Belum ada pendaftaran pasien untuk jadwal praktik hari ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle mr-1"></i>
        Halaman ini menampilkan pendaftaran yang sudah disetujui dan sesuai dengan jadwal dokter yang sedang login.
    </div>

</div>
