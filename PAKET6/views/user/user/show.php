
<?php

$id = $_GET['id'] ?? 0;

$query = mysqli_query($koneksi, "SELECT * FROM user WHERE iduser='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>window.location='index.php?page=user';</script>";
    exit;
}

$foto = empty($data['foto'])
    ? 'default.png'
    : $data['foto'];
?>

<style>
    .foto-user {
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
                    Detail User
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
                        <a href="index.php?page=user">
                            User
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

            <!-- FOTO USER -->
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
                                src="assets/images/user/<?= htmlspecialchars($foto); ?>"
                                class="foto-user"
                                alt="Foto User">

                        </div>

                        <h3 class="profile-username text-center mt-3">
                            <?= htmlspecialchars($data['namauser']); ?>
                        </h3>

                        <p class="text-muted text-center">
                            @<?= htmlspecialchars($data['username']); ?>
                        </p>

                        <div class="text-center">

                            <span class="badge bg-primary p-2">
                                <i class="fas fa-user-cog"></i>
                                User Sistem
                            </span>

                        </div>

                    </div>

                </div>

            </div>

            <!-- INFORMASI USER -->
            <div class="col-md-8">

                <div class="card card-secondary card-outline">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle"></i>
                            Informasi User
                        </h3>
                    </div>

                    <div class="card-body">

                        <table class="table table-bordered">

                            <tr>
                                <th width="30%">ID User</th>
                                <td>
                                    <?= $data['iduser']; ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Nama User</th>
                                <td>
                                    <?= htmlspecialchars($data['namauser']); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Username</th>
                                <td>
                                    <?= htmlspecialchars($data['username']); ?>
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

                        <a href="index.php?page=user"
                            class="btn btn-secondary">

                            <i class="fas fa-arrow-left"></i>
                            Kembali

                        </a>

                        <a href="index.php?page=edituser&id=<?= $data['iduser']; ?>"
                            class="btn btn-warning">

                            <i class="fas fa-edit"></i>
                            Edit User

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>
