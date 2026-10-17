<?php
$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM pasien WHERE idpasien='$id'");
$pasien = mysqli_fetch_array($data);
?>

<div class="card shadow-sm">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Detail Pasien</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th width="30%">ID Pasien</th>
                <td><?php echo $pasien['idpasien']; ?></td>
            </tr>
            <tr>
                <th>Nama Pasien</th>
                <td><?php echo $pasien['namapasien']; ?></td>
            </tr>
            <tr>
                <th>Jenis Kelamin</th>
                <td><?php echo $pasien['jeniskelamin']; ?></td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td><?php echo $pasien['alamat']; ?></td>
            </tr>
            <tr>
                <th>No HP</th>
                <td><?php echo $pasien['nohp']; ?></td>
            </tr>
        </table>

        <a href="index.php?page=pasien" class="btn btn-secondary">Kembali</a>
        <a href="index.php?page=editpasien&id=<?php echo $pasien['idpasien']; ?>" class="btn btn-warning">Edit</a>
    </div>
</div>