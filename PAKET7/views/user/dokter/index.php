<?php
// ============================================
// AMBIL DATA DOKTER
// ============================================
$queryDokter = mysqli_query($koneksi, "SELECT * FROM dokter ORDER BY iddokter DESC");

$totalDokter = mysqli_num_rows(
    mysqli_query($koneksi, "SELECT iddokter FROM dokter")
);

$totalAkun = mysqli_num_rows(
    mysqli_query($koneksi, "SELECT iddokter FROM dokter WHERE username IS NOT NULL AND username != ''")
);

$belumAkun = $totalDokter - $totalAkun;
?>

<section class="content">
    <div class="container-fluid">

        <div class="card card-primary card-outline shadow-sm">

            <div class="card-header">
                <div class="row align-items-center">

                    <div class="col-md-4">
                        <h3 class="card-title">
                            <i class="fas fa-user-md me-1"></i>
                            Daftar Dokter
                        </h3>
                    </div>

                    <div class="col-md-8">
                        <div class="d-flex justify-content-md-end flex-wrap">

                            <span class="btn btn-sm btn-primary me-1 mb-1">
                                Total Dokter: <?= $totalDokter ?>
                            </span>

                            <span class="btn btn-sm btn-success me-1 mb-1">
                                Memiliki Akun: <?= $totalAkun ?>
                            </span>

                            <span class="btn btn-sm btn-secondary me-2 mb-1">
                                Belum Memiliki Akun: <?= $belumAkun ?>
                            </span>

                            <a href="index.php?page=createdokter"
                               class="btn btn-sm btn-primary mb-1">
                                <i class="fas fa-plus me-1"></i>
                                Tambah Dokter
                            </a>

                        </div>
                    </div>

                </div>
            </div>

            <div class="card-body">

                <!-- NOTIFIKASI -->
                <?php if (isset($_SESSION['success_dokter'])) { ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-1"></i>
                        <?= e($_SESSION['success_dokter']) ?>
                    </div>
                    <?php unset($_SESSION['success_dokter']); ?>
                <?php } ?>

                <?php if (isset($_SESSION['error_dokter'])) { ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        <?= e($_SESSION['error_dokter']) ?>
                    </div>
                    <?php unset($_SESSION['error_dokter']); ?>
                <?php } ?>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="bg-light">
                            <tr>
                                <th width="50">No.</th>
                                <th width="70">Foto</th>
                                <th>Nama Dokter</th>
                                <th>Spesialis</th>
                                <th>Username</th>
                                <th>No. HP</th>
                                <th width="160">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (mysqli_num_rows($queryDokter) > 0) { ?>

                                <?php $no = 1; ?>

                                <?php while ($data = mysqli_fetch_assoc($queryDokter)) { ?>

                                    <tr>

                                        <td><?= $no++ ?></td>

                                        <td>
                                            <img src="<?= e(!empty($data['foto'])
                                                ? 'assets/images/dokter/' . $data['foto']
                                                : 'assets/images/dokter/default.png') ?>"
                                                 width="50"
                                                 height="50"
                                                 style="object-fit: cover; border-radius: 5px;"
                                                 alt="Foto Dokter">
                                        </td>

                                        <td><?= e($data['namadokter']) ?></td>

                                        <td><?= e($data['spesialis']) ?></td>

                                        <td>
                                            <?= !empty($data['username'])
                                                ? e($data['username'])
                                                : '-' ?>
                                        </td>

                                        <td>
                                            <?= !empty($data['nohp'])
                                                ? e($data['nohp'])
                                                : '-' ?>
                                        </td>

                                        <td>

                                            <a href="index.php?page=showdokter&iddokter=<?= (int) $data['iddokter'] ?>"
                                               class="btn btn-sm btn-info"
                                               title="Detail Dokter">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="index.php?page=editdokter&iddokter=<?= (int) $data['iddokter'] ?>"
                                               class="btn btn-sm btn-warning"
                                               title="Edit Dokter">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="index.php"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus data dokter ini?')">

                                                <input type="hidden" name="page" value="prosesdokter">
                                                <input type="hidden" name="aksi" value="hapus">
                                                <input type="hidden" name="iddokter" value="<?= (int) $data['iddokter'] ?>">

                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        title="Hapus Dokter">
                                                    <i class="fas fa-trash"></i>
                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                <?php } ?>

                            <?php } else { ?>

                                <tr>
                                    <td colspan="7" class="text-center">
                                        Belum ada data dokter.
                                    </td>
                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
</section>