<?php
$data = mysqli_query($koneksi, "
    SELECT k.*, p.namapasien, d.namadokter, o.namaobat, u.namauser
    FROM kunjungan k
    JOIN pasien p ON k.idpasien = p.idpasien
    JOIN dokter d ON k.iddokter = d.iddokter
    JOIN obat o ON k.idobat = o.idobat
    JOIN user u ON k.iduser = u.iduser
    ORDER BY k.idkunjungan DESC
");
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1">Data Kunjungan</h3>
        <p class="text-muted mb-0">Transaksi kunjungan pasien Rumah Sakit Ahmadi</p>
    </div>
    <a href="index.php?page=createkunjungan" class="btn btn-primary">+ Tambah Kunjungan</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th>Obat</th>
                        <th>Biaya</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($kunjungan = mysqli_fetch_assoc($data)) { ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= date('d-m-Y', strtotime($kunjungan['tanggal'])); ?></td>
                        <td><?= $kunjungan['namapasien']; ?></td>
                        <td><?= $kunjungan['namadokter']; ?></td>
                        <td><?= $kunjungan['namaobat']; ?></td>
                        <td>Rp <?= number_format($kunjungan['biaya'], 0, ',', '.'); ?></td>
                        <td><?= $kunjungan['namauser']; ?></td>
                        <td>
                            <a href="index.php?page=showkunjungan&id=<?= $kunjungan['idkunjungan']; ?>" class="btn btn-info btn-sm">Detail</a>
                            <a href="index.php?page=editkunjungan&id=<?= $kunjungan['idkunjungan']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="proses/proseskunjungan.php?proses=hapus" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kunjungan ini?')">
                                <input type="hidden" name="idkunjungan" value="<?= $kunjungan['idkunjungan']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if (mysqli_num_rows($data) == 0) { ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada data kunjungan.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>