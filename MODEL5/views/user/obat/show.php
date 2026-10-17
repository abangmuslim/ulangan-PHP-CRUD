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
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Detail Obat</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th width="200">ID Obat</th>
                <td><?= $obat['idobat']; ?></td>
            </tr>
            <tr>
                <th>Nama Obat</th>
                <td><?= $obat['namaobat']; ?></td>
            </tr>
            <tr>
                <th>Jenis Obat</th>
                <td><?= $obat['jenisobat'] ?? '-'; ?></td>
            </tr>
            <tr>
                <th>Stok</th>
                <td><?= $obat['stok']; ?></td>
            </tr>
            <tr>
                <th>Harga</th>
                <td>Rp <?= number_format($obat['harga'], 0, ',', '.'); ?></td>
            </tr>
        </table>
        <a href="index.php?page=obat" class="btn btn-secondary">Kembali</a>
        <a href="index.php?page=editobat&id=<?= $obat['idobat']; ?>" class="btn btn-warning">Edit Obat</a>
    </div>
</div>