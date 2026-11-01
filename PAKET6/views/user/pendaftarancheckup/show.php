
<?php

// ========================================
// MENGAMBIL ID PENDAFTARAN
// ========================================

$idpendaftaran = isset($_GET['idpendaftaran'])
    ? (int) $_GET['idpendaftaran']
    : 0;


// ========================================
// MENGAMBIL DETAIL PENDAFTARAN
// ========================================

$stmt = mysqli_prepare($koneksi, "
    SELECT
        pendaftarancheckup.*,
        pasien.namapasien,
        pasien.username,
        pasien.nohp,
        pasien.alamat,
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

    WHERE pendaftarancheckup.idpendaftaran = ?
");

mysqli_stmt_bind_param($stmt, "i", $idpendaftaran);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

?>

<div class="container-fluid">

    <?php if (!$data) : ?>

        <!-- ========================================
             DATA TIDAK DITEMUKAN
        ========================================= -->

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <i class="bi bi-exclamation-circle fs-1 text-danger"></i>

                <h5 class="fw-bold mt-3">
                    Data Pendaftaran Tidak Ditemukan
                </h5>

                <p class="text-muted">
                    Data pendaftaran yang Anda cari tidak tersedia.
                </p>

                <a href="index.php?page=pendaftarancheckupadmin"
                   class="btn btn-secondary">

                    <i class="bi bi-arrow-left me-1"></i>
                    Kembali

                </a>

            </div>

        </div>

    <?php else : ?>


        <!-- ========================================
             JUDUL HALAMAN
        ========================================= -->

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

            <div>

                <h4 class="fw-bold mb-1">
                    <i class="bi bi-clipboard2-pulse me-2"></i>
                    Detail Pendaftaran Checkup
                </h4>

                <p class="text-muted mb-0">
                    Informasi lengkap permohonan pemeriksaan pasien.
                </p>

            </div>

            <a href="index.php?page=pendaftarancheckupadmin"
               class="btn btn-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Kembali

            </a>

        </div>


        <!-- ========================================
             NOTIFIKASI PROSES PENDAFTARAN
        ========================================= -->

        <?php

        $status = $_GET['status'] ?? '';

        $pesan = [

            'disetujui' => [
                'success',
                'Pendaftaran berhasil disetujui.'
            ],

            'ditolak' => [
                'success',
                'Pendaftaran berhasil ditolak.'
            ],

            'sudahdiproses' => [
                'warning',
                'Pendaftaran ini sudah pernah diproses.'
            ],

            'tanggallewat' => [
                'danger',
                'Tanggal pemeriksaan sudah lewat. Pendaftaran tidak dapat disetujui.'
            ],

            'jadwaltidakcocok' => [
                'danger',
                'Tanggal pemeriksaan tidak sesuai dengan hari praktik dokter.'
            ],

            'catatankosong' => [
                'warning',
                'Alasan penolakan wajib diisi.'
            ],

            'gagal' => [
                'danger',
                'Proses pendaftaran gagal dilakukan.'
            ]

        ];

        if (isset($pesan[$status])) :

        ?>

            <div class="alert alert-<?= $pesan[$status][0]; ?> alert-dismissible fade show"
                 role="alert">

                <i class="bi bi-info-circle me-2"></i>

                <?= htmlspecialchars($pesan[$status][1]); ?>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        <?php endif; ?>


        <!-- ========================================
             INFORMASI STATUS
        ========================================= -->

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <div>

                        <h6 class="text-muted mb-1">
                            Nomor Pendaftaran
                        </h6>

                        <h4 class="fw-bold mb-0">
                            #<?= $data['idpendaftaran']; ?>
                        </h4>

                    </div>

                    <div class="text-end">

                        <div class="text-muted small mb-2">
                            Status Pendaftaran
                        </div>

                        <?php if ($data['status'] == 'Menunggu') : ?>

                            <span class="badge bg-warning text-dark fs-6">

                                <i class="bi bi-clock-history me-1"></i>
                                Menunggu

                            </span>

                        <?php elseif ($data['status'] == 'Disetujui') : ?>

                            <span class="badge bg-success fs-6">

                                <i class="bi bi-check-circle me-1"></i>
                                Disetujui

                            </span>

                        <?php else : ?>

                            <span class="badge bg-danger fs-6">

                                <i class="bi bi-x-circle me-1"></i>
                                Ditolak

                            </span>

                        <?php endif; ?>

                    </div>

                </div>


                <!-- INFORMASI LANJUTAN -->

                <?php if (!empty($data['idkunjungan'])) : ?>

                    <div class="alert alert-success mt-3 mb-0">

                        <i class="bi bi-check-circle-fill me-2"></i>

                        Pendaftaran ini sudah diproses menjadi kunjungan
                        dengan nomor

                        <strong>#<?= $data['idkunjungan']; ?></strong>.

                        <a href="index.php?page=showkunjungan&idkunjungan=<?= $data['idkunjungan']; ?>"
                           class="alert-link ms-1">

                            Lihat Kunjungan

                        </a>

                    </div>

                <?php elseif ($data['status'] == 'Disetujui') : ?>

                    <div class="alert alert-info mt-3 mb-0">

                        <i class="bi bi-info-circle-fill me-2"></i>

                        Pendaftaran sudah disetujui. Silakan menunggu
                        pasien datang untuk melanjutkan proses kunjungan.

                    </div>

                <?php elseif ($data['status'] == 'Menunggu') : ?>

                    <div class="alert alert-warning mt-3 mb-0">

                        <i class="bi bi-info-circle-fill me-2"></i>

                        Permohonan ini menunggu pemeriksaan dan keputusan admin.

                    </div>

                <?php else : ?>

                    <div class="alert alert-danger mt-3 mb-0">

                        <i class="bi bi-exclamation-circle-fill me-2"></i>

                        Permohonan pendaftaran ini telah ditolak.

                    </div>

                <?php endif; ?>

            </div>

        </div>


        <div class="row g-4">


            <!-- ========================================
                 INFORMASI PASIEN
            ========================================= -->

            <div class="col-lg-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white py-3">

                        <h5 class="fw-bold mb-0">

                            <i class="bi bi-person-circle me-2 text-primary"></i>
                            Informasi Pasien

                        </h5>

                    </div>

                    <div class="card-body">

                        <table class="table table-borderless mb-0">

                            <tr>

                                <td width="140">Nama Pasien</td>
                                <td width="10">:</td>

                                <td class="fw-semibold">
                                    <?= htmlspecialchars($data['namapasien']); ?>
                                </td>

                            </tr>

                            <tr>

                                <td>Username</td>
                                <td>:</td>

                                <td>
                                    <?= htmlspecialchars($data['username'] ?? '-'); ?>
                                </td>

                            </tr>

                            <tr>

                                <td>Nomor HP</td>
                                <td>:</td>

                                <td>
                                    <?= htmlspecialchars($data['nohp'] ?? '-'); ?>
                                </td>

                            </tr>

                            <tr>

                                <td>Alamat</td>
                                <td>:</td>

                                <td>
                                    <?= nl2br(htmlspecialchars($data['alamat'] ?? '-')); ?>
                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

            </div>


            <!-- ========================================
                 INFORMASI DOKTER
            ========================================= -->

            <div class="col-lg-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white py-3">

                        <h5 class="fw-bold mb-0">

                            <i class="bi bi-person-badge me-2 text-success"></i>
                            Informasi Dokter

                        </h5>

                    </div>

                    <div class="card-body">

                        <table class="table table-borderless mb-0">

                            <tr>

                                <td width="140">Nama Dokter</td>
                                <td width="10">:</td>

                                <td class="fw-semibold">
                                    <?= htmlspecialchars($data['namadokter']); ?>
                                </td>

                            </tr>

                            <tr>

                                <td>Spesialis</td>
                                <td>:</td>

                                <td>
                                    <?= htmlspecialchars($data['spesialis'] ?? '-'); ?>
                                </td>

                            </tr>

                            <tr>

                                <td>Hari Praktik</td>
                                <td>:</td>

                                <td>
                                    <?= htmlspecialchars($data['haripraktik']); ?>
                                </td>

                            </tr>

                            <tr>

                                <td>Jam Praktik</td>
                                <td>:</td>

                                <td>

                                    <?= date('H:i', strtotime($data['jam_mulai'])); ?>

                                    -

                                    <?= date('H:i', strtotime($data['jam_selesai'])); ?>

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

            </div>


            <!-- ========================================
                 INFORMASI PENDAFTARAN
            ========================================= -->

            <div class="col-12">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">

                        <h5 class="fw-bold mb-0">

                            <i class="bi bi-calendar-check me-2 text-primary"></i>
                            Informasi Pendaftaran

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted small">
                                    Tanggal Pemeriksaan
                                </label>

                                <h6 class="fw-semibold">

                                    <?= date(
                                        'd-m-Y',
                                        strtotime($data['tanggalperiksa'])
                                    ); ?>

                                </h6>

                            </div>


                            <div class="col-md-6">

                                <label class="text-muted small">
                                    Tanggal Pengajuan
                                </label>

                                <h6 class="fw-semibold">

                                    <?= date(
                                        'd-m-Y H:i',
                                        strtotime($data['tanggalpengajuan'])
                                    ); ?>

                                </h6>

                            </div>


                            <div class="col-12">

                                <label class="text-muted small">
                                    Keluhan Pasien
                                </label>

                                <div class="bg-light rounded p-3">

                                    <?= nl2br(htmlspecialchars($data['keluhan'])); ?>

                                </div>

                            </div>


                            <div class="col-12">

                                <label class="text-muted small">
                                    Catatan Admin
                                </label>

                                <div class="bg-light rounded p-3">

                                    <?php if (!empty($data['catatanadmin'])) : ?>

                                        <?= nl2br(htmlspecialchars($data['catatanadmin'])); ?>

                                    <?php else : ?>

                                        <span class="text-muted">
                                            Belum ada catatan admin.
                                        </span>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ========================================
                 TINDAKAN ADMIN
            ========================================= -->

            <?php if ($data['status'] == 'Menunggu' && empty($data['idkunjungan'])) : ?>


                <div class="col-12">

                    <div class="card border-0 shadow-sm">

                        <div class="card-header bg-white py-3">

                            <h5 class="fw-bold mb-0">

                                <i class="bi bi-gear me-2"></i>
                                Tindakan Admin

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row g-4">


                                <!-- SETUJUI PENDAFTARAN -->

                                <div class="col-md-6">

                                    <div class="border rounded p-3 h-100">

                                        <h6 class="fw-bold text-success">

                                            <i class="bi bi-check-circle me-2"></i>
                                            Setujui Pendaftaran

                                        </h6>

                                        <p class="text-muted small">

                                            Menyetujui permohonan pasien agar dapat
                                            melanjutkan ke tahap kedatangan dan
                                            proses kunjungan.

                                        </p>

                                        <form action="proses/prosespendaftarancheckup.php?aksi=setujui"
                                              method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pendaftaran ini?')">

                                            <input type="hidden"
                                                   name="idpendaftaran"
                                                   value="<?= $data['idpendaftaran']; ?>">

                                            <button type="submit"
                                                    class="btn btn-success">

                                                <i class="bi bi-check-lg me-1"></i>
                                                Setujui Pendaftaran

                                            </button>

                                        </form>

                                    </div>

                                </div>


                                <!-- TOLAK PENDAFTARAN -->

                                <div class="col-md-6"
                                     id="form-penolakan">

                                    <div class="border rounded p-3 h-100">

                                        <h6 class="fw-bold text-danger">

                                            <i class="bi bi-x-circle me-2"></i>
                                            Tolak Pendaftaran

                                        </h6>

                                        <p class="text-muted small">

                                            Jika permohonan tidak dapat disetujui,
                                            berikan alasan penolakan agar pasien
                                            mendapatkan informasi yang jelas.

                                        </p>

                                        <form action="proses/prosespendaftarancheckup.php?aksi=tolak"
                                              method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menolak pendaftaran ini?')">

                                            <input type="hidden"
                                                   name="idpendaftaran"
                                                   value="<?= $data['idpendaftaran']; ?>">

                                            <div class="mb-3">

                                                <label class="form-label">
                                                    Catatan / Alasan Penolakan
                                                </label>

                                                <textarea name="catatanadmin"
                                                          class="form-control"
                                                          rows="3"
                                                          placeholder="Tuliskan alasan penolakan..."
                                                          required></textarea>

                                            </div>

                                            <button type="submit"
                                                    class="btn btn-danger">

                                                <i class="bi bi-x-lg me-1"></i>
                                                Tolak Pendaftaran

                                            </button>

                                        </form>

                                    </div>

                                </div>


                            </div>

                        </div>

                    </div>

                </div>


            <?php elseif ($data['status'] == 'Disetujui' && empty($data['idkunjungan'])) : ?>


                <!-- ========================================
                     PROSES KUNJUNGAN
                ========================================= -->

                <div class="col-12">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">

                            <div>

                                <h6 class="fw-bold mb-1">

                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                    Pendaftaran Telah Disetujui

                                </h6>

                                <p class="text-muted mb-0">

                                    Jika pasien sudah datang, lanjutkan
                                    ke proses kunjungan.

                                </p>

                            </div>

                            <a href="index.php?page=createkunjungan&idpendaftaran=<?= $data['idpendaftaran']; ?>"
                               class="btn btn-primary">

                                <i class="bi bi-arrow-right-circle me-1"></i>
                                Proses Kunjungan

                            </a>

                        </div>

                    </div>

                </div>


            <?php endif; ?>


        </div>

    <?php endif; ?>

</div>
