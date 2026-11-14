
<?php
// ============================================
// PENDAFTARAN CHECKUP PASIEN
// views/pasien/pendaftaran/index.php
// ============================================

$idpasien = $_SESSION['idpasien'] ?? 0;

if (!$idpasien) {
    echo '<div class="alert alert-danger">Sesi pasien tidak ditemukan. Silakan login kembali.</div>';
    return;
}

// Filter status
$status = $_GET['status'] ?? 'Semua';
$statusValid = ['Semua', 'Menunggu', 'Disetujui', 'Ditolak'];

if (!in_array($status, $statusValid, true)) {
    $status = 'Semua';
}

// Ringkasan pendaftaran milik pasien
$sqlRingkasan = mysqli_query($koneksi, "
    SELECT
        COUNT(*) AS total,
        SUM(status = 'Menunggu') AS menunggu,
        SUM(status = 'Disetujui') AS disetujui,
        SUM(status = 'Ditolak') AS ditolak
    FROM pendaftarancheckup
    WHERE idpasien = '$idpasien'
");

$ringkasan = mysqli_fetch_assoc($sqlRingkasan);

// Ambil daftar pendaftaran
$sql = "
    SELECT
        pc.*,
        d.namadokter,
        d.spesialis,
        j.haripraktik,
        j.jam_mulai,
        j.jam_selesai
    FROM pendaftarancheckup pc
    INNER JOIN jadwaldokter j ON pc.idjadwal = j.idjadwal
    INNER JOIN dokter d ON j.iddokter = d.iddokter
    WHERE pc.idpasien = '$idpasien'
";

if ($status !== 'Semua') {
    $statusFilter = mysqli_real_escape_string($koneksi, $status);
    $sql .= " AND pc.status = '$statusFilter'";
}

$sql .= " ORDER BY pc.tanggalpengajuan DESC, pc.idpendaftaran DESC";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    echo '<div class="alert alert-danger">Data pendaftaran gagal dimuat.</div>';
    return;
}

$pesan = $_GET['pesan'] ?? '';
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Pendaftaran Checkup</h4>
            <p class="text-muted mb-0">
                Daftar pendaftaran pemeriksaan Anda di Klinik Ahmadi.
            </p>
        </div>

            <a href="index.php?page=creatependaftaranpasien" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Daftar Checkup
            </a>
    </div>

    <?php if ($pesan === 'berhasil') { ?>
        <div class="alert alert-success alert-dismissible fade show">
            Pendaftaran berhasil dikirim. Silakan menunggu persetujuan petugas.
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } elseif ($pesan === 'gagal') { ?>
        <div class="alert alert-danger alert-dismissible fade show">
            Pendaftaran gagal diproses. Silakan periksa kembali data Anda.
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>

    <div class="row">

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= (int)($ringkasan['total'] ?? 0); ?></h3>
                    <p>Total Pendaftaran</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?= (int)($ringkasan['menunggu'] ?? 0); ?></h3>
                    <p>Menunggu Persetujuan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= (int)($ringkasan['disetujui'] ?? 0); ?></h3>
                    <p>Disetujui</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?= (int)($ringkasan['ditolak'] ?? 0); ?></h3>
                    <p>Ditolak</p>
                </div>
                <div class="icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>

    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list mr-1"></i> Riwayat Pendaftaran
            </h3>

            <div class="card-tools">
                <form method="GET" action="index.php">
                    <input type="hidden" name="page" value="pendaftaranpasien">

                    <select name="status" class="form-control form-control-sm"
                            onchange="this.form.submit()">
                        <?php foreach ($statusValid as $pilihan) { ?>
                            <option value="<?= htmlspecialchars($pilihan); ?>"
                                <?= $status === $pilihan ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($pilihan); ?>
                            </option>
                        <?php } ?>
                    </select>
                </form>
            </div>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="45">No.</th>
                        <th>Dokter</th>
                        <th>Jadwal Praktik</th>
                        <th>Tanggal Periksa</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Antrean</th>
                        <th width="90">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (mysqli_num_rows($query) > 0) { ?>
                        <?php $no = 1; ?>
                        <?php while ($data = mysqli_fetch_assoc($query)) { ?>

                            <?php
                            $badgeStatus = 'secondary';

                            if ($data['status'] === 'Menunggu') {
                                $badgeStatus = 'warning';
                            } elseif ($data['status'] === 'Disetujui') {
                                $badgeStatus = 'success';
                            } elseif ($data['status'] === 'Ditolak') {
                                $badgeStatus = 'danger';
                            }

                            $badgeAntrian = 'secondary';

                            if ($data['statusantrian'] === 'Menunggu') {
                                $badgeAntrian = 'warning';
                            } elseif ($data['statusantrian'] === 'Dipanggil') {
                                $badgeAntrian = 'info';
                            } elseif ($data['statusantrian'] === 'Selesai') {
                                $badgeAntrian = 'success';
                            }
                            ?>

                            <tr>
                                <td><?= $no++; ?></td>

                                <td>
                                    <strong>
                                        <?= htmlspecialchars($data['namadokter']); ?>
                                    </strong>
                                    <br>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($data['spesialis'] ?? '-'); ?>
                                    </small>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['haripraktik']); ?>
                                    <br>
                                    <small>
                                        <?= htmlspecialchars(substr($data['jam_mulai'], 0, 5)); ?>
                                        -
                                        <?= htmlspecialchars(substr($data['jam_selesai'], 0, 5)); ?>
                                    </small>
                                </td>

                                <td>
                                    <?= date('d-m-Y', strtotime($data['tanggalperiksa'])); ?>
                                </td>

                                <td>
                                    <?php if ($data['jenispendaftaran'] === 'Online') { ?>
                                        <span class="badge badge-primary">Online</span>
                                    <?php } else { ?>
                                        <span class="badge badge-secondary">Langsung</span>
                                    <?php } ?>
                                </td>

                                <td>
                                    <span class="badge badge-<?= $badgeStatus; ?>">
                                        <?= htmlspecialchars($data['status']); ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if ($data['status'] === 'Disetujui' && $data['nomorantrian']) { ?>
                                        <strong class="text-primary">
                                            No. <?= (int)$data['nomorantrian']; ?>
                                        </strong>
                                        <br>
                                        <span class="badge badge-<?= $badgeAntrian; ?>">
                                            <?= htmlspecialchars($data['statusantrian']); ?>
                                        </span>
                                    <?php } elseif ($data['status'] === 'Disetujui') { ?>
                                        <span class="text-muted">Belum check-in</span>
                                    <?php } else { ?>
                                        <span class="text-muted">-</span>
                                    <?php } ?>
                                </td>

                                <td>
                                    <a href="index.php?page=showpendaftaranpasien&id=<?= (int)$data['idpendaftaran']; ?>"
                                    class="btn btn-info btn-sm" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>

                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                Belum ada data pendaftaran.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
