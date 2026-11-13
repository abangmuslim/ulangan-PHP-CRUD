
<?php
// Ambil data dokter yang sedang login
$iddokter = (int) $_SESSION['iddokter'];

$queryProfil = mysqli_query($koneksi, "
    SELECT *
    FROM dokter
    WHERE iddokter = '$iddokter'
");

$dokter = mysqli_fetch_assoc($queryProfil);

if (!$dokter) {
    echo "<div class='alert alert-danger'>Data profil dokter tidak ditemukan.</div>";
    return;
}

$fotoDokter = !empty($dokter['foto']) ? $dokter['foto'] : 'default.png';
?>


<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Profil Dokter</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="index.php?page=dashboarddokter">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Profil Dokter</li>
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

            <!-- Kartu Identitas Dokter -->
            <div class="col-md-4">
                <div class="card card-success card-outline">
                    <div class="card-body box-profile text-center">

                        <img
                            class="profile-user-img img-fluid img-circle"
                            src="assets/images/dokter/<?= e($fotoDokter) ?>"
                            alt="Foto Dokter"
                            style="width: 130px; height: 130px; object-fit: cover;"
                        >

                        <h3 class="profile-username text-center mt-3">
                            <?= e($dokter['namadokter']) ?>
                        </h3>

                        <p class="text-muted text-center">
                            <?= e($dokter['spesialis']) ?>
                        </p>

                        <span class="badge badge-success">
                            <i class="fas fa-user-md mr-1"></i> Dokter
                        </span>

                        <hr>

                        <a href="index.php?page=editprofil" class="btn btn-success btn-block">
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
                            Informasi Dokter
                        </h3>
                    </div>

                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">
                                ID Dokter
                            </div>
                            <div class="col-md-8 font-weight-bold">
                                <?= e($dokter['iddokter']) ?>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">
                                Nama Dokter
                            </div>
                            <div class="col-md-8 font-weight-bold">
                                <?= e($dokter['namadokter']) ?>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">
                                Spesialis
                            </div>
                            <div class="col-md-8">
                                <?= e($dokter['spesialis']) ?>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">
                                Username
                            </div>
                            <div class="col-md-8">
                                <?= e($dokter['username']) ?>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">
                                Nomor HP
                            </div>
                            <div class="col-md-8">
                                <?= !empty($dokter['nohp']) ? e($dokter['nohp']) : '-' ?>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-0">
                            <div class="col-md-4 text-muted">
                                Alamat
                            </div>
                            <div class="col-md-8">
                                <?= !empty($dokter['alamat']) ? nl2br(e($dokter['alamat'])) : '-' ?>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <a href="index.php?page=dashboarddokter" class="btn btn-default">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>

                        <a href="index.php?page=editprofil" class="btn btn-success float-right">
                            <i class="fas fa-edit mr-1"></i> Ubah Profil
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

