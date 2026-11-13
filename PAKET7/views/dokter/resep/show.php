<?php
$iddokter = (int) $_SESSION['iddokter'];
$idkunjungan = (int) ($_GET['idkunjungan'] ?? 0);

// Ambil data kunjungan
$stmt = mysqli_prepare($koneksi, "
    SELECT
        k.idkunjungan,
        k.tanggalkunjungan,
        k.keluhan,
        k.diagnosis,
        k.statuskunjungan,
        p.namapasien
    FROM kunjungan k
    JOIN pasien p ON k.idpasien = p.idpasien
    WHERE k.idkunjungan = ?
    AND k.iddokter = ?
");

mysqli_stmt_bind_param($stmt, "ii", $idkunjungan, $iddokter);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$kunjungan = mysqli_fetch_assoc($result);

if (!$kunjungan) {
    echo "<section class='content'><div class='alert alert-danger'>Kunjungan tidak ditemukan atau bukan milik Anda.</div></section>";
    return;
}

// Ambil detail resep
$stmt = mysqli_prepare($koneksi, "
    SELECT
        d.*,
        o.namaobat
    FROM detilkunjungan d
    JOIN obat o ON d.idobat = o.idobat
    WHERE d.idkunjungan = ?
    ORDER BY d.iddetilkunjungan ASC
");

mysqli_stmt_bind_param($stmt, "i", $idkunjungan);
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);
?>

<section class="content-header">
    <div class="container-fluid">
        <h1>Detail Resep Dokter</h1>
        <p class="text-muted mb-0">Informasi resep obat pasien.</p>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'berhasil') { ?>
            <div class="alert alert-success">
                Resep berhasil disimpan.
            </div>
        <?php } ?>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Kunjungan</h3>
            </div>

            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th width="180">Nama Pasien</th>
                        <td>: <?= e($kunjungan['namapasien']); ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Kunjungan</th>
                        <td>: <?= date('d-m-Y', strtotime($kunjungan['tanggalkunjungan'])); ?></td>
                    </tr>
                    <tr>
                        <th>Keluhan</th>
                        <td>: <?= e($kunjungan['keluhan']); ?></td>
                    </tr>
                    <tr>
                        <th>Diagnosis</th>
                        <td>: <?= e($kunjungan['diagnosis']); ?></td>
                    </tr>
                    <tr>
                        <th>Status Pemeriksaan</th>
                        <td>:
                            <?php if ($kunjungan['statuskunjungan'] == 'Proses') { ?>
                                <span class="badge badge-warning">Proses</span>
                            <?php } else { ?>
                                <span class="badge badge-success">Selesai</span>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Obat yang Diresepkan</h3>

                <?php if ($kunjungan['statuskunjungan'] == 'Proses') { ?>
                    <div class="card-tools">
                        <a href="index.php?page=createresep&idkunjungan=<?= $idkunjungan; ?>"
                           class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah Obat
                        </a>
                    </div>
                <?php } ?>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="50">No.</th>
                            <th>Nama Obat</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                            <th>Aturan Pakai</th>

                            <?php if ($kunjungan['statuskunjungan'] == 'Proses') { ?>
                                <th width="90">Aksi</th>
                            <?php } ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $no = 1;
                        $total = 0;

                        if (mysqli_num_rows($query) > 0) {
                            while ($data = mysqli_fetch_assoc($query)) {
                                $total += $data['subtotal'];
                        ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= e($data['namaobat']); ?></td>
                                    <td><?= (int) $data['jumlah']; ?></td>
                                    <td>
                                        Rp <?= number_format($data['harga'], 0, ',', '.'); ?>
                                    </td>
                                    <td>
                                        Rp <?= number_format($data['subtotal'], 0, ',', '.'); ?>
                                    </td>
                                    <td><?= e($data['aturanpakai']); ?></td>

                                    <?php if ($kunjungan['statuskunjungan'] == 'Proses') { ?>
                                        <td>
                                            <a href="index.php?page=editresep&idkunjungan=<?= $idkunjungan; ?>&iddetilkunjungan=<?= $data['iddetilkunjungan']; ?>"
                                               class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Ubah
                                            </a>
                                        </td>
                                    <?php } ?>
                                </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="<?= $kunjungan['statuskunjungan'] == 'Proses' ? 7 : 6; ?>"
                                    class="text-center">
                                    Belum ada resep obat.
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>

                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">Total Resep</th>
                            <th colspan="<?= $kunjungan['statuskunjungan'] == 'Proses' ? 3 : 2; ?>">
                                Rp <?= number_format($total, 0, ',', '.'); ?>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="card-footer">
                <a href="index.php?page=resep" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>

                <?php if ($kunjungan['statuskunjungan'] == 'Selesai') { ?>
                    <span class="text-muted ml-2">
                        <i class="fas fa-lock"></i>
                        Resep dikunci karena pemeriksaan telah selesai.
                    </span>
                <?php } ?>
            </div>
        </div>

    </div>
</section>