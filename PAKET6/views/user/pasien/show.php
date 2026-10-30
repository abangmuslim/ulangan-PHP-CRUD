
<?php
$id = $_GET['id'] ?? 0;
$id = (int) $id;

$query = mysqli_query($koneksi, "SELECT * FROM pasien WHERE idpasien='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>window.location='index.php?page=pasien';</script>";
    exit;
}

$foto = empty($data['foto'])
    ? 'default.png'
    : $data['foto'];
?>

<style>
    .foto-pasien {
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
                    <i class="fas fa-user"></i>
                    Detail Pasien
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
                        <a href="index.php?page=pasien">
                            Pasien
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

            <!-- FOTO PASIEN -->
            <div class="col-md-4">

                <div class="card card-primary card-outline">

                    <div class="card-header text-center">
                        <h3 class="card-title float-none">
                            <i class="fas fa-image"></i>
                            Foto Profil
                        </h3>
                    </div>

                    <div class="card-body box-profile">

                        <div class="text-center">

                            <img
                                src="assets/images/pasien/<?= htmlspecialchars($foto); ?>"
                                class="foto-pasien"
                                alt="Foto Pasien">

                        </div>

                        <h3 class="profile-username text-center mt-3">
                            <?= htmlspecialchars($data['namapasien']); ?>
                        </h3>

                        <p class="text-muted text-center">
                            @<?= htmlspecialchars($data['username'] ?? '-'); ?>
                        </p>

                        <div class="text-center">

                            <span class="badge bg-primary p-2">
                                <i class="fas fa-user-injured"></i>
                                Pasien
                            </span>

                        </div>

                    </div>

                </div>

            </div>

            <!-- INFORMASI PASIEN -->
            <div class="col-md-8">

                <div class="card card-secondary card-outline">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle"></i>
                            Informasi Pasien
                        </h3>
                    </div>

                    <div class="card-body">

                        <table class="table table-bordered">

                            <tr>
                                <th width="30%">
                                    ID Pasien
                                </th>

                                <td>
                                    <?= $data['idpasien']; ?>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Nama Pasien
                                </th>

                                <td>
                                    <?= htmlspecialchars($data['namapasien']); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Username
                                </th>

                                <td>
                                    <?= htmlspecialchars($data['username'] ?? '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    No HP
                                </th>

                                <td>
                                    <?= htmlspecialchars($data['nohp'] ?? '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Alamat
                                </th>

                                <td>
                                    <?= nl2br(htmlspecialchars($data['alamat'] ?? '-')); ?>
                                </td>
                            </tr>

                        </table>

                    </div>

                    <div class="card-footer text-end">

                        <a href="index.php?page=pasien"
                            class="btn btn-secondary">

                            <i class="fas fa-arrow-left"></i>
                            Kembali

                        </a>

                        <a href="index.php?page=editpasien&id=<?= $data['idpasien']; ?>"
                            class="btn btn-warning">

                            <i class="fas fa-edit"></i>
                            Edit Pasien

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>
