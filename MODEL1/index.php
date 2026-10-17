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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Pasien - Rumah Sakit Ahmadi</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .container { width: 90%; margin: auto; }
        .box { border: 1px solid #ccc; padding: 10px; margin-bottom: 20px; }
        label { display: block; margin-top: 10px; }
        input, select, textarea { width: 100%; padding: 5px; box-sizing: border-box; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
        button, .tombol { padding: 5px 10px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Aplikasi Rumah Sakit Ahmadi</h2>
        <p>Pengelolaan Data Pasien</p>

        <div class="box">
            <h3><?= $edit ? 'Edit Pasien' : 'Tambah Pasien' ?></h3>

            <form method="post" action="proses.php?proses=<?= $edit ? 'update' : 'insert' ?>">
                <?php if ($edit) { ?>
                    <input type="hidden" name="idpasien" value="<?= $edit['idpasien'] ?>">
                <?php } ?>

                <label>Nama Pasien</label>
                <input type="text" name="namapasien" value="<?= $edit['namapasien'] ?? '' ?>" required>

                <label>Jenis Kelamin</label>
                <select name="jeniskelamin" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki" <?= ($edit['jeniskelamin'] ?? '') == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="Perempuan" <?= ($edit['jeniskelamin'] ?? '') == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                </select>

                <label>Alamat</label>
                <textarea name="alamat" rows="3"><?= $edit['alamat'] ?? '' ?></textarea>

                <label>No. HP</label>
                <input type="text" name="nohp" value="<?= $edit['nohp'] ?? '' ?>">

                <br><br>
                <?php if ($edit) { ?>
                    <button type="submit">Update Pasien</button>
                    <a href="index.php" class="tombol">Batal</a>
                <?php } else { ?>
                    <button type="submit">Simpan Pasien</button>
                <?php } ?>
            </form>
        </div>

        <div class="box">
            <h3>Daftar Pasien</h3>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Pasien</th>
                        <th>Jenis Kelamin</th>
                        <th>Alamat</th>
                        <th>No. HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($p = mysqli_fetch_assoc($data)) { ?>
                        <tr>
                            <td><?= $p['idpasien'] ?></td>
                            <td><?= $p['namapasien'] ?></td>
                            <td><?= $p['jeniskelamin'] ?></td>
                            <td><?= $p['alamat'] ?? '' ?></td>
                            <td><?= $p['nohp'] ?? '' ?></td>
                            <td>
                                <a href="index.php?edit=<?= $p['idpasien'] ?>">Edit</a> |
                                <form method="post" action="proses.php?proses=hapus" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus pasien ini?')">
                                    <input type="hidden" name="idpasien" value="<?= $p['idpasien'] ?>" style="width:auto">
                                    <button type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>

                    <?php if (mysqli_num_rows($data) == 0) { ?>
                        <tr>
                            <td colspan="6" style="text-align:center">Belum ada data pasien.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
