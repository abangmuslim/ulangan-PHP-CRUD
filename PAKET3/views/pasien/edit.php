<?php
include "koneksi.php";

$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM pasien WHERE idpasien='$id'");
$pasien = mysqli_fetch_array($data);

if (!$pasien) {
    echo "Data pasien tidak ditemukan.";
    echo '<p><a href="index.php?page=pasien">Kembali</a></p>';
    exit;
}
?>

<h2>Edit Pasien</h2>

<form action="proses/prosespasien.php?proses=update" method="post">
    <input type="hidden" name="idpasien" value="<?= $pasien['idpasien']; ?>">

    <p>
        <label>Nama Pasien</label><br>
        <input type="text" name="namapasien" value="<?= $pasien['namapasien']; ?>" required>
    </p>
    <p>
        <label>Jenis Kelamin</label><br>
        <select name="jeniskelamin" required>
            <option value="Laki-laki" <?= $pasien['jeniskelamin'] == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
            <option value="Perempuan" <?= $pasien['jeniskelamin'] == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
        </select>
    </p>
    <p>
        <label>Alamat</label><br>
        <textarea name="alamat" rows="3" required><?= $pasien['alamat']; ?></textarea>
    </p>
    <p>
        <label>No. HP</label><br>
        <input type="text" name="nohp" value="<?= $pasien['nohp']; ?>" required>
    </p>
    <button type="submit">Simpan Perubahan</button>
    <a href="index.php?page=pasien">Kembali</a>
</form>