<?php
$data = mysqli_query($koneksi, "SELECT * FROM obat ORDER BY idobat DESC");
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1">Data Obat</h3>
        <p class="text-muted mb-0">Daftar obat Rumah Sakit Ahmadi</p>
    </div>
    <a href="index.php?page=createobat" class="btn btn-primary">+ Tambah Obat</a>
</div>

<?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagalhapus') { ?>
<div class="alert alert-warning">Obat tidak dapat dihapus karena sudah digunakan dalam data kunjungan.</div>
<?php } ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th width="60">No.</th>
                        <th>Nama Obat</th>
                        <th>Jenis Obat</th>
                        <th width="100">Stok</th>
                        <th>Harga</th>
                        <th width="230">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($obat = mysqli_fetch_assoc($data)) { ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $obat['namaobat']; ?></td>
                        <td><?= $obat['jenisobat'] ?? '-'; ?></td>
                        <td><?= $obat['stok']; ?></td>
                        <td>Rp <?= number_format($obat['harga'], 0, ',', '.'); ?></td>
                        <td>
                            <a href="index.php?page=showobat&id=<?= $obat['idobat']; ?>" class="btn btn-info btn-sm">Detail</a>
                            <a href="index.php?page=editobat&id=<?= $obat['idobat']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="proses/prosesobat.php?proses=hapus" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus obat ini?')">
                                <input type="hidden" name="idobat" value="<?= $obat['idobat']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if (mysqli_num_rows($data) == 0) { ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data obat.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>