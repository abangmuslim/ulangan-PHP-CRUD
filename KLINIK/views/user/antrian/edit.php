<?php
$id = (int) ($_GET['id'] ?? 0);
$query = mysqli_query($koneksi, "SELECT * FROM user WHERE iduser='$id'");
$edit = mysqli_fetch_assoc($query);

if (!$edit) {
    echo '<div class="alert alert-warning">Data user tidak ditemukan.</div>';
    echo '<a href="index.php?page=user" class="btn btn-secondary">Kembali</a>';
    return;
}
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Edit User</h5>
            </div>
            <div class="card-body">
                <form action="proses/prosesuser.php?proses=update" method="POST">
                    <input type="hidden" name="iduser" value="<?= $edit['iduser']; ?>">

                    <div class="mb-3">
                        <label class="form-label">Nama User</label>
                        <input type="text" name="namauser" class="form-control" maxlength="100" value="<?= $edit['namauser']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" maxlength="50" value="<?= $edit['username']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control">
                        <small class="text-muted">Kosongkan jika password tidak ingin diubah.</small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.php?page=user" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-warning">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>