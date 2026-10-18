<?php
// Ringkasan data dashboard
include_once "proses/prosesdashboarduser.php";

// Menghitung jumlah data dari tabel
function totalDataDashboard($koneksi, $tabel) {
    $tabelDiizinkan = [
        'user', 'pasien', 'dokter', 'obat',
        'kunjungan', 'antrian'
    ];

    if (!in_array($tabel, $tabelDiizinkan, true)) {
        return 0;
    }

    $query = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM `$tabel`");

    if (!$query) {
        return 0;
    }

    $data = mysqli_fetch_assoc($query);
    return (int)($data['total'] ?? 0);
}

$totalUser = totalDataDashboard($koneksi, 'user');
$totalPasien = totalDataDashboard($koneksi, 'pasien');
$totalDokter = totalDataDashboard($koneksi, 'dokter');
$totalObat = totalDataDashboard($koneksi, 'obat');
$totalKunjungan = totalDataDashboard($koneksi, 'kunjungan');
$totalAntrian = totalDataDashboard($koneksi, 'antrian');

$namaAdmin = $_SESSION['namauser'] ?? 'Administrator';
?>

<!-- Content Header -->
<section class="content-header pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-7">
                <h1 class="m-0" style="font-size:23px;font-weight:600;">
                    <i class="fas fa-hospital text-primary mr-2"></i>
                    Dashboard Admin
                </h1>
                <p class="text-muted mb-0 mt-1" style="font-size:12px;">
                    Selamat datang, <?= htmlspecialchars($namaAdmin); ?>.
                    Berikut ringkasan data Rumah Sakit Ahmadi.
                </p>
            </div>
            <div class="col-sm-5 text-sm-right mt-2 mt-sm-0">
                <span class="badge badge-light border p-2" style="font-size:11px;">
                    <i class="far fa-calendar-alt mr-1"></i>
                    <?= date('d-m-Y'); ?>
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content">
    <div class="container-fluid">

        <!-- Statistik -->
        <div class="row">

            <div class="col-6 col-md-4 col-xl-2">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= number_format($totalPasien); ?></h3>
                        <p>Data Pasien</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-injured"></i>
                    </div>
                    <a href="index.php?page=pasien" class="small-box-footer">
                        Lihat Data <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= number_format($totalDokter); ?></h3>
                        <p>Data Dokter</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <a href="index.php?page=dokter" class="small-box-footer">
                        Lihat Data <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= number_format($totalKunjungan); ?></h3>
                        <p>Kunjungan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-notes-medical"></i>
                    </div>
                    <a href="index.php?page=kunjungan" class="small-box-footer">
                        Lihat Data <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= number_format($totalAntrian); ?></h3>
                        <p>Data Antrian</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-list-ol"></i>
                    </div>
                    <a href="index.php?page=antrian" class="small-box-footer">
                        Lihat Data <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3><?= number_format($totalObat); ?></h3>
                        <p>Data Obat</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <a href="index.php?page=obat" class="small-box-footer">
                        Lihat Data <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3><?= number_format($totalUser); ?></h3>
                        <p>Data User</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <a href="index.php?page=user" class="small-box-footer">
                        Lihat Data <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

        </div>

        <!-- Panel Informasi -->
        <div class="row">

            <div class="col-lg-8">
                <div class="card card-outline card-primary">
                    <div class="card-header py-2">
                        <h3 class="card-title" style="font-size:14px;font-weight:600;">
                            <i class="fas fa-th-large mr-1"></i>
                            Akses Cepat
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <div class="col-6 col-md-3 mb-3">
                                <a href="index.php?page=createpasien"
                                   class="btn btn-light border btn-block py-3">
                                    <i class="fas fa-user-plus text-info d-block mb-2"
                                       style="font-size:22px;"></i>
                                    <span style="font-size:12px;">Tambah Pasien</span>
                                </a>
                            </div>

                            <div class="col-6 col-md-3 mb-3">
                                <a href="index.php?page=createdokter"
                                   class="btn btn-light border btn-block py-3">
                                    <i class="fas fa-user-md text-success d-block mb-2"
                                       style="font-size:22px;"></i>
                                    <span style="font-size:12px;">Tambah Dokter</span>
                                </a>
                            </div>

                            <div class="col-6 col-md-3 mb-3">
                                <a href="index.php?page=createkunjungan"
                                   class="btn btn-light border btn-block py-3">
                                    <i class="fas fa-notes-medical text-primary d-block mb-2"
                                       style="font-size:22px;"></i>
                                    <span style="font-size:12px;">Kunjungan Baru</span>
                                </a>
                            </div>

                            <div class="col-6 col-md-3 mb-3">
                                <a href="index.php?page=createantrian"
                                   class="btn btn-light border btn-block py-3">
                                    <i class="fas fa-list-ol text-danger d-block mb-2"
                                       style="font-size:22px;"></i>
                                    <span style="font-size:12px;">Tambah Antrian</span>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-outline card-success">
                    <div class="card-header py-2">
                        <h3 class="card-title" style="font-size:14px;font-weight:600;">
                            <i class="fas fa-hospital-user mr-1"></i>
                            Informasi Sistem
                        </h3>
                    </div>

                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            <div>
                                <div style="font-size:12px;font-weight:600;">
                                    Sistem Rumah Sakit
                                </div>
                                <small class="text-muted">Rumah Sakit Ahmadi</small>
                            </div>
                            <span class="badge badge-success ml-auto">Aktif</span>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-database text-primary mr-2"></i>
                            <div>
                                <div style="font-size:12px;font-weight:600;">
                                    Database
                                </div>
                                <small class="text-muted">rumahsakitahmadi</small>
                            </div>
                            <span class="badge badge-light border ml-auto">MySQL</span>
                        </div>

                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-shield text-warning mr-2"></i>
                            <div>
                                <div style="font-size:12px;font-weight:600;">
                                    Hak Akses
                                </div>
                                <small class="text-muted">Administrator</small>
                            </div>
                            <span class="badge badge-primary ml-auto">
                                <?= htmlspecialchars(ucfirst($_SESSION['role'] ?? 'Admin')); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Catatan Dashboard -->
        <div class="alert alert-light border mt-1 py-2 mb-3">
            <i class="fas fa-info-circle text-primary mr-1"></i>
            <span style="font-size:12px;">
                Gunakan menu di sidebar untuk mengelola data pasien, dokter,
                obat, jadwal dokter, kunjungan, antrian, dan laporan rumah sakit.
            </span>
        </div>

    </div>
</section>