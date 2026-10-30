
<?php
// =====================================================
// IDENTITAS PASIEN
// =====================================================

$idpasien = (int) ($_SESSION['idpasien'] ?? 0);

$namapasien = htmlspecialchars(
    $_SESSION['namapasien'] ?? 'Pasien',
    ENT_QUOTES,
    'UTF-8'
);


// =====================================================
// DATA PROFIL PASIEN
// =====================================================

$queryProfil = mysqli_query($koneksi, "
    SELECT *
    FROM pasien
    WHERE idpasien = '$idpasien'
");

$dataPasien = $queryProfil ? mysqli_fetch_assoc($queryProfil) : null;


// =====================================================
// RINGKASAN KUNJUNGAN PASIEN
// =====================================================

$queryRingkasan = mysqli_query($koneksi, "
    SELECT
        COUNT(*) AS total_kunjungan,
        COALESCE(SUM(totalkunjungan), 0) AS total_biaya
    FROM kunjungan
    WHERE idpasien = '$idpasien'
");

$dataRingkasan = $queryRingkasan
    ? mysqli_fetch_assoc($queryRingkasan)
    : null;

$totalKunjungan = $dataRingkasan['total_kunjungan'] ?? 0;
$totalBiaya = $dataRingkasan['total_biaya'] ?? 0;


// =====================================================
// JADWAL DOKTER 7 HARI KE DEPAN
// =====================================================

$daftarHari = [
    1 => 'senin',
    2 => 'selasa',
    3 => 'rabu',
    4 => 'kamis',
    5 => 'jumat',
    6 => 'sabtu',
    7 => 'minggu'
];

$namaHari = [
    'senin' => 'Senin',
    'selasa' => 'Selasa',
    'rabu' => 'Rabu',
    'kamis' => 'Kamis',
    'jumat' => 'Jumat',
    'sabtu' => 'Sabtu',
    'minggu' => 'Minggu'
];

$querySemuaJadwal = mysqli_query($koneksi, "
    SELECT
        jadwaldokter.idjadwal,
        jadwaldokter.haripraktik,
        jadwaldokter.jam_mulai,
        jadwaldokter.jam_selesai,
        dokter.namadokter,
        dokter.spesialis
    FROM jadwaldokter
    INNER JOIN dokter
        ON jadwaldokter.iddokter = dokter.iddokter
");

$jadwalDokter = [];

if ($querySemuaJadwal) {
    while ($dataJadwal = mysqli_fetch_assoc($querySemuaJadwal)) {

        $hari = strtolower(trim($dataJadwal['haripraktik']));

        $jadwalDokter[$hari][] = $dataJadwal;
    }
}


// Menyusun jadwal untuk 7 hari ke depan
$jadwalTujuhHari = [];

for ($i = 0; $i < 7; $i++) {

    $tanggal = date('Y-m-d', strtotime("+$i day"));

    $nomorHari = (int) date('N', strtotime($tanggal));

    $hari = $daftarHari[$nomorHari];

    $jadwalTujuhHari[] = [
        'tanggal' => $tanggal,
        'hari' => $hari,
        'jadwal' => $jadwalDokter[$hari] ?? []
    ];
}


// =====================================================
// STATUS LAYANAN BERDASARKAN JADWAL HARI INI
// =====================================================

$hariSekarang = $daftarHari[(int) date('N')];

$jamSekarang = date('H:i:s');

$statusLayanan = false;
$jadwalHariIni = $jadwalDokter[$hariSekarang] ?? [];

foreach ($jadwalHariIni as $jadwal) {

    if (
        $jamSekarang >= $jadwal['jam_mulai'] &&
        $jamSekarang <= $jadwal['jam_selesai']
    ) {
        $statusLayanan = true;
        break;
    }
}


// =====================================================
// 5 RIWAYAT KUNJUNGAN TERAKHIR
// =====================================================

$queryRiwayat = mysqli_query($koneksi, "
    SELECT
        kunjungan.idkunjungan,
        kunjungan.tanggalkunjungan,
        kunjungan.keluhan,
        kunjungan.diagnosis,
        kunjungan.totalkunjungan,
        dokter.namadokter
    FROM kunjungan
    INNER JOIN dokter
        ON kunjungan.iddokter = dokter.iddokter
    WHERE kunjungan.idpasien = '$idpasien'
    ORDER BY
        kunjungan.tanggalkunjungan DESC,
        kunjungan.idkunjungan DESC
    LIMIT 5
");


// =====================================================
// SAPAAN BERDASARKAN WAKTU
// =====================================================

$jam = (int) date('H');

if ($jam >= 4 && $jam < 11) {
    $sapaan = 'Selamat Pagi';
} elseif ($jam >= 11 && $jam < 15) {
    $sapaan = 'Selamat Siang';
} elseif ($jam >= 15 && $jam < 18) {
    $sapaan = 'Selamat Sore';
} else {
    $sapaan = 'Selamat Malam';
}

?>

<style>

/* =====================================================
   DASHBOARD PASIEN
===================================================== */

.pasien-welcome {
    background: linear-gradient(120deg, #146c43, #198754);
    color: white;
    padding: 30px;
    border-radius: 16px;
    position: relative;
    overflow: hidden;
}

.pasien-welcome-icon {
    font-size: 100px;
    opacity: .18;
}

.pasien-subtitle {
    color: #7b8794;
    font-size: 13px;
}

.pasien-section-title {
    font-size: 18px;
    font-weight: 700;
    color: #343a40;
}

.pasien-card {
    border: none;
    border-radius: 13px;
    height: 100%;
    transition: .2s ease;
}

.pasien-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 7px 18px rgba(0,0,0,.09) !important;
}

.pasien-icon {
    width: 46px;
    height: 46px;
    border-radius: 11px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 22px;
    flex-shrink: 0;
}

.pasien-number {
    font-size: 27px;
    font-weight: 700;
    color: #263238;
}

.pasien-action {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 17px;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    color: #343a40;
    text-decoration: none;
    background: white;
    height: 100%;
    transition: .2s;
}

.pasien-action:hover {
    border-color: #198754;
    background: #f5fbf7;
    color: #198754;
}

.pasien-action i {
    font-size: 26px;
}

.pasien-status {
    border-radius: 13px;
    border: none;
}

.pasien-status-buka {
    background: #e8f7ed;
    color: #146c43;
}

.pasien-status-tutup {
    background: #fff0f0;
    color: #b02a37;
}

.pasien-schedule {
    border: none;
    border-radius: 13px;
    overflow: hidden;
}

.pasien-schedule .card-header {
    background: #198754;
    color: white;
    font-weight: 600;
    padding: 14px 17px;
}

.pasien-table th {
    background: #e8f5ed;
    color: #146c43;
    font-size: 12px;
    white-space: nowrap;
}

.pasien-table td {
    font-size: 12px;
    vertical-align: middle;
}

.pasien-table-riwayat th {
    background: #198754;
    color: white;
    font-size: 12px;
    white-space: nowrap;
}

.pasien-table-riwayat td {
    font-size: 12px;
    vertical-align: middle;
}

.pasien-info {
    border: none;
    border-radius: 13px;
}

@media(max-width: 575px) {

    .pasien-welcome {
        padding: 23px 18px;
    }

    .pasien-welcome-icon {
        display: none;
    }

    .pasien-number {
        font-size: 23px;
    }

}

</style>


<div class="container-fluid p-0">


    <!-- =================================================
         SAMBUTAN PASIEN
    ================================================== -->

    <div class="pasien-welcome shadow-sm mb-4">

        <div class="row align-items-center">

            <div class="col-md-9">

                <span class="badge bg-light text-success px-3 py-2 mb-3">
                    <i class="bi bi-person-circle me-1"></i>
                    Portal Pasien
                </span>

                <h2 class="fw-bold mb-2">
                    <?= $sapaan; ?>, <?= $namapasien; ?>!
                </h2>

                <p class="mb-3">
                    Selamat datang di layanan pasien Klinik Rumah Sakit Ahmadi.
                    Temukan informasi jadwal dokter, lakukan pendaftaran checkup,
                    dan pantau riwayat kunjungan Anda melalui halaman ini.
                </p>

                <div class="small">
                    <i class="bi bi-calendar3 me-2"></i>
                    <?= date('d-m-Y'); ?>

                    <span class="mx-2">|</span>

                    <i class="bi bi-shield-check me-1"></i>
                    Area Pasien
                </div>

            </div>

            <div class="col-md-3 text-center">
                <i class="bi bi-hospital pasien-welcome-icon"></i>
            </div>

        </div>

    </div>


    <!-- =================================================
         STATUS LAYANAN
    ================================================== -->

    <div class="mb-3">

        <h5 class="pasien-section-title mb-1">
            <i class="bi bi-heart-pulse-fill text-success me-2"></i>
            Informasi Pelayanan Klinik
        </h5>

        <p class="pasien-subtitle mb-0">
            Periksa jadwal pelayanan sebelum datang ke klinik.
        </p>

    </div>


    <div class="row g-3 mb-4">

        <div class="col-12 col-lg-5">

            <div class="card pasien-status shadow-sm h-100">

                <div class="card-body p-4">

                    <?php if ($statusLayanan): ?>

                        <div class="d-flex align-items-center gap-3">

                            <div class="pasien-icon bg-success text-white">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>

                            <div>
                                <div class="fw-bold fs-5 text-success">
                                    Layanan Berdasarkan Jadwal: BUKA
                                </div>

                                <small class="text-muted">
                                    Ada dokter yang sedang dalam jam praktik hari ini.
                                </small>
                            </div>

                        </div>

                    <?php else: ?>

                        <div class="d-flex align-items-center gap-3">

                            <div class="pasien-icon bg-danger text-white">
                                <i class="bi bi-clock-fill"></i>
                            </div>

                            <div>
                                <div class="fw-bold fs-5 text-danger">
                                    Layanan Berdasarkan Jadwal: TUTUP
                                </div>

                                <small class="text-muted">
                                    Tidak ada dokter yang sedang dalam jam praktik saat ini.
                                </small>
                            </div>

                        </div>

                    <?php endif; ?>

                    <hr>

                    <div class="small text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Status dihitung berdasarkan jadwal praktik dokter,
                        bukan status operasional manual dari administrator.
                    </div>

                </div>

            </div>

        </div>


        <div class="col-12 col-lg-7">

            <div class="card pasien-schedule shadow-sm h-100">

                <div class="card-header">
                    <i class="bi bi-calendar-check me-2"></i>
                    Jadwal Dokter Hari Ini
                    (<?= ucfirst($hariSekarang); ?>)
                </div>

                <div class="card-body p-2">

                    <div class="table-responsive">

                        <table class="table table-bordered table-striped pasien-table mb-0">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Dokter</th>
                                    <th>Spesialis</th>
                                    <th>Jam Praktik</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if (!empty($jadwalHariIni)): ?>

                                    <?php $no = 1; ?>

                                    <?php foreach ($jadwalHariIni as $jadwal): ?>

                                        <tr>

                                            <td class="text-center">
                                                <?= $no++; ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($jadwal['namadokter']); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($jadwal['spesialis']); ?>
                                            </td>

                                            <td class="text-nowrap">
                                                <?= date('H:i', strtotime($jadwal['jam_mulai'])); ?>
                                                -
                                                <?= date('H:i', strtotime($jadwal['jam_selesai'])); ?>
                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            Tidak ada jadwal dokter hari ini.
                                        </td>
                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

                <div class="card-footer bg-white text-end">

                    <a href="index.php?page=daftardokter" class="btn btn-sm btn-outline-success">
                        Lihat Daftar Dokter
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>

                </div>

            </div>

        </div>

    </div>


    <!-- =================================================
         RINGKASAN PASIEN
    ================================================== -->

    <div class="mb-3">

        <h5 class="pasien-section-title mb-1">
            <i class="bi bi-grid-1x2-fill text-success me-2"></i>
            Ringkasan Aktivitas Saya
        </h5>

        <p class="pasien-subtitle mb-0">
            Ringkasan informasi aktivitas kunjungan Anda.
        </p>

    </div>


    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card pasien-card shadow-sm">

                <div class="card-body p-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="pasien-subtitle mb-2">
                                Total Kunjungan
                            </div>

                            <div class="pasien-number">
                                <?= $totalKunjungan; ?>
                            </div>

                            <small class="text-success">
                                Riwayat pemeriksaan
                            </small>
                        </div>

                        <div class="pasien-icon bg-primary-subtle text-primary">
                            <i class="bi bi-clipboard2-pulse-fill"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card pasien-card shadow-sm">

                <div class="card-body p-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="pasien-subtitle mb-2">
                                Total Biaya Kunjungan
                            </div>

                            <div class="fw-bold fs-5 text-success">
                                Rp <?= number_format((float) $totalBiaya, 0, ',', '.'); ?>
                            </div>

                            <small class="text-success">
                                Akumulasi biaya
                            </small>
                        </div>

                        <div class="pasien-icon bg-success-subtle text-success">
                            <i class="bi bi-wallet2"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-12 col-sm-6 col-xl-3">

            <a href="index.php?page=profil" class="text-decoration-none">

                <div class="card pasien-card shadow-sm">

                    <div class="card-body p-3">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <div class="pasien-subtitle mb-2">
                                    Profil Saya
                                </div>

                                <div class="fw-bold fs-5 text-dark">
                                    Lihat Profil
                                </div>

                                <small class="text-success">
                                    Data pribadi
                                </small>
                            </div>

                            <div class="pasien-icon bg-info-subtle text-info">
                                <i class="bi bi-person-vcard-fill"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </a>

        </div>


        <div class="col-12 col-sm-6 col-xl-3">

            <a href="index.php?page=riwayatkunjungan" class="text-decoration-none">

                <div class="card pasien-card shadow-sm">

                    <div class="card-body p-3">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <div class="pasien-subtitle mb-2">
                                    Riwayat
                                </div>

                                <div class="fw-bold fs-5 text-dark">
                                    Kunjungan Saya
                                </div>

                                <small class="text-success">
                                    Lihat pemeriksaan
                                </small>
                            </div>

                            <div class="pasien-icon bg-warning-subtle text-warning">
                                <i class="bi bi-clock-history"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </a>

        </div>

    </div>


    <!-- =================================================
         AKSES CEPAT
    ================================================== -->

    <div class="mb-3">

        <h5 class="pasien-section-title mb-1">
            <i class="bi bi-lightning-charge-fill text-warning me-2"></i>
            Layanan Pasien
        </h5>

        <p class="pasien-subtitle mb-0">
            Akses layanan yang tersedia untuk akun pasien.
        </p>

    </div>


    <div class="row g-3 mb-4">

        <div class="col-12 col-md-6 col-xl-4">

            <a href="index.php?page=pendaftarancheckup" class="pasien-action shadow-sm">

                <i class="bi bi-calendar2-plus-fill text-success"></i>

                <div>
                    <div class="fw-bold">Pendaftaran Checkup</div>
                    <small class="text-muted">
                        Daftar untuk pemeriksaan kesehatan
                    </small>
                </div>

            </a>

        </div>


        <div class="col-12 col-md-6 col-xl-4">

            <a href="index.php?page=profil" class="pasien-action shadow-sm">

                <i class="bi bi-person-vcard-fill text-primary"></i>

                <div>
                    <div class="fw-bold">Profil Saya</div>
                    <small class="text-muted">
                        Lihat informasi akun pasien
                    </small>
                </div>

            </a>

        </div>


        <div class="col-12 col-md-6 col-xl-4">

            <a href="index.php?page=editprofil" class="pasien-action shadow-sm">

                <i class="bi bi-pencil-square text-warning"></i>

                <div>
                    <div class="fw-bold">Edit Profil</div>
                    <small class="text-muted">
                        Perbarui data pribadi
                    </small>
                </div>

            </a>

        </div>

    </div>


    <!-- =================================================
         JADWAL DOKTER 7 HARI
    ================================================== -->

    <div class="mb-3">

        <h5 class="pasien-section-title mb-1">
            <i class="bi bi-calendar-week-fill text-success me-2"></i>
            Jadwal Praktik Dokter 7 Hari
        </h5>

        <p class="pasien-subtitle mb-0">
            Periksa jadwal dokter sebelum merencanakan kunjungan.
        </p>

    </div>


    <div class="card pasien-schedule shadow-sm mb-4">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover pasien-table mb-0">

                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="17%">Tanggal</th>
                            <th width="15%">Hari</th>
                            <th>Dokter</th>
                            <th>Spesialis</th>
                            <th>Jam Praktik</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php $no = 1; ?>

                        <?php foreach ($jadwalTujuhHari as $hari): ?>

                            <?php if (!empty($hari['jadwal'])): ?>

                                <?php foreach ($hari['jadwal'] as $jadwal): ?>

                                    <tr>

                                        <td class="text-center">
                                            <?= $no++; ?>
                                        </td>

                                        <td class="text-nowrap">
                                            <?= date('d-m-Y', strtotime($hari['tanggal'])); ?>
                                        </td>

                                        <td>
                                            <?= $namaHari[$hari['hari']]; ?>
                                        </td>

                                        <td class="fw-semibold">
                                            <?= htmlspecialchars($jadwal['namadokter']); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($jadwal['spesialis']); ?>
                                        </td>

                                        <td class="text-nowrap">
                                            <?= date('H:i', strtotime($jadwal['jam_mulai'])); ?>
                                            -
                                            <?= date('H:i', strtotime($jadwal['jam_selesai'])); ?>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        <?php endforeach; ?>


                        <?php if ($no === 1): ?>

                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Belum ada jadwal dokter untuk 7 hari ke depan.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- =================================================
         RIWAYAT KUNJUNGAN TERAKHIR
    ================================================== -->

    <div class="mb-3">

        <h5 class="pasien-section-title mb-1">
            <i class="bi bi-clock-history text-success me-2"></i>
            Kunjungan Terakhir Saya
        </h5>

        <p class="pasien-subtitle mb-0">
            Lima catatan pemeriksaan terakhir pada akun Anda.
        </p>

    </div>


    <div class="card pasien-info shadow-sm mb-4">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover pasien-table-riwayat mb-0">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Kunjungan</th>
                            <th>Dokter</th>
                            <th>Keluhan</th>
                            <th>Diagnosis</th>
                            <th class="text-end">Total Biaya</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if ($queryRiwayat && mysqli_num_rows($queryRiwayat) > 0): ?>

                            <?php $no = 1; ?>

                            <?php while ($riwayat = mysqli_fetch_assoc($queryRiwayat)): ?>

                                <tr>

                                    <td class="text-center">
                                        <?= $no++; ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= date('d-m-Y', strtotime($riwayat['tanggalkunjungan'])); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($riwayat['namadokter']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($riwayat['keluhan']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($riwayat['diagnosis']); ?>
                                    </td>

                                    <td class="text-end text-nowrap fw-semibold text-success">
                                        Rp <?= number_format((float) $riwayat['totalkunjungan'], 0, ',', '.'); ?>
                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-clipboard-x fs-3 d-block mb-2"></i>
                                    Anda belum memiliki riwayat kunjungan.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>


        <div class="card-footer bg-white d-flex justify-content-between align-items-center">

            <small class="text-muted">
                Maksimal 5 kunjungan terakhir.
            </small>

            <a href="index.php?page=riwayatkunjungan" class="btn btn-sm btn-success">
                Lihat Semua Riwayat
                <i class="bi bi-arrow-right ms-1"></i>
            </a>

        </div>

    </div>


    <!-- =================================================
         INFORMASI PASIEN
    ================================================== -->

    <div class="card pasien-info shadow-sm mb-3">

        <div class="card-body p-4">

            <div class="d-flex align-items-start gap-3">

                <div class="pasien-icon bg-success-subtle text-success">
                    <i class="bi bi-info-circle-fill"></i>
                </div>

                <div>

                    <h6 class="fw-bold mb-2">
                        Informasi Layanan Pasien
                    </h6>

                    <p class="text-muted small mb-0">
                        Gunakan menu Pendaftaran Checkup untuk melakukan
                        pendaftaran pemeriksaan. Pastikan informasi profil
                        dan nomor telepon selalu benar agar pihak klinik
                        dapat menghubungi Anda apabila diperlukan.
                        Periksa jadwal dokter sebelum datang ke klinik.
                    </p>

                </div>

            </div>

        </div>

    </div>


</div>
