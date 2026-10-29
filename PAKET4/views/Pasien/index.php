<?php
$data = mysqli_query($koneksi, "SELECT * FROM pasien ORDER BY idpasien DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Data Pasien</h4>
    <a href="index.php?page=createpasien" class="btn btn-primary">Tambah Pasien</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Pasien</th>
                        <th>Jenis Kelamin</th>
                        <th>No HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($pasien = mysqli_fetch_array($data)) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $pasien['namapasien']; ?></td>
                        <td><?php echo $pasien['jeniskelamin']; ?></td>
                        <td><?php echo $pasien['nohp']; ?></td>
                        <td>
                            <a href="index.php?page=showpasien&id=<?php echo $pasien['idpasien']; ?>" class="btn btn-info btn-sm">Detail</a>
                            <a href="index.php?page=editpasien&id=<?php echo $pasien['idpasien']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="proses/prosespasien.php?proses=hapus" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pasien ini?')">
                                <input type="hidden" name="idpasien" value="<?php echo $pasien['idpasien']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>