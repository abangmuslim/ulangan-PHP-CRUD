
<?php
$querydokter = mysqli_query($koneksi, "
    SELECT * FROM dokter
    ORDER BY namadokter ASC
");

$totaldokter = mysqli_num_rows($querydokter);
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
                    <i class="bi bi-calendar-plus"></i>
                    Tambah Jadwal Dokter
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
                        Tambah
                    </li>

                </ol>
            </div>

        </div>

    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="card card-primary card-outline">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-calendar-plus me-1"></i>
                    Form Tambah Jadwal Dokter
                </h3>
            </div>

            <form action="proses/prosesjadwaldokter.php?aksi=tambah"
                method="POST">

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

                                        <option value="<?= $dokter['iddokter']; ?>">
                                            <?= htmlspecialchars($dokter['namadokter']); ?>
                                            - <?= htmlspecialchars($dokter['spesialis'] ?? ''); ?>
                                        </option>

                                    <?php } ?>

                                </select>

                                <?php if ($totaldokter == 0) { ?>
                                    <small class="text-danger">
                                        Belum ada data dokter. Tambahkan dokter terlebih dahulu.
                                    </small>
                                <?php } ?>

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

                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                    <option value="Minggu">Minggu</option>

                                </select>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Jam Mulai Praktik
                                </label>

                                <input type="time"
                                    name="jam_mulai"
                                    class="form-control"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Jam Selesai Praktik
                                </label>

                                <input type="time"
                                    name="jam_selesai"
                                    class="form-control"
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

                                    <i class="bi bi-calendar2-week text-primary"
                                        style="font-size: 100px;"></i>

                                </div>

                                <div class="alert alert-info">
                                    <strong>Perhatian!</strong>

                                    <ul class="mb-0 mt-2">
                                        <li>Pilih dokter yang akan praktik.</li>
                                        <li>Tentukan hari praktik dokter.</li>
                                        <li>Isi jam mulai dan jam selesai praktik.</li>
                                        <li>Pastikan jam selesai lebih besar dari jam mulai.</li>
                                    </ul>
                                </div>

                                <div class="text-center mt-4">

                                    <button type="submit"
                                        class="btn btn-primary mb-2"
                                        <?= $totaldokter == 0 ? 'disabled' : ''; ?>>

                                        <i class="bi bi-save"></i>
                                        Simpan Jadwal

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
