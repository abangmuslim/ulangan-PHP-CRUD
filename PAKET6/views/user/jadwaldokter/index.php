
<?php
$query = mysqli_query($koneksi, "
    SELECT jadwaldokter.*, dokter.namadokter, dokter.spesialis
    FROM jadwaldokter
    INNER JOIN dokter ON jadwaldokter.iddokter = dokter.iddokter
    ORDER BY jadwaldokter.idjadwal DESC
");

$totaljadwal = mysqli_num_rows($query);
?>

<section class="content-header">
    <div class="container-fluid">

        <div class="row mb-2">

            <div class="col-sm-6">
                <h1>
                    <i class="bi bi-calendar-week"></i>
                    Data Jadwal Dokter
                </h1>
            </div>

            <div class="col-sm-6 text-end">
                <span class="btn btn-outline-primary me-2">
                    Total Jadwal: <?= $totaljadwal; ?>
                </span>

                <a href="index.php?page=createjadwaldokter"
                    class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i>
                    Tambah Jadwal
                </a>
            </div>

        </div>

    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="card card-primary card-outline">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-calendar-week me-1"></i>
                    Manajemen Jadwal Dokter
                </h3>
            </div>

            <div class="card-body table-responsive">

                <table class="table table-bordered table-striped table-hover align-middle">

                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Dokter</th>
                            <th>Spesialis</th>
                            <th>Hari Praktik</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $no = 1;

                        if ($totaljadwal > 0) {
                            while ($data = mysqli_fetch_assoc($query)) {
                        ?>

                                <tr>

                                    <td><?= $no++; ?></td>

                                    <td>
                                        <?= htmlspecialchars($data['namadokter']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($data['spesialis'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <span class="badge bg-info">
                                            <?= htmlspecialchars($data['haripraktik']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(substr($data['jam_mulai'], 0, 5)); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(substr($data['jam_selesai'], 0, 5)); ?>
                                    </td>

                                    <td>

                                        <a href="index.php?page=showjadwaldokter&id=<?= $data['idjadwal']; ?>"
                                            class="btn btn-info btn-sm"
                                            title="Detail">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>

                                        <a href="index.php?page=editjadwaldokter&id=<?= $data['idjadwal']; ?>"
                                            class="btn btn-warning btn-sm"
                                            title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="proses/prosesjadwaldokter.php?aksi=hapus"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus jadwal dokter ini?')">

                                            <input type="hidden"
                                                name="idjadwal"
                                                value="<?= $data['idjadwal']; ?>">

                                            <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                title="Hapus">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            <?php
                            }
                        } else {
                            ?>

                            <tr>
                                <td colspan="7" class="text-center">
                                    Belum ada data jadwal dokter.
                                </td>
                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</section>
