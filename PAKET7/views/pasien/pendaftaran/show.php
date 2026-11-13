
<?php
$idpasien = $_SESSION['idpasien'];
$idpendaftaran = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Ambil detail pendaftaran milik pasien yang sedang login.
$query = mysqli_query($koneksi, "
    SELECT
        pendaftarancheckup.*,
        pasien.namapasien,
        dokter.namadokter,
        dokter.spesialis,
        jadwaldokter.haripraktik,
        jadwaldokter.jam_mulai,
        jadwaldokter.jam_selesai
    FROM pendaftarancheckup
    INNER JOIN pasien
        ON pendaftarancheckup.idpasien = pasien.idpasien
    INNER JOIN jadwaldokter
        ON pendaftarancheckup.idjadwal = jadwaldokter.idjadwal
    INNER JOIN dokter
        ON jadwaldokter.iddokter = dokter.iddokter
    WHERE pendaftarancheckup.idpendaftaran = '$idpendaftaran'
    AND pendaftarancheckup.idpasien = '$idpasien'
");

$data = mysqli_fetch_assoc($query);
?>

<?php if (!$data) { ?>

    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle me-1"></i>
        Data pendaftaran tidak ditemukan atau Anda tidak memiliki akses.
    </div>

    <a href="index.php?page=pendaftaranpasien"
       class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>

<?php } else { ?>

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">Detail Pendaftaran Checkup</h4>
            <p class="text-muted mb-0">
                Informasi lengkap permohonan pemeriksaan Anda.
            </p>
        </div>

        <a href="index.php?page=pendaftaranpasien"
           class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali
        </a>

    </div>

    <div class="row">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-file-medical me-2"></i>
                        Informasi Pendaftaran
                    </h5>
                </div>

                <div class="card-body p-4">

                    <div class="row mb-3">

                        <div class="col-md-6">
                            <small class="text-muted">Nomor Pendaftaran</small>
                            <h6 class="fw-bold">
                                REG-<?= $data['idpendaftaran']; ?>
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Tanggal Pengajuan</small>
                            <h6 class="fw-bold">
                                <?= date(
                                    'd-m-Y H:i',
                                    strtotime($data['tanggalpengajuan'])
                                ); ?>
                            </h6>
                        </div>

                    </div>

                    <hr>

                    <div class="row mb-3">

                        <div class="col-md-6">
                            <small class="text-muted">Nama Pasien</small>
                            <h6 class="fw-bold">
                                <?= e($data['namapasien']); ?>
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Jenis Pendaftaran</small>
                            <h6 class="fw-bold">
                                <?= e($data['jenispendaftaran']); ?>
                            </h6>
                        </div>

                    </div>

                    <hr>

                    <div class="row mb-3">

                        <div class="col-md-6">
                            <small class="text-muted">Dokter</small>
                            <h6 class="fw-bold">
                                <?= e($data['namadokter']); ?>
                            </h6>
                            <small class="text-muted">
                                <?= e($data['spesialis']); ?>
                            </small>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Tanggal Pemeriksaan</small>
                            <h6 class="fw-bold">
                                <?= date(
                                    'd-m-Y',
                                    strtotime($data['tanggalperiksa'])
                                ); ?>
                            </h6>
                        </div>

                    </div>

                    <hr>

                    <div class="mb-3">
                        <small class="text-muted">Jadwal Praktik Dokter</small>
                        <h6 class="fw-bold">
                            <?= e($data['haripraktik']); ?>
                            |
                            <?= e($data['jam_mulai']); ?>
                            -
                            <?= e($data['jam_selesai']); ?>
                        </h6>
                    </div>

                    <hr>

                    <div>
                        <small class="text-muted">Keluhan Pasien</small>
                        <p class="mb-0">
                            <?= nl2br(e($data['keluhan'])); ?>
                        </p>
                    </div>

                </div>
            </div>

            <?php if (!empty($data['catatanadmin'])) { ?>

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-comment-alt me-2"></i>
                            Catatan Admin/Petugas
                        </h5>
                    </div>

                    <div class="card-body">
                        <?= nl2br(e($data['catatanadmin'])); ?>
                    </div>

                </div>

            <?php } ?>

        </div>

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-clipboard-check me-2"></i>
                        Status Pendaftaran
                    </h5>
                </div>

                <div class="card-body text-center">

                    <?php if ($data['status'] == 'Menunggu') { ?>

                        <i class="fas fa-clock fa-3x text-warning mb-3"></i>

                        <h5 class="fw-bold text-warning">
                            Menunggu Konfirmasi
                        </h5>

                        <p class="text-muted small">
                            Permohonan Anda sedang menunggu pemeriksaan dan persetujuan admin/petugas.
                        </p>

                    <?php } elseif ($data['status'] == 'Disetujui') { ?>

                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>

                        <h5 class="fw-bold text-success">
                            Pendaftaran Disetujui
                        </h5>

                        <p class="text-muted small">
                            Permohonan Anda telah disetujui. Silakan datang ke klinik sesuai jadwal pemeriksaan.
                        </p>

                    <?php } else { ?>

                        <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>

                        <h5 class="fw-bold text-danger">
                            Pendaftaran Ditolak
                        </h5>

                        <p class="text-muted small">
                            Mohon periksa catatan admin/petugas untuk mengetahui keterangan penolakan.
                        </p>

                    <?php } ?>

                </div>

            </div>

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-ticket-alt me-2"></i>
                        Informasi Antrean
                    </h5>
                </div>

                <div class="card-body text-center">

                    <?php if (!empty($data['nomorantrian'])) { ?>

                        <small class="text-muted">Nomor Antrean Anda</small>

                        <h1 class="display-3 fw-bold text-success">
                            <?= $data['nomorantrian']; ?>
                        </h1>

                        <span class="badge bg-primary">
                            <?= e($data['statusantrian']); ?>
                        </span>

                        <p class="text-muted small mt-3 mb-0">
                            Silakan menunggu panggilan dokter sesuai nomor antrean.
                        </p>

                    <?php } elseif ($data['status'] == 'Disetujui') { ?>

                        <i class="fas fa-ticket-alt fa-3x text-info mb-3"></i>

                        <h6 class="fw-bold">Belum Mendapat Nomor Antrean</h6>

                        <p class="text-muted small mb-0">
                            Nomor antrean akan diberikan petugas ketika Anda datang dan melakukan check-in di klinik.
                        </p>

                    <?php } else { ?>

                        <i class="fas fa-hourglass-half fa-3x text-secondary mb-3"></i>

                        <h6 class="fw-bold">Belum Masuk Antrean</h6>

                        <p class="text-muted small mb-0">
                            Nomor antrean diberikan setelah pendaftaran disetujui dan Anda melakukan check-in.
                        </p>

                    <?php } ?>

                </div>

            </div>

        </div>

    </div>

<?php } ?>
