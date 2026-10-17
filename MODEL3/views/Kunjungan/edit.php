<?php
$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM kunjungan WHERE idkunjungan='$id'");
$kunjungan = mysqli_fetch_array($data);
$pasien = mysqli_query($koneksi, "SELECT * FROM pasien ORDER BY namapasien");
?>

<div class="card shadow-sm">
    <div class="card-header bg-warning">
        <h5 class="mb-0">Edit Kunjungan</h5>
    </div>
    <div class="card-body">
        <form action="proses/proseskunjungan.php?proses=update" method="post">
            <input type="hidden" name="idkunjungan" value="<?php echo $kunjungan['idkunjungan']; ?>">

            <div class="mb-3">
                <label class="form-label">Nama Pasien</label>
                <select name="idpasien" class="form-select" required>
                    <?php while ($p = mysqli_fetch_array($pasien)) { ?>
                    <option value="<?php echo $p['idpasien']; ?>" <?php if ($p['idpasien'] == $kunjungan['idpasien']) echo 'selected'; ?>>
                        <?php echo $p['namapasien']; ?>
                    </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Kunjungan</label>
                <input type="date" name="tanggal" class="form-control" value="<?php echo $kunjungan['tanggal']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Keluhan</label>
                <textarea name="keluhan" class="form-control" rows="3"><?php echo $kunjungan['keluhan']; ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Biaya</label>
                <input type="number" name="biaya" class="form-control" min="0" value="<?php echo $kunjungan['biaya']; ?>" required>
            </div>

            <button type="submit" class="btn btn-warning">Update</button>
            <a href="index.php?page=kunjungan" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>