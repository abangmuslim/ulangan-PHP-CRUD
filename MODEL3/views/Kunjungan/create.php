<?php
$pasien = mysqli_query($koneksi, "SELECT * FROM pasien ORDER BY namapasien");
?>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Tambah Kunjungan</h5>
    </div>
    <div class="card-body">
        <form action="proses/proseskunjungan.php?proses=insert" method="post">
            <div class="mb-3">
                <label class="form-label">Nama Pasien</label>
                <select name="idpasien" class="form-select" required>
                    <option value="">Pilih Pasien</option>
                    <?php while ($p = mysqli_fetch_array($pasien)) { ?>
                    <option value="<?php echo $p['idpasien']; ?>">
                        <?php echo $p['namapasien']; ?>
                    </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Kunjungan</label>
                <input type="date" name="tanggal" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Keluhan</label>
                <textarea name="keluhan" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Biaya</label>
                <input type="number" name="biaya" class="form-control" min="0" value="0" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php?page=kunjungan" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>