<?php
$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT kunjungan.*, pasien.namapasien
    FROM kunjungan
    JOIN pasien ON kunjungan.idpasien=pasien.idpasien
    WHERE idkunjungan='$id'");
$kunjungan = mysqli_fetch_array($data);
?>

<div class="card shadow-sm">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Detail Kunjungan</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th width="30%">ID Kunjungan</th>
                <td><?php echo $kunjungan['idkunjungan']; ?></td>
            </tr>
            <tr>
                <th>Nama Pasien</th>
                <td><?php echo $kunjungan['namapasien']; ?></td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td><?php echo $kunjungan['tanggal']; ?></td>
            </tr>
            <tr>
                <th>Keluhan</th>
                <td><?php echo $kunjungan['keluhan']; ?></td>
            </tr>
            <tr>
                <th>Biaya</th>
                <td>Rp <?php echo number_format($kunjungan['biaya'], 0, ',', '.'); ?></td>
            </tr>
        </table>

        <a href="index.php?page=kunjungan" class="btn btn-secondary">Kembali</a>
        <a href="index.php?page=editkunjungan&id=<?php echo $kunjungan['idkunjungan']; ?>" class="btn btn-warning">Edit</a>
    </div>
</div>