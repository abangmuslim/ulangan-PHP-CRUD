<?php
$id = (int) ($_GET['id'] ?? 0);
$query = mysqli_query($koneksi, "SELECT * FROM dokter WHERE iddokter='$id'");
$edit = mysqli_fetch_assoc($query);

if (!$edit) {
    echo '<div class="alert alert-warning">Data dokter tidak ditemukan.</div>';
    echo '<a href="index.php?page=dokter" class="btn btn-secondary">Kembali</a>';
    return;
}
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Edit Dokter</h5>
            </div>
            <div class="card-body">
                <form action="proses/prosesdokter.php?proses=update" method="POST">
                    <input type="hidden" name="iddokter" value="<?= $edit['iddokter']; ?>">

                    <div class="mb-3">
                        <label class="form-label">Nama Dokter</label>
                        <input type="text" name="namadokter" class="form-control" maxlength="100" value="<?= $edit['namadokter']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Spesialis</label>
                        <input type="text" name="spesialis" class="form-control" maxlength="100" value="<?= $edit['spesialis'] ?? ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor HP</label>
                        <input type="text" name="nohp" class="form-control" maxlength="15" value="<?= $edit['nohp'] ?? ''; ?>">
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.php?page=dokter" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-warning">Update Dokter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>