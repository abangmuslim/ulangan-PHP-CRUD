
<?php
// =====================================================
// IDENTITAS PASIEN
// =====================================================

$idpasien = (int) ($_SESSION['idpasien'] ?? 0);

if ($idpasien <= 0) {
    echo '<div class="alert alert-danger">Sesi pasien tidak ditemukan. Silakan login kembali.</div>';
    return;
}


// =====================================================
// DATA PROFIL PASIEN
// =====================================================

$queryProfil = mysqli_query($koneksi, "
    SELECT *
    FROM pasien
    WHERE idpasien = '$idpasien'
    LIMIT 1
");

$dataPasien = $queryProfil ? mysqli_fetch_assoc($queryProfil) : null;

if (!$dataPasien) {
    echo '<div class="alert alert-danger">Data pasien tidak ditemukan.</div>';
    return;
}

$namapasien = $dataPasien['namapasien'] ?? 'Pasien';

$fotoPasien = !empty($dataPasien['foto'])
    ? $dataPasien['foto']
    : 'default.png';


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

$totalKunjungan = (int) ($dataRingkasan['total_kunjungan'] ?? 0);
$totalBiaya = (float) ($dataRingkasan['total_biaya'] ?? 0);


// =====================================================
// RINGKASAN PENDAFTARAN PASIEN
// =====================================================

$queryPendaftaran = mysqli_query($koneksi, "
    SELECT
        COUNT(*) AS total_pendaftaran,
        SUM(CASE WHEN status = 'Menunggu' THEN 1 ELSE 0 END) AS menunggu,
        SUM(CASE WHEN status = 'Disetujui' THEN 1 ELSE 0 END) AS disetujui,
        SUM(CASE WHEN status = 'Ditolak' THEN 1 ELSE 0 END) AS ditolak
    FROM pendaftarancheckup
    WHERE idpasien = '$idpasien'
");

$dataPendaftaran = $queryPendaftaran
    ? mysqli_fetch_assoc($queryPendaftaran)
    : null;

$totalPendaftaran = (int) ($dataPendaftaran['total_pendaftaran'] ?? 0);
$totalMenunggu = (int) ($dataPendaftaran['menunggu'] ?? 0);
$totalDisetujui = (int) ($dataPendaftaran['disetujui'] ?? 0);


// =====================================================
// RINGKASAN ANTREAN AKTIF
// =====================================================

$queryAntreanAktif = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM pendaftarancheckup
    WHERE idpasien = '$idpasien'
    AND tanggalperiksa = CURDATE()
    AND status = 'Disetujui'
    AND statusantrian IN ('Menunggu', 'Dipanggil', 'Diperiksa')
");

$dataAntreanAktif = $queryAntreanAktif
    ? mysqli_fetch_assoc($queryAntreanAktif)
    : null;

$totalAntreanAktif = (int) ($dataAntreanAktif['total'] ?? 0);


// =====================================================
// DAFTAR HARI
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


// =====================================================
// SEMUA JADWAL DOKTER
// =====================================================

$querySemuaJadwal = mysqli_query($koneksi, "
    SELECT
        j.idjadwal,
        j.haripraktik,
        j.jam_mulai,
        j.jam_selesai,
        d.namadokter,
        d.spesialis
    FROM jadwaldokter j
    INNER JOIN dokter d ON j.iddokter = d.iddokter
    ORDER BY j.jam_mulai ASC
");

$jadwalDokter = [];

if ($querySemuaJadwal) {
    while ($dataJadwal = mysqli_fetch_assoc($querySemuaJadwal)) {
        $hari = strtolower(trim($dataJadwal['haripraktik']));
        $jadwalDokter[$hari][] = $dataJadwal;
    }
}


// =====================================================
// JADWAL DOKTER 7 HARI KE DEPAN
// =====================================================

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
// JADWAL DOKTER HARI INI
// =====================================================

$hariSekarang = $daftarHari[(int) date('N')];
$jamSekarang = date('H:i:s');

$jadwalHariIni = $jadwalDokter[$hariSekarang] ?? [];
$statusLayanan = false;

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
// 5 PENDAFTARAN TERAKHIR PASIEN
// =====================================================

$queryPendaftaranTerakhir = mysqli_query($koneksi, "
    SELECT
        pc.idpendaftaran,
        pc.tanggalperiksa,
        pc.jenispendaftaran,
        pc.nomorantrian,
        pc.status,
        pc.statusantrian,
        d.namadokter,
        d.spesialis
    FROM pendaftarancheckup pc
    INNER JOIN jadwaldokter j ON pc.idjadwal = j.idjadwal
    INNER JOIN dokter d ON j.iddokter = d.iddokter
    WHERE pc.idpasien = '$idpasien'
    ORDER BY
        pc.tanggalpengajuan DESC,
        pc.idpendaftaran DESC
    LIMIT 5
");


// =====================================================
// 5 RIWAYAT KUNJUNGAN TERAKHIR
// =====================================================

$queryRiwayat = mysqli_query($koneksi, "
    SELECT
        k.idkunjungan,
        k.tanggalkunjungan,
        k.keluhan,
        k.diagnosis,
        k.totalkunjungan,
        d.namadokter
    FROM kunjungan k
    INNER JOIN dokter d ON k.iddokter = d.iddokter
    WHERE k.idpasien = '$idpasien'
    ORDER BY
        k.tanggalkunjungan DESC,
        k.idkunjungan DESC
    LIMIT 5
");


// =====================================================
// STATUS BADGE
// =====================================================

$warnaPendaftaran = [
    'Menunggu' => 'warning',
    'Disetujui' => 'success',
    'Ditolak' => 'danger'
];

$warnaAntrean = [
    'Menunggu' => 'warning',
    'Dipanggil' => 'info',
    'Diperiksa' => 'primary',
    'Selesai' => 'success',
    'Batal' => 'danger'
];

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

.pasien-schedule,
.pasien-info {
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

@media(max-width: 575px) {
    .pasien-welcome {
        padding: 23px 18px;
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
        <div class="d-flex align-items-center">

            <div class="flex-shrink-0 me-4">
                <img
                    src="assets/images/pasien/<?= e($fotoPasien); ?>"
                    alt="Foto Pasien"
                    class="rounded-circle shadow-sm"
                    style="width:130px;height:130px;object-fit:cover;border:3px solid #fff;"
                >
            </div>

            <div class="flex-grow-1 ml-3">

                <span class="badge bg-light text-success px-3 py-2 mb-2">
                    <i class="bi bi-person-badge me-1"></i>
                    Portal Pasien
                </span>

                <h2 class="fw-bold mb-2">
                    <?= $sapaan; ?>, <?= e($namapasien); ?>!
                </h2>

                <p class="mb-2">
                    Selamat datang di portal pasien Klinik Ahmadi.
                    Lakukan pendaftaran pemeriksaan, pantau status antrean,
                    dan lihat riwayat kunjungan kesehatan Anda melalui halaman ini.
                </p>

                <div class="small">
                    <i class="bi bi-calendar3 me-2"></i>
                    <?= date('d-m-Y'); ?>

                    <span class="mx-2">|</span>

                    <i class="bi bi-shield-check me-1"></i>
                    Area Pasien
                </div>

            </div>
        </div>
    </div>


    <!-- =================================================
         INFORMASI PELAYANAN KLINIK
    ================================================== -->

    <div class="mb-3">
        <h5 class="pasien-section-title mb-1">
            <i class="bi bi-heart-pulse-fill text-success me-2"></i>
            Informasi Pelayanan Klinik
        </h5>

        <p class="pasien-subtitle mb-0">
            Periksa jadwal pelayanan sebelum melakukan pendaftaran.
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
                    Jadwal Dokter Hari Ini (<?= $namaHari[$hariSekarang]; ?>)
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
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td><?= e($jadwal['namadokter']); ?></td>
                                            <td><?= e($jadwal['spesialis']); ?></td>
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
                    <a href="index.php?page=daftardokterpasien" class="btn btn-sm btn-outline-success">
                        Lihat Daftar Dokter
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

            </div>
        </div>

    </div>


    <!-- =================================================
         RINGKASAN AKTIVITAS PASIEN
    ================================================== -->

    <div class="mb-3">
        <h5 class="pasien-section-title mb-1">
            <i class="bi bi-grid-1x2-fill text-success me-2"></i>
            Ringkasan Aktivitas Saya
        </h5>

        <p class="pasien-subtitle mb-0">
            Ringkasan pendaftaran dan kunjungan pada akun Anda.
        </p>
    </div>


    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card pasien-card shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="pasien-subtitle mb-2">Total Pendaftaran</div>
                            <div class="pasien-number"><?= $totalPendaftaran; ?></div>
                            <small class="text-success">Seluruh pengajuan</small>
                        </div>

                        <div class="pasien-icon bg-primary-subtle text-primary">
                            <i class="bi bi-calendar2-check-fill"></i>
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
                            <div class="pasien-subtitle mb-2">Menunggu Persetujuan</div>
                            <div class="pasien-number"><?= $totalMenunggu; ?></div>
                            <small class="text-warning">Belum diproses</small>
                        </div>

                        <div class="pasien-icon bg-warning-subtle text-warning">
                            <i class="bi bi-hourglass-split"></i>
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
                            <div class="pasien-subtitle mb-2">Antrean Aktif Hari Ini</div>
                            <div class="pasien-number"><?= $totalAntreanAktif; ?></div>
                            <small class="text-info">Antrean pemeriksaan</small>
                        </div>

                        <div class="pasien-icon bg-info-subtle text-info">
                            <i class="bi bi-people-fill"></i>
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
                            <div class="pasien-subtitle mb-2">Total Kunjungan</div>
                            <div class="pasien-number"><?= $totalKunjungan; ?></div>
                            <small class="text-success">Riwayat pemeriksaan</small>
                        </div>

                        <div class="pasien-icon bg-success-subtle text-success">
                            <i class="bi bi-clipboard2-pulse-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <!-- =================================================
         AKSES CEPAT LAYANAN PASIEN
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
            <a href="index.php?page=creatependaftaranpasien" class="pasien-action shadow-sm">
                <i class="bi bi-calendar2-plus-fill text-success"></i>
                <div>
                    <div class="fw-bold">Pendaftaran Checkup</div>
                    <small class="text-muted">Daftar pemeriksaan kesehatan</small>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <a href="index.php?page=antrianpasien" class="pasien-action shadow-sm">
                <i class="bi bi-people-fill text-primary"></i>
                <div>
                    <div class="fw-bold">Antrean Saya</div>
                    <small class="text-muted">Pantau nomor dan status antrean</small>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <a href="index.php?page=riwayatkunjungan" class="pasien-action shadow-sm">
                <i class="bi bi-clock-history text-warning"></i>
                <div>
                    <div class="fw-bold">Riwayat Kunjungan</div>
                    <small class="text-muted">Lihat riwayat pemeriksaan</small>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <a href="index.php?page=profilpasien" class="pasien-action shadow-sm">
                <i class="bi bi-person-vcard-fill text-info"></i>
                <div>
                    <div class="fw-bold">Profil Saya</div>
                    <small class="text-muted">Lihat informasi akun pasien</small>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <a href="index.php?page=editprofilpasien" class="pasien-action shadow-sm">
                <i class="bi bi-pencil-square text-danger"></i>
                <div>
                    <div class="fw-bold">Edit Profil</div>
                    <small class="text-muted">Perbarui data pribadi</small>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <a href="index.php?page=informasiobat" class="pasien-action shadow-sm">
                <i class="bi bi-capsule text-success"></i>
                <div>
                    <div class="fw-bold">Informasi Obat</div>
                    <small class="text-muted">Lihat informasi obat klinik</small>
                </div>
            </a>
        </div>

    </div>


    <!-- =================================================
         JADWAL DOKTER 7 HARI KE DEPAN
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
                                        <td class="text-center"><?= $no++; ?></td>

                                        <td class="text-nowrap">
                                            <?= date('d-m-Y', strtotime($hari['tanggal'])); ?>
                                        </td>

                                        <td><?= $namaHari[$hari['hari']]; ?></td>

                                        <td class="fw-semibold">
                                            <?= e($jadwal['namadokter']); ?>
                                        </td>

                                        <td><?= e($jadwal['spesialis']); ?></td>

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
         PENDAFTARAN TERAKHIR
    ================================================== -->

    <div class="mb-3">
        <h5 class="pasien-section-title mb-1">
            <i class="bi bi-calendar2-check-fill text-success me-2"></i>
            Pendaftaran Checkup Terakhir
        </h5>

        <p class="pasien-subtitle mb-0">
            Pantau status pengajuan pendaftaran pemeriksaan Anda.
        </p>
    </div>


    <div class="card pasien-info shadow-sm mb-4">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-bordered table-hover pasien-table-riwayat mb-0">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Periksa</th>
                            <th>Dokter</th>
                            <th>Jenis Pendaftaran</th>
                            <th>Nomor Antrean</th>
                            <th>Status Pendaftaran</th>
                            <th>Status Antrean</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if ($queryPendaftaranTerakhir && mysqli_num_rows($queryPendaftaranTerakhir) > 0): ?>

                            <?php $no = 1; ?>

                            <?php while ($pendaftaran = mysqli_fetch_assoc($queryPendaftaranTerakhir)): ?>

                                <?php
                                $status = $pendaftaran['status'];
                                $statusAntrean = $pendaftaran['statusantrian'] ?: 'Belum Antri';
                                ?>

                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>

                                    <td class="text-nowrap">
                                        <?= date('d-m-Y', strtotime($pendaftaran['tanggalperiksa'])); ?>
                                    </td>

                                    <td>
                                        <?= e($pendaftaran['namadokter']); ?>
                                        <small class="d-block text-muted">
                                            <?= e($pendaftaran['spesialis']); ?>
                                        </small>
                                    </td>

                                    <td><?= e($pendaftaran['jenispendaftaran']); ?></td>

                                    <td class="text-center">
                                        <?php if (!empty($pendaftaran['nomorantrian'])): ?>
                                            <span class="badge bg-dark">
                                                <?= e($pendaftaran['nomorantrian']); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <span class="badge bg-<?= $warnaPendaftaran[$status] ?? 'secondary'; ?>">
                                            <?= e($status); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-<?= $warnaAntrean[$statusAntrean] ?? 'secondary'; ?>">
                                            <?= e($statusAntrean); ?>
                                        </span>
                                    </td>
                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>
                                    Anda belum memiliki pendaftaran checkup.
                                    <br>
                                    <a href="index.php?page=creatependaftaranpasien"
                                       class="btn btn-sm btn-success mt-2">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Daftar Checkup
                                    </a>
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <small class="text-muted">Maksimal 5 pendaftaran terakhir.</small>

            <a href="index.php?page=pendaftaranpasien" class="btn btn-sm btn-success">
                Lihat Semua Pendaftaran
                <i class="bi bi-arrow-right ms-1"></i>
            </a>
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
                                    <td class="text-center"><?= $no++; ?></td>

                                    <td class="text-nowrap">
                                        <?= date('d-m-Y', strtotime($riwayat['tanggalkunjungan'])); ?>
                                    </td>

                                    <td><?= e($riwayat['namadokter']); ?></td>

                                    <td><?= e($riwayat['keluhan']); ?></td>

                                    <td><?= e($riwayat['diagnosis']); ?></td>

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
            <small class="text-muted">Maksimal 5 kunjungan terakhir.</small>

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
                        Gunakan menu Pendaftaran Checkup untuk mengajukan
                        pemeriksaan kesehatan. Setelah mengajukan pendaftaran,
                        pantau status persetujuan melalui dashboard ini.
                        Nomor antrean diberikan setelah proses check-in oleh
                        petugas klinik. Pastikan data profil dan nomor telepon
                        Anda selalu benar agar pihak klinik dapat menghubungi
                        Anda apabila diperlukan.
                    </p>
                </div>

            </div>

        </div>
    </div>

</div>
