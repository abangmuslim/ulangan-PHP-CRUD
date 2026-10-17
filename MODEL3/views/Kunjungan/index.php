<?php
$data = mysqli_query($koneksi, "SELECT kunjungan.*, pasien.namapasien
    FROM kunjungan
    JOIN pasien ON kunjungan.idpasien=pasien.idpasien
    ORDER BY idkunjungan DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Data Kunjungan</h4>
    <a href="index.php?page=createkunjungan" class="btn btn-primary">Tambah Kunjungan</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Pasien</th>
                        <th>Tanggal</th>
                        <th>Keluhan</th>
                        <th>Biaya</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($kunjungan = mysqli_fetch_array($data)) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $kunjungan['namapasien']; ?></td>
                        <td><?php echo $kunjungan['tanggal']; ?></td>
                        <td><?php echo $kunjungan['keluhan']; ?></td>
                        <td>Rp <?php echo number_format($kunjungan['biaya'], 0, ',', '.'); ?></td>
                        <td>
                            <a href="index.php?page=showkunjungan&id=<?php echo $kunjungan['idkunjungan']; ?>" class="btn btn-info btn-sm">Detail</a>
                            <a href="index.php?page=editkunjungan&id=<?php echo $kunjungan['idkunjungan']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="proses/proseskunjungan.php?proses=hapus" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kunjungan ini?')">
                                <input type="hidden" name="idkunjungan" value="<?php echo $kunjungan['idkunjungan']; ?>">
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