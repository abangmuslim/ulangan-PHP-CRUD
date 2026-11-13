
<?php
$iddokter = (int) ($_SESSION['iddokter'] ?? 0);

$query = mysqli_query($koneksi, "
    SELECT
        k.*,
        p.namapasien,
        p.nohp,
        pc.nomorantrian,
        pc.statusantrian
    FROM kunjungan k
    JOIN pasien p ON k.idpasien = p.idpasien
    LEFT JOIN pendaftarancheckup pc ON k.idkunjungan = pc.idkunjungan
    WHERE k.iddokter = '$iddokter'
    ORDER BY k.tanggalkunjungan DESC, k.idkunjungan DESC
");
?>

    <section class="content-header">
        <div class="container-fluid">
            <h1>Pemeriksaan Pasien</h1>
            <p class="text-muted">Daftar kunjungan pasien yang menjadi tanggung jawab Anda.</p>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-stethoscope mr-1"></i> Data Pemeriksaan
                    </h3>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="text-center">
                            <tr>
                                <th width="50">No</th>
                                <th>Tanggal</th>
                                <th>No. Antrean</th>
                                <th>Nama Pasien</th>
                                <th>Keluhan</th>
                                <th>Status Pemeriksaan</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($query) > 0): ?>
                                <?php $no = 1; ?>
                                <?php while ($data = mysqli_fetch_assoc($query)): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><?= date('d-m-Y', strtotime($data['tanggalkunjungan'])); ?></td>
                                        <td class="text-center">
                                            <?= $data['nomorantrian'] ? e($data['nomorantrian']) : '-'; ?>
                                        </td>
                                        <td><?= e($data['namapasien']); ?></td>
                                        <td><?= e($data['keluhan'] ?: '-'); ?></td>
                                        <td class="text-center">
                                            <?php if ($data['statuskunjungan'] == 'Proses'): ?>
                                                <span class="badge badge-warning">Proses</span>
                                            <?php else: ?>
                                                <span class="badge badge-success">Selesai</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="index.php?page=showpemeriksaan&idkunjungan=<?= $data['idkunjungan']; ?>"
                                               class="btn btn-info btn-sm" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <?php if ($data['statuskunjungan'] == 'Proses'): ?>
                                                <a href="index.php?page=createpemeriksaan&idkunjungan=<?= $data['idkunjungan']; ?>"
                                                   class="btn btn-primary btn-sm" title="Periksa">
                                                    <i class="fas fa-stethoscope"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        Belum ada data kunjungan pasien.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

