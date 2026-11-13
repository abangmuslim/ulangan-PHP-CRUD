
<?php
$idjadwal = (int) ($_GET['idjadwal'] ?? 0);

if ($idjadwal <= 0) {
    echo "<script>alert('ID jadwal tidak valid.');window.location.href='index.php?page=jadwaldokter';</script>";
    exit;
}

// Ambil data jadwal
$stmt = mysqli_prepare($koneksi, "
    SELECT iddokter, haripraktik, jam_mulai, jam_selesai
    FROM jadwaldokter
    WHERE idjadwal = ?
");

mysqli_stmt_bind_param($stmt, "i", $idjadwal);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $iddokter, $haripraktik, $jam_mulai, $jam_selesai);
$adaJadwal = mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if (!$adaJadwal) {
    echo "<script>alert('Data jadwal tidak ditemukan.');window.location.href='index.php?page=jadwaldokter';</script>";
    exit;
}

// Ambil daftar dokter
$queryDokter = mysqli_query($koneksi, "
    SELECT iddokter, namadokter, spesialis
    FROM dokter
    ORDER BY namadokter
");

$daftarHari = [
    'Senin', 'Selasa', 'Rabu', 'Kamis',
    'Jumat', 'Sabtu', 'Minggu'
];
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Jadwal Dokter</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="index.php?page=dashboarduser">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="index.php?page=jadwaldokter">Jadwal Dokter</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Jadwal</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-warning card-outline">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-edit mr-2"></i>
                            Form Edit Jadwal Praktik
                        </h3>
                    </div>

                    <form action="index.php?page=prosesjadwaldokter" method="POST">
                        <?= csrfField() ?>
                        <input type="hidden" name="page" value="prosesjadwaldokter">
                        <input type="hidden" name="aksi" value="ubah">
                        <input type="hidden" name="idjadwal" value="<?= $idjadwal ?>">

                        <div class="card-body">

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-1"></i>
                                Silakan perbarui dokter, hari, dan jam praktik pada jadwal yang dipilih.
                            </div>

                            <!-- PILIH DOKTER -->
                            <div class="form-group">
                                <label for="iddokter">
                                    <i class="fas fa-user-md mr-1 text-success"></i>
                                    Dokter <span class="text-danger">*</span>
                                </label>

                                <select name="iddokter" id="iddokter" class="form-control" required>
                                    <option value="">-- Pilih Dokter --</option>

                                    <?php if ($queryDokter) : ?>
                                        <?php while ($dokter = mysqli_fetch_assoc($queryDokter)) : ?>
                                            <option value="<?= (int) $dokter['iddokter'] ?>"
                                                <?= ((int) $dokter['iddokter'] == $iddokter) ? 'selected' : '' ?>>
                                                <?= e($dokter['namadokter']) ?>
                                                <?= $dokter['spesialis'] ? ' - ' . e($dokter['spesialis']) : '' ?>
                                            </option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <hr>

                            <h5 class="mb-3">
                                <i class="fas fa-calendar-alt text-success mr-1"></i>
                                Data Jadwal Praktik
                            </h5>

                            <!-- HARI PRAKTIK -->
                            <div class="form-group">
                                <label for="haripraktik">
                                    Hari Praktik <span class="text-danger">*</span>
                                </label>

                                <select name="haripraktik" id="haripraktik" class="form-control" required>
                                    <option value="">-- Pilih Hari Praktik --</option>

                                    <?php foreach ($daftarHari as $hari) : ?>
                                        <option value="<?= e($hari) ?>"
                                            <?= ($haripraktik == $hari) ? 'selected' : '' ?>>
                                            <?= e($hari) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- JAM PRAKTIK -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jam_mulai">
                                            <i class="far fa-clock mr-1 text-success"></i>
                                            Jam Mulai <span class="text-danger">*</span>
                                        </label>

                                        <input type="time" name="jam_mulai" id="jam_mulai"
                                            class="form-control"
                                            value="<?= e(substr($jam_mulai, 0, 5)) ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jam_selesai">
                                            <i class="far fa-clock mr-1 text-success"></i>
                                            Jam Selesai <span class="text-danger">*</span>
                                        </label>

                                        <input type="time" name="jam_selesai" id="jam_selesai"
                                            class="form-control"
                                            value="<?= e(substr($jam_selesai, 0, 5)) ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="callout callout-warning mb-0">
                                <h6>
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Perhatian
                                </h6>
                                <small>
                                    Jam selesai harus lebih besar daripada jam mulai.
                                    Pastikan perubahan jadwal tidak bertabrakan dengan jadwal praktik lainnya.
                                </small>
                            </div>

                        </div>

                        <div class="card-footer d-flex justify-content-between">
                            <a href="index.php?page=jadwaldokter" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>

                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save mr-1"></i> Perbarui Jadwal
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
