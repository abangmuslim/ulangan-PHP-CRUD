
<?php
// ============================================
// HALAMAN DATA PENDAFTARAN CHECKUP
// ZONA ADMIN DAN PETUGAS
// ============================================

// ============================================
// 1. FILTER STATUS
// ============================================

$statusFilter = $_GET['status'] ?? '';

$statusValid = [
    'Menunggu',
    'Disetujui',
    'Ditolak'
];

$whereStatus = '';

if (in_array($statusFilter, $statusValid, true)) {
    $statusSQL = mysqli_real_escape_string($koneksi, $statusFilter);
    $whereStatus = " WHERE pc.status = '$statusSQL'";
}

// ============================================
// 2. RINGKASAN PENDAFTARAN
// ============================================

$qTotal = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM pendaftarancheckup
");

$qMenunggu = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM pendaftarancheckup
    WHERE status = 'Menunggu'
");

$qDisetujui = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM pendaftarancheckup
    WHERE status = 'Disetujui'
");

$qDitolak = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM pendaftarancheckup
    WHERE status = 'Ditolak'
");

if (!$qTotal || !$qMenunggu || !$qDisetujui || !$qDitolak) {
    echo '<div class="alert alert-danger">Gagal mengambil ringkasan pendaftaran: ' .
         e(mysqli_error($koneksi)) . '</div>';
    return;
}

$totalPendaftaran = mysqli_fetch_assoc($qTotal)['total'] ?? 0;
$totalMenunggu = mysqli_fetch_assoc($qMenunggu)['total'] ?? 0;
$totalDisetujui = mysqli_fetch_assoc($qDisetujui)['total'] ?? 0;
$totalDitolak = mysqli_fetch_assoc($qDitolak)['total'] ?? 0;

// ============================================
// 3. DATA PENDAFTARAN
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
        d.spesialis

    FROM pendaftarancheckup pc

    INNER JOIN pasien p
        ON pc.idpasien = p.idpasien

    INNER JOIN jadwaldokter j
        ON pc.idjadwal = j.idjadwal

    INNER JOIN dokter d
        ON j.iddokter = d.iddokter

    $whereStatus

    ORDER BY
        pc.tanggalperiksa DESC,
        pc.tanggalpengajuan DESC,
        pc.idpendaftaran DESC
");

if (!$query) {
    echo '<div class="alert alert-danger">Gagal mengambil data pendaftaran: ' .
         e(mysqli_error($koneksi)) . '</div>';
    return;
}
?>

<div class="container-fluid">

    <!-- ============================================
         4. JUDUL HALAMAN
    ============================================= -->

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1 font-weight-bold">
                <i class="fas fa-clipboard-list text-primary mr-2"></i>
                Pendaftaran Checkup
            </h3>

            <p class="text-muted mb-0">
                Kelola pendaftaran pemeriksaan pasien dan verifikasi permohonan checkup.
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="index.php?page=creatependaftarancheckup" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i>
                Pendaftaran Langsung
            </a>
        </div>

    </div>

    <!-- ============================================
         5. PESAN SISTEM
    ============================================= -->

    <?php if (isset($_GET['pesan'])): ?>

        <?php
        $pesan = $_GET['pesan'];

        $pesanTampil = [
            'berhasil' => ['success', 'Pendaftaran berhasil ditambahkan.'],
            'disetujui' => ['success', 'Pendaftaran berhasil disetujui.'],
            'ditolak' => ['warning', 'Pendaftaran berhasil ditolak.'],
            'gagal' => ['danger', 'Proses pendaftaran gagal dilakukan.'],
            'data tidak ditemukan' => ['warning', 'Data pendaftaran tidak ditemukan.'],
            'sudah diproses' => ['info', 'Pendaftaran ini sudah pernah diproses.']
        ];
        ?>

        <?php if (isset($pesanTampil[$pesan])): ?>

            <div class="alert alert-<?= $pesanTampil[$pesan][0]; ?> alert-dismissible fade show">

                <?= e($pesanTampil[$pesan][1]); ?>

                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>

            </div>

        <?php endif; ?>

    <?php endif; ?>

    <!-- ============================================
         6. RINGKASAN PENDAFTARAN
    ============================================= -->

    <div class="row">

        <!-- TOTAL -->
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-info">

                <div class="inner">
                    <h3><?= number_format($totalPendaftaran); ?></h3>
                    <p>Total Pendaftaran</p>
                </div>

                <div class="icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>

                <a href="index.php?page=pendaftarancheckup" class="small-box-footer">
                    Lihat semua
                    <i class="fas fa-arrow-circle-right"></i>
                </a>

            </div>
        </div>

        <!-- MENUNGGU -->
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-warning">

                <div class="inner">
                    <h3><?= number_format($totalMenunggu); ?></h3>
                    <p>Menunggu Verifikasi</p>
                </div>

                <div class="icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>

                <a href="index.php?page=pendaftarancheckup&status=Menunggu" class="small-box-footer">
                    Lihat pendaftaran
                    <i class="fas fa-arrow-circle-right"></i>
                </a>

            </div>
        </div>

        <!-- DISETUJUI -->
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-success">

                <div class="inner">
                    <h3><?= number_format($totalDisetujui); ?></h3>
                    <p>Disetujui</p>
                </div>

                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>

                <a href="index.php?page=pendaftarancheckup&status=Disetujui" class="small-box-footer">
                    Lihat pendaftaran
                    <i class="fas fa-arrow-circle-right"></i>
                </a>

            </div>
        </div>

        <!-- DITOLAK -->
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-danger">

                <div class="inner">
                    <h3><?= number_format($totalDitolak); ?></h3>
                    <p>Ditolak</p>
                </div>

                <div class="icon">
                    <i class="fas fa-times-circle"></i>
                </div>

                <a href="index.php?page=pendaftarancheckup&status=Ditolak" class="small-box-footer">
                    Lihat pendaftaran
                    <i class="fas fa-arrow-circle-right"></i>
                </a>

            </div>
        </div>

    </div>

    <!-- ============================================
         7. TABEL PENDAFTARAN
    ============================================= -->

    <div class="card card-outline card-primary shadow-sm">

        <div class="card-header">

            <h3 class="card-title">
                <i class="fas fa-table mr-1"></i>
                Daftar Pendaftaran Pasien
            </h3>

            <div class="card-tools">

                <form method="GET" action="index.php" class="form-inline">

                    <input type="hidden" name="page" value="pendaftarancheckup">

                    <select
                        name="status"
                        class="form-control form-control-sm mr-2"
                        onchange="this.form.submit()">

                        <option value="">Semua Status</option>

                        <option value="Menunggu" <?= $statusFilter === 'Menunggu' ? 'selected' : ''; ?>>
                            Menunggu
                        </option>

                        <option value="Disetujui" <?= $statusFilter === 'Disetujui' ? 'selected' : ''; ?>>
                            Disetujui
                        </option>

                        <option value="Ditolak" <?= $statusFilter === 'Ditolak' ? 'selected' : ''; ?>>
                            Ditolak
                        </option>

                    </select>

                </form>

            </div>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table id="example1" class="table table-bordered table-striped table-hover">

                    <thead class="text-center">
                        <tr>
                            <th width="45">No.</th>
                            <th width="110">ID Daftar</th>
                            <th>Pasien</th>
                            <th>Dokter dan Jadwal</th>
                            <th>Tanggal Periksa</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th width="145">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php $no = 1; ?>

                        <?php if (mysqli_num_rows($query) > 0): ?>

                            <?php while ($data = mysqli_fetch_assoc($query)): ?>

                                <?php
                                // ============================================
                                // FORMAT TANGGAL
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

                                // ============================================
                                // BADGE STATUS
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
                                // FORMAT JAM PRAKTIK
                                // ============================================

                                $jamMulai = !empty($data['jam_mulai'])
                                    ? date('H:i', strtotime($data['jam_mulai']))
                                    : '-';

                                $jamSelesai = !empty($data['jam_selesai'])
                                    ? date('H:i', strtotime($data['jam_selesai']))
                                    : '-';
                                ?>

                                <tr>

                                    <!-- NOMOR -->
                                    <td class="text-center">
                                        <?= $no++; ?>
                                    </td>

                                    <!-- ID PENDAFTARAN -->
                                    <td class="text-center">

                                        <span class="font-weight-bold text-primary">
                                            #<?= (int)$data['idpendaftaran']; ?>
                                        </span>

                                        <br>

                                        <small class="text-muted">
                                            <?= e($tanggalPengajuan); ?>
                                        </small>

                                    </td>

                                    <!-- DATA PASIEN -->
                                    <td>

                                        <strong>
                                            <?= e($data['namapasien']); ?>
                                        </strong>

                                        <br>

                                        <small class="text-muted">
                                            <i class="fas fa-phone-alt mr-1"></i>
                                            <?= e($data['nohp'] ?? '-'); ?>
                                        </small>

                                        <br>

                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            <?= e($data['alamat'] ?? '-'); ?>
                                        </small>

                                    </td>

                                    <!-- DOKTER DAN JADWAL -->
                                    <td>

                                        <strong>
                                            <?= e($data['namadokter']); ?>
                                        </strong>

                                        <?php if (!empty($data['spesialis'])): ?>
                                            <br>
                                            <small class="text-muted">
                                                <?= e($data['spesialis']); ?>
                                            </small>
                                        <?php endif; ?>

                                        <br>

                                        <small class="text-muted">
                                            <?= e($data['haripraktik']); ?>,
                                            <?= e($jamMulai); ?> -
                                            <?= e($jamSelesai); ?>
                                        </small>

                                    </td>

                                    <!-- TANGGAL PERIKSA -->
                                    <td class="text-center">

                                        <strong>
                                            <?= e($tanggalPeriksa); ?>
                                        </strong>

                                    </td>

                                    <!-- JENIS PENDAFTARAN -->
                                    <td class="text-center">

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

                                    <!-- STATUS -->
                                    <td class="text-center">

                                        <span class="badge badge-<?= $badgeStatus; ?> px-2 py-2">
                                            <?= e($data['status']); ?>
                                        </span>

                                        <?php if ($data['status'] === 'Disetujui'): ?>

                                            <br>

                                            <small class="text-muted">
                                                Antrean:
                                                <?= !empty($data['nomorantrian'])
                                                    ? (int)$data['nomorantrian']
                                                    : '-'; ?>
                                            </small>

                                        <?php endif; ?>

                                    </td>

                                    <!-- AKSI -->
                                    <td class="text-center text-nowrap">

                                        <!-- DETAIL -->
                                        <a
                                            href="index.php?page=showpendaftarancheckup&idpendaftaran=<?= (int)$data['idpendaftaran']; ?>"
                                            class="btn btn-info btn-sm"
                                            title="Detail Pendaftaran">

                                            <i class="fas fa-eye"></i>

                                        </a>

                                        <?php if ($data['status'] === 'Menunggu'): ?>

                                            <!-- SETUJUI -->
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

                                                <button
                                                    type="submit"
                                                    class="btn btn-success btn-sm"
                                                    title="Setujui">

                                                    <i class="fas fa-check"></i>

                                                </button>

                                            </form>

                                            <!-- TOLAK -->
                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                title="Tolak"
                                                data-toggle="modal"
                                                data-target="#modalTolak<?= (int)$data['idpendaftaran']; ?>">

                                                <i class="fas fa-times"></i>

                                            </button>

                                            <!-- MODAL TOLAK -->
                                            <div
                                                class="modal fade"
                                                id="modalTolak<?= (int)$data['idpendaftaran']; ?>"
                                                tabindex="-1">

                                                <div class="modal-dialog">

                                                    <div class="modal-content">

                                                        <form action="index.php" method="POST">

                                                            <?= csrfField(); ?>

                                                            <input
                                                                type="hidden"
                                                                name="page"
                                                                value="prosespendaftarancheckup">

                                                            <input
                                                                type="hidden"
                                                                name="aksi"
                                                                value="tolak">

                                                            <input
                                                                type="hidden"
                                                                name="idpendaftaran"
                                                                value="<?= (int)$data['idpendaftaran']; ?>">

                                                            <div class="modal-header bg-danger">

                                                                <h5 class="modal-title">
                                                                    <i class="fas fa-times-circle mr-1"></i>
                                                                    Tolak Pendaftaran
                                                                </h5>

                                                                <button
                                                                    type="button"
                                                                    class="close text-white"
                                                                    data-dismiss="modal">

                                                                    <span>&times;</span>

                                                                </button>

                                                            </div>

                                                            <div class="modal-body text-left">

                                                                <p>
                                                                    Anda akan menolak pendaftaran
                                                                    <strong>
                                                                        <?= e($data['namapasien']); ?>
                                                                    </strong>.
                                                                </p>

                                                                <div class="form-group">

                                                                    <label>
                                                                        Alasan Penolakan
                                                                    </label>

                                                                    <textarea
                                                                        name="catatanadmin"
                                                                        class="form-control"
                                                                        rows="3"
                                                                        required
                                                                        placeholder="Tuliskan alasan penolakan secara jelas..."></textarea>

                                                                </div>

                                                            </div>

                                                            <div class="modal-footer">

                                                                <button
                                                                    type="button"
                                                                    class="btn btn-secondary"
                                                                    data-dismiss="modal">

                                                                    Batal

                                                                </button>

                                                                <button
                                                                    type="submit"
                                                                    class="btn btn-danger">

                                                                    <i class="fas fa-times mr-1"></i>
                                                                    Tolak Pendaftaran

                                                                </button>

                                                            </div>

                                                        </form>

                                                    </div>

                                                </div>

                                            </div>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">

                                    <i class="fas fa-folder-open fa-2x mb-2"></i>

                                    <br>

                                    Tidak ada data pendaftaran.

                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

        <div class="card-footer text-muted">

            <i class="fas fa-info-circle mr-1"></i>

            Pendaftaran berstatus Menunggu harus diverifikasi sebelum pasien masuk ke proses antrean.

        </div>

    </div>

</div>
