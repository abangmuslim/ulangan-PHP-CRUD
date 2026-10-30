
<?php
$query = mysqli_query($koneksi, "SELECT * FROM user ORDER BY iduser DESC");

$totaluser = mysqli_num_rows($query);
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">

            <div class="col-sm-6">
                <h1 class="mb-0">
                    <i class="fas fa-users-cog"></i> Data User
                </h1>
            </div>

            <div class="col-sm-6">
                <div class="d-flex justify-content-end align-items-center">

                    <span class="btn btn-outline-primary me-2">
                        <i class="fas fa-users"></i>
                        Total User:
                        <strong><?= $totaluser; ?></strong>
                    </span>

                    <a href="index.php?page=createuser"
                        class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah User
                    </a>

                </div>
            </div>

        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="card card-primary card-outline">

            <div class="card-header">
                <h3 class="card-title">
                    Manajemen User Sistem
                </h3>
            </div>

            <div class="card-body">

                <table id="example1"
                    class="table table-bordered table-hover table-striped">

                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Foto</th>
                            <th>Nama User</th>
                            <th>Username</th>
                            <th>No HP</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $no = 1;

                        while ($data = mysqli_fetch_assoc($query)) {
                            $foto = empty($data['foto'])
                                ? 'default.png'
                                : $data['foto'];
                        ?>

                            <tr>

                                <td><?= $no++; ?></td>

                                <td class="text-center">
                                    <img
                                        src="assets/images/user/<?= htmlspecialchars($foto); ?>"
                                        class="img-circle elevation-2"
                                        width="50"
                                        height="50"
                                        style="object-fit: cover;">
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['namauser']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['username']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['nohp'] ?? '-'); ?>
                                </td>

                                <td>
                                    <a href="index.php?page=showuser&id=<?= $data['iduser']; ?>"
                                        class="btn btn-info btn-sm"
                                        title="Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>

                                    <a href="index.php?page=edituser&id=<?= $data['iduser']; ?>"
                                        class="btn btn-warning btn-sm"
                                        title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="proses/prosesuser.php?aksi=hapus"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus user ini?')">

                                        <input type="hidden"
                                            name="iduser"
                                            value="<?= $data['iduser']; ?>">

                                        <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>
        </div>

    </div>
</section>
