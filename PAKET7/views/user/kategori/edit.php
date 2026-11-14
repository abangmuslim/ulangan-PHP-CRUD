
<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$queryedit = mysqli_query($koneksi, "
    SELECT * FROM kategori
    WHERE idkategori = '$id'
");

$dataedit = mysqli_fetch_assoc($queryedit);

if (!$dataedit) {
    echo "<script>window.location='index.php?page=kategori';</script>";
    exit;
}

$query = mysqli_query($koneksi, "
    SELECT
        k.idkategori,
        k.namakategori,
        COUNT(o.idobat) AS jumlahobat
    FROM kategori k
    LEFT JOIN obat o ON k.idkategori = o.idkategori
    GROUP BY k.idkategori, k.namakategori
    ORDER BY k.idkategori DESC
");

$total = mysqli_num_rows($query);
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">

            <div class="col-sm-6">
                <h1 class="mb-0">
                    <i class="bi bi-collection-fill"></i>
                    Data Kategori Obat
                </h1>
            </div>

            <div class="col-sm-6 text-sm-end">
                <span class="btn btn-outline-primary">
                    <i class="bi bi-collection-fill me-1"></i>
                    Total Kategori:
                    <strong><?= $total; ?></strong>
                </span>
            </div>

        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <div class="row">

            <!-- FORM EDIT KATEGORI -->
            <div class="col-md-4">

                <div class="card card-warning card-outline">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-pencil-square me-1"></i>
                            Edit Kategori
                        </h3>
                    </div>

                    <form action="index.php" method="POST">

                        <input type="hidden" name="page" value="proseskategori">
                        <input type="hidden" name="aksi" value="ubah">
                        <input type="hidden" name="idkategori" value="<?= $dataedit['idkategori']; ?>">

                        <?= csrfField(); ?>

                        <div class="card-body">

                            <div class="mb-2">

                                <label for="namakategori" class="form-label">
                                    Nama Kategori
                                </label>

                                <input type="text"
                                    name="namakategori"
                                    id="namakategori"
                                    class="form-control form-control-sm"
                                    value="<?= e($dataedit['namakategori']); ?>"
                                    placeholder="Masukkan nama kategori"
                                    maxlength="100"
                                    required>

                            </div>

                            <div class="alert alert-light border mt-3 mb-0">
                                <i class="bi bi-info-circle text-primary me-1"></i>
                                ID Kategori:
                                <strong><?= $dataedit['idkategori']; ?></strong>
                            </div>

                        </div>

                        <div class="card-footer text-end">

                            <a href="index.php?page=kategori"
                                class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i>
                                Batal
                            </a>

                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-save"></i>
                                Update
                            </button>

                        </div>

                    </form>

                </div>

            </div>

            <!-- DAFTAR KATEGORI -->
            <div class="col-md-8">

                <div class="card card-primary card-outline">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-list-ul me-1"></i>
                            Daftar Kategori Obat
                        </h3>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table id="example1"
                                class="table table-bordered table-hover table-striped table-sm align-middle">

                                <thead>
                                    <tr>
                                        <th width="8%" class="text-center">No</th>
                                        <th>Nama Kategori</th>
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
                                                    <?= e($data['namakategori']); ?>
                                                </td>

                                                <td class="text-center">
                                                    <span class="badge bg-info">
                                                        <?= $data['jumlahobat']; ?> Obat
                                                    </span>
                                                </td>

                                                <td class="text-center">

                                                    <a href="index.php?page=showkategori&id=<?= $data['idkategori']; ?>"
                                                        class="btn btn-info btn-sm"
                                                        title="Detail">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>

                                                    <a href="index.php?page=editkategori&id=<?= $data['idkategori']; ?>"
                                                        class="btn btn-warning btn-sm"
                                                        title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    <form action="index.php"
                                                        method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">

                                                        <input type="hidden" name="page" value="proseskategori">
                                                        <input type="hidden" name="aksi" value="hapus">
                                                        <input type="hidden" name="idkategori" value="<?= $data['idkategori']; ?>">

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
                                                Belum ada data kategori obat.
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
