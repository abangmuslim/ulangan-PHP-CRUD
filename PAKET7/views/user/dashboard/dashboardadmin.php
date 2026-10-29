
<?php

require_once __DIR__ . '/../../../proses/prosesdashboarduser.php';

$formatTanggalDashboard = function ($tanggal) {
    if (empty($tanggal)) {
        return '-';
    }
    $waktu = strtotime($tanggal);
    return $waktu ? date('d-m-Y H:i', $waktu) : '-';
};
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-7">
                <h1 class="m-0">
                    <i class="fas fa-tachometer-alt mr-2 text-primary"></i>Dashboard Admin
                </h1>
                <small class="text-muted">Ringkasan pengelolaan Rumah Sakit Ahmadi</small>
            </div>
            <div class="col-sm-5 text-sm-right mt-2 mt-sm-0">
                <span class="text-muted">
                    <i class="far fa-calendar-alt mr-1"></i><?= date('d-m-Y'); ?>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <!-- Statistik -->
        <div class="row">
            <div class="col-6 col-md-4 col-xl-2">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= number_format($totalPasien, 0, ',', '.'); ?></h3>
                        <p>Total Pasien</p>
                    </div>
                    <div class="icon"><i class="fas fa-user-injured"></i></div>
                    <a href="index.php?page=pasien" class="small-box-footer">
                        Data Pasien <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= number_format($totalDokter, 0, ',', '.'); ?></h3>
                        <p>Total Dokter</p>
                    </div>
                    <div class="icon"><i class="fas fa-user-md"></i></div>
                    <a href="index.php?page=dokter" class="small-box-footer">
                        Data Dokter <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3><?= number_format($totalObat, 0, ',', '.'); ?></h3>
                        <p>Total Obat</p>
                    </div>
                    <div class="icon"><i class="fas fa-pills"></i></div>
                    <a href="index.php?page=obat" class="small-box-footer">
                        Data Obat <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= number_format($kunjunganHariIni, 0, ',', '.'); ?></h3>
                        <p>Kunjungan Hari Ini</p>
                    </div>
                    <div class="icon"><i class="fas fa-notes-medical"></i></div>
                    <a href="index.php?page=kunjungan" class="small-box-footer">
                        Data Kunjungan <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= number_format($antrianMenunggu, 0, ',', '.'); ?></h3>
                        <p>Antrean Menunggu</p>
                    </div>
                    <div class="icon"><i class="fas fa-list-ol"></i></div>
                    <a href="index.php?page=antrian" class="small-box-footer">
                        Data Antrean <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3><?= number_format($stokMenipis, 0, ',', '.'); ?></h3>
                        <p>Stok Obat Menipis</p>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <a href="index.php?page=obat" class="small-box-footer">
                        Periksa Stok <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Informasi -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history mr-1"></i> Kunjungan Terbaru
                        </h3>
                        <div class="card-tools">
                            <a href="index.php?page=kunjungan" class="btn btn-tool" title="Lihat semua kunjungan">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover table-striped text-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th style="width:45px;">No.</th>
                                    <th>Pasien</th>
                                    <th>Dokter</th>
                                    <th>Tanggal</th>
                                    <th>Keluhan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($kunjunganTerbaru)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            Belum ada data kunjungan.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($kunjunganTerbaru as $nomor => $kunjungan): ?>
                                        <tr>
                                            <td><?= $nomor + 1; ?></td>
                                            <td><?= e($kunjungan['namapasien'] ?? 'Pasien tidak ditemukan'); ?></td>
                                            <td><?= e($kunjungan['namadokter'] ?? 'Dokter tidak ditemukan'); ?></td>
                                            <td><?= e($formatTanggalDashboard($kunjungan['tanggalkunjungan'] ?? '')); ?></td>
                                            <td><?= e($kunjungan['keluhan'] ?? '-'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-right">
                        <a href="index.php?page=kunjungan" class="btn btn-sm btn-primary">
                            <i class="fas fa-list mr-1"></i>Lihat Kunjungan
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-outline card-danger">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-exclamation-triangle mr-1 text-danger"></i> Peringatan Stok Obat
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($daftarStokMenipis)): ?>
                            <div class="text-center text-muted p-4">
                                <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                                <p class="mb-0">Tidak ada obat dengan stok menipis.</p>
                            </div>
                        <?php else: ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($daftarStokMenipis as $obat): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="pr-2">
                                            <div class="font-weight-bold"><?= e($obat['namaobat']); ?></div>
                                            <small class="text-muted">ID Obat: <?= (int) $obat['idobat']; ?></small>
                                        </div>
                                        <span class="badge badge-<?= (int) $obat['stok'] <= 5 ? 'danger' : 'warning'; ?> badge-pill">
                                            <?= number_format((int) $obat['stok'], 0, ',', '.'); ?> unit
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer text-right">
                        <a href="index.php?page=obat" class="btn btn-sm btn-danger">
                            <i class="fas fa-pills mr-1"></i>Kelola Obat
                        </a>
                    </div>
                </div>

                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bolt mr-1"></i> Akses Cepat
                        </h3>
                    </div>
                    <div class="card-body">
                        <a href="index.php?page=pasien" class="btn btn-outline-primary btn-block text-left">
                            <i class="fas fa-user-injured mr-2"></i>Data Pasien
                        </a>
                        <a href="index.php?page=kunjungan" class="btn btn-outline-primary btn-block text-left">
                            <i class="fas fa-notes-medical mr-2"></i>Data Kunjungan
                        </a>
                        <a href="index.php?page=antrian" class="btn btn-outline-primary btn-block text-left">
                            <i class="fas fa-list-ol mr-2"></i>Antrean Pasien
                        </a>
                        <a href="index.php?page=laporanharian" class="btn btn-outline-primary btn-block text-left mb-0">
                            <i class="fas fa-file-medical-alt mr-2"></i>Laporan Harian
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
