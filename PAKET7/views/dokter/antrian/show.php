
<?php
// ============================================
// DETAIL ANTREAN PASIEN DOKTER
// ============================================

// Ambil ID dokter dari session
$iddokter = (int) ($_SESSION['iddokter'] ?? 0);

// Ambil ID pendaftaran dari URL
$idpendaftaran = (int) ($_GET['idpendaftaran'] ?? 0);

// Pastikan dokter sudah login
if ($iddokter <= 0) {
    echo '<div class="alert alert-danger">
        Sesi dokter tidak ditemukan. Silakan login kembali.
    </div>';
    return;
}

// Pastikan ID pendaftaran tersedia
if ($idpendaftaran <= 0) {
    echo '<div class="alert alert-warning">
        Data pendaftaran tidak ditemukan.
    </div>';
    return;
}

// ============================================
// AMBIL DETAIL PENDAFTARAN DAN ANTREAN
// ============================================

$queryDetail = mysqli_query($koneksi, "
    SELECT
        pc.idpendaftaran,
        pc.idpasien,
        pc.idjadwal,
        pc.idkunjungan,
        pc.tanggalperiksa,
        pc.keluhan,
        pc.jenispendaftaran,
        pc.nomorantrian,
        pc.status,
        pc.statusantrian,
        pc.catatanadmin,
        pc.tanggalpengajuan,

        p.namapasien,
        p.username,
        p.nohp,
        p.alamat,
        p.foto,

        d.namadokter,
        d.spesialis,

        j.haripraktik,
        j.jam_mulai,
        j.jam_selesai

    FROM pendaftarancheckup pc

    INNER JOIN pasien p
        ON pc.idpasien = p.idpasien

    INNER JOIN jadwaldokter j
        ON pc.idjadwal = j.idjadwal

    INNER JOIN dokter d
        ON j.iddokter = d.iddokter

    WHERE pc.idpendaftaran = '$idpendaftaran'
    AND j.iddokter = '$iddokter'
    AND pc.status = 'Disetujui'
    AND pc.nomorantrian IS NOT NULL
    AND pc.statusantrian NOT IN ('Belum Antri', 'Tidak Hadir')

    LIMIT 1
");

// Periksa query
if (!$queryDetail) {
    echo '<div class="alert alert-danger">
        Gagal mengambil detail antrean:
        ' . e(mysqli_error($koneksi)) . '
    </div>';
    return;
}

// Periksa apakah data ditemukan
if (mysqli_num_rows($queryDetail) == 0) {
    echo '<div class="alert alert-warning">
        Data antrean tidak ditemukan atau bukan milik dokter yang sedang login.
    </div>';
    return;
}

// Ambil data
$antrian = mysqli_fetch_assoc($queryDetail);

// ============================================
// PERSIAPAN STATUS
// ============================================

$statusAntrian = $antrian['statusantrian'];

$warnaStatus = [
    'Menunggu'       => 'warning',
    'Dipanggil'      => 'info',
    'Diperiksa'      => 'primary',
    'Selesai'        => 'success',
    'Batal'          => 'danger',
    'Tidak Dilayani' => 'danger'
];

$badgeStatus = $warnaStatus[$statusAntrian] ?? 'secondary';

$warnaJenis = ($antrian['jenispendaftaran'] == 'Online')
    ? 'primary'
    : 'success';

// ============================================
// FOTO PASIEN
// ============================================

$fotoPasien = !empty($antrian['foto'])
    ? $antrian['foto']
    : 'default.png';
?>

<div class="container-fluid">

    <!-- JUDUL HALAMAN -->
    <div class="row mb-3">

        <div class="col-md-8">
            <h4 class="mb-1">
                <i class="fas fa-user-injured text-primary mr-2"></i>
                Detail Antrean Pasien
            </h4>

            <p class="text-muted mb-0">
                Informasi lengkap pendaftaran dan antrean pasien.
            </p>
        </div>

        <div class="col-md-4 text-md-right">
            <a href="index.php?page=antriandokter"
               class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i>
                Kembali
            </a>
        </div>

    </div>

    <!-- INFORMASI NOMOR ANTREAN -->
    <div class="card card-primary card-outline">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-3 text-center border-right">

                    <p class="text-muted mb-1">
                        Nomor Antrean
                    </p>

                    <h1 class="text-primary font-weight-bold">
                        <?= e($antrian['nomorantrian']); ?>
                    </h1>

                </div>

                <div class="col-md-5">

                    <h5 class="mb-1">
                        <?= e($antrian['namapasien']); ?>
                    </h5>

                    <p class="text-muted mb-2">
                        <?= e($antrian['nohp'] ?? '-'); ?>
                    </p>

                    <span class="badge badge-<?= $warnaJenis; ?> p-2">
                        <?= e($antrian['jenispendaftaran']); ?>
                    </span>

                </div>

                <div class="col-md-4 text-md-right">

                    <p class="text-muted mb-1">
                        Status Antrean
                    </p>

                    <h5>
                        <span class="badge badge-<?= $badgeStatus; ?> p-2">
                            <?= e($statusAntrian); ?>
                        </span>
                    </h5>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

        <!-- IDENTITAS PASIEN -->
        <div class="col-lg-6">

            <div class="card card-outline card-info">

                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user mr-1"></i>
                        Identitas Pasien
                    </h3>
                </div>

                <div class="card-body">

                    <div class="text-center mb-3">

                        <img src="assets/images/pasien/<?= e($fotoPasien); ?>"
                             class="img-circle elevation-2"
                             width="110"
                             height="110"
                             style="object-fit:cover;"
                             alt="Foto Pasien">

                        <h5 class="mt-2 mb-0">
                            <?= e($antrian['namapasien']); ?>
                        </h5>

                    </div>

                    <table class="table table-bordered">

                        <tr>
                            <th width="38%">ID Pasien</th>
                            <td><?= e($antrian['idpasien']); ?></td>
                        </tr>

                        <tr>
                            <th>Username</th>
                            <td><?= e($antrian['username'] ?? '-'); ?></td>
                        </tr>

                        <tr>
                            <th>Nomor HP</th>
                            <td><?= e($antrian['nohp'] ?? '-'); ?></td>
                        </tr>

                        <tr>
                            <th>Alamat</th>
                            <td><?= e($antrian['alamat'] ?? '-'); ?></td>
                        </tr>

                    </table>

                </div>

            </div>

        </div>

        <!-- INFORMASI PENDAFTARAN -->
        <div class="col-lg-6">

            <div class="card card-outline card-primary">

                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clipboard-list mr-1"></i>
                        Informasi Pendaftaran
                    </h3>
                </div>

                <div class="card-body">

                    <table class="table table-bordered">

                        <tr>
                            <th width="40%">ID Pendaftaran</th>
                            <td>
                                <?= e($antrian['idpendaftaran']); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Tanggal Pemeriksaan</th>
                            <td>
                                <?= date('d-m-Y', strtotime($antrian['tanggalperiksa'])); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Tanggal Pengajuan</th>
                            <td>
                                <?= date('d-m-Y H:i', strtotime($antrian['tanggalpengajuan'])); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Jenis Pendaftaran</th>
                            <td>
                                <span class="badge badge-<?= $warnaJenis; ?>">
                                    <?= e($antrian['jenispendaftaran']); ?>
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th>Status Pendaftaran</th>
                            <td>
                                <span class="badge badge-success">
                                    <?= e($antrian['status']); ?>
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th>Nomor Antrean</th>
                            <td>
                                <strong>
                                    <?= e($antrian['nomorantrian']); ?>
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <th>ID Kunjungan</th>
                            <td>
                                <?= !empty($antrian['idkunjungan'])
                                    ? e($antrian['idkunjungan'])
                                    : '<span class="text-muted">Belum terbentuk</span>'; ?>
                            </td>
                        </tr>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <!-- INFORMASI JADWAL DOKTER -->
    <div class="card card-outline card-success">

        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-calendar-alt mr-1"></i>
                Jadwal Praktik Dokter
            </h3>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <p class="text-muted mb-1">
                        Nama Dokter
                    </p>

                    <h5>
                        <?= e($antrian['namadokter']); ?>
                    </h5>

                </div>

                <div class="col-md-4">

                    <p class="text-muted mb-1">
                        Spesialis
                    </p>

                    <h5>
                        <?= e($antrian['spesialis'] ?? '-'); ?>
                    </h5>

                </div>

                <div class="col-md-4">

                    <p class="text-muted mb-1">
                        Hari Praktik
                    </p>

                    <h5>
                        <?= e($antrian['haripraktik']); ?>
                    </h5>

                </div>

            </div>

            <hr>

            <div class="row">

                <div class="col-md-6">

                    <p class="text-muted mb-1">
                        Jam Mulai
                    </p>

                    <h5>
                        <i class="fas fa-clock text-success mr-1"></i>
                        <?= e(substr($antrian['jam_mulai'], 0, 5)); ?>
                    </h5>

                </div>

                <div class="col-md-6">

                    <p class="text-muted mb-1">
                        Jam Selesai
                    </p>

                    <h5>
                        <i class="fas fa-clock text-danger mr-1"></i>
                        <?= e(substr($antrian['jam_selesai'], 0, 5)); ?>
                    </h5>

                </div>

            </div>

        </div>

    </div>

    <!-- KELUHAN PASIEN -->
    <div class="card card-outline card-warning">

        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-notes-medical mr-1"></i>
                Keluhan Pasien
            </h3>
        </div>

        <div class="card-body">

            <?= nl2br(e($antrian['keluhan'])); ?>

        </div>

    </div>

    <!-- CATATAN ADMIN / PETUGAS -->
    <div class="card card-outline card-secondary">

        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-comment-alt mr-1"></i>
                Catatan Admin / Petugas
            </h3>
        </div>

        <div class="card-body">

            <?php if (!empty($antrian['catatanadmin'])): ?>

                <?= nl2br(e($antrian['catatanadmin'])); ?>

            <?php else: ?>

                <span class="text-muted">
                    Tidak ada catatan dari admin/petugas.
                </span>

            <?php endif; ?>

        </div>

    </div>

    <!-- INFORMASI -->
    <div class="alert alert-info">

        <i class="fas fa-info-circle mr-1"></i>

        Data ini merupakan informasi pendaftaran dan antrean pasien
        yang telah disetujui oleh admin/petugas.

    </div>

</div>
