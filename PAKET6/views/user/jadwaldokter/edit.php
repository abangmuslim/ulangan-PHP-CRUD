
<?php
$id = $_GET['id'] ?? 0;
$id = (int) $id;

$query = mysqli_query($koneksi, "
    SELECT * FROM jadwaldokter
    WHERE idjadwal='$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>window.location='index.php?page=jadwaldokter';</script>";
    exit;
}

$querydokter = mysqli_query($koneksi, "
    SELECT * FROM dokter
    ORDER BY namadokter ASC
");
?>

<style>
    .panel-info {
        height: 100%;
        padding: 25px;
        border-left: 1px solid #dee2e6;
    }

    @media (max-width: 767px) {
        .panel-info {
            border-left: none;
            border-top: 1px solid #dee2e6;
            margin-top: 20px;
        }
    }
</style>

<section class="content-header">
    <div class="container-fluid">

        <div class="row mb-2">

            <div class="col-sm-6">
                <h1>
                    <i class="bi bi-calendar-gear"></i>
                    Edit Jadwal Dokter
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
                        <a href="index.php?page=jadwaldokter">
                            Jadwal Dokter
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        Edit
                    </li>

                </ol>
            </div>

        </div>

    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="card card-warning card-outline">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-pencil-square me-1"></i>
                    Form Edit Jadwal Dokter
                </h3>
            </div>

            <form action="proses/prosesjadwaldokter.php?aksi=ubah"
                method="POST">

                <input type="hidden"
                    name="idjadwal"
                    value="<?= $data['idjadwal']; ?>">

                <div class="card-body">

                    <div class="row">

                        <!-- DATA JADWAL -->
                        <div class="col-md-7">

                            <div class="mb-3">

                                <label class="form-label">
                                    Nama Dokter
                                </label>

                                <select name="iddokter"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        -- Pilih Dokter --
                                    </option>

                                    <?php while ($dokter = mysqli_fetch_assoc($querydokter)) { ?>

                                        <option value="<?= $dokter['iddokter']; ?>"
                                            <?= $data['iddokter'] == $dokter['iddokter'] ? 'selected' : ''; ?>>

                                            <?= htmlspecialchars($dokter['namadokter']); ?>
                                            - <?= htmlspecialchars($dokter['spesialis'] ?? ''); ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Hari Praktik
                                </label>

                                <select name="haripraktik"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        -- Pilih Hari Praktik --
                                    </option>

                                    <?php
                                    $hari = [
                                        'Senin',
                                        'Selasa',
                                        'Rabu',
                                        'Kamis',
                                        'Jumat',
                                        'Sabtu',
                                        'Minggu'
                                    ];

                                    foreach ($hari as $namahari) {
                                    ?>

                                        <option value="<?= $namahari; ?>"
                                            <?= $data['haripraktik'] == $namahari ? 'selected' : ''; ?>>

                                            <?= $namahari; ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Jam Mulai Praktik
                                </label>

                                <input type="time"
                                    name="jam_mulai"
                                    class="form-control"
                                    value="<?= htmlspecialchars(substr($data['jam_mulai'], 0, 5)); ?>"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Jam Selesai Praktik
                                </label>

                                <input type="time"
                                    name="jam_selesai"
                                    class="form-control"
                                    value="<?= htmlspecialchars(substr($data['jam_selesai'], 0, 5)); ?>"
                                    required>

                            </div>

                        </div>

                        <!-- INFORMASI JADWAL -->
                        <div class="col-md-5">

                            <div class="panel-info">

                                <div class="text-center mb-4">

                                    <h5>
                                        <i class="bi bi-info-circle me-1"></i>
                                        Informasi Jadwal
                                    </h5>

                                    <i class="bi bi-calendar2-week text-warning"
                                        style="font-size: 100px;"></i>

                                </div>

                                <div class="alert alert-warning">
                                    <strong>Perhatian!</strong>

                                    <ul class="mb-0 mt-2">
                                        <li>Pastikan dokter yang dipilih benar.</li>
                                        <li>Periksa kembali hari praktik.</li>
                                        <li>Pastikan jam selesai lebih besar dari jam mulai.</li>
                                        <li>Perubahan jadwal akan memengaruhi informasi praktik dokter.</li>
                                    </ul>
                                </div>

                                <div class="text-center mt-4">

                                    <button type="submit"
                                        class="btn btn-warning mb-2">

                                        <i class="bi bi-save"></i>
                                        Update Jadwal

                                    </button>

                                    <button type="reset"
                                        class="btn btn-secondary mb-2">

                                        <i class="bi bi-arrow-clockwise"></i>
                                        Reset

                                    </button>

                                    <a href="index.php?page=jadwaldokter"
                                        class="btn btn-dark mb-2">

                                        <i class="bi bi-arrow-left"></i>
                                        Kembali

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>
</section>
