
<?php
// ============================================
// DATA OBAT
// ============================================

$queryObat = mysqli_query($koneksi, "
    SELECT
        o.idobat,
        o.namaobat,
        o.harga,
        o.tanggalstok,
        o.stok,
        o.foto,
        m.namamerk,
        k.namakategori
    FROM obat o
    LEFT JOIN merk m ON o.idmerk = m.idmerk
    LEFT JOIN kategori k ON o.idkategori = k.idkategori
    ORDER BY o.idobat DESC
");

// ============================================
// STATISTIK OBAT
// ============================================

$queryStatistik = mysqli_query($koneksi, "
    SELECT
        COUNT(idobat) AS totalobat,
        COALESCE(SUM(stok), 0) AS totalstok,
        SUM(CASE WHEN stok <= 0 THEN 1 ELSE 0 END) AS stokhabis
    FROM obat
");

$statistik = mysqli_fetch_assoc($queryStatistik);

$totalObat = $statistik['totalobat'];
$totalStok = $statistik['totalstok'];
$stokHabis = $statistik['stokhabis'];
?>

<div class="card card-outline card-primary shadow-sm">

    <div class="card-header">
        <div class="row align-items-center">

            <div class="col-md-4">
                <h3 class="card-title font-weight-bold">
                    <i class="bi bi-capsule me-1"></i>
                    Daftar Obat
                </h3>
            </div>

            <div class="col-md-8">
                <div class="d-flex justify-content-md-end flex-wrap">

                    <span class="btn btn-sm btn-primary me-1 mb-1">
                        <i class="bi bi-capsule me-1"></i>
                        Total Obat: <?= $totalObat; ?>
                    </span>

                    <span class="btn btn-sm btn-success me-1 mb-1">
                        <i class="bi bi-box-seam me-1"></i>
                        Total Stok: <?= number_format($totalStok, 0, ',', '.'); ?>
                    </span>

                    <span class="btn btn-sm btn-danger me-2 mb-1">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        Stok Habis: <?= $stokHabis; ?>
                    </span>

                    <a href="index.php?page=createobat"
                       class="btn btn-sm btn-primary mb-1">
                        <i class="bi bi-plus-circle"></i>
                        Tambah Obat
                    </a>

                </div>
            </div>

        </div>
    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table id="example1"
                   class="table table-bordered table-hover table-striped table-sm align-middle">

                <thead class="bg-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="75">Foto</th>
                        <th>Nama Obat</th>
                        <th>Merk</th>
                        <th>Kategori</th>
                        <th width="130">Harga</th>
                        <th width="100" class="text-center">Stok</th>
                        <th width="120" class="text-center">Tanggal Stok</th>
                        <th width="130" class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    $no = 1;

                    if (mysqli_num_rows($queryObat) > 0) {
                        while ($obat = mysqli_fetch_assoc($queryObat)) {

                            $fotoObat = !empty($obat['foto'])
                                ? 'assets/images/obat/' . $obat['foto']
                                : 'assets/images/obat/default.png';
                    ?>

                            <tr>

                                <td class="text-center">
                                    <?= $no++; ?>
                                </td>

                                <td class="text-center">
                                    <img src="<?= e($fotoObat); ?>"
                                         width="55"
                                         height="55"
                                         class="rounded border"
                                         style="object-fit: cover;"
                                         alt="Foto Obat"
                                         onerror="this.src='assets/images/obat/default.png'">
                                </td>

                                <td>
                                    <strong><?= e($obat['namaobat']); ?></strong>
                                </td>

                                <td>
                                    <?= e($obat['namamerk'] ?: '-'); ?>
                                </td>

                                <td>
                                    <?= e($obat['namakategori'] ?: '-'); ?>
                                </td>

                                <td>
                                    Rp <?= number_format($obat['harga'], 0, ',', '.'); ?>
                                </td>

                                <td class="text-center">

                                    <?php if ($obat['stok'] <= 0) { ?>

                                        <span class="badge bg-danger">
                                            Habis
                                        </span>

                                    <?php } else { ?>

                                        <span class="badge bg-success">
                                            <?= number_format($obat['stok'], 0, ',', '.'); ?>
                                        </span>

                                    <?php } ?>

                                </td>

                                <td class="text-center">
                                    <?= !empty($obat['tanggalstok'])
                                        ? date('d-m-Y', strtotime($obat['tanggalstok']))
                                        : '-' ?>
                                </td>

                                <td class="text-center">

                                    <a href="index.php?page=showobat&idobat=<?= $obat['idobat']; ?>"
                                       class="btn btn-info btn-sm"
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="index.php?page=editobat&idobat=<?= $obat['idobat']; ?>"
                                       class="btn btn-warning btn-sm"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="index.php"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus data obat ini?')">

                                        <input type="hidden" name="page" value="prosesobat">
                                        <input type="hidden" name="aksi" value="hapus">
                                        <input type="hidden" name="idobat" value="<?= $obat['idobat']; ?>">

                                        <?= csrfField(); ?>

                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php
                        }
                    } else {
                        ?>

                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                <i class="bi bi-inbox me-1"></i>
                                Belum ada data obat.
                            </td>
                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

    <div class="card-footer">
        <small class="text-muted">
            <i class="bi bi-info-circle me-1"></i>
            Data obat terhubung dengan master merk dan kategori obat.
        </small>
    </div>

</div>
