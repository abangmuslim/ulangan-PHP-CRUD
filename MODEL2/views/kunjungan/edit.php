<?php
include "koneksi.php";

$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM kunjungan WHERE idkunjungan='$id'");
$kunjungan = mysqli_fetch_array($data);

if (!$kunjungan) {
    echo "Data kunjungan tidak ditemukan.";
    echo '<p><a href="index.php?page=kunjungan">Kembali</a></p>';
    exit;
}

$pasien = mysqli_query($koneksi, "SELECT * FROM pasien ORDER BY namapasien ASC");
?>

<h2>Edit Kunjungan</h2>

<form action="proses/proseskunjungan.php?proses=update" method="post">
    <input type="hidden" name="idkunjungan" value="<?= $kunjungan['idkunjungan']; ?>">

    <p>
        <label>Nama Pasien</label><br>
        <select name="idpasien" required>
            <option value="">-- Pilih Pasien --</option>
            <?php while ($data = mysqli_fetch_array($pasien)) { ?>
                <option value="<?= $data['idpasien']; ?>"
                    <?= $kunjungan['idpasien'] == $data['idpasien'] ? 'selected' : ''; ?>>
                    <?= $data['namapasien']; ?>
                </option>
            <?php } ?>
        </select>
    </p>
    <p>
        <label>Tanggal Kunjungan</label><br>
        <input type="date" name="tanggal" value="<?= $kunjungan['tanggal']; ?>" required>
    </p>
    <p>
        <label>Keluhan</label><br>
        <textarea name="keluhan" rows="3" required><?= $kunjungan['keluhan']; ?></textarea>
    </p>
    <p>
        <label>Biaya</label><br>
        <input type="number" name="biaya" min="0" value="<?= $kunjungan['biaya']; ?>" required>
    </p>
    <button type="submit">Simpan Perubahan</button>
    <a href="index.php?page=kunjungan">Kembali</a>
</form>