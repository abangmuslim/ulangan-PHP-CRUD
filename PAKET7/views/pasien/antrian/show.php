
<?php
// ============================================
// DETAIL ANTREAN PASIEN DOKTER
// ============================================

$iddokter = (int) ($_SESSION['iddokter'] ?? 0);
$idpendaftaran = (int) ($_GET['idpendaftaran'] ?? 0);

// Validasi sesi dokter dan parameter
if ($iddokter <= 0 || $idpendaftaran <= 0) {
    echo '<div class="alert alert-danger">Data antrean tidak valid.</div>';
    return;
}

// Ambil detail antrean berdasarkan dokter yang login
$queryDetail = mysqli_query($koneksi, "
    SELECT
        pc.*,
        p.namapasien,
        p.nohp,
        p.alamat,
        p.foto,
        j.haripraktik,
        j.jam_mulai,
        j.jam_selesai,
        d.namadokter,
        d.spesialis
    FROM pendaftarancheckup pc
    INNER JOIN pasien p ON pc.idpasien = p.idpasien
    INNER JOIN jadwaldokter j ON pc.idjadwal = j.idjadwal
    INNER JOIN dokter d ON j.iddokter = d.iddokter
    WHERE pc.idpendaftaran = '$idpendaftaran'
    AND j.iddokter = '$iddokter'
    AND pc.status = 'Disetujui'
    LIMIT 1
");

if (!$queryDetail) {
    echo '<div class="alert alert-danger">Gagal mengambil detail antrean: ' . e(mysqli_error($koneksi)) . '</div>';
    return;
}

$antrian = mysqli_fetch_assoc($queryDetail);

// Pastikan data ditemukan
if (!$antrian) {
    echo '<div class="alert alert-warning">
        Data antrean tidak ditemukan atau bukan termasuk jadwal praktik Anda.
    </div>';
    return;
}

$statusAntrian = $antrian['statusantrian'] ?: 'Belum Antri';

$warnaStatus = [
    'Belum Antri' => 'secondary',
    'Menunggu'    => 'warning',
    'Dipanggil'   => 'info',
    'Diperiksa'   => 'primary',
    'Selesai'     => 'success',
    'Batal'       => 'danger'
];

$badgeStatus = $warnaStatus[$statusAntrian] ?? 'secondary';

$fotoPasien = !empty($antrian['foto'])
    ? $antrian['foto']
    : 'default.png';
?>

<div class="container-fluid">

    <!-- Judul Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">
                <i class="fas fa-user-injured text-info mr-2"></i>
                Detail Antrean Pasien
            </h4>
            <p class="text-muted mb-0">
                Informasi pendaftaran dan jadwal pemeriksaan pasien.
            </p>
        </div>

        <a href="index.php?page=antriandokter" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali
        </a>
    </div>

    <div class="row">

        <!-- Informasi Pasien -->
        <div class="col-md-5">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Identitas Pasien</h3>
                </div>

                <div class="card-body text-center">
                    <img
                        src="assets/images/pasien/<?= e($fotoPasien); ?>"
                        alt="Foto Pasien"
                        class="img-circle elevation-2 mb-3"
                        style="width:120px;height:120px;object-fit:cover;"
                    >

                    <h5 class="font-weight-bold">
                        <?= e($antrian['namapasien']); ?>
                    </h5>

                    <p class="text-muted mb-3">Pasien Klinik Ahmadi</p>

                    <table class="table table-sm table-bordered text-left">
                        <tr>
                            <th width="35%">No. HP</th>
                            <td><?= e($antrian['nohp']); ?></td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td><?= e($antrian['alamat']); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Informasi Antrean -->
        <div class="col-md-7">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">Informasi Pendaftaran dan Antrean</h3>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Nomor Pendaftaran</th>
                            <td><?= (int) $antrian['idpendaftaran']; ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Pemeriksaan</th>
                            <td>
                                <?= date('d-m-Y', strtotime($antrian['tanggalperiksa'])); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Nomor Antrean</th>
                            <td>
                                <?php if (!empty($antrian['nomorantrian'])): ?>
                                    <span class="badge badge-dark p-2">
                                        <?= e($antrian['nomorantrian']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">Belum mendapatkan nomor antrean</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Jenis Pendaftaran</th>
                            <td><?= e($antrian['jenispendaftaran']); ?></td>
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
                            <th>Status Antrean</th>
                            <td>
                                <span class="badge badge-<?= $badgeStatus; ?>">
                                    <?= e($statusAntrian); ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Jadwal Praktik</th>
                            <td>
                                <?= e($antrian['haripraktik']); ?>,
                                <?= e(substr($antrian['jam_mulai'], 0, 5)); ?>
                                -
                                <?= e(substr($antrian['jam_selesai'], 0, 5)); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Dokter</th>
                            <td>
                                <?= e($antrian['namadokter']); ?>
                                (<?= e($antrian['spesialis']); ?>)
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Keluhan Pasien -->
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-notes-medical mr-1"></i>
                        Keluhan Pasien
                    </h3>
                </div>

                <div class="card-body">
                    <?php if (!empty($antrian['keluhan'])): ?>
                        <?= nl2br(e($antrian['keluhan'])); ?>
                    <?php else: ?>
                        <span class="text-muted">Tidak ada keluhan yang dicatat.</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Catatan Admin -->
            <?php if (!empty($antrian['catatanadmin'])): ?>
                <div class="card card-secondary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-clipboard mr-1"></i>
                            Catatan Admin/Petugas
                        </h3>
                    </div>
                    <div class="card-body">
                        <?= nl2br(e($antrian['catatanadmin'])); ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>
