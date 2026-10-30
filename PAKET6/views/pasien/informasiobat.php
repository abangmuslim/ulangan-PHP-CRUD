
<?php
// Mengambil data obat beserta merek dan kategori
$sql = "SELECT
            o.idobat,
            o.namaobat,
            o.harga,
            o.stok,
            o.foto,
            m.namamerk,
            k.namakategori
        FROM obat o
        LEFT JOIN merk m ON o.idmerk = m.idmerk
        LEFT JOIN kategori k ON o.idkategori = k.idkategori
        ORDER BY o.namaobat ASC";

$result = mysqli_query($koneksi, $sql);
?>

<div class="container-fluid">

    <!-- Judul Halaman -->
    <div class="mb-4">
        <h3 class="fw-bold mb-1">Informasi Obat</h3>
        <p class="text-muted mb-0">
            Lihat informasi obat, kategori, merek, harga, dan ketersediaan stok.
        </p>
    </div>

    <!-- Ringkasan Obat -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="bg-success bg-opacity-10 text-success rounded p-3 me-3">
                    <i class="bi bi-capsule fs-3"></i>
                </div>

                <div>
                    <h6 class="text-muted mb-1">Jumlah Jenis Obat</h6>
                    <h4 class="fw-bold mb-0">
                        <?= mysqli_num_rows($result); ?> Jenis Obat
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Informasi Obat -->
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-capsules text-success"></i>
                Daftar Informasi Obat
            </h5>
        </div>

        <div class="card-body">

            <?php if (mysqli_num_rows($result) > 0) : ?>

                <div class="row g-3">

                    <?php while ($data = mysqli_fetch_assoc($result)) : ?>

                        <div class="col-12">

                            <div class="card border shadow-sm">
                                <div class="card-body p-3">

                                    <div class="d-flex align-items-center">

                                        <!-- Foto Obat -->
                                        <div class="me-3 flex-shrink-0">
                                            <?php if (!empty($data['foto'])) : ?>

                                                <img
                                                    src="assets/images/obat/<?= htmlspecialchars($data['foto']); ?>"
                                                    alt="<?= htmlspecialchars($data['namaobat']); ?>"
                                                    class="rounded border"
                                                    style="width:60px;height:60px;object-fit:cover;"
                                                >

                                            <?php else : ?>

                                                <div class="bg-light rounded border d-flex align-items-center justify-content-center"
                                                     style="width:60px;height:60px;">
                                                    <i class="bi bi-capsule text-secondary fs-2"></i>
                                                </div>

                                            <?php endif; ?>
                                        </div>

                                        <!-- Informasi Obat -->
                                        <div class="flex-grow-1">

                                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">

                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        <?= htmlspecialchars($data['namaobat']); ?>
                                                    </h6>

                                                    <small class="text-muted">
                                                        <i class="bi bi-tag"></i>
                                                        <?= htmlspecialchars($data['namamerk'] ?? '-'); ?>
                                                        <span class="mx-1">|</span>
                                                        <i class="bi bi-grid"></i>
                                                        <?= htmlspecialchars($data['namakategori'] ?? '-'); ?>
                                                    </small>
                                                </div>

                                                <div class="text-md-end">
                                                    <div class="fw-bold text-primary">
                                                        Rp <?= number_format($data['harga'], 0, ',', '.'); ?>
                                                    </div>

                                                    <small class="text-muted">
                                                        Harga per satuan
                                                    </small>
                                                </div>

                                            </div>

                                            <hr class="my-2">

                                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                                                <small class="text-muted">
                                                    <i class="bi bi-box-seam"></i>
                                                    Stok tersedia:
                                                    <strong><?= (int) $data['stok']; ?></strong>
                                                </small>

                                                <?php if ($data['stok'] > 0) : ?>
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i>
                                                        Tersedia
                                                    </span>
                                                <?php else : ?>
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-x-circle"></i>
                                                        Stok Habis
                                                    </span>
                                                <?php endif; ?>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>

                    <?php endwhile; ?>

                </div>

                <div class="alert alert-info mt-4 mb-0">
                    <i class="bi bi-info-circle"></i>
                    <strong>Informasi:</strong>
                    Daftar ini merupakan informasi persediaan obat.
                    Penggunaan obat harus mengikuti petunjuk dokter atau tenaga
                    kesehatan yang berwenang.
                </div>

            <?php else : ?>

                <div class="text-center py-5">
                    <i class="bi bi-capsule-pill text-muted" style="font-size:55px;"></i>

                    <h5 class="fw-bold mt-3">Belum Ada Data Obat</h5>

                    <p class="text-muted mb-0">
                        Informasi obat belum tersedia.
                    </p>
                </div>

            <?php endif; ?>

        </div>
    </div>

</div>
