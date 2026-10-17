<?php
include "koneksi.php";

$data = mysqli_query($koneksi, "SELECT kunjungan.*, pasien.namapasien
    FROM kunjungan
    INNER JOIN pasien ON kunjungan.idpasien = pasien.idpasien
    ORDER BY idkunjungan DESC");
?>

<h2>Data Kunjungan</h2>
<p>
    <button ><a href="index.php?page=createkunjungan">+ Tambah Kunjungan</a></button>
</p>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>No</th>
        <th>Nama Pasien</th>
        <th>Tanggal</th>
        <th>Keluhan</th>
        <th>Biaya</th>
        <th>Aksi</th>
    </tr>
    <?php
    $no = 1;
    while ($kunjungan = mysqli_fetch_array($data)) {
    ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= $kunjungan['namapasien']; ?></td>
        <td><?= $kunjungan['tanggal']; ?></td>
        <td><?= $kunjungan['keluhan']; ?></td>
        <td>Rp <?= number_format($kunjungan['biaya'], 0, ',', '.'); ?></td>
        <td>
            <a href="index.php?page=showkunjungan&id=<?= $kunjungan['idkunjungan']; ?>">Detail</a> |
            <a href="index.php?page=editkunjungan&id=<?= $kunjungan['idkunjungan']; ?>">Edit</a> |
            <form action="proses/proseskunjungan.php?proses=hapus" method="post" style="display:inline;">
                <input type="hidden" name="idkunjungan" value="<?= $kunjungan['idkunjungan']; ?>">
                <button type="submit" onclick="return confirm('Yakin ingin menghapus kunjungan ini?')">Hapus</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>