
<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = mysqli_query($koneksi, "
    SELECT
        m.idmerk,
        m.namamerk,
        COUNT(o.idobat) AS jumlahobat
    FROM merk m
    LEFT JOIN obat o ON m.idmerk = o.idmerk
    WHERE m.idmerk = '$id'
    GROUP BY m.idmerk, m.namamerk
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>window.location='index.php?page=merk';</script>";
    exit;
}
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">

            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="bi bi-tags-fill"></i>
                    Detail Merk Obat
                </h1>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="index.php?page=dashboarduser">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="index.php?page=merk">Merk Obat</a>
                    </li>
                    <li class="breadcrumb-item active">Detail</li>
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
                    Informasi Merk Obat
                </h3>
            </div>

            <div class="card-body">

                <div class="row">

                    <!-- KOLOM KIRI -->
                    <div class="col-md-4">

                        <div class="border rounded text-center p-4">

                            <i class="bi bi-tags-fill text-primary"
                                style="font-size: 100px;"></i>

                            <h4 class="mt-3">
                                <?= e($data['namamerk']); ?>
                            </h4>

                            <span class="badge bg-primary">
                                Merk Obat
                            </span>

                        </div>

                    </div>

                    <!-- KOLOM KANAN -->
                    <div class="col-md-8">

                        <table class="table table-bordered">

                            <tr>
                                <th width="35%">ID Merk</th>
                                <td><?= $data['idmerk']; ?></td>
                            </tr>

                            <tr>
                                <th>Nama Merk</th>
                                <td>
                                    <?= e($data['namamerk']); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Jumlah Obat</th>
                                <td>
                                    <span class="badge bg-info">
                                        <?= $data['jumlahobat']; ?> Obat
                                    </span>
                                </td>
                            </tr>

                        </table>

                        <div class="alert alert-light border">
                            <i class="bi bi-info-circle text-primary me-1"></i>
                            Data merk ini digunakan sebagai identitas
                            merek pada data obat.
                        </div>

                    </div>

                </div>

            </div>

            <div class="card-footer">

                <a href="index.php?page=merk"
                    class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>

                <a href="index.php?page=editmerk&id=<?= $data['idmerk']; ?>"
                    class="btn btn-warning btn-sm float-end">
                    <i class="bi bi-pencil-square"></i>
                    Edit Merk
                </a>

            </div>

        </div>

    </div>
</div>
