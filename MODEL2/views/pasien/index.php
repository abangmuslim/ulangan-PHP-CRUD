<?php
include "koneksi.php";

$data = mysqli_query($koneksi, "SELECT * FROM pasien ORDER BY idpasien DESC");
?>

<h2>Data Pasien</h2>
<p>
    <button ><a href="index.php?page=createpasien">+ Tambah Pasien</a></button>
</p>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>No</th>
        <th>Nama Pasien</th>
        <th>Jenis Kelamin</th>
        <th>No. HP</th>
        <th>Aksi</th>
    </tr>
    <?php
    $no = 1;
    while ($pasien = mysqli_fetch_array($data)) {
    ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= $pasien['namapasien']; ?></td>
        <td><?= $pasien['jeniskelamin']; ?></td>
        <td><?= $pasien['nohp']; ?></td>
        <td>
            <a href="index.php?page=showpasien&id=<?= $pasien['idpasien']; ?>">Detail</a> |
            <a href="index.php?page=editpasien&id=<?= $pasien['idpasien']; ?>">Edit</a> |
            <form action="proses/prosespasien.php?proses=hapus" method="post" style="display:inline;">
                <input type="hidden" name="idpasien" value="<?= $pasien['idpasien']; ?>">
                <button type="submit" onclick="return confirm('Yakin ingin menghapus pasien ini?')">Hapus</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>