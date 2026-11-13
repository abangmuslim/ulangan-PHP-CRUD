
<?php
$iduser = (int) ($_GET['id'] ?? 0);

$query = mysqli_prepare($koneksi, "SELECT * FROM user WHERE iduser = ?");
mysqli_stmt_bind_param($query, "i", $iduser);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo '<div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-1"></i>
            Data user tidak ditemukan.
          </div>';
    return;
}

$fotouser = !empty($data['foto'])
    ? 'assets/images/user/' . $data['foto']
    : 'assets/images/user/default.png';
?>

<style>
    /* CARD INFORMASI */
    .card-info-detail {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        margin-bottom: 0;
    }

    .card-info-detail .card-body {
        padding: 20px;
    }

    /* LABEL DAN NILAI SATU BARIS */
    .detail-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 18px;
    }

    .label-detail {
        width: 110px;
        min-width: 110px;
        color: #6c757d;
        font-size: 15px;
        margin: 0;
    }

    .nilai-detail {
        flex: 1;
        min-width: 0;
        font-size: 15px;
        font-weight: 600;
        color: #212529;
        margin: 0;
        overflow-wrap: anywhere;
    }

    /* PANEL FOTO */
    .panel-foto {
        height: 100%;
        border-left: 1px solid #dee2e6;
        padding-left: 20px;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .foto-detail {
        width: 400px;
        max-width: 100%;
        height: 450px;
        object-fit: cover;
        border: 2px solid #dee2e6;
        border-radius: 5px;
        padding: 4px;
        background: #fff;
        display: block;
    }

    /* TOMBOL */
    .footer-info-detail {
        padding: 15px 20px;
        border-top: 1px solid #dee2e6;
        background: #f8f9fa;
        border-radius: 0 0 5px 5px;
    }

    /* RESPONSIVE */
    @media (max-width: 767px) {
        .panel-foto {
            border-left: none;
            border-top: 1px solid #dee2e6;
            padding: 20px 0 0;
            margin-top: 20px;
        }

        .foto-detail {
            width: 100%;
            max-width: 400px;
            height: auto;
            aspect-ratio: 400 / 450;
        }

        .label-detail {
            width: 100px;
            min-width: 100px;
            font-size: 14px;
        }

        .nilai-detail {
            font-size: 14px;
        }
    }
</style>

<section class="content">
    <div class="container-fluid">

        <div class="card card-primary card-outline shadow-sm">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user me-1"></i>
                    Detail User
                </h3>
            </div>

            <div class="card-body">

                <div class="row align-items-stretch">

                    <!-- INFORMASI USER -->
                    <div class="col-md-7">

                        <div class="card card-info-detail">

                            <div class="card-body">

                                <!-- ID USER -->
                                <div class="detail-item">
                                    <div class="label-detail">
                                        ID User
                                    </div>

                                    <div class="nilai-detail">
                                        <?= e($data['iduser']) ?>
                                    </div>
                                </div>

                                <!-- USERNAME -->
                                <div class="detail-item">
                                    <div class="label-detail">
                                        Username
                                    </div>

                                    <div class="nilai-detail">
                                        <?= e($data['username']) ?>
                                    </div>
                                </div>

                                <!-- NOMOR HP -->
                                <div class="detail-item">
                                    <div class="label-detail">
                                        Nomor HP
                                    </div>

                                    <div class="nilai-detail">
                                        <?= !empty($data['nohp'])
                                            ? e($data['nohp'])
                                            : '-' ?>
                                    </div>
                                </div>

                                <!-- ALAMAT -->
                                <div class="detail-item">
                                    <div class="label-detail">
                                        Alamat
                                    </div>

                                    <div class="nilai-detail">
                                        <?= !empty($data['alamat'])
                                            ? nl2br(e($data['alamat']))
                                            : '-' ?>
                                    </div>
                                </div>

                                <!-- NAMA USER -->
                                <div class="detail-item">
                                    <div class="label-detail">
                                        Nama User
                                    </div>

                                    <div class="nilai-detail">
                                        <?= e($data['namauser']) ?>
                                    </div>
                                </div>

                                <!-- LEVEL USER -->
                                <div class="detail-item mb-0">
                                    <div class="label-detail">
                                        Level User
                                    </div>

                                    <div class="nilai-detail">

                                        <?php if ($data['level'] == 'admin') { ?>

                                            <span class="badge bg-danger">
                                                <i class="fas fa-user-shield me-1"></i>
                                                Admin
                                            </span>

                                        <?php } else { ?>

                                            <span class="badge bg-success">
                                                <i class="fas fa-user-tie me-1"></i>
                                                Petugas
                                            </span>

                                        <?php } ?>

                                    </div>
                                </div>

                            </div>

                            <!-- TOMBOL AKSI -->
                            <div class="footer-info-detail">

                                <a href="index.php?page=edituser&id=<?= (int) $data['iduser'] ?>"
                                   class="btn btn-warning">
                                    <i class="fas fa-edit me-1"></i>
                                    Edit User
                                </a>

                                <a href="index.php?page=user"
                                   class="btn btn-dark">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Kembali
                                </a>

                            </div>

                        </div>

                    </div>

                    <!-- FOTO USER -->
                    <div class="col-md-5">

                        <div class="panel-foto">

                            <img src="<?= e($fotouser) ?>"
                                 class="foto-detail"
                                 alt="Foto User">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>
