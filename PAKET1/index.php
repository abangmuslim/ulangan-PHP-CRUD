<?php
include "koneksi.php";

$edit = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $queryedit = mysqli_query($koneksi, "SELECT * FROM pasien WHERE idpasien='$id'");
    $edit = mysqli_fetch_assoc($queryedit);
}

$data = mysqli_query($koneksi, "SELECT * FROM pasien ORDER BY idpasien DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pasien - Rumah Sakit Ahmadi</title>
</head>
<body>

<h2>Aplikasi Rumah Sakit Ahmadi</h2>
<p>Pengelolaan Data Pasien</p>

<h3><?= $edit ? 'Edit Pasien' : 'Tambah Pasien' ?></h3>

<form method="post" action="proses.php?proses=<?= $edit ? 'update' : 'insert' ?>">

    <?php if ($edit) { ?>
        <input type="hidden" name="idpasien" value="<?= $edit['idpasien'] ?>">
    <?php } ?>

    <p>
        Nama Pasien<br>
        <input type="text" name="namapasien" value="<?= $edit['namapasien'] ?? '' ?>" required>
    </p>

    <p>
        Jenis Kelamin<br>
        <select name="jeniskelamin" required>
            <option value="">-- Pilih Jenis Kelamin --</option>
            <option value="Laki-laki" <?= ($edit['jeniskelamin'] ?? '') == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
            <option value="Perempuan" <?= ($edit['jeniskelamin'] ?? '') == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
        </select>
    </p>

    <p>
        Alamat<br>
        <textarea name="alamat" rows="3"><?= $edit['alamat'] ?? '' ?></textarea>
    </p>

    <p>
        No. HP<br>
        <input type="text" name="nohp" value="<?= $edit['nohp'] ?? '' ?>">
    </p>

    <?php if ($edit) { ?>
        <button type="submit">Update Pasien</button>
        <a href="index.php">Batal</a>
    <?php } else { ?>
        <button type="submit">Simpan Pasien</button>
    <?php } ?>

</form>

<hr>

<h3>Daftar Pasien</h3>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nama Pasien</th>
        <th>Jenis Kelamin</th>
        <th>Alamat</th>
        <th>No. HP</th>
        <th>Aksi</th>
    </tr>

    <?php while ($p = mysqli_fetch_assoc($data)) { ?>
        <tr>
            <td><?= $p['idpasien'] ?></td>
            <td><?= $p['namapasien'] ?></td>
            <td><?= $p['jeniskelamin'] ?></td>
            <td><?= $p['alamat'] ?></td>
            <td><?= $p['nohp'] ?></td>
            <td>
                <a href="index.php?edit=<?= $p['idpasien'] ?>">Edit</a>

                <form method="post" action="proses.php?proses=hapus">
                    <input type="hidden" name="idpasien" value="<?= $p['idpasien'] ?>">
                    <button type="submit" onclick="return confirm('Yakin ingin menghapus pasien ini?')">Hapus</button>
                </form>
            </td>
        </tr>
    <?php } ?>

    <?php if (mysqli_num_rows($data) == 0) { ?>
        <tr>
            <td colspan="6">Belum ada data pasien.</td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
