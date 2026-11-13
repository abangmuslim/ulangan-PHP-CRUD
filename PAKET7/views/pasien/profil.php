<?php
// Ambil data pasien yang sedang login
$idpasien = (int) $_SESSION['idpasien'];

$queryProfil = mysqli_prepare($koneksi, "
    SELECT *
    FROM pasien
    WHERE idpasien = ?
");

mysqli_stmt_bind_param($queryProfil, "i", $idpasien);
mysqli_stmt_execute($queryProfil);
$resultProfil = mysqli_stmt_get_result($queryProfil);
$pasien = mysqli_fetch_assoc($resultProfil);

if (!$pasien) {
    echo "<div class='alert alert-danger'>Data profil pasien tidak ditemukan.</div>";
    return;
}

$fotoPasien = !empty($pasien['foto']) ? $pasien['foto'] : 'default.png';
?>

<!-- Header Halaman -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Profil Pasien</h1>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="index.php?page=dashboardpasien">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Profil Pasien</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Konten Halaman -->
<section class="content">
    <div class="container-fluid">

        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'sukses') { ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-check-circle mr-2"></i>
                Profil berhasil diperbarui.
            </div>
        <?php } ?>

        <div class="row">

            <!-- Kartu Identitas Pasien -->
            <div class="col-md-4">
                <div class="card card-success card-outline">
                    <div class="card-body box-profile text-center">

                        <img
                            class="profile-user-img img-fluid img-circle"
                            src="assets/images/pasien/<?= e($fotoPasien) ?>"
                            alt="Foto Pasien"
                            style="width:130px;height:130px;object-fit:cover;"
                        >

                        <h3 class="profile-username text-center mt-3">
                            <?= e($pasien['namapasien']) ?>
                        </h3>

                        <p class="text-muted text-center">
                            Pasien Klinik Ahmadi
                        </p>

                        <span class="badge badge-success">
                            <i class="fas fa-user mr-1"></i> Pasien
                        </span>

                        <hr>

                        <a href="index.php?page=editprofilpasien" class="btn btn-success btn-block">
                            <i class="fas fa-edit mr-1"></i> Edit Profil
                        </a>

                    </div>
                </div>
            </div>

            <!-- Informasi Profil -->
            <div class="col-md-8">
                <div class="card card-success card-outline">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-id-card mr-2"></i>
                            Informasi Pasien
                        </h3>
                    </div>

                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">
                                ID Pasien
                            </div>
                            <div class="col-md-8 font-weight-bold">
                                <?= e($pasien['idpasien']) ?>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">
                                Nama Pasien
                            </div>
                            <div class="col-md-8 font-weight-bold">
                                <?= e($pasien['namapasien']) ?>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">
                                Username
                            </div>
                            <div class="col-md-8">
                                <?= e($pasien['username']) ?>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">
                                Nomor HP
                            </div>
                            <div class="col-md-8">
                                <?= !empty($pasien['nohp']) ? e($pasien['nohp']) : '-' ?>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-0">
                            <div class="col-md-4 text-muted">
                                Alamat
                            </div>
                            <div class="col-md-8">
                                <?= !empty($pasien['alamat']) ? nl2br(e($pasien['alamat'])) : '-' ?>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <a href="index.php?page=dashboardpasien" class="btn btn-default">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>

                        <a href="index.php?page=editprofilpasien" class="btn btn-success float-right">
                            <i class="fas fa-edit mr-1"></i> Ubah Profil
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>