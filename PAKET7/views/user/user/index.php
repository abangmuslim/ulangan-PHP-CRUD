
<?php
// ============================================
// STATISTIK USER
// ============================================
$totaluser = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM user"))['total'];
$totaladmin = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM user WHERE level='admin'"))['total'];
$totalpetugas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM user WHERE level='petugas'"))['total'];

// ============================================
// PENCARIAN USER
// ============================================
$cari = $_GET['cari'] ?? '';

$sql = "SELECT * FROM user
        WHERE namauser LIKE ?
        OR username LIKE ?
        OR level LIKE ?
        ORDER BY iduser DESC";

$stmt = mysqli_prepare($koneksi, $sql);
$keyword = "%$cari%";
mysqli_stmt_bind_param($stmt, "sss", $keyword, $keyword, $keyword);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>



<section class="content">
    <div class="container-fluid">

        <div class="card card-outline card-primary shadow-sm">

            <div class="card-header">
                <div class="row align-items-center">

                    <!-- Kolom Kiri: Judul Header -->
                    <div class="col-sm-6">
                        <h3 class="card-title">
                            <i class="fas fa-list mr-1"></i>
                            Daftar User
                        </h3>
                    </div>

                    <!-- Kolom Kanan: Rangkuman dan Tombol -->
                    <div class="col-sm-6">
                        <div class="d-flex justify-content-sm-end align-items-center flex-wrap">

                            <span class="btn btn-outline-primary mr-2 mb-1">
                                <i class="fas fa-users"></i>
                                Total User:
                                <strong><?= $totaluser ?></strong>
                            </span>

                            <span class="btn btn-outline-danger mr-2 mb-1">
                                <i class="fas fa-user-shield"></i>
                                Admin:
                                <strong><?= $totaladmin ?></strong>
                            </span>

                            <span class="btn btn-outline-success mr-2 mb-1">
                                <i class="fas fa-user-tie"></i>
                                Petugas:
                                <strong><?= $totalpetugas ?></strong>
                            </span>

                            <a href="index.php?page=createuser" class="btn btn-primary mb-1">
                                <i class="fas fa-plus"></i> Tambah User
                            </a>

                        </div>
                    </div>

                </div>
            </div>

            <div class="card-body">

                <!-- PENCARIAN -->
                <form method="GET" action="index.php" class="mb-3">
                    <input type="hidden" name="page" value="user">

                    <div class="input-group">
                        <input type="text"
                               name="cari"
                               class="form-control"
                               placeholder="Cari nama, username, atau level user..."
                               value="<?= e($cari) ?>">

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Cari
                        </button>

                        <a href="index.php?page=user"
                           class="btn btn-secondary">
                            <i class="fas fa-sync"></i> Reset
                        </a>
                    </div>
                </form>

                <!-- TABEL USER -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th width="50">No</th>
                                <th width="80">Foto</th>
                                <th>Nama User</th>
                                <th>Username</th>
                                <th>No HP</th>
                                <th width="110">Level</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php $no = 1; ?>

                                <?php while ($user = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>

                                        <td>
                                            <img
                                                src="assets/images/user/<?= !empty($user['foto']) ? e($user['foto']) : 'default.png' ?>"
                                                width="45"
                                                height="45"
                                                class="rounded-circle"
                                                style="object-fit:cover">
                                        </td>

                                        <td><?= e($user['namauser']) ?></td>

                                        <td><?= e($user['username']) ?></td>

                                        <td><?= e($user['nohp'] ?: '-') ?></td>

                                        <td>
                                            <?php if ($user['level'] == 'admin'): ?>
                                                <span class="badge bg-danger">Admin</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Petugas</span>
                                            <?php endif; ?>
                                        </td>

                                        <td>

                                            <a href="index.php?page=showuser&id=<?= $user['iduser'] ?>"
                                               class="btn btn-info btn-sm"
                                               title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="index.php?page=edituser&id=<?= $user['iduser'] ?>"
                                               class="btn btn-warning btn-sm"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form method="POST"
                                                  action="index.php"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus user ini?')">

                                                <input type="hidden" name="page" value="prosesuser">
                                                <input type="hidden" name="aksi" value="hapus">
                                                <input type="hidden" name="iduser" value="<?= $user['iduser'] ?>">

                                                <button type="submit"
                                                        class="btn btn-danger btn-sm"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>

                                            </form>

                                        </td>
                                    </tr>
                                <?php endwhile; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        Data user tidak ditemukan.
                                    </td>
                                </tr>

                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="card-footer text-muted">
                <small>
                    <i class="fas fa-info-circle"></i>
                    Pengelolaan akun administrator dan petugas klinik.
                </small>
            </div>

        </div>

    </div>
</section>
