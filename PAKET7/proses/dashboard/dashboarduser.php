
<?php
// DASHBOARD DOKTER
?>

<div class="content-wrapper">

    <!-- HEADER HALAMAN -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">
                        Dashboard Dokter
                    </h1>
                    <p class="text-muted mb-0">
                        Ringkasan aktivitas dan pelayanan pasien
                    </p>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="index.php?page=dashboarddokter">
                                Home
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Dashboard
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- KONTEN DASHBOARD -->
    <section class="content">
        <div class="container-fluid">

            <!-- SAPAAN DOKTER -->
            <div class="card border-0 shadow-sm mb-4"
                 style="background:linear-gradient(110deg,#087f70,#075c52);color:white;">

                <div class="card-body p-4">
                    <div class="row align-items-center">

                        <div class="col-md-8">
                            <span class="badge badge-light text-success mb-2">
                                <i class="fas fa-user-md mr-1"></i>
                                AREA DOKTER
                            </span>

                            <h3 class="font-weight-bold mb-2">
                                Selamat Datang,
                                <?= e($dokter['namadokter']) ?>
                            </h3>

                            <p class="mb-2">
                                Selamat bertugas. Semoga pelayanan kesehatan
                                hari ini berjalan lancar dan memberikan
                                manfaat bagi seluruh pasien.
                            </p>

                            <div class="mt-3">
                                <i class="far fa-calendar-alt mr-2"></i>
                                <?= $hariIni ?>, <?= $tanggalDashboard ?>
                                <span class="mx-2">|</span>
                                <i class="fas fa-stethoscope mr-1"></i>
                                <?= e($dokter['spesialis'] ?? 'Dokter Umum') ?>
                            </div>
                        </div>

                        <div class="col-md-4 text-center d-none d-md-block">
                            <i class="fas fa-user-md"
                               style="font-size:95px;opacity:.8;"></i>
                        </div>

                    </div>
                </div>
            </div>

            <!-- KARTU STATISTIK -->
            <div class="row">

                <!-- ANTRIAN -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info shadow-sm">
                        <div class="inner">
                            <h3><?= $totalAntrian ?></h3>
                            <p>Antrian Hari Ini</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="index.php?page=antriandokter"
                           class="small-box-footer">
                            Lihat Antrian
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- PROSES -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning shadow-sm">
                        <div class="inner">
                            <h3><?= $totalProses ?></h3>
                            <p>Pemeriksaan Proses</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <a href="index.php?page=pemeriksaandokter"
                           class="small-box-footer">
                            Lihat Pemeriksaan
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- SELESAI -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success shadow-sm">
                        <div class="inner">
                            <h3><?= $totalSelesai ?></h3>
                            <p>Pemeriksaan Selesai</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <a href="index.php?page=pemeriksaandokter"
                           class="small-box-footer">
                            Lihat Pemeriksaan
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- PASIEN -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary shadow-sm">
                        <div class="inner">
                            <h3><?= $totalPasien ?></h3>
                            <p>Total Pasien Diperiksa</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-injured"></i>
                        </div>
                        <a href="index.php?page=pemeriksaandokter"
                           class="small-box-footer">
                            Riwayat Pasien
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

            </div>

            <!-- AKSES CEPAT -->
            <div class="card card-outline card-success shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-th-large mr-2"></i>
                        Akses Cepat
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row text-center">

                        <div class="col-md-3 col-6 mb-3">
                            <a href="index.php?page=antriandokter"
                               class="btn btn-outline-info btn-block p-3">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <br>
                                Antrian Pasien
                            </a>
                        </div>

                        <div class="col-md-3 col-6 mb-3">
                            <a href="index.php?page=pemeriksaandokter"
                               class="btn btn-outline-success btn-block p-3">
                                <i class="fas fa-stethoscope fa-2x mb-2"></i>
                                <br>
                                Pemeriksaan
                            </a>
                        </div>

                        <div class="col-md-3 col-6 mb-3">
                            <a href="index.php?page=resepdokter"
                               class="btn btn-outline-primary btn-block p-3">
                                <i class="fas fa-prescription fa-2x mb-2"></i>
                                <br>
                                Resep Obat
                            </a>
                        </div>

                        <div class="col-md-3 col-6 mb-3">
                            <a href="index.php?page=jadwalkunjungandokter"
                               class="btn btn-outline-warning btn-block p-3">
                                <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                <br>
                                Jadwal Praktik
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">

                <!-- ANTRIAN PASIEN -->
                <div class="col-lg-7">
                    <div class="card card-outline card-info shadow-sm">

                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-users mr-2"></i>
                                Antrian Pasien Hari Ini
                            </h3>

                            <div class="card-tools">
                                <a href="index.php?page=antriandokter"
                                   class="btn btn-sm btn-info">
                                    Lihat Semua
                                </a>
                            </div>
                        </div>

                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>No.</th>
                                        <th>Pasien</th>
                                        <th>Keluhan</th>
                                        <th>Jam</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if (mysqli_num_rows($queryDaftarAntrian) > 0) : ?>
                                        <?php $no = 1; ?>
                                        <?php while ($antrian = mysqli_fetch_assoc($queryDaftarAntrian)) : ?>
                                            <tr>
                                                <td>
                                                    <span class="badge badge-info">
                                                        <?= $antrian['nomorantrian'] ?? '-' ?>
                                                    </span>
                                                </td>

                                                <td>
                                                    <?= e($antrian['namapasien']) ?>
                                                </td>

                                                <td>
                                                    <?= e($antrian['keluhan']) ?>
                                                </td>

                                                <td>
                                                    <?= date('H:i', strtotime($antrian['jam_mulai'])) ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    if ($antrian['statusantrian'] == 'Belum Antri') {
                                                        echo '<span class="badge badge-secondary">Belum Antri</span>';
                                                    } elseif ($antrian['statusantrian'] == 'Menunggu') {
                                                        echo '<span class="badge badge-warning">Menunggu</span>';
                                                    } elseif ($antrian['statusantrian'] == 'Dipanggil') {
                                                        echo '<span class="badge badge-info">Dipanggil</span>';
                                                    } else {
                                                        echo '<span class="badge badge-success">' . e($antrian['statusantrian']) . '</span>';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>

                                    <?php else : ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted p-4">
                                                <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                                <br>
                                                Belum ada antrian pasien hari ini.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- JADWAL PRAKTIK -->
                <div class="col-lg-5">
                    <div class="card card-outline card-success shadow-sm">

                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Jadwal Praktik
                            </h3>
                        </div>

                        <div class="card-body p-0">
                            <ul class="products-list product-list-in-card pl-2 pr-2">

                                <?php if (mysqli_num_rows($queryJadwal) > 0) : ?>

                                    <?php while ($jadwal = mysqli_fetch_assoc($queryJadwal)) : ?>

                                        <li class="item">
                                            <div class="product-info ml-0">
                                                <span class="product-title">
                                                    <?= e($jadwal['haripraktik']) ?>

                                                    <?php if ($jadwal['haripraktik'] == $hariIni) : ?>
                                                        <span class="badge badge-success float-right">
                                                            Hari Ini
                                                        </span>
                                                    <?php endif; ?>
                                                </span>

                                                <span class="product-description">
                                                    <i class="far fa-clock mr-1"></i>
                                                    <?= date('H:i', strtotime($jadwal['jam_mulai'])) ?>
                                                    -
                                                    <?= date('H:i', strtotime($jadwal['jam_selesai'])) ?>
                                                </span>
                                            </div>
                                        </li>

                                    <?php endwhile; ?>

                                <?php else : ?>

                                    <li class="item text-center text-muted p-3">
                                        Belum ada jadwal praktik.
                                    </li>

                                <?php endif; ?>

                            </ul>
                        </div>

                        <div class="card-footer text-center">
                            <a href="index.php?page=jadwalkunjungandokter"
                               class="text-success">
                                Lihat Jadwal Lengkap
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>

                    </div>
                </div>

            </div>

            <!-- RIWAYAT PEMERIKSAAN -->
            <div class="card card-outline card-primary shadow-sm">

                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-history mr-2"></i>
                        Riwayat Pemeriksaan Terbaru
                    </h3>

                    <div class="card-tools">
                        <a href="index.php?page=pemeriksaandokter"
                           class="btn btn-sm btn-primary">
                            Lihat Semua
                        </a>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>Nama Pasien</th>
                                <th>Diagnosis</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (mysqli_num_rows($queryRiwayat) > 0) : ?>

                                <?php $no = 1; ?>
                                <?php while ($riwayat = mysqli_fetch_assoc($queryRiwayat)) : ?>

                                    <tr>
                                        <td><?= $no++ ?></td>

                                        <td>
                                            <?= date('d-m-Y', strtotime($riwayat['tanggalkunjungan'])) ?>
                                        </td>

                                        <td>
                                            <?= e($riwayat['namapasien']) ?>
                                        </td>

                                        <td>
                                            <?= e($riwayat['diagnosis'] ?: '-') ?>
                                        </td>

                                        <td>
                                            <?php if ($riwayat['statuskunjungan'] == 'Selesai') : ?>
                                                <span class="badge badge-success">
                                                    Selesai
                                                </span>
                                            <?php else : ?>
                                                <span class="badge badge-warning">
                                                    Proses
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                <?php endwhile; ?>

                            <?php else : ?>

                                <tr>
                                    <td colspan="5" class="text-center text-muted p-4">
                                        Belum ada riwayat pemeriksaan.
                                    </td>
                                </tr>

                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>
