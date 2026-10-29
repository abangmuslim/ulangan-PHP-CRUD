<?php
$data = mysqli_query($koneksi, "SELECT * FROM dokter ORDER BY iddokter DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1">Data Dokter</h3>
        <p class="text-muted mb-0">Daftar dokter Rumah Sakit Ahmadi</p>
    </div>
    <a href="index.php?page=createdokter" class="btn btn-primary">+ Tambah Dokter</a>
</div>

<?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagalhapus') { ?>
    <div class="alert alert-warning">Dokter tidak dapat dihapus karena sudah memiliki data kunjungan.</div>
<?php } ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th width="60">No.</th>
                        <th>Nama Dokter</th>
                        <th>Spesialis</th>
                        <th>No. HP</th>
                        <th width="230">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($dokter = mysqli_fetch_assoc($data)) {
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $dokter['namadokter']; ?></td>
                        <td><?= $dokter['spesialis'] ?? '-'; ?></td>
                        <td><?= $dokter['nohp'] ?? '-'; ?></td>
                        <td>
                            <a href="index.php?page=showdokter&id=<?= $dokter['iddokter']; ?>" class="btn btn-info btn-sm">Detail</a>
                            <a href="index.php?page=editdokter&id=<?= $dokter['iddokter']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="proses/prosesdokter.php?proses=hapus" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus dokter ini?')">
                                <input type="hidden" name="iddokter" value="<?= $dokter['iddokter']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>

                    <?php if (mysqli_num_rows($data) == 0) { ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada data dokter.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>