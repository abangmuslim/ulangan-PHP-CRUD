<?php
$id = (int) ($_GET['id'] ?? 0);
$data = mysqli_query($koneksi, "
    SELECT k.*, p.namapasien, p.jeniskelamin, p.alamat, p.nohp AS nohppasien,
           d.namadokter, d.spesialis, o.namaobat, o.jenisobat,
           u.namauser
    FROM kunjungan k
    JOIN pasien p ON k.idpasien = p.idpasien
    JOIN dokter d ON k.iddokter = d.iddokter
    JOIN obat o ON k.idobat = o.idobat
    JOIN user u ON k.iduser = u.iduser
    WHERE k.idkunjungan='$id'
");
$kunjungan = mysqli_fetch_assoc($data);

if (!$kunjungan) {
    echo '<div class="alert alert-warning">Data kunjungan tidak ditemukan.</div>';
    echo '<a href="index.php?page=kunjungan" class="btn btn-secondary">Kembali</a>';
    return;
}
?>
<div class="card shadow-sm">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Detail Kunjungan Pasien</h5>
    </div>
    <div class="card-body">
        <h6 class="text-primary mb-3">Informasi Kunjungan</h6>
        <table class="table table-bordered">
            <tr>
                <th width="200">ID Kunjungan</th>
                <td><?= $kunjungan['idkunjungan']; ?></td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td><?= date('d-m-Y', strtotime($kunjungan['tanggal'])); ?></td>
            </tr>
            <tr>
                <th>Petugas Pencatat</th>
                <td><?= $kunjungan['namauser']; ?></td>
            </tr>
            <tr>
                <th>Biaya</th>
                <td>Rp <?= number_format($kunjungan['biaya'], 0, ',', '.'); ?></td>
            </tr>
        </table>

        <h6 class="text-primary mt-4 mb-3">Informasi Pasien</h6>
        <table class="table table-bordered">
            <tr>
                <th width="200">Nama Pasien</th>
                <td><?= $kunjungan['namapasien']; ?></td>
            </tr>
            <tr>
                <th>Jenis Kelamin</th>
                <td><?= $kunjungan['jeniskelamin'] ?? '-'; ?></td>
            </tr>
            <tr>
                <th>No. HP</th>
                <td><?= $kunjungan['nohppasien'] ?? '-'; ?></td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td><?= $kunjungan['alamat'] ?? '-'; ?></td>
            </tr>
            <tr>
                <th>Keluhan</th>
                <td><?= $kunjungan['keluhan'] ?? '-'; ?></td>
            </tr>
        </table>

        <h6 class="text-primary mt-4 mb-3">Informasi Dokter dan Obat</h6>
        <table class="table table-bordered">
            <tr>
                <th width="200">Nama Dokter</th>
                <td><?= $kunjungan['namadokter']; ?></td>
            </tr>
            <tr>
                <th>Spesialis</th>
                <td><?= $kunjungan['spesialis'] ?? '-'; ?></td>
            </tr>
            <tr>
                <th>Nama Obat</th>
                <td><?= $kunjungan['namaobat']; ?></td>
            </tr>
            <tr>
                <th>Jenis Obat</th>
                <td><?= $kunjungan['jenisobat'] ?? '-'; ?></td>
            </tr>
        </table>

        <a href="index.php?page=kunjungan" class="btn btn-secondary">Kembali</a>
        <a href="index.php?page=editkunjungan&id=<?= $kunjungan['idkunjungan']; ?>" class="btn btn-warning">Edit Kunjungan</a>
    </div>
</div>