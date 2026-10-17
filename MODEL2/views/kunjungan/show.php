<?php
include "koneksi.php";

$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT kunjungan.*, pasien.namapasien, pasien.jeniskelamin, pasien.alamat, pasien.nohp
    FROM kunjungan
    INNER JOIN pasien ON kunjungan.idpasien = pasien.idpasien
    WHERE kunjungan.idkunjungan='$id'");

$kunjungan = mysqli_fetch_array($data);

if (!$kunjungan) {
    echo "Data kunjungan tidak ditemukan.";
    echo '<p><a href="index.php?page=kunjungan">Kembali</a></p>';
    exit;
}
?>

<h2>Detail Kunjungan</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID Kunjungan</th>
        <td><?= $kunjungan['idkunjungan']; ?></td>
    </tr>
    <tr>
        <th>Nama Pasien</th>
        <td><?= $kunjungan['namapasien']; ?></td>
    </tr>
    <tr>
        <th>Jenis Kelamin</th>
        <td><?= $kunjungan['jeniskelamin']; ?></td>
    </tr>
    <tr>
        <th>Alamat</th>
        <td><?= $kunjungan['alamat']; ?></td>
    </tr>
    <tr>
        <th>No. HP</th>
        <td><?= $kunjungan['nohp']; ?></td>
    </tr>
    <tr>
        <th>Tanggal Kunjungan</th>
        <td><?= $kunjungan['tanggal']; ?></td>
    </tr>
    <tr>
        <th>Keluhan</th>
        <td><?= $kunjungan['keluhan']; ?></td>
    </tr>
    <tr>
        <th>Biaya</th>
        <td>Rp <?= number_format($kunjungan['biaya'], 0, ',', '.'); ?></td>
    </tr>
</table>

<p><a href="index.php?page=kunjungan">Kembali ke Data Kunjungan</a></p>
<p><a href="index.php?page=editkunjungan&id=<?= $kunjungan['idkunjungan']; ?>">Edit Kunjungan</a></p>