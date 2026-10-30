
<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = mysqli_query($koneksi, "
    SELECT
        o.*,
        m.namamerk,
        k.namakategori
    FROM obat o
    INNER JOIN merk m ON o.idmerk = m.idmerk
    INNER JOIN kategori k ON o.idkategori = k.idkategori
    WHERE o.idobat = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>window.location='index.php?page=obat';</script>";
    exit;
}

$foto = $data['foto'];
$pathfoto = "assets/images/obat/" . $foto;
$fotoada = !empty($foto) && file_exists($pathfoto);
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">

            <div class="col-sm-6">
                <h1 class="m-0">Detail Obat</h1>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="index.php?page=dashboarduser">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="index.php?page=obat">Data Obat</a>
                    </li>
                    <li class="breadcrumb-item active">Detail Obat</li>
                </ol>
            </div>

        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <div class="card card-primary card-outline">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-info-circle me-1"></i>
                    Informasi Obat
                </h3>
            </div>

            <div class="card-body">

                <div class="row">

                    <!-- KOLOM KIRI: FOTO OBAT -->
                    <div class="col-md-4">

                        <div class="border rounded text-center p-3">

                            <?php if ($fotoada) { ?>

                                <img src="<?= $pathfoto; ?>"
                                    alt="Foto <?= htmlspecialchars($data['namaobat']); ?>"
                                    class="img-fluid rounded"
                                    style="width: 100%; max-width: 220px; height: 250px; object-fit: contain;">

                            <?php } else { ?>

                                <div class="d-flex align-items-center justify-content-center"
                                    style="height: 250px;">

                                    <i class="bi bi-capsule text-secondary"
                                        style="font-size: 110px;"></i>

                                </div>

                            <?php } ?>

                            <h4 class="mt-3">
                                <?= htmlspecialchars($data['namaobat']); ?>
                            </h4>

                            <span class="badge bg-primary">
                                <?= htmlspecialchars($data['namamerk']); ?>
                            </span>

                        </div>

                    </div>

                    <!-- KOLOM KANAN: INFORMASI OBAT -->
                    <div class="col-md-8">

                        <table class="table table-bordered">

                            <tr>
                                <th width="35%">ID Obat</th>
                                <td><?= $data['idobat']; ?></td>
                            </tr>

                            <tr>
                                <th>Nama Obat</th>
                                <td>
                                    <?= htmlspecialchars($data['namaobat']); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Merk</th>
                                <td>
                                    <span class="badge bg-primary">
                                        <?= htmlspecialchars($data['namamerk']); ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th>Kategori</th>
                                <td>
                                    <span class="badge bg-info">
                                        <?= htmlspecialchars($data['namakategori']); ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th>Harga</th>
                                <td>
                                    <strong>
                                        Rp <?= number_format($data['harga'], 0, ',', '.'); ?>
                                    </strong>
                                </td>
                            </tr>

                            <tr>
                                <th>Stok</th>
                                <td>
                                    <?php if ($data['stok'] > 0) { ?>
                                        <span class="badge bg-success">
                                            <?= $data['stok']; ?> tersedia
                                        </span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger">
                                            Stok Habis
                                        </span>
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Tanggal Stok</th>
                                <td>
                                    <?php
                                    if (!empty($data['tanggalstok']) && $data['tanggalstok'] != '0000-00-00') {
                                        echo date('d-m-Y', strtotime($data['tanggalstok']));
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                            </tr>

                        </table>

                        <div class="alert alert-light border">
                            <i class="bi bi-info-circle text-primary me-1"></i>
                            Informasi obat ini digunakan sebagai data master
                            untuk transaksi kunjungan pasien.
                        </div>

                    </div>

                </div>

            </div>

            <div class="card-footer">

                <a href="index.php?page=obat" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>
                    Kembali
                </a>

                <a href="index.php?page=editobat&id=<?= $data['idobat']; ?>"
                    class="btn btn-warning btn-sm float-end">
                    <i class="bi bi-pencil-square me-1"></i>
                    Edit Obat
                </a>

            </div>

        </div>

    </div>
</div>
