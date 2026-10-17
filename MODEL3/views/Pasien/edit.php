<?php
$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM pasien WHERE idpasien='$id'");
$pasien = mysqli_fetch_array($data);
?>

<div class="card shadow-sm">
    <div class="card-header bg-warning">
        <h5 class="mb-0">Edit Pasien</h5>
    </div>
    <div class="card-body">
        <form action="proses/prosespasien.php?proses=update" method="post">
            <input type="hidden" name="idpasien" value="<?php echo $pasien['idpasien']; ?>">

            <div class="mb-3">
                <label class="form-label">Nama Pasien</label>
                <input type="text" name="namapasien" class="form-control" value="<?php echo $pasien['namapasien']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jeniskelamin" class="form-select">
                    <option value="Laki-laki" <?php if ($pasien['jeniskelamin'] == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php if ($pasien['jeniskelamin'] == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3"><?php echo $pasien['alamat']; ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">No HP</label>
                <input type="text" name="nohp" class="form-control" value="<?php echo $pasien['nohp']; ?>">
            </div>

            <button type="submit" class="btn btn-warning">Update</button>
            <a href="index.php?page=pasien" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>