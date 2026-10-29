<?php
$id = (int) ($_GET['id'] ?? 0);
$query = mysqli_query($koneksi, "SELECT * FROM pasien WHERE idpasien='$id'");
$detail = mysqli_fetch_assoc($query);

if (!$detail) {
    echo '<div class="alert alert-warning">Data pasien tidak ditemukan.</div>';
    echo '<a href="index.php?page=pasien" class="btn btn-secondary">Kembali</a>';
    return;
}
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Detail Pasien</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="35%">ID Pasien</th>
                        <td><?= $detail['idpasien']; ?></td>
                    </tr>
                    <tr>
                        <th>Nama Pasien</th>
                        <td><?= $detail['namapasien']; ?></td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td><?= $detail['jeniskelamin'] ?? '-'; ?></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td><?= $detail['alamat'] ?? '-'; ?></td>
                    </tr>
                    <tr>
                        <th>Nomor HP</th>
                        <td><?= $detail['nohp'] ?? '-'; ?></td>
                    </tr>
                </table>
                <div class="d-flex justify-content-between">
                    <a href="index.php?page=pasien" class="btn btn-secondary">Kembali</a>
                    <a href="index.php?page=editpasien&id=<?= $detail['idpasien']; ?>" class="btn btn-warning">Edit Pasien</a>
                </div>
            </div>
        </div>
    </div>
</div>
