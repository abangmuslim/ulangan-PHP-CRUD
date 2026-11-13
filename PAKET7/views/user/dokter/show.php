<?php
// ============================================
// AMBIL DATA DOKTER
// ============================================
$iddokter = (int) ($_GET['iddokter'] ?? 0);

$query = mysqli_prepare($koneksi, "SELECT * FROM dokter WHERE iddokter = ?");
mysqli_stmt_bind_param($query, "i", $iddokter);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo '<div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-1"></i>
            Data dokter tidak ditemukan.
          </div>';
    return;
}

$fotodokter = !empty($data['foto'])
    ? 'assets/images/dokter/' . $data['foto']
    : 'assets/images/dokter/default.png';
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
                    <i class="fas fa-user-md me-1"></i>
                    Detail Dokter
                </h3>
            </div>

            <div class="card-body">

                <div class="row align-items-stretch">

                    <!-- INFORMASI DOKTER -->
                    <div class="col-md-7">

                        <div class="card card-info-detail">

                            <div class="card-body">

                                <!-- ID DOKTER -->
                                <div class="detail-item">
                                    <div class="label-detail">
                                        ID Dokter
                                    </div>

                                    <div class="nilai-detail">
                                        <?= e($data['iddokter']) ?>
                                    </div>
                                </div>

                                <!-- USERNAME -->
                                <div class="detail-item">
                                    <div class="label-detail">
                                        Username
                                    </div>

                                    <div class="nilai-detail">
                                        <?= !empty($data['username'])
                                            ? e($data['username'])
                                            : '-' ?>
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

                                <!-- NAMA DOKTER -->
                                <div class="detail-item">
                                    <div class="label-detail">
                                        Nama Dokter
                                    </div>

                                    <div class="nilai-detail">
                                        <?= e($data['namadokter']) ?>
                                    </div>
                                </div>

                                <!-- SPESIALIS -->
                                <div class="detail-item mb-0">
                                    <div class="label-detail">
                                        Spesialis
                                    </div>

                                    <div class="nilai-detail">
                                        <?= e($data['spesialis']) ?>
                                    </div>
                                </div>

                            </div>

                            <!-- TOMBOL AKSI -->
                            <div class="footer-info-detail">

                                <a href="index.php?page=editdokter&iddokter=<?= (int) $data['iddokter'] ?>"
                                   class="btn btn-warning">
                                    <i class="fas fa-edit me-1"></i>
                                    Edit Dokter
                                </a>

                                <a href="index.php?page=dokter"
                                   class="btn btn-dark">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Kembali
                                </a>

                            </div>

                        </div>

                    </div>

                    <!-- FOTO DOKTER -->
                    <div class="col-md-5">

                        <div class="panel-foto">

                            <img src="<?= e($fotodokter) ?>"
                                 class="foto-detail"
                                 alt="Foto Dokter">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>