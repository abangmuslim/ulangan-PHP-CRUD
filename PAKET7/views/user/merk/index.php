
<?php
$query = mysqli_query($koneksi, "
    SELECT
        m.idmerk,
        m.namamerk,
        COUNT(o.idobat) AS jumlahobat
    FROM merk m
    LEFT JOIN obat o ON m.idmerk = o.idmerk
    GROUP BY m.idmerk, m.namamerk
    ORDER BY m.idmerk DESC
");

$total = mysqli_num_rows($query);
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">

            <div class="col-sm-6">
                <h1 class="mb-0">
                    <i class="bi bi-tags-fill"></i>
                    Data Merk Obat
                </h1>
            </div>

            <div class="col-sm-6 text-sm-end">
                <span class="btn btn-outline-primary">
                    <i class="bi bi-tags-fill me-1"></i>
                    Total Merk:
                    <strong><?= $total; ?></strong>
                </span>
            </div>

        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <div class="row">

            <!-- FORM TAMBAH MERK -->
            <div class="col-md-4">

                <div class="card card-success card-outline">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-plus-circle me-1"></i>
                            Tambah Merk
                        </h3>
                    </div>

                    <form action="index.php" method="POST">

                        <input type="hidden" name="page" value="prosesmerk">
                        <input type="hidden" name="aksi" value="tambah">

                        <?= csrfField(); ?>

                        <div class="card-body">

                            <div class="mb-2">
                                <label for="namamerk" class="form-label">
                                    Nama Merk
                                </label>

                                <input type="text"
                                    name="namamerk"
                                    id="namamerk"
                                    class="form-control form-control-sm"
                                    placeholder="Masukkan nama merk"
                                    maxlength="100"
                                    required>
                            </div>

                            <div class="alert alert-light border mt-3 mb-0">
                                <i class="bi bi-info-circle text-primary me-1"></i>
                                Masukkan nama merek obat yang akan ditambahkan.
                            </div>

                        </div>

                        <div class="card-footer text-end">

                            <button type="reset" class="btn btn-warning btn-sm">
                                <i class="bi bi-arrow-counterclockwise"></i>
                                Reset
                            </button>

                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-save"></i>
                                Simpan
                            </button>

                        </div>

                    </form>

                </div>

            </div>

            <!-- DAFTAR MERK -->
            <div class="col-md-8">

                <div class="card card-primary card-outline">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-list-ul me-1"></i>
                            Daftar Merk Obat
                        </h3>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table id="example1"
                                class="table table-bordered table-hover table-striped table-sm align-middle">

                                <thead>
                                    <tr>
                                        <th width="8%" class="text-center">No</th>
                                        <th>Nama Merk</th>
                                        <th width="18%" class="text-center">Jumlah Obat</th>
                                        <th width="20%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    $no = 1;

                                    if ($total > 0) {
                                        while ($data = mysqli_fetch_assoc($query)) {
                                    ?>

                                            <tr>

                                                <td class="text-center">
                                                    <?= $no++; ?>
                                                </td>

                                                <td>
                                                    <?= e($data['namamerk']); ?>
                                                </td>

                                                <td class="text-center">
                                                    <span class="badge bg-info">
                                                        <?= $data['jumlahobat']; ?> Obat
                                                    </span>
                                                </td>

                                                <td class="text-center">

                                                    <a href="index.php?page=showmerk&id=<?= $data['idmerk']; ?>"
                                                        class="btn btn-info btn-sm"
                                                        title="Detail">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>

                                                    <a href="index.php?page=editmerk&id=<?= $data['idmerk']; ?>"
                                                        class="btn btn-warning btn-sm"
                                                        title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    <form action="index.php"
                                                        method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus merk ini?')">

                                                        <input type="hidden" name="page" value="prosesmerk">
                                                        <input type="hidden" name="aksi" value="hapus">
                                                        <input type="hidden" name="idmerk" value="<?= $data['idmerk']; ?>">

                                                        <?= csrfField(); ?>

                                                        <button type="submit"
                                                            class="btn btn-danger btn-sm"
                                                            title="Hapus">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>

                                                    </form>

                                                </td>

                                            </tr>

                                        <?php
                                        }
                                    } else {
                                        ?>

                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                Belum ada data merk obat.
                                            </td>
                                        </tr>

                                    <?php } ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>
