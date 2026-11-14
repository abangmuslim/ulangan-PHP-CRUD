
<?php
// ============================================
// HALAMAN DETAIL PENDAFTARAN CHECKUP
// ZONA ADMIN DAN PETUGAS
// ============================================

// ============================================
// 1. AMBIL ID PENDAFTARAN
// ============================================

$idpendaftaran = isset($_GET['idpendaftaran'])
    ? (int)$_GET['idpendaftaran']
    : 0;

if ($idpendaftaran <= 0) {
    echo '<div class="alert alert-warning">
        ID pendaftaran tidak valid.
        <a href="index.php?page=pendaftarancheckup">Kembali</a>
    </div>';
    return;
}

// ============================================
// 2. AMBIL DATA PENDAFTARAN
// ============================================

$query = mysqli_query($koneksi, "
    SELECT
        pc.idpendaftaran,
        pc.idpasien,
        pc.idjadwal,
        pc.idkunjungan,
        pc.tanggalperiksa,
        pc.tanggalpengajuan,
        pc.tanggalcheckin,
        pc.keluhan,
        pc.jenispendaftaran,
        pc.nomorantrian,
        pc.status,
        pc.statusantrian,
        pc.catatanadmin,

        p.namapasien,
        p.nohp,
        p.alamat,

        j.haripraktik,
        j.jam_mulai,
        j.jam_selesai,

        d.namadokter,
        d.spesialis,
        d.nohp AS nohp_dokter

    FROM pendaftarancheckup pc

    INNER JOIN pasien p
        ON pc.idpasien = p.idpasien

    INNER JOIN jadwaldokter j
        ON pc.idjadwal = j.idjadwal

    INNER JOIN dokter d
        ON j.iddokter = d.iddokter

    WHERE pc.idpendaftaran = $idpendaftaran

    LIMIT 1
");

if (!$query) {
    echo '<div class="alert alert-danger">
        Gagal mengambil detail pendaftaran: ' .
        e(mysqli_error($koneksi)) . '
    </div>';
    return;
}

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo '<div class="alert alert-warning">
        Data pendaftaran tidak ditemukan.
        <a href="index.php?page=pendaftarancheckup">Kembali</a>
    </div>';
    return;
}

// ============================================
// 3. FORMAT TANGGAL
// ============================================

$tanggalPeriksa = '-';
if (!empty($data['tanggalperiksa'])) {
    $tanggalPeriksa = date(
        'd-m-Y',
        strtotime($data['tanggalperiksa'])
    );
}

$tanggalPengajuan = '-';
if (!empty($data['tanggalpengajuan'])) {
    $tanggalPengajuan = date(
        'd-m-Y H:i',
        strtotime($data['tanggalpengajuan'])
    );
}

$tanggalCheckin = '-';
if (!empty($data['tanggalcheckin'])) {
    $tanggalCheckin = date(
        'd-m-Y H:i',
        strtotime($data['tanggalcheckin'])
    );
}

$jamMulai = !empty($data['jam_mulai'])
    ? date('H:i', strtotime($data['jam_mulai']))
    : '-';

$jamSelesai = !empty($data['jam_selesai'])
    ? date('H:i', strtotime($data['jam_selesai']))
    : '-';

// ============================================
// 4. BADGE STATUS
// ============================================

$badgeStatus = 'secondary';

if ($data['status'] === 'Menunggu') {
    $badgeStatus = 'warning';
} elseif ($data['status'] === 'Disetujui') {
    $badgeStatus = 'success';
} elseif ($data['status'] === 'Ditolak') {
    $badgeStatus = 'danger';
}

// ============================================
// 5. BADGE STATUS ANTRIAN
// ============================================

$badgeAntrian = 'secondary';

if ($data['statusantrian'] === 'Menunggu') {
    $badgeAntrian = 'warning';
} elseif ($data['statusantrian'] === 'Dipanggil') {
    $badgeAntrian = 'info';
} elseif ($data['statusantrian'] === 'Diperiksa') {
    $badgeAntrian = 'primary';
} elseif ($data['statusantrian'] === 'Selesai') {
    $badgeAntrian = 'success';
}
?>

<div class="container-fluid">

    <!-- ============================================
         JUDUL HALAMAN
    ============================================= -->

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1 font-weight-bold">
                <i class="fas fa-file-medical-alt text-primary mr-2"></i>
                Detail Pendaftaran Checkup
            </h3>

            <p class="text-muted mb-0">
                Informasi lengkap pendaftaran pemeriksaan pasien.
            </p>
        </div>

        <div class="mt-3 mt-md-0">

            <a
                href="index.php?page=pendaftarancheckup"
                class="btn btn-secondary">

                <i class="fas fa-arrow-left mr-1"></i>
                Kembali

            </a>

        </div>

    </div>

    <!-- ============================================
         INFORMASI STATUS
    ============================================= -->

    <div class="row">

        <div class="col-lg-4 col-md-6">

            <div class="small-box bg-info">

                <div class="inner">

                    <h3>
                        #<?= (int)$data['idpendaftaran']; ?>
                    </h3>

                    <p>Nomor Pendaftaran</p>

                </div>

                <div class="icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>

            </div>

        </div>

        <div class="col-lg-4 col-md-6">

            <div class="small-box bg-<?= $badgeStatus === 'warning' ? 'warning' : ($badgeStatus === 'success' ? 'success' : ($badgeStatus === 'danger' ? 'danger' : 'secondary')); ?>">

                <div class="inner">

                    <h3>
                        <?= e($data['status']); ?>
                    </h3>

                    <p>Status Pendaftaran</p>

                </div>

                <div class="icon">
                    <i class="fas fa-tasks"></i>
                </div>

            </div>

        </div>

        <div class="col-lg-4 col-md-6">

            <div class="small-box bg-primary">

                <div class="inner">

                    <h3>
                        <?= !empty($data['nomorantrian'])
                            ? (int)$data['nomorantrian']
                            : '-'; ?>
                    </h3>

                    <p>Nomor Antrean</p>

                </div>

                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>

            </div>

        </div>

    </div>

    <!-- ============================================
         DETAIL DATA
    ============================================= -->

    <div class="row">

        <!-- DATA PASIEN -->
        <div class="col-lg-6">

            <div class="card card-outline card-primary shadow-sm">

                <div class="card-header">

                    <h3 class="card-title">
                        <i class="fas fa-user mr-1"></i>
                        Informasi Pasien
                    </h3>

                </div>

                <div class="card-body">

                    <table class="table table-bordered">

                        <tr>
                            <th width="38%">ID Pasien</th>
                            <td>
                                <?= (int)$data['idpasien']; ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Nama Pasien</th>
                            <td>
                                <strong>
                                    <?= e($data['namapasien']); ?>
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <th>Nomor HP</th>
                            <td>
                                <?= e($data['nohp'] ?? '-'); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Alamat</th>
                            <td>
                                <?= nl2br(e($data['alamat'] ?? '-')); ?>
                            </td>
                        </tr>

                    </table>

                </div>

            </div>

        </div>

        <!-- DATA DOKTER -->
        <div class="col-lg-6">

            <div class="card card-outline card-success shadow-sm">

                <div class="card-header">

                    <h3 class="card-title">
                        <i class="fas fa-user-md mr-1"></i>
                        Informasi Dokter
                    </h3>

                </div>

                <div class="card-body">

                    <table class="table table-bordered">

                        <tr>
                            <th width="38%">ID Jadwal</th>
                            <td>
                                <?= (int)$data['idjadwal']; ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Nama Dokter</th>
                            <td>
                                <strong>
                                    <?= e($data['namadokter']); ?>
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <th>Spesialisasi</th>
                            <td>
                                <?= e($data['spesialis'] ?? '-'); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Nomor HP</th>
                            <td>
                                <?= e($data['nohp_dokter'] ?? '-'); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Hari Praktik</th>
                            <td>
                                <?= e($data['haripraktik']); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Jam Praktik</th>
                            <td>
                                <?= e($jamMulai); ?>
                                -
                                <?= e($jamSelesai); ?>
                            </td>
                        </tr>

                    </table>

                </div>

            </div>

        </div>

        <!-- DATA PENDAFTARAN -->
        <div class="col-lg-12">

            <div class="card card-outline card-info shadow-sm">

                <div class="card-header">

                    <h3 class="card-title">
                        <i class="fas fa-clipboard-check mr-1"></i>
                        Informasi Pendaftaran
                    </h3>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">

                            <table class="table table-bordered">

                                <tr>
                                    <th width="42%">ID Pendaftaran</th>
                                    <td>
                                        #<?= (int)$data['idpendaftaran']; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Tanggal Pengajuan</th>
                                    <td>
                                        <?= e($tanggalPengajuan); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Tanggal Periksa</th>
                                    <td>
                                        <strong>
                                            <?= e($tanggalPeriksa); ?>
                                        </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Jenis Pendaftaran</th>
                                    <td>

                                        <?php if ($data['jenispendaftaran'] === 'Online'): ?>

                                            <span class="badge badge-info">
                                                Online
                                            </span>

                                        <?php else: ?>

                                            <span class="badge badge-primary">
                                                Langsung
                                            </span>

                                        <?php endif; ?>

                                    </td>
                                </tr>

                            </table>

                        </div>

                        <div class="col-md-6">

                            <table class="table table-bordered">

                                <tr>
                                    <th width="42%">Status Pendaftaran</th>
                                    <td>
                                        <span class="badge badge-<?= $badgeStatus; ?>">
                                            <?= e($data['status']); ?>
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Status Antrean</th>
                                    <td>
                                        <span class="badge badge-<?= $badgeAntrian; ?>">
                                            <?= e($data['statusantrian']); ?>
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Nomor Antrean</th>
                                    <td>
                                        <?= !empty($data['nomorantrian'])
                                            ? (int)$data['nomorantrian']
                                            : '-'; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>ID Kunjungan</th>
                                    <td>
                                        <?= !empty($data['idkunjungan'])
                                            ? (int)$data['idkunjungan']
                                            : '-'; ?>
                                    </td>
                                </tr>

                            </table>

                        </div>

                    </div>

                    <hr>

                    <!-- KELUHAN -->
                    <div class="form-group">

                        <label>
                            <i class="fas fa-comment-medical mr-1"></i>
                            Keluhan Pasien
                        </label>

                        <div class="border rounded p-3 bg-light">
                            <?= nl2br(e($data['keluhan'])); ?>
                        </div>

                    </div>

                    <!-- CATATAN ADMIN -->
                    <div class="form-group mb-0">

                        <label>
                            <i class="fas fa-sticky-note mr-1"></i>
                            Catatan Admin/Petugas
                        </label>

                        <div class="border rounded p-3 bg-light">

                            <?= !empty($data['catatanadmin'])
                                ? nl2br(e($data['catatanadmin']))
                                : '<span class="text-muted">Belum ada catatan.</span>'; ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- INFORMASI CHECK-IN -->
        <div class="col-lg-12">

            <div class="card card-outline card-warning shadow-sm">

                <div class="card-header">

                    <h3 class="card-title">
                        <i class="fas fa-sign-in-alt mr-1"></i>
                        Informasi Check-in Pasien
                    </h3>

                </div>

                <div class="card-body">

                    <table class="table table-bordered mb-0">

                        <tr>
                            <th width="25%">Tanggal Check-in</th>
                            <td>
                                <?= e($tanggalCheckin); ?>
                            </td>
                        </tr>

                    </table>

                    <small class="text-muted d-block mt-2">
                        Check-in dicatat ketika pasien datang ke klinik untuk mengikuti pemeriksaan.
                    </small>

                </div>

            </div>

        </div>

    </div>

    <!-- ============================================
         TOMBOL AKSI
    ============================================= -->

    <div class="card shadow-sm">

        <div class="card-body">

            <a
                href="index.php?page=pendaftarancheckup"
                class="btn btn-secondary">

                <i class="fas fa-arrow-left mr-1"></i>
                Kembali ke Daftar Pendaftaran

            </a>

            <?php if ($data['status'] === 'Menunggu'): ?>

                <form
                    action="index.php"
                    method="POST"
                    class="d-inline"
                    onsubmit="return confirm('Setujui pendaftaran pasien ini?');">

                    <?= csrfField(); ?>

                    <input
                        type="hidden"
                        name="page"
                        value="prosespendaftarancheckup">

                    <input
                        type="hidden"
                        name="aksi"
                        value="setujui">

                    <input
                        type="hidden"
                        name="idpendaftaran"
                        value="<?= (int)$data['idpendaftaran']; ?>">

                    <button type="submit" class="btn btn-success">

                        <i class="fas fa-check mr-1"></i>
                        Setujui Pendaftaran

                    </button>

                </form>

            <?php endif; ?>

        </div>

    </div>

</div>
