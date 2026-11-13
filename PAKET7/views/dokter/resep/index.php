<?php
$iddokter = (int) $_SESSION['iddokter'];

$query = mysqli_query($koneksi, "
    SELECT
        k.idkunjungan,
        k.tanggalkunjungan,
        k.keluhan,
        k.diagnosis,
        k.statuskunjungan,
        p.namapasien,
        COUNT(d.iddetilkunjungan) AS jumlahobat,
        COALESCE(SUM(d.subtotal), 0) AS totalresep
    FROM kunjungan k
    JOIN pasien p ON k.idpasien = p.idpasien
    LEFT JOIN detilkunjungan d ON k.idkunjungan = d.idkunjungan
    WHERE k.iddokter = $iddokter
    GROUP BY
        k.idkunjungan,
        k.tanggalkunjungan,
        k.keluhan,
        k.diagnosis,
        k.statuskunjungan,
        p.namapasien
    ORDER BY k.tanggalkunjungan DESC, k.idkunjungan DESC
");
?>

<section class="content-header">
    <div class="container-fluid">
        <h1>Resep Dokter</h1>
        <p class="text-muted mb-0">Daftar resep berdasarkan kunjungan pasien.</p>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'berhasil') { ?>
            <div class="alert alert-success">
                Data resep berhasil disimpan.
            </div>
        <?php } ?>

        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal') { ?>
            <div class="alert alert-danger">
                Proses resep gagal dilakukan.
            </div>
        <?php } ?>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Kunjungan Pasien</h3>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="50">No.</th>
                            <th>Pasien</th>
                            <th>Tanggal Kunjungan</th>
                            <th>Keluhan</th>
                            <th>Jumlah Obat</th>
                            <th>Total Resep</th>
                            <th>Status Pemeriksaan</th>
                            <th width="170">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $no = 1;

                        if ($query && mysqli_num_rows($query) > 0) {
                            while ($data = mysqli_fetch_assoc($query)) {
                        ?>
                                <tr>
                                    <td><?= $no++; ?></td>

                                    <td><?= e($data['namapasien']); ?></td>

                                    <td>
                                        <?= date('d-m-Y', strtotime($data['tanggalkunjungan'])); ?>
                                    </td>

                                    <td><?= e($data['keluhan']); ?></td>

                                    <td class="text-center">
                                        <?= (int) $data['jumlahobat']; ?>
                                    </td>

                                    <td>
                                        Rp <?= number_format($data['totalresep'], 0, ',', '.'); ?>
                                    </td>

                                    <td>
                                        <?php if ($data['statuskunjungan'] == 'Proses') { ?>
                                            <span class="badge badge-warning">Proses</span>
                                        <?php } else { ?>
                                            <span class="badge badge-success">Selesai</span>
                                        <?php } ?>
                                    </td>

                                    <td>
                                        <a href="index.php?page=showresep&idkunjungan=<?= $data['idkunjungan']; ?>"
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>

                                        <?php if ($data['statuskunjungan'] == 'Proses') { ?>
                                            <a href="index.php?page=createresep&idkunjungan=<?= $data['idkunjungan']; ?>"
                                               class="btn btn-success btn-sm">
                                                <i class="fas fa-plus"></i> Resep
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="8" class="text-center">
                                    Belum ada kunjungan pasien.
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>