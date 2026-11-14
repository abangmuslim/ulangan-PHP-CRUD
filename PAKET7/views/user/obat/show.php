
<?php
// ============================================
// AMBIL DATA OBAT
// ============================================

$idobat = (int) ($_GET['idobat'] ?? 0);

$query = mysqli_prepare($koneksi, "
    SELECT
        o.*,
        m.namamerk,
        k.namakategori
    FROM obat o
    LEFT JOIN merk m ON o.idmerk = m.idmerk
    LEFT JOIN kategori k ON o.idkategori = k.idkategori
    WHERE o.idobat = ?
");

mysqli_stmt_bind_param($query, "i", $idobat);
mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo '<div class="alert alert-danger">
            <i class="bi bi-exclamation-circle me-1"></i>
            Data obat tidak ditemukan.
          </div>';
    return;
}

// ============================================
// FOTO OBAT
// ============================================

$fotoObat = !empty($data['foto'])
    ? 'assets/images/obat/' . $data['foto']
    : 'assets/images/obat/default.png';
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

    /* LABEL DAN NILAI */
    .detail-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 18px;
    }

    .label-detail {
        width: 125px;
        min-width: 125px;
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
        object-fit: contain;
        border: 2px solid #dee2e6;
        border-radius: 5px;
        padding: 8px;
        background: #fff;
        display: block;
    }

    /* FOOTER */
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
            width: 110px;
            min-width: 110px;
            font-size: 14px;
        }

        .nilai-detail {
            font-size: 14px;
        }
    }
</style>

<div class="card card-primary card-outline shadow-sm">

    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-capsule me-1"></i>
            Detail Obat
        </h3>
    </div>

    <div class="card-body">

        <div class="row align-items-stretch">

            <!-- INFORMASI OBAT -->
            <div class="col-md-7">

                <div class="card card-info-detail">

                    <div class="card-body">

                        <!-- ID OBAT -->
                        <div class="detail-item">
                            <div class="label-detail">
                                ID Obat
                            </div>

                            <div class="nilai-detail">
                                <?= e($data['idobat']); ?>
                            </div>
                        </div>

                        <!-- NAMA OBAT -->
                        <div class="detail-item">
                            <div class="label-detail">
                                Nama Obat
                            </div>

                            <div class="nilai-detail">
                                <?= e($data['namaobat']); ?>
                            </div>
                        </div>

                        <!-- MERK -->
                        <div class="detail-item">
                            <div class="label-detail">
                                Merk
                            </div>

                            <div class="nilai-detail">
                                <?= !empty($data['namamerk'])
                                    ? e($data['namamerk'])
                                    : '-' ?>
                            </div>
                        </div>

                        <!-- KATEGORI -->
                        <div class="detail-item">
                            <div class="label-detail">
                                Kategori
                            </div>

                            <div class="nilai-detail">
                                <?= !empty($data['namakategori'])
                                    ? e($data['namakategori'])
                                    : '-' ?>
                            </div>
                        </div>

                        <!-- HARGA -->
                        <div class="detail-item">
                            <div class="label-detail">
                                Harga
                            </div>

                            <div class="nilai-detail">
                                Rp <?= number_format($data['harga'], 0, ',', '.'); ?>
                            </div>
                        </div>

                        <!-- STOK -->
                        <div class="detail-item">
                            <div class="label-detail">
                                Stok
                            </div>

                            <div class="nilai-detail">

                                <?php if ($data['stok'] <= 0) { ?>

                                    <span class="badge bg-danger">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        Stok Habis
                                    </span>

                                <?php } else { ?>

                                    <span class="badge bg-success">
                                        <?= number_format($data['stok'], 0, ',', '.'); ?> Unit
                                    </span>

                                <?php } ?>

                            </div>
                        </div>

                        <!-- TANGGAL STOK -->
                        <div class="detail-item mb-0">
                            <div class="label-detail">
                                Tanggal Stok
                            </div>

                            <div class="nilai-detail">
                                <?= !empty($data['tanggalstok'])
                                    ? date('d-m-Y', strtotime($data['tanggalstok']))
                                    : '-' ?>
                            </div>
                        </div>

                    </div>

                    <!-- TOMBOL AKSI -->
                    <div class="footer-info-detail">

                        <a href="index.php?page=editobat&idobat=<?= (int) $data['idobat']; ?>"
                           class="btn btn-warning">
                            <i class="bi bi-pencil-square me-1"></i>
                            Edit Obat
                        </a>

                        <a href="index.php?page=obat"
                           class="btn btn-dark">
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali
                        </a>

                    </div>

                </div>

            </div>

            <!-- FOTO OBAT -->
            <div class="col-md-5">

                <div class="panel-foto">

                    <div class="w-100 text-center">

                        <h5 class="mb-3">
                            <i class="bi bi-image me-1"></i>
                            Foto Obat
                        </h5>

                        <img src="<?= e($fotoObat); ?>"
                             class="foto-detail"
                             alt="Foto <?= e($data['namaobat']); ?>"
                             onerror="this.src='assets/images/obat/default.png'">

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
