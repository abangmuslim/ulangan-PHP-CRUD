
<?php
$iddokter = (int) ($_SESSION['iddokter'] ?? 0);
$idjadwal = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = mysqli_query($koneksi, "
    SELECT idjadwal, haripraktik, jam_mulai, jam_selesai
    FROM jadwaldokter
    WHERE idjadwal = $idjadwal AND iddokter = $iddokter
");

$jadwal = $query ? mysqli_fetch_assoc($query) : null;
?>

<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Detail Jadwal Praktik</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <?php if (!$jadwal) : ?>

            <div class="alert alert-danger">
                Jadwal tidak ditemukan atau bukan milik Anda.
            </div>
            <a href="index.php?page=jadwaldokterdokter" class="btn btn-secondary">
                Kembali
            </a>

        <?php else : ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Jadwal Praktik</h3>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="35%">ID Jadwal</th>
                                    <td><?= (int) $jadwal['idjadwal'] ?></td>
                                </tr>
                                <tr>
                                    <th>Nama Dokter</th>
                                    <td><?= e($_SESSION['namadokter'] ?? '') ?></td>
                                </tr>
                                <tr>
                                    <th>Hari Praktik</th>
                                    <td><?= e($jadwal['haripraktik']) ?></td>
                                </tr>
                                <tr>
                                    <th>Jam Mulai</th>
                                    <td><?= e(substr($jadwal['jam_mulai'], 0, 5)) ?></td>
                                </tr>
                                <tr>
                                    <th>Jam Selesai</th>
                                    <td><?= e(substr($jadwal['jam_selesai'], 0, 5)) ?></td>
                                </tr>
                            </table>
                        </div>

                        <div class="card-footer">
                            <a href="index.php?page=editjadwaldokterdokter&id=<?= (int) $jadwal['idjadwal'] ?>"
                               class="btn btn-warning">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>

                            <form action="index.php?page=jadwaldokterdokter" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                <?= csrfField() ?>
                                <input type="hidden" name="page" value="jadwaldokterdokter">
                                <input type="hidden" name="aksi" value="hapus">
                                <input type="hidden" name="idjadwal" value="<?= (int) $jadwal['idjadwal'] ?>">

                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>

                            <a href="index.php?page=jadwaldokterdokter" class="btn btn-secondary">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </div>
</section>
