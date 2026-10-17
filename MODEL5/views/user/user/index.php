
<?php
$data = mysqli_query($koneksi, "SELECT * FROM user ORDER BY iduser DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1">Data User</h3>
        <p class="text-muted mb-0">Daftar pengguna aplikasi Rumah Sakit Ahmadi</p>
    </div>
    <a href="index.php?page=createuser" class="btn btn-primary">+ Tambah User</a>
</div>

<?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagalhapus') { ?>
    <div class="alert alert-warning">User tidak dapat dihapus karena sudah memiliki data kunjungan.</div>
<?php } ?>

<?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagallogin') { ?>
    <div class="alert alert-warning">User yang sedang login tidak dapat dihapus.</div>
<?php } ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th width="60">No.</th>
                        <th>Nama User</th>
                        <th>Username</th>
                        <th width="230">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($user = mysqli_fetch_assoc($data)) {
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $user['namauser']; ?></td>
                        <td><?= $user['username']; ?></td>
                        <td>
                            <a href="index.php?page=showuser&id=<?= $user['iduser']; ?>" class="btn btn-info btn-sm">Detail</a>
                            <a href="index.php?page=edituser&id=<?= $user['iduser']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="proses/prosesuser.php?proses=hapus" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                <input type="hidden" name="iduser" value="<?= $user['iduser']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>

                    <?php if (mysqli_num_rows($data) == 0) { ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada data user.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
