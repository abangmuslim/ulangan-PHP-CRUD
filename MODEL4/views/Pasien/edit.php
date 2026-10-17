
<?php
$id = (int) ($_GET['id'] ?? 0);
$query = mysqli_query($koneksi, "SELECT * FROM pasien WHERE idpasien='$id'");
$edit = mysqli_fetch_assoc($query);

if (!$edit) {
    echo '<div class="alert alert-warning">Data pasien tidak ditemukan.</div>';
    echo '<a href="index.php?page=pasien" class="btn btn-secondary">Kembali</a>';
    return;
}
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Edit Pasien</h5>
            </div>
            <div class="card-body">
                <form action="proses/prosespasien.php?proses=update" method="POST">
                    <input type="hidden" name="idpasien" value="<?= $edit['idpasien']; ?>">

                    <div class="mb-3">
                        <label class="form-label">Nama Pasien</label>
                        <input type="text" name="namapasien" class="form-control" maxlength="100" value="<?= $edit['namapasien']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jeniskelamin" class="form-select">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" <?= $edit['jeniskelamin'] == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                            <option value="Perempuan" <?= $edit['jeniskelamin'] == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3"><?= $edit['alamat'] ?? ''; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor HP</label>
                        <input type="text" name="nohp" class="form-control" maxlength="15" value="<?= $edit['nohp'] ?? ''; ?>">
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.php?page=pasien" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-warning">Update Pasien</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
