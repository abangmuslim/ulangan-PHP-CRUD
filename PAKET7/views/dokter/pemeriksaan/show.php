
<?php
$iddokter = (int) ($_SESSION['iddokter'] ?? 0);
$idkunjungan = (int) ($_GET['idkunjungan'] ?? 0);

$query = mysqli_query($koneksi, "
    SELECT
        k.*,
        p.namapasien,
        p.nohp,
        p.alamat,
        pc.nomorantrian,
        pc.statusantrian,
        pc.jenispendaftaran
    FROM kunjungan k
    JOIN pasien p ON k.idpasien = p.idpasien
    LEFT JOIN pendaftarancheckup pc ON k.idkunjungan = pc.idkunjungan
    WHERE k.idkunjungan = '$idkunjungan'
    AND k.iddokter = '$iddokter'
    LIMIT 1
");

$data = mysqli_fetch_assoc($query);
?>


    <section class="content-header">
        <div class="container-fluid">
            <h1>Detail Pemeriksaan</h1>
            <p class="text-muted">Informasi kunjungan dan hasil pemeriksaan pasien.</p>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <?php if (!$data): ?>
                <div class="alert alert-danger">
                    Data pemeriksaan tidak ditemukan atau bukan merupakan tanggung jawab Anda.
                </div>
                <a href="index.php?page=pemeriksaan" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>

            <?php else: ?>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-injured mr-1"></i> Identitas Pasien
                        </h3>
                        <div class="card-tools">
                            <?php if ($data['statuskunjungan'] == 'Proses'): ?>
                                <span class="badge badge-warning">Proses</span>
                            <?php else: ?>
                                <span class="badge badge-success">Selesai</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="150">Nama Pasien</th>
                                        <td>: <?= e($data['namapasien']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>No. HP</th>
                                        <td>: <?= e($data['nohp'] ?: '-'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td>: <?= e($data['alamat'] ?: '-'); ?></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="150">Tanggal Kunjungan</th>
                                        <td>: <?= date('d-m-Y', strtotime($data['tanggalkunjungan'])); ?></td>
                                    </tr>
                                    <tr>
                                        <th>No. Antrean</th>
                                        <td>: <?= $data['nomorantrian'] ? e($data['nomorantrian']) : '-'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Jenis Pendaftaran</th>
                                        <td>: <?= e($data['jenispendaftaran'] ?: '-'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Status Antrean</th>
                                        <td>: <?= e($data['statusantrian'] ?: '-'); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-stethoscope mr-1"></i> Hasil Pemeriksaan
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label>Keluhan Pasien</label>
                            <div class="border rounded p-3 bg-light">
                                <?= nl2br(e($data['keluhan'] ?: 'Tidak ada keluhan.')); ?>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label>Diagnosis / Hasil Pemeriksaan</label>
                            <div class="border rounded p-3">
                                <?= $data['diagnosis'] ? nl2br(e($data['diagnosis'])) : '<span class="text-muted">Diagnosis belum diisi.</span>'; ?>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <a href="index.php?page=pemeriksaan" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>

                        <?php if ($data['statuskunjungan'] == 'Proses'): ?>
                            <a href="index.php?page=createpemeriksaan&idkunjungan=<?= $data['idkunjungan']; ?>"
                               class="btn btn-primary">
                                <i class="fas fa-edit mr-1"></i> Lanjutkan Pemeriksaan
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </section>

