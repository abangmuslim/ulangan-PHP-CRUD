<?php
$id = (int) ($_GET['id'] ?? 0);
$data = mysqli_query($koneksi, "SELECT * FROM obat WHERE idobat='$id'");
$obat = mysqli_fetch_assoc($data);

if (!$obat) {
    echo '<div class="alert alert-warning">Data obat tidak ditemukan.</div>';
    echo '<a href="index.php?page=obat" class="btn btn-secondary">Kembali</a>';
    return;
}
?>
<div class="card shadow-sm">
    <div class="card-header bg-warning">
        <h5 class="mb-0">Edit Obat</h5>
    </div>
    <div class="card-body">
        <form action="proses/prosesobat.php?proses=update" method="POST">
            <input type="hidden" name="idobat" value="<?= $obat['idobat']; ?>">
            <div class="mb-3">
                <label class="form-label">Nama Obat</label>
                <input type="text" name="namaobat" class="form-control" value="<?= $obat['namaobat']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis Obat</label>
                <input type="text" name="jenisobat" class="form-control" value="<?= $obat['jenisobat'] ?? ''; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" min="0" value="<?= $obat['stok']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" min="0" value="<?= $obat['harga']; ?>" required>
            </div>
            <a href="index.php?page=obat" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update Obat</button>
        </form>
    </div>
</div>