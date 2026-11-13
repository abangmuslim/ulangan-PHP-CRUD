
<?php
$idpasien = (int) ($_SESSION['idpasien'] ?? 0);
$idpendaftaran = (int) ($_GET['idpendaftaran'] ?? 0);

$query = mysqli_query($koneksi, "
    SELECT
        p.*,
        d.namadokter,
        d.spesialis,
        d.nohp AS nohpdokter,
        j.haripraktik,
        j.jam_mulai,
        j.jam_selesai,
        k.idkunjungan,
        k.diagnosis,
        k.statuskunjungan
    FROM pendaftarancheckup p
    JOIN jadwaldokter j ON p.idjadwal = j.idjadwal
    JOIN dokter d ON j.iddokter = d.iddokter
    LEFT JOIN kunjungan k ON p.idkunjungan = k.idkunjungan
    WHERE p.idpendaftaran = '$idpendaftaran'
    AND p.idpasien = '$idpasien'
");

$data = mysqli_fetch_assoc($query);
?>

<div class="container-fluid">

    <?php if ($data) : ?>

        <?php
        $warnaPendaftaran = 'secondary';

        if ($data['status'] == 'Menunggu') {
            $warnaPendaftaran = 'warning';
        } elseif ($data['status'] == 'Disetujui') {
            $warnaPendaftaran = 'success';
        } elseif ($data['status'] == 'Ditolak') {
            $warnaPendaftaran = 'danger';
        }

        $warnaAntrian = 'secondary';

        if ($data['statusantrian'] == 'Menunggu') {
            $warnaAntrian = 'warning';
        } elseif ($data['statusantrian'] == 'Dipanggil') {
            $warnaAntrian = 'primary';
        } elseif ($data['statusantrian'] == 'Diperiksa') {
            $warnaAntrian = 'info';
        } elseif ($data['statusantrian'] == 'Selesai') {
            $warnaAntrian = 'success';
        } elseif ($data['statusantrian'] == 'Tidak Hadir') {
            $warnaAntrian = 'danger';
        }
        ?>

        <div class="card shadow-sm border-0" style="border-radius:14px;">

            <div class="card-header bg-white py-3">
                <h5 class="mb-1 font-weight-bold">
                    <i class="fas fa-ticket-alt text-success mr-2"></i>
                    Detail Antrean Pemeriksaan
                </h5>
                <small class="text-muted">
                    Informasi lengkap pendaftaran pemeriksaan Anda.
                </small>
            </div>

            <div class="card-body">

                <!-- INFORMASI NOMOR ANTREAN -->
                <div class="text-center p-4 mb-4"
                     style="background:#e8f7ef;border-radius:12px;">

                    <h6 class="text-muted mb-2">
                        NOMOR ANTREAN ANDA
                    </h6>

                    <?php if (!empty($data['nomorantrian'])) : ?>

                        <h1 class="text-success font-weight-bold mb-2">
                            <?= e($data['nomorantrian']); ?>
                        </h1>

                    <?php else : ?>

                        <h4 class="text-muted font-weight-bold mb-2">
                            Belum Tersedia
                        </h4>

                    <?php endif; ?>

                    <span class="badge badge-<?= $warnaAntrian; ?> p-2">
                        <?= e($data['statusantrian']); ?>
                    </span>

                </div>

                <!-- INFORMASI PENDAFTARAN -->
                <h6 class="font-weight-bold text-success mb-3">
                    <i class="fas fa-clipboard-list mr-1"></i>
                    Informasi Pendaftaran
                </h6>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th width="35%">ID Pendaftaran</th>
                            <td><?= e($data['idpendaftaran']); ?></td>
                        </tr>

                        <tr>
                            <th>Tanggal Pengajuan</th>
                            <td>
                                <?= date('d-m-Y H:i', strtotime($data['tanggalpengajuan'])); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Jenis Pendaftaran</th>
                            <td><?= e($data['jenispendaftaran']); ?></td>
                        </tr>

                        <tr>
                            <th>Tanggal Pemeriksaan</th>
                            <td>
                                <?= date('d-m-Y', strtotime($data['tanggalperiksa'])); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Keluhan</th>
                            <td><?= nl2br(e($data['keluhan'])); ?></td>
                        </tr>

                        <tr>
                            <th>Status Pendaftaran</th>
                            <td>
                                <span class="badge badge-<?= $warnaPendaftaran; ?>">
                                    <?= e($data['status']); ?>
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th>Catatan Admin</th>
                            <td>
                                <?= !empty($data['catatanadmin'])
                                    ? nl2br(e($data['catatanadmin']))
                                    : '<span class="text-muted">Belum ada catatan</span>'; ?>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- INFORMASI DOKTER -->
                <h6 class="font-weight-bold text-success mt-4 mb-3">
                    <i class="fas fa-user-md mr-1"></i>
                    Informasi Dokter dan Jadwal
                </h6>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th width="35%">Nama Dokter</th>
                            <td><?= e($data['namadokter']); ?></td>
                        </tr>

                        <tr>
                            <th>Spesialis</th>
                            <td><?= e($data['spesialis'] ?? '-'); ?></td>
                        </tr>

                        <tr>
                            <th>Hari Praktik</th>
                            <td><?= e($data['haripraktik']); ?></td>
                        </tr>

                        <tr>
                            <th>Jam Praktik</th>
                            <td>
                                <?= date('H:i', strtotime($data['jam_mulai'])); ?>
                                -
                                <?= date('H:i', strtotime($data['jam_selesai'])); ?>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- INFORMASI CHECK-IN -->
                <h6 class="font-weight-bold text-success mt-4 mb-3">
                    <i class="fas fa-clock mr-1"></i>
                    Informasi Check-in
                </h6>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th width="35%">Tanggal Check-in</th>
                            <td>
                                <?= !empty($data['tanggalcheckin'])
                                    ? date('d-m-Y H:i', strtotime($data['tanggalcheckin']))
                                    : '<span class="text-muted">Belum melakukan check-in</span>'; ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Nomor Antrean</th>
                            <td>
                                <?= !empty($data['nomorantrian'])
                                    ? e($data['nomorantrian'])
                                    : '<span class="text-muted">Belum mendapatkan nomor antrean</span>'; ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Status Antrean</th>
                            <td>
                                <span class="badge badge-<?= $warnaAntrian; ?>">
                                    <?= e($data['statusantrian']); ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- INFORMASI PEMERIKSAAN -->
                <?php if (!empty($data['idkunjungan'])) : ?>

                    <h6 class="font-weight-bold text-success mt-4 mb-3">
                        <i class="fas fa-stethoscope mr-1"></i>
                        Informasi Pemeriksaan
                    </h6>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th width="35%">ID Kunjungan</th>
                                <td><?= e($data['idkunjungan']); ?></td>
                            </tr>

                            <tr>
                                <th>Diagnosis</th>
                                <td>
                                    <?= !empty($data['diagnosis'])
                                        ? nl2br(e($data['diagnosis']))
                                        : '<span class="text-muted">Belum tersedia</span>'; ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Status Kunjungan</th>
                                <td><?= e($data['statuskunjungan']); ?></td>
                            </tr>
                        </table>
                    </div>

                <?php endif; ?>

                <div class="mt-4">
                    <a href="index.php?page=antrianpasien"
                       class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Kembali
                    </a>
                </div>

            </div>
        </div>

    <?php else : ?>

        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>

                <h5>Data Antrean Tidak Ditemukan</h5>

                <p class="text-muted">
                    Data tidak tersedia atau bukan milik akun pasien Anda.
                </p>

                <a href="index.php?page=antrianpasien"
                   class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali
                </a>
            </div>
        </div>

    <?php endif; ?>

</div>
