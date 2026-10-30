
<?php
// =====================================================
// DATA ADMINISTRATOR
// =====================================================

$namauser = htmlspecialchars(
    $_SESSION['namauser'] ?? 'Administrator',
    ENT_QUOTES,
    'UTF-8'
);


// =====================================================
// RINGKASAN DATA KLINIK
// =====================================================

$resultPasien = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pasien");
$totalPasien = $resultPasien ? mysqli_fetch_assoc($resultPasien)['total'] : 0;

$resultDokter = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM dokter");
$totalDokter = $resultDokter ? mysqli_fetch_assoc($resultDokter)['total'] : 0;

$resultObat = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM obat");
$totalObat = $resultObat ? mysqli_fetch_assoc($resultObat)['total'] : 0;

$resultKunjungan = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM kunjungan");
$totalKunjungan = $resultKunjungan ? mysqli_fetch_assoc($resultKunjungan)['total'] : 0;

$resultJadwal = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM jadwaldokter");
$totalJadwal = $resultJadwal ? mysqli_fetch_assoc($resultJadwal)['total'] : 0;

$resultKategori = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM kategori");
$totalKategori = $resultKategori ? mysqli_fetch_assoc($resultKategori)['total'] : 0;

$resultMerk = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM merk");
$totalMerk = $resultMerk ? mysqli_fetch_assoc($resultMerk)['total'] : 0;

$resultUser = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM user");
$totalUser = $resultUser ? mysqli_fetch_assoc($resultUser)['total'] : 0;


// =====================================================
// JADWAL DOKTER MINGGU INI
// =====================================================

// Urutan hari dimulai dari Senin
$daftarHari = [
    'senin',
    'selasa',
    'rabu',
    'kamis',
    'jumat',
    'sabtu',
    'minggu'
];

// Menentukan hari pertama minggu ini
$nomorHariIni = (int) date('N');
$posisiHariIni = $nomorHariIni - 1;

// Mengurutkan hari mulai hari ini sampai Minggu,
// kemudian dilanjutkan Senin sampai sebelum hari ini.
$hariMingguIni = array_merge(
    array_slice($daftarHari, $posisiHariIni),
    array_slice($daftarHari, 0, $posisiHariIni)
);

// Membuat daftar hari untuk query SQL
$hariSQL = "'" . implode("','", $hariMingguIni) . "'";

// Mengambil jadwal dokter minggu ini
$queryJadwalMinggu = mysqli_query($koneksi, "
    SELECT DISTINCT
        dokter.namadokter,
        jadwaldokter.haripraktik
    FROM jadwaldokter
    INNER JOIN dokter
        ON jadwaldokter.iddokter = dokter.iddokter
    WHERE LOWER(jadwaldokter.haripraktik) IN ($hariSQL)
    ORDER BY
        FIELD(LOWER(jadwaldokter.haripraktik), $hariSQL),
        dokter.namadokter ASC
");


// =====================================================
// 10 KUNJUNGAN TERAKHIR
// =====================================================

$queryKunjunganTerakhir = mysqli_query($koneksi, "
    SELECT
        kunjungan.idkunjungan,
        kunjungan.tanggalkunjungan,
        kunjungan.keluhan,
        kunjungan.diagnosis,
        kunjungan.totalkunjungan,

        pasien.namapasien,
        dokter.namadokter,
        user.namauser,

        GROUP_CONCAT(
            DISTINCT CONCAT(
                obat.namaobat,
                ' (',
                detilkunjungan.jumlah,
                ')'
            )
            SEPARATOR ', '
        ) AS daftarobat

    FROM kunjungan

    INNER JOIN pasien
        ON kunjungan.idpasien = pasien.idpasien

    INNER JOIN dokter
        ON kunjungan.iddokter = dokter.iddokter

    INNER JOIN user
        ON kunjungan.iduser = user.iduser

    LEFT JOIN detilkunjungan
        ON kunjungan.idkunjungan = detilkunjungan.idkunjungan

    LEFT JOIN obat
        ON detilkunjungan.idobat = obat.idobat

    GROUP BY
        kunjungan.idkunjungan,
        kunjungan.tanggalkunjungan,
        kunjungan.keluhan,
        kunjungan.diagnosis,
        kunjungan.totalkunjungan,
        pasien.namapasien,
        dokter.namadokter,
        user.namauser

    ORDER BY kunjungan.tanggalkunjungan DESC,
             kunjungan.idkunjungan DESC

    LIMIT 10
");

?>

<style>

/* =====================================================
   STYLE DASHBOARD
===================================================== */

.dashboard-welcome {
    background: linear-gradient(120deg, #146c43, #198754);
    color: white;
    border-radius: 16px;
    padding: 30px;
    position: relative;
    overflow: hidden;
}

.dashboard-welcome .welcome-icon {
    font-size: 100px;
    opacity: .18;
}

.dashboard-subtitle {
    color: #7b8794;
    font-size: 13px;
}

.dashboard-section-title {
    font-size: 18px;
    font-weight: 700;
    color: #343a40;
}

/* Kartu ringkasan */

.dashboard-card {
    border: none;
    border-radius: 12px;
    height: 100%;
    transition: .2s ease;
}

.dashboard-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 7px 18px rgba(0,0,0,.09) !important;
}

.dashboard-icon {
    width: 43px;
    height: 43px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
    flex-shrink: 0;
}

.dashboard-number {
    font-size: 25px;
    font-weight: 700;
    color: #263238;
    line-height: 1.2;
}

/* Kartu jadwal dokter */

.dashboard-jadwal {
    border: none;
    border-radius: 12px;
    height: 100%;
}

.dashboard-jadwal .card-header {
    background: #198754;
    color: white;
    font-weight: 600;
    padding: 13px 15px;
    border-radius: 12px 12px 0 0;
}

.table-jadwal th {
    background: #e8f5ed;
    color: #146c43;
    font-size: 12px;
    white-space: nowrap;
}

.table-jadwal td {
    font-size: 12px;
    vertical-align: middle;
}

/* Akses cepat */

.dashboard-action {
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 16px;
    color: #343a40;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: .2s;
    height: 100%;
    background: white;
}

.dashboard-action:hover {
    border-color: #198754;
    color: #198754;
    background: #f5fbf7;
}

.dashboard-action i {
    font-size: 25px;
}

/* Tabel kunjungan */

.table-kunjungan th {
    background: #198754;
    color: white;
    font-size: 12px;
    white-space: nowrap;
    vertical-align: middle;
}

.table-kunjungan td {
    font-size: 12px;
    vertical-align: middle;
}

.table-kunjungan tbody tr:hover {
    background: #f3faf5;
}

.text-kunjungan {
    max-width: 180px;
    min-width: 120px;
    white-space: normal;
}

.card-dashboard {
    border: none;
    border-radius: 13px;
    overflow: hidden;
}

@media(max-width: 575px) {

    .dashboard-welcome {
        padding: 23px 18px;
    }

    .dashboard-welcome .welcome-icon {
        display: none;
    }

    .dashboard-number {
        font-size: 23px;
    }

}

</style>


<div class="container-fluid p-0">


    <!-- =================================================
         SAMBUTAN DASHBOARD
    ================================================== -->

    <div class="dashboard-welcome shadow-sm mb-4">

        <div class="row align-items-center">

            <div class="col-md-9">

                <div class="mb-2">
                    <span class="badge bg-light text-success px-3 py-2">
                        <i class="bi bi-hospital me-1"></i>
                        Administrator Panel
                    </span>
                </div>

                <h2 class="fw-bold mb-2">
                    Selamat Datang, <?= $namauser; ?>!
                </h2>

                <p class="mb-3">
                    Dashboard Sistem Informasi Klinik Rumah Sakit Ahmadi.
                    Kelola data pasien, dokter, jadwal pelayanan, obat,
                    dan kunjungan pasien dengan lebih mudah.
                </p>

                <div class="small">
                    <i class="bi bi-calendar3 me-2"></i>
                    <?= date('d-m-Y'); ?>

                    <span class="mx-2">|</span>

                    <i class="bi bi-shield-check me-1"></i>
                    Area Administrator
                </div>

            </div>

            <div class="col-md-3 text-center">
                <i class="bi bi-hospital welcome-icon"></i>
            </div>

        </div>

    </div>


    <!-- =================================================
         RINGKASAN DATA DAN JADWAL DOKTER
    ================================================== -->

    <div class="mb-3">

        <h5 class="dashboard-section-title mb-1">
            <i class="bi bi-grid-1x2-fill text-success me-2"></i>
            Ringkasan Data Klinik
        </h5>

        <p class="dashboard-subtitle mb-0">
            Informasi jumlah data yang tersimpan dalam sistem.
        </p>

    </div>


    <div class="row g-3 mb-4">


        <!-- BAGIAN KIRI: RINGKASAN DATA -->

        <div class="col-12 col-xl-9">

            <div class="row g-3">


                <!-- TOTAL PASIEN -->

                <div class="col-6 col-md-3">

                    <a href="index.php?page=pasien" class="text-decoration-none">

                        <div class="card dashboard-card shadow-sm">

                            <div class="card-body p-3">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>
                                        <div class="dashboard-subtitle mb-2">
                                            Total Pasien
                                        </div>

                                        <div class="dashboard-number">
                                            <?= $totalPasien; ?>
                                        </div>

                                        <small class="text-success">
                                            Data pasien
                                        </small>
                                    </div>

                                    <div class="dashboard-icon bg-primary-subtle text-primary">
                                        <i class="bi bi-people-fill"></i>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </a>

                </div>


                <!-- TOTAL DOKTER -->

                <div class="col-6 col-md-3">

                    <a href="index.php?page=dokter" class="text-decoration-none">

                        <div class="card dashboard-card shadow-sm">

                            <div class="card-body p-3">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>
                                        <div class="dashboard-subtitle mb-2">
                                            Total Dokter
                                        </div>

                                        <div class="dashboard-number">
                                            <?= $totalDokter; ?>
                                        </div>

                                        <small class="text-success">
                                            Tenaga medis
                                        </small>
                                    </div>

                                    <div class="dashboard-icon bg-success-subtle text-success">
                                        <i class="bi bi-person-badge-fill"></i>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </a>

                </div>


                <!-- TOTAL OBAT -->

                <div class="col-6 col-md-3">

                    <a href="index.php?page=obat" class="text-decoration-none">

                        <div class="card dashboard-card shadow-sm">

                            <div class="card-body p-3">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>
                                        <div class="dashboard-subtitle mb-2">
                                            Total Obat
                                        </div>

                                        <div class="dashboard-number">
                                            <?= $totalObat; ?>
                                        </div>

                                        <small class="text-success">
                                            Data obat
                                        </small>
                                    </div>

                                    <div class="dashboard-icon bg-warning-subtle text-warning">
                                        <i class="bi bi-capsule"></i>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </a>

                </div>


                <!-- TOTAL KUNJUNGAN -->

                <div class="col-6 col-md-3">

                    <a href="index.php?page=kunjungan" class="text-decoration-none">

                        <div class="card dashboard-card shadow-sm">

                            <div class="card-body p-3">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>
                                        <div class="dashboard-subtitle mb-2">
                                            Total Kunjungan
                                        </div>

                                        <div class="dashboard-number">
                                            <?= $totalKunjungan; ?>
                                        </div>

                                        <small class="text-success">
                                            Transaksi kunjungan
                                        </small>
                                    </div>

                                    <div class="dashboard-icon bg-danger-subtle text-danger">
                                        <i class="bi bi-clipboard2-pulse-fill"></i>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </a>

                </div>


                <!-- TOTAL JADWAL -->

                <div class="col-6 col-md-3">

                    <a href="index.php?page=jadwaldokter" class="text-decoration-none">

                        <div class="card dashboard-card shadow-sm">

                            <div class="card-body p-3">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>
                                        <div class="dashboard-subtitle mb-2">
                                            Jadwal Dokter
                                        </div>

                                        <div class="dashboard-number">
                                            <?= $totalJadwal; ?>
                                        </div>

                                        <small class="text-success">
                                            Data jadwal
                                        </small>
                                    </div>

                                    <div class="dashboard-icon bg-info-subtle text-info">
                                        <i class="bi bi-calendar-week-fill"></i>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </a>

                </div>


                <!-- TOTAL KATEGORI -->

                <div class="col-6 col-md-3">

                    <a href="index.php?page=kategori" class="text-decoration-none">

                        <div class="card dashboard-card shadow-sm">

                            <div class="card-body p-3">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>
                                        <div class="dashboard-subtitle mb-2">
                                            Kategori Obat
                                        </div>

                                        <div class="dashboard-number">
                                            <?= $totalKategori; ?>
                                        </div>

                                        <small class="text-success">
                                            Jenis kategori
                                        </small>
                                    </div>

                                    <div class="dashboard-icon bg-secondary-subtle text-secondary">
                                        <i class="bi bi-tags-fill"></i>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </a>

                </div>


                <!-- TOTAL MERK -->

                <div class="col-6 col-md-3">

                    <a href="index.php?page=merk" class="text-decoration-none">

                        <div class="card dashboard-card shadow-sm">

                            <div class="card-body p-3">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>
                                        <div class="dashboard-subtitle mb-2">
                                            Merek Obat
                                        </div>

                                        <div class="dashboard-number">
                                            <?= $totalMerk; ?>
                                        </div>

                                        <small class="text-success">
                                            Data merek
                                        </small>
                                    </div>

                                    <div class="dashboard-icon bg-primary-subtle text-primary">
                                        <i class="bi bi-bookmark-star-fill"></i>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </a>

                </div>


                <!-- TOTAL ADMIN -->

                <div class="col-6 col-md-3">

                    <a href="index.php?page=user" class="text-decoration-none">

                        <div class="card dashboard-card shadow-sm">

                            <div class="card-body p-3">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>
                                        <div class="dashboard-subtitle mb-2">
                                            Administrator
                                        </div>

                                        <div class="dashboard-number">
                                            <?= $totalUser; ?>
                                        </div>

                                        <small class="text-success">
                                            Pengelola sistem
                                        </small>
                                    </div>

                                    <div class="dashboard-icon bg-dark-subtle text-dark">
                                        <i class="bi bi-person-gear"></i>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </a>

                </div>


            </div>

        </div>


        <!-- BAGIAN KANAN: JADWAL DOKTER MINGGU INI -->

        <div class="col-12 col-xl-3">

            <div class="card dashboard-jadwal shadow-sm">

                <div class="card-header">
                    <i class="bi bi-calendar2-week me-2"></i>
                    Dokter Minggu Ini
                </div>

                <div class="card-body p-2">

                    <div class="table-responsive">

                        <table class="table table-bordered table-striped table-jadwal mb-0">

                            <thead>
                                <tr>
                                    <th width="12%">No</th>
                                    <th>Dokter</th>
                                    <th>Hari</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if ($queryJadwalMinggu && mysqli_num_rows($queryJadwalMinggu) > 0): ?>

                                    <?php $no = 1; ?>

                                    <?php while ($jadwal = mysqli_fetch_assoc($queryJadwalMinggu)): ?>

                                        <tr>

                                            <td class="text-center">
                                                <?= $no++; ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($jadwal['namadokter']); ?>
                                            </td>

                                            <td>
                                                <?= ucfirst(htmlspecialchars($jadwal['haripraktik'])); ?>
                                            </td>

                                        </tr>

                                    <?php endwhile; ?>

                                <?php else: ?>

                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">
                                            Belum ada jadwal dokter minggu ini.
                                        </td>
                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

                <div class="card-footer bg-white text-muted small">
                    <i class="bi bi-info-circle me-1"></i>
                    Jadwal praktik minggu berjalan.
                </div>

            </div>

        </div>


    </div>


    <!-- =================================================
         AKSES CEPAT
    ================================================== -->

    <div class="mb-3">

        <h5 class="dashboard-section-title mb-1">
            <i class="bi bi-lightning-charge-fill text-warning me-2"></i>
            Akses Cepat
        </h5>

        <p class="dashboard-subtitle mb-0">
            Pilih menu untuk menjalankan aktivitas administrasi klinik.
        </p>

    </div>


    <div class="row g-3 mb-4">


        <div class="col-12 col-md-6 col-xl-3">

            <a href="index.php?page=createpasien" class="dashboard-action shadow-sm">

                <i class="bi bi-person-plus-fill text-primary"></i>

                <div>
                    <div class="fw-bold">Tambah Pasien</div>
                    <small class="text-muted">Registrasi pasien baru</small>
                </div>

            </a>

        </div>


        <div class="col-12 col-md-6 col-xl-3">

            <a href="index.php?page=createkunjungan" class="dashboard-action shadow-sm">

                <i class="bi bi-clipboard-plus-fill text-success"></i>

                <div>
                    <div class="fw-bold">Catat Kunjungan</div>
                    <small class="text-muted">Input pemeriksaan pasien</small>
                </div>

            </a>

        </div>


        <div class="col-12 col-md-6 col-xl-3">

            <a href="index.php?page=createobat" class="dashboard-action shadow-sm">

                <i class="bi bi-capsule-pill text-warning"></i>

                <div>
                    <div class="fw-bold">Tambah Obat</div>
                    <small class="text-muted">Kelola data obat</small>
                </div>

            </a>

        </div>


        <div class="col-12 col-md-6 col-xl-3">

            <a href="index.php?page=jadwaldokter" class="dashboard-action shadow-sm">

                <i class="bi bi-calendar-check-fill text-info"></i>

                <div>
                    <div class="fw-bold">Jadwal Dokter</div>
                    <small class="text-muted">Lihat jadwal pelayanan</small>
                </div>

            </a>

        </div>


    </div>


    <!-- =================================================
         INFORMASI SISTEM
    ================================================== -->

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 13px;">

        <div class="card-body p-4">

            <div class="d-flex align-items-start gap-3">

                <div class="dashboard-icon bg-success-subtle text-success flex-shrink-0">
                    <i class="bi bi-info-circle-fill"></i>
                </div>

                <div>

                    <h6 class="fw-bold mb-2">
                        Informasi Sistem Klinik
                    </h6>

                    <p class="text-muted small mb-0">
                        Gunakan sistem ini untuk mengelola data pasien,
                        dokter, jadwal pelayanan, persediaan obat,
                        kategori, merek obat, serta transaksi kunjungan.
                        Pastikan data yang dimasukkan selalu benar dan
                        diperbarui sesuai aktivitas pelayanan klinik.
                    </p>

                </div>

            </div>

        </div>

    </div>


    <!-- =================================================
         10 KUNJUNGAN TERAKHIR
    ================================================== -->

    <div class="mb-3">

        <h5 class="dashboard-section-title mb-1">
            <i class="bi bi-clipboard2-pulse-fill text-success me-2"></i>
            10 Kunjungan Terakhir
        </h5>

        <p class="dashboard-subtitle mb-0">
            Riwayat kunjungan pasien yang terakhir dicatat dalam sistem.
        </p>

    </div>


    <div class="card card-dashboard shadow-sm mb-4">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover table-kunjungan mb-0">

                    <thead>

                        <tr>
                            <th class="text-center">No</th>
                            <th>Tanggal</th>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th>Admin</th>
                            <th>Keluhan</th>
                            <th>Diagnosis</th>
                            <th>Obat</th>
                            <th class="text-end">Total Biaya</th>
                        </tr>

                    </thead>


                    <tbody>

                        <?php if ($queryKunjunganTerakhir && mysqli_num_rows($queryKunjunganTerakhir) > 0): ?>

                            <?php $no = 1; ?>

                            <?php while ($kunjungan = mysqli_fetch_assoc($queryKunjunganTerakhir)): ?>

                                <tr>

                                    <td class="text-center">
                                        <?= $no++; ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= date('d-m-Y', strtotime($kunjungan['tanggalkunjungan'])); ?>
                                    </td>

                                    <td class="fw-semibold">
                                        <?= htmlspecialchars($kunjungan['namapasien']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($kunjungan['namadokter']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($kunjungan['namauser']); ?>
                                    </td>

                                    <td class="text-kunjungan">
                                        <?= htmlspecialchars($kunjungan['keluhan']); ?>
                                    </td>

                                    <td class="text-kunjungan">
                                        <?= htmlspecialchars($kunjungan['diagnosis']); ?>
                                    </td>

                                    <td class="text-kunjungan">

                                        <?php if (!empty($kunjungan['daftarobat'])): ?>

                                            <?= htmlspecialchars($kunjungan['daftarobat']); ?>

                                        <?php else: ?>

                                            <span class="text-muted">Tidak ada obat</span>

                                        <?php endif; ?>

                                    </td>

                                    <td class="text-end text-nowrap fw-semibold text-success">
                                        Rp <?= number_format((float) $kunjungan['totalkunjungan'], 0, ',', '.'); ?>
                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="9" class="text-center py-4 text-muted">

                                    <i class="bi bi-clipboard-x fs-3 d-block mb-2"></i>

                                    Belum ada data kunjungan pasien.

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>


        <div class="card-footer bg-white d-flex justify-content-between align-items-center">

            <small class="text-muted">
                Menampilkan maksimal 10 kunjungan terakhir.
            </small>

            <a href="index.php?page=kunjungan" class="btn btn-sm btn-success">
                Lihat Semua Kunjungan
                <i class="bi bi-arrow-right ms-1"></i>
            </a>

        </div>

    </div>


</div>
