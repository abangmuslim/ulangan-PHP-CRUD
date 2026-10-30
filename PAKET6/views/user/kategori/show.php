
<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = mysqli_query($koneksi, "
    SELECT
        k.idkategori,
        k.namakategori,
        COUNT(o.idobat) AS jumlahobat
    FROM kategori k
    LEFT JOIN obat o ON k.idkategori = o.idkategori
    WHERE k.idkategori = '$id'
    GROUP BY k.idkategori, k.namakategori
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>window.location='index.php?page=kategori';</script>";
    exit;
}
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">

            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="bi bi-grid-fill"></i>
                    Detail Kategori Obat
                </h1>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="index.php?page=dashboarduser">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="index.php?page=kategori">Kategori Obat</a>
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
                    Informasi Kategori Obat
                </h3>
            </div>

            <div class="card-body">

                <div class="row">

                    <!-- KOLOM KIRI -->
                    <div class="col-md-4">

                        <div class="border rounded text-center p-4">

                            <i class="bi bi-folder-fill text-primary"
                                style="font-size: 100px;"></i>

                            <h4 class="mt-3">
                                <?= htmlspecialchars($data['namakategori']); ?>
                            </h4>

                            <span class="badge bg-primary">
                                Kategori Obat
                            </span>

                        </div>

                    </div>

                    <!-- KOLOM KANAN -->
                    <div class="col-md-8">

                        <table class="table table-bordered">

                            <tr>
                                <th width="35%">ID Kategori</th>
                                <td><?= $data['idkategori']; ?></td>
                            </tr>

                            <tr>
                                <th>Nama Kategori</th>
                                <td>
                                    <?= htmlspecialchars($data['namakategori']); ?>
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
                            Kategori ini digunakan untuk mengelompokkan
                            obat berdasarkan jenisnya.
                        </div>

                    </div>

                </div>

            </div>

            <div class="card-footer">

                <a href="index.php?page=kategori"
                    class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>

                <a href="index.php?page=editkategori&id=<?= $data['idkategori']; ?>"
                    class="btn btn-warning btn-sm float-end">
                    <i class="bi bi-pencil-square"></i>
                    Edit Kategori
                </a>

            </div>

        </div>

    </div>
</div>
