
<?php
$query = mysqli_query($koneksi, "SELECT * FROM dokter ORDER BY iddokter DESC");
$totaldokter = mysqli_num_rows($query);
?>

<div class="content-wrapper p-3">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Dokter</h1>
                </div>

                <div class="col-sm-6 text-end">
                    <span class="btn btn-outline-primary me-2">
                        Total Dokter: <?= $totaldokter; ?>
                    </span>

                    <a href="index.php?page=createdokter" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Dokter
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Manajemen Data Dokter</h3>
                </div>

                <div class="card-body table-responsive">

                    <table class="table table-bordered table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th width="100">Foto</th>
                                <th>Nama Dokter</th>
                                <th>Spesialis</th>
                                <th>No HP</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $no = 1;

                            if ($totaldokter > 0) {
                                while ($data = mysqli_fetch_assoc($query)) {
                                    $foto = !empty($data['foto']) ? $data['foto'] : 'default.png';
                            ?>

                                    <tr>
                                        <td><?= $no++; ?></td>

                                        <td class="text-center">
                                            <img src="assets/images/dokter/<?= htmlspecialchars($foto); ?>"
                                                width="65"
                                                height="65"
                                                class="img-thumbnail"
                                                style="object-fit: cover;">
                                        </td>

                                        <td><?= htmlspecialchars($data['namadokter']); ?></td>
                                        <td><?= htmlspecialchars($data['spesialis'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($data['nohp'] ?? '-'); ?></td>

                                        <td>
                                            <a href="index.php?page=showdokter&id=<?= $data['iddokter']; ?>"
                                                class="btn btn-info btn-sm"
                                                title="Detail">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>

                                            <a href="index.php?page=editdokter&id=<?= $data['iddokter']; ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <form action="proses/prosesdokter.php?aksi=hapus"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus data dokter ini?')">

                                                <input type="hidden"
                                                    name="iddokter"
                                                    value="<?= $data['iddokter']; ?>">

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
                                    <td colspan="6" class="text-center">
                                        Belum ada data dokter.
                                    </td>
                                </tr>

                            <?php } ?>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </section>

</div>
