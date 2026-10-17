<?php
include "koneksi.php";

$pasien = mysqli_query($koneksi, "SELECT * FROM pasien ORDER BY namapasien ASC");
?>

<h2>Tambah Kunjungan</h2>

<form action="proses/proseskunjungan.php?proses=insert" method="post">
    <p>
        <label>Nama Pasien</label><br>
        <select name="idpasien" required>
            <option value="">-- Pilih Pasien --</option>
            <?php while ($data = mysqli_fetch_array($pasien)) { ?>
                <option value="<?= $data['idpasien']; ?>">
                    <?= $data['namapasien']; ?>
                </option>
            <?php } ?>
        </select>
    </p>
    <p>
        <label>Tanggal Kunjungan</label><br>
        <input type="date" name="tanggal" required>
    </p>
    <p>
        <label>Keluhan</label><br>
        <textarea name="keluhan" rows="3" required></textarea>
    </p>
    <p>
        <label>Biaya</label><br>
        <input type="number" name="biaya" min="0" required>
    </p>
    <button type="submit">Simpan</button>
    <a href="index.php?page=kunjungan">Kembali</a>
</form>