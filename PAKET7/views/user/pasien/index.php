<?php
$queryPasien = mysqli_query($koneksi, "SELECT * FROM pasien ORDER BY idpasien DESC");
$totalPasien = mysqli_num_rows(mysqli_query($koneksi, "SELECT idpasien FROM pasien"));
$totalAkun = mysqli_num_rows(mysqli_query($koneksi, "SELECT idpasien FROM pasien WHERE username IS NOT NULL AND username != ''"));
$belumAkun = $totalPasien - $totalAkun;
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-4">
                <h3 class="card-title font-weight-bold">Daftar Pasien</h3>
            </div>
            <div class="col-md-8">
                <div class="d-flex justify-content-md-end flex-wrap">
                    <span class="btn btn-sm btn-primary mr-1 mb-1">
                        Total Pasien: <?= $totalPasien; ?>
                    </span>
                    <span class="btn btn-sm btn-success mr-1 mb-1">
                        Memiliki Akun: <?= $totalAkun; ?>
                    </span>
                    <span class="btn btn-sm btn-secondary mr-2 mb-1">
                        Belum Memiliki Akun: <?= $belumAkun; ?>
                    </span>
                    <a href="index.php?page=createpasien" class="btn btn-sm btn-primary mb-1">
                        <i class="fas fa-plus"></i> Tambah Pasien
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <?php if (isset($_GET['pesan'])): ?>
            <?php if ($_GET['pesan'] == 'berhasil'): ?>
                <div class="alert alert-success">Data pasien berhasil disimpan.</div>
            <?php elseif ($_GET['pesan'] == 'dihapus'): ?>
                <div class="alert alert-success">Data pasien berhasil dihapus.</div>
            <?php elseif ($_GET['pesan'] == 'gagal'): ?>
                <div class="alert alert-danger">Data pasien gagal diproses.</div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="bg-light">
                    <tr>
                        <th width="50">No.</th>
                        <th width="70">Foto</th>
                        <th>Nama Pasien</th>
                        <th>Username</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($queryPasien) > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($pasien = mysqli_fetch_assoc($queryPasien)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <img src="assets/images/pasien/<?= e($pasien['foto'] ?: 'default.png'); ?>"
                                         width="50" height="50"
                                         class="img-circle"
                                         style="object-fit: cover;"
                                         onerror="this.src='assets/images/pasien/default.png'">
                                </td>
                                <td><?= e($pasien['namapasien']); ?></td>
                                <td>
                                    <?= e($pasien['username'] ?: '-'); ?>
                                </td>
                                <td><?= e($pasien['nohp'] ?: '-'); ?></td>
                                <td><?= e($pasien['alamat'] ?: '-'); ?></td>
                                <td>
                                    <a href="index.php?page=showpasien&idpasien=<?= $pasien['idpasien']; ?>"
                                       class="btn btn-sm btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="index.php?page=editpasien&idpasien=<?= $pasien['idpasien']; ?>"
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="index.php" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus data pasien ini?')">
                                        <input type="hidden" name="page" value="prosespasien">
                                        <input type="hidden" name="aksi" value="hapus">
                                        <input type="hidden" name="idpasien" value="<?= $pasien['idpasien']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data pasien.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>