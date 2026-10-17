
<?php
$data = mysqli_query($koneksi, "SELECT * FROM pasien ORDER BY idpasien DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1">Data Pasien</h3>
        <p class="text-muted mb-0">Daftar pasien Rumah Sakit Ahmadi</p>
    </div>
    <a href="index.php?page=createpasien" class="btn btn-primary">+ Tambah Pasien</a>
</div>

<?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagalhapus') { ?>
    <div class="alert alert-warning">Pasien tidak dapat dihapus karena sudah memiliki data kunjungan.</div>
<?php } ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th width="60">No.</th>
                        <th>Nama Pasien</th>
                        <th>Jenis Kelamin</th>
                        <th>No. HP</th>
                        <th width="230">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($pasien = mysqli_fetch_assoc($data)) {
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $pasien['namapasien']; ?></td>
                        <td><?= $pasien['jeniskelamin'] ?? '-'; ?></td>
                        <td><?= $pasien['nohp'] ?? '-'; ?></td>
                        <td>
                            <a href="index.php?page=showpasien&id=<?= $pasien['idpasien']; ?>" class="btn btn-info btn-sm">Detail</a>
                            <a href="index.php?page=editpasien&id=<?= $pasien['idpasien']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="proses/prosespasien.php?proses=hapus" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pasien ini?')">
                                <input type="hidden" name="idpasien" value="<?= $pasien['idpasien']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>

                    <?php if (mysqli_num_rows($data) == 0) { ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada data pasien.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
