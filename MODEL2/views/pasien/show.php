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

<h2>Detail Pasien</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID Pasien</th>
        <td><?= $pasien['idpasien']; ?></td>
    </tr>
    <tr>
        <th>Nama Pasien</th>
        <td><?= $pasien['namapasien']; ?></td>
    </tr>
    <tr>
        <th>Jenis Kelamin</th>
        <td><?= $pasien['jeniskelamin']; ?></td>
    </tr>
    <tr>
        <th>Alamat</th>
        <td><?= $pasien['alamat']; ?></td>
    </tr>
    <tr>
        <th>No. HP</th>
        <td><?= $pasien['nohp']; ?></td>
    </tr>
</table>

<p><a href="index.php?page=pasien">Kembali ke Data Pasien</a></p>
<p><a href="index.php?page=editpasien&id=<?= $pasien['idpasien']; ?>">Edit Pasien</a></p>