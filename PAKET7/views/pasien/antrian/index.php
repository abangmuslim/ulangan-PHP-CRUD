
<?php
$idpasien = (int) ($_SESSION['idpasien'] ?? 0);

$query = mysqli_query($koneksi, "
    SELECT
        p.*,
        d.namadokter,
        d.spesialis,
        j.haripraktik,
        j.jam_mulai,
        j.jam_selesai
    FROM pendaftarancheckup p
    JOIN jadwaldokter j ON p.idjadwal = j.idjadwal
    JOIN dokter d ON j.iddokter = d.iddokter
    WHERE p.idpasien = '$idpasien'
    ORDER BY p.tanggalperiksa DESC, p.idpendaftaran DESC
");
?>

<div class="container-fluid">
    <div class="card shadow-sm border-0" style="border-radius:14px;">
        <div class="card-header bg-white py-3">
            <h5 class="mb-1 font-weight-bold">
                <i class="fas fa-users text-success mr-2"></i>
                Antrean Pemeriksaan
            </h5>
            <small class="text-muted">
                Informasi pendaftaran dan antrean pemeriksaan Anda.
            </small>
        </div>

        <div class="card-body">
            <?php if (mysqli_num_rows($query) > 0) : ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th width="50">No.</th>
                                <th>Tanggal Periksa</th>
                                <th>Dokter</th>
                                <th>Jadwal</th>
                                <th>No. Antrean</th>
                                <th>Status Pendaftaran</th>
                                <th>Status Antrean</th>
                                <th width="80">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $no = 1; ?>
                            <?php while ($data = mysqli_fetch_assoc($query)) : ?>

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

                                <tr>
                                    <td><?= $no++; ?></td>

                                    <td>
                                        <?= date('d-m-Y', strtotime($data['tanggalperiksa'])); ?>
                                    </td>

                                    <td>
                                        <strong>
                                            <?= e($data['namadokter']); ?>
                                        </strong>
                                        <br>
                                        <small class="text-muted">
                                            <?= e($data['spesialis'] ?? '-'); ?>
                                        </small>
                                    </td>

                                    <td>
                                        <?= e($data['haripraktik']); ?>
                                        <br>
                                        <small class="text-muted">
                                            <?= date('H:i', strtotime($data['jam_mulai'])); ?>
                                            -
                                            <?= date('H:i', strtotime($data['jam_selesai'])); ?>
                                        </small>
                                    </td>

                                    <td class="text-center">
                                        <?php if (!empty($data['nomorantrian'])) : ?>
                                            <span class="badge badge-success p-2">
                                                <?= e($data['nomorantrian']); ?>
                                            </span>
                                        <?php else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <span class="badge badge-<?= $warnaPendaftaran; ?>">
                                            <?= e($data['status']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge badge-<?= $warnaAntrian; ?>">
                                            <?= e($data['statusantrian']); ?>
                                        </span>
                                    </td>

                                    <td class="text-center">                                        
                                        <a href="index.php?page=showantrianpasien&idpendaftaran=<?= $data['idpendaftaran']; ?>"
                                            class="btn btn-sm btn-info"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>                                        
                                    </td>
                                </tr>

                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php else : ?>

                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>

                    <h5>Belum Ada Data Antrean</h5>

                    <p class="text-muted mb-3">
                        Anda belum memiliki pendaftaran pemeriksaan.
                    </p>

                    <a href="index.php?page=pendaftaranpasien"
                       class="btn btn-success">
                        <i class="fas fa-calendar-plus mr-1"></i>
                        Daftar Pemeriksaan
                    </a>
                </div>

            <?php endif; ?>
        </div>
    </div>
</div>
