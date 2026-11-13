
<?php
// ============================================
// DAFTAR ANTREAN PASIEN DOKTER
// ============================================

// Ambil ID dokter dari session
$iddokter = (int) ($_SESSION['iddokter'] ?? 0);

// Pastikan dokter sudah login
if ($iddokter <= 0) {
    echo '<div class="alert alert-danger">
        Sesi dokter tidak ditemukan. Silakan login kembali.
    </div>';
    return;
}

// ============================================
// AMBIL DATA ANTREAN HARI INI
// ============================================

$queryAntrian = mysqli_query($koneksi, "
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
        p.nohp,
        p.alamat,
        j.haripraktik,
        j.jam_mulai,
        j.jam_selesai
    FROM pendaftarancheckup pc
    INNER JOIN pasien p ON pc.idpasien = p.idpasien
    INNER JOIN jadwaldokter j ON pc.idjadwal = j.idjadwal
    WHERE j.iddokter = '$iddokter'
    AND pc.tanggalperiksa = CURDATE()
    AND pc.status = 'Disetujui'
    AND pc.nomorantrian IS NOT NULL
    AND pc.statusantrian NOT IN ('Belum Antri', 'Tidak Hadir')
    ORDER BY
        pc.nomorantrian ASC,
        pc.tanggalpengajuan ASC
");

// Periksa query
if (!$queryAntrian) {
    echo '<div class="alert alert-danger">
        Gagal mengambil data antrean:
        ' . e(mysqli_error($koneksi)) . '
    </div>';
    return;
}

// ============================================
// HITUNG JUMLAH ANTREAN
// ============================================

$daftarAntrian = [];

$totalAntrian = 0;
$totalMenunggu = 0;
$totalDipanggil = 0;
$totalDiperiksa = 0;
$totalSelesai = 0;
$totalBatal = 0;

while ($data = mysqli_fetch_assoc($queryAntrian)) {

    $daftarAntrian[] = $data;

    $totalAntrian++;

    switch ($data['statusantrian']) {

        case 'Menunggu':
            $totalMenunggu++;
            break;

        case 'Dipanggil':
            $totalDipanggil++;
            break;

        case 'Diperiksa':
            $totalDiperiksa++;
            break;

        case 'Selesai':
            $totalSelesai++;
            break;

        case 'Batal':
        case 'Tidak Dilayani':
            $totalBatal++;
            break;
    }
}

// ============================================
// WARNA STATUS ANTREAN
// ============================================

$warnaStatus = [
    'Menunggu'       => 'warning',
    'Dipanggil'      => 'info',
    'Diperiksa'      => 'primary',
    'Selesai'        => 'success',
    'Batal'          => 'danger',
    'Tidak Dilayani' => 'danger'
];
?>

<div class="container-fluid">

    <!-- JUDUL HALAMAN -->
    <div class="row mb-3">
        <div class="col-md-8">
            <h4 class="mb-1">
                <i class="fas fa-users text-primary mr-2"></i>
                Antrean Pasien
            </h4>
            <p class="text-muted mb-0">
                Daftar pasien yang telah disetujui dan mendapatkan nomor antrean hari ini.
            </p>
        </div>

        <div class="col-md-4 text-md-right">
            <span class="badge badge-primary p-2">
                <i class="fas fa-calendar-alt mr-1"></i>
                <?= date('d-m-Y'); ?>
            </span>
        </div>
    </div>

    <!-- RINGKASAN ANTREAN -->
    <div class="row">

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= $totalAntrian; ?></h3>
                    <p>Total Antrean</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?= $totalMenunggu; ?></h3>
                    <p>Menunggu</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3><?= $totalDiperiksa; ?></h3>
                    <p>Sedang Diperiksa</p>
                </div>
                <div class="icon">
                    <i class="fas fa-stethoscope"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= $totalSelesai; ?></h3>
                    <p>Selesai</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- RINGKASAN TAMBAHAN -->
    <div class="row mb-3">

        <div class="col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-bullhorn"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Pasien Dipanggil</span>
                    <span class="info-box-number">
                        <?= $totalDipanggil; ?> Pasien
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-ban"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Batal / Tidak Dilayani</span>
                    <span class="info-box-number">
                        <?= $totalBatal; ?> Pasien
                    </span>
                </div>
            </div>
        </div>

    </div>

    <!-- TABEL ANTREAN -->
    <div class="card card-outline card-primary">

        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list mr-1"></i>
                Daftar Antrean Pasien
            </h3>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped table-hover">

                <thead class="bg-light">
                    <tr>
                        <th width="45">No.</th>
                        <th width="90">Antrean</th>
                        <th>Nama Pasien</th>
                        <th>No. HP</th>
                        <th>Jam Praktik</th>
                        <th>Jenis Pendaftaran</th>
                        <th>Status Antrean</th>
                        <th width="80">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if ($totalAntrian > 0): ?>

                        <?php $no = 1; ?>

                        <?php foreach ($daftarAntrian as $antrian): ?>

                            <?php
                            $statusAntrian = $antrian['statusantrian'];

                            $badgeStatus = $warnaStatus[$statusAntrian] ?? 'secondary';

                            $warnaJenis = ($antrian['jenispendaftaran'] == 'Online')
                                ? 'primary'
                                : 'success';
                            ?>

                            <tr>

                                <td><?= $no++; ?></td>

                                <td class="text-center">
                                    <span class="badge badge-dark p-2">
                                        <?= e($antrian['nomorantrian']); ?>
                                    </span>
                                </td>

                                <td>
                                    <strong>
                                        <?= e($antrian['namapasien']); ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= e($antrian['nohp'] ?? '-'); ?>
                                </td>

                                <td>
                                    <?= e(substr($antrian['jam_mulai'], 0, 5)); ?>
                                    -
                                    <?= e(substr($antrian['jam_selesai'], 0, 5)); ?>
                                </td>

                                <td>
                                    <span class="badge badge-<?= $warnaJenis; ?>">
                                        <?= e($antrian['jenispendaftaran']); ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="badge badge-<?= $badgeStatus; ?>">
                                        <?= e($statusAntrian); ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <a href="index.php?page=showantriandokter&idpendaftaran=<?= (int) $antrian['idpendaftaran']; ?>"
                                       class="btn btn-sm btn-info"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="8" class="text-center text-muted p-4">
                                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                                <br>
                                <strong>Belum Ada Antrean Pasien</strong>
                                <br>
                                Belum ada pasien yang disetujui dan mendapatkan nomor antrean hari ini.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

        <div class="card-footer text-muted">
            <i class="fas fa-info-circle mr-1"></i>
            Hanya pasien yang sudah disetujui admin/petugas dan mendapatkan nomor antrean yang ditampilkan.
        </div>

    </div>

</div>
