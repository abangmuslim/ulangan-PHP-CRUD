
<?php
$query = mysqli_query($koneksi, "
    SELECT
        o.*,
        m.namamerk,
        k.namakategori
    FROM obat o
    INNER JOIN merk m ON o.idmerk = m.idmerk
    INNER JOIN kategori k ON o.idkategori = k.idkategori
    ORDER BY o.idobat DESC
");

$total = mysqli_num_rows($query);
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">

            <div class="col-sm-6">
                <h1 class="mb-0">
                    <i class="bi bi-capsule"></i>
                    Data Obat
                </h1>
            </div>

            <div class="col-sm-6">
                <div class="d-flex justify-content-end">
                    <span class="btn btn-outline-primary">
                        <i class="bi bi-capsule me-1"></i>
                        Total Obat:
                        <strong><?= $total; ?></strong>
                    </span>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <div class="card card-primary card-outline">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-list-ul me-1"></i>
                    Daftar Obat
                </h3>

                <div class="card-tools">
                    <a href="index.php?page=createobat" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>
                        Tambah Obat
                    </a>
                </div>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="example1"
                        class="table table-bordered table-hover table-striped table-sm align-middle">

                        <thead>
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="9%" class="text-center">Foto</th>
                                <th>Nama Obat</th>
                                <th>Merk</th>
                                <th>Kategori</th>
                                <th width="13%" class="text-end">Harga</th>
                                <th width="9%" class="text-center">Stok</th>
                                <th width="13%" class="text-center">Aksi</th>
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

                                        <td class="text-center">

                                            <?php
                                            $foto = $data['foto'];
                                            $pathfoto = "assets/images/obat/" . $foto;

                                            if (!empty($foto) && file_exists($pathfoto)) {
                                            ?>
                                                <img src="<?= $pathfoto; ?>"
                                                    alt="Foto Obat"
                                                    width="55"
                                                    height="60"
                                                    class="rounded border"
                                                    style="object-fit: cover;">
                                            <?php
                                            } else {
                                            ?>
                                                <i class="bi bi-capsule text-secondary"
                                                    style="font-size: 35px;"></i>
                                            <?php } ?>

                                        </td>

                                        <td>
                                            <strong>
                                                <?= htmlspecialchars($data['namaobat']); ?>
                                            </strong>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['namamerk']); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['namakategori']); ?>
                                        </td>

                                        <td class="text-end">
                                            Rp <?= number_format($data['harga'], 0, ',', '.'); ?>
                                        </td>

                                        <td class="text-center">

                                            <?php if ($data['stok'] > 0) { ?>
                                                <span class="badge bg-success">
                                                    <?= $data['stok']; ?>
                                                </span>
                                            <?php } else { ?>
                                                <span class="badge bg-danger">
                                                    Habis
                                                </span>
                                            <?php } ?>

                                        </td>

                                        <td class="text-center">

                                            <a href="index.php?page=showobat&id=<?= $data['idobat']; ?>"
                                                class="btn btn-info btn-sm"
                                                title="Detail">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>

                                            <a href="index.php?page=editobat&id=<?= $data['idobat']; ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <form action="proses/prosesobat.php?aksi=hapus"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus obat ini?')">

                                                <input type="hidden"
                                                    name="idobat"
                                                    value="<?= $data['idobat']; ?>">

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
                                    <td colspan="8" class="text-center text-muted">
                                        Belum ada data obat.
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
