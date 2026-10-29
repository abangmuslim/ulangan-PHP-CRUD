<?php
$id = (int) ($_GET['id'] ?? 0);
$query = mysqli_query($koneksi, "SELECT * FROM dokter WHERE iddokter='$id'");
$detail = mysqli_fetch_assoc($query);

if (!$detail) {
    echo '<div class="alert alert-warning">Data dokter tidak ditemukan.</div>';
    echo '<a href="index.php?page=dokter" class="btn btn-secondary">Kembali</a>';
    return;
}
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Detail Dokter</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="35%">ID Dokter</th>
                        <td><?= $detail['iddokter']; ?></td>
                    </tr>
                    <tr>
                        <th>Nama Dokter</th>
                        <td><?= $detail['namadokter']; ?></td>
                    </tr>
                    <tr>
                        <th>Spesialis</th>
                        <td><?= $detail['spesialis'] ?? '-'; ?></td>
                    </tr>
                    <tr>
                        <th>Nomor HP</th>
                        <td><?= $detail['nohp'] ?? '-'; ?></td>
                    </tr>
                </table>
                <div class="d-flex justify-content-between">
                    <a href="index.php?page=dokter" class="btn btn-secondary">Kembali</a>
                    <a href="index.php?page=editdokter&id=<?= $detail['iddokter']; ?>" class="btn btn-warning">Edit Dokter</a>
                </div>
            </div>
        </div>
    </div>
</div>