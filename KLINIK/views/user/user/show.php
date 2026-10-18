<?php
$id = (int) ($_GET['id'] ?? 0);
$query = mysqli_query($koneksi, "SELECT * FROM user WHERE iduser='$id'");
$detail = mysqli_fetch_assoc($query);

if (!$detail) {
    echo '<div class="alert alert-warning">Data user tidak ditemukan.</div>';
    echo '<a href="index.php?page=user" class="btn btn-secondary">Kembali</a>';
    return;
}
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Detail User</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="35%">ID User</th>
                        <td><?= $detail['iduser']; ?></td>
                    </tr>
                    <tr>
                        <th>Nama User</th>
                        <td><?= $detail['namauser']; ?></td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td><?= $detail['username']; ?></td>
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td><span class="text-muted">Dirahasiakan</span></td>
                    </tr>
                </table>
                <div class="d-flex justify-content-between">
                    <a href="index.php?page=user" class="btn btn-secondary">Kembali</a>
                    <a href="index.php?page=edituser&id=<?= $detail['iduser']; ?>" class="btn btn-warning">Edit User</a>
                </div>
            </div>
        </div>
    </div>
</div>