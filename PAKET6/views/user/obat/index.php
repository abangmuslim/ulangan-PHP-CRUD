
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

$totalobat = mysqli_num_rows($query);
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">

            <div class="col-sm-6">
                <h1 class="mb-0">
                    <i class="fas fa-capsules"></i> Data Obat
                </h1>
            </div>

            <div class="col-sm-6">
                <div class="d-flex justify-content-end align-items-center">

                    <span class="btn btn-outline-primary me-2">
                        <i class="fas fa-capsules"></i>
                        Total Obat:
                        <strong><?= $totalobat; ?></strong>
                    </span>

                    <a href="index.php?page=createobat"
                        class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Obat
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
                    Manajemen Obat Sistem
                </h3>
            </div>

            <div class="card-body">

                <table id="example1"
                    class="table table-bordered table-hover table-striped">

                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Foto</th>
                            <th>Nama Obat</th>
                            <th>Merk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
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

                            $pathfoto = "assets/images/obat/" . $foto;
                        ?>

                            <tr>

                                <td>
                                    <?= $no++; ?>
                                </td>

                                <td class="text-center">

                                    <?php if (
                                        !empty($data['foto']) &&
                                        file_exists($pathfoto)
                                    ) { ?>

                                        <img
                                            src="<?= htmlspecialchars($pathfoto); ?>"
                                            class="img-circle elevation-2"
                                            width="50"
                                            height="50"
                                            style="object-fit: cover;">

                                    <?php } else { ?>

                                        <i class="fas fa-capsules text-secondary"
                                            style="font-size: 30px;"></i>

                                    <?php } ?>

                                </td>

                                <td>
                                    <?= htmlspecialchars($data['namaobat']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['namamerk']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['namakategori']); ?>
                                </td>

                                <td>
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

                                <td>

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

                        <?php } ?>

                    </tbody>

                </table>

            </div>
        </div>

    </div>
</section>
