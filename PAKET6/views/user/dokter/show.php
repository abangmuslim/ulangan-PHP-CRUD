
<?php

$id = $_GET['id'] ?? 0;
$id = (int) $id;

$query = mysqli_query($koneksi, "SELECT * FROM dokter WHERE iddokter='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>window.location='index.php?page=dokter';</script>";
    exit;
}

$foto = empty($data['foto'])
    ? 'default.png'
    : $data['foto'];
?>

<style>
    .foto-dokter {
        width: 190px;
        height: 250px;
        object-fit: cover;
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 4px;
        background: #fff;
    }
</style>

<section class="content-header">
    <div class="container-fluid">

        <div class="row mb-2">

            <div class="col-sm-6">
                <h1>
                    <i class="bi bi-person-badge"></i>
                    Detail Dokter
                </h1>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">

                    <li class="breadcrumb-item">
                        <a href="index.php?page=dashboarduser">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="index.php?page=dokter">
                            Dokter
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        Detail
                    </li>

                </ol>
            </div>

        </div>

    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="row">

            <!-- FOTO DOKTER -->
            <div class="col-md-4">

                <div class="card card-primary card-outline">

                    <div class="card-header text-center">
                        <h3 class="card-title float-none">
                            <i class="bi bi-image"></i>
                            Foto Profil
                        </h3>
                    </div>

                    <div class="card-body box-profile">

                        <div class="text-center">

                            <img
                                src="assets/images/dokter/<?= htmlspecialchars($foto); ?>"
                                class="foto-dokter"
                                alt="Foto Dokter">

                        </div>

                        <h3 class="profile-username text-center mt-3">
                            <?= htmlspecialchars($data['namadokter']); ?>
                        </h3>

                        <p class="text-muted text-center">
                            <?= htmlspecialchars($data['spesialis'] ?? 'Spesialis belum diisi'); ?>
                        </p>

                        <div class="text-center">

                            <span class="badge bg-primary p-2">
                                <i class="bi bi-person-badge"></i>
                                Dokter Rumah Sakit
                            </span>

                        </div>

                    </div>

                </div>

            </div>

            <!-- INFORMASI DOKTER -->
            <div class="col-md-8">

                <div class="card card-secondary card-outline">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-info-circle"></i>
                            Informasi Dokter
                        </h3>
                    </div>

                    <div class="card-body">

                        <table class="table table-bordered">

                            <tr>
                                <th width="30%">ID Dokter</th>
                                <td>
                                    <?= $data['iddokter']; ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Nama Dokter</th>
                                <td>
                                    <?= htmlspecialchars($data['namadokter']); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Spesialis</th>
                                <td>
                                    <?= htmlspecialchars($data['spesialis'] ?? '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>No HP</th>
                                <td>
                                    <?= htmlspecialchars($data['nohp'] ?? '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Alamat</th>
                                <td>
                                    <?= nl2br(htmlspecialchars($data['alamat'] ?? '-')); ?>
                                </td>
                            </tr>

                        </table>

                    </div>

                    <div class="card-footer text-end">

                        <a href="index.php?page=dokter"
                            class="btn btn-secondary">

                            <i class="bi bi-arrow-left"></i>
                            Kembali

                        </a>

                        <a href="index.php?page=editdokter&id=<?= $data['iddokter']; ?>"
                            class="btn btn-warning">

                            <i class="bi bi-pencil-square"></i>
                            Edit Dokter

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>
