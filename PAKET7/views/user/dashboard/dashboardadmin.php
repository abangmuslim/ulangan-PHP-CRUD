

<?php
// =====================================================
// IDENTITAS ADMIN
// =====================================================

$namauser = htmlspecialchars(
    $_SESSION['namauser'] ?? 'Administrator',
    ENT_QUOTES,
    'UTF-8'
);

// =====================================================
// DATA DASHBOARD ADMIN
// DATA DISEDIAKAN OLEH dashboarduser.php
// =====================================================

// Ringkasan data klinik
$totalAdmin       = $totalAdmin ?? 0;
$totalPetugas     = $totalPetugas ?? 0;
$totalPasien      = $totalPasien ?? 0;
$totalDokter      = $totalDokter ?? 0;
$totalObat        = $totalObat ?? 0;
$totalJadwal      = $totalJadwal ?? 0;
$totalPendaftaran = $totalPendaftaran ?? 0;
$totalKunjungan   = $totalKunjungan ?? 0;

// Ringkasan status pendaftaran
$totalMenunggu  = $totalMenunggu ?? 0;
$totalDisetujui = $totalDisetujui ?? 0;
$totalDitolak   = $totalDitolak ?? 0;

// Data tabel dashboard
$queryJadwalMinggu = $queryJadwalMinggu ?? false;
$queryPendaftaran  = $queryPendaftaran ?? false;
$queryKunjungan    = $queryKunjungan ?? false;
?>


<style>
.dashboard-welcome {
    background: linear-gradient(120deg, #146c43, #198754);
    color: white;
    border-radius: 14px;
    padding: 25px;
}
.dashboard-section-title {
    font-size: 17px;
    font-weight: 700;
    color: #343a40;
}
.dashboard-subtitle {
    font-size: 12px;
    color: #7b8794;
}
.dashboard-card {
    border: none;
    border-radius: 12px;
    height: 100%;
    transition: .2s;
}
.dashboard-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,.09) !important;
}
.dashboard-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
}
.dashboard-number {
    font-size: 25px;
    font-weight: 700;
    color: #263238;
}
.dashboard-action {
    display: block;
    padding: 15px;
    border: 1px solid #e9ecef;
    border-radius: 11px;
    color: #343a40;
    text-decoration: none;
    background: white;
    height: 100%;
}
.dashboard-action:hover {
    color: #198754;
    border-color: #198754;
    background: #f5fbf7;
}
.dashboard-table th {
    background: #198754;
    color: white;
    font-size: 12px;
    white-space: nowrap;
}
.dashboard-table td {
    font-size: 12px;
    vertical-align: middle;
}
@media(max-width:575px) {
    .dashboard-welcome { padding: 20px; }
    .dashboard-number { font-size: 22px; }
}
</style>

<div class="container-fluid p-0">

    <!-- SAMBUTAN ADMIN -->

    <div class="dashboard-welcome shadow-sm mb-4">
        <div class="row align-items-center">
            <div class="col-md-9">
                <span class="badge bg-light text-success mb-2">
                    Administrator Panel
                </span>

                <h3 class="fw-bold mb-2">
                    Selamat Datang, <?= $namauser; ?>!
                </h3>

                <p class="mb-2">
                    Selamat bekerja di Sistem Informasi Klinik Ahmadi.
                    Kelola seluruh data dan aktivitas pelayanan klinik
                    melalui dashboard administrator.
                </p>

                <small>
                    <i class="fas fa-calendar-alt me-2"></i>
                    <?= date('d-m-Y'); ?>

                    <span class="mx-2">|</span>

                    <i class="fas fa-user-shield me-1"></i>
                    Administrator
                </small>
            </div>

            <div class="col-md-3 text-center d-none d-md-block">
                <i class="fas fa-hospital fa-5x" style="opacity:.2;"></i>
            </div>
        </div>
    </div>

    <!-- RINGKASAN DATA -->

    <div class="mb-3">
        <h5 class="dashboard-section-title mb-1">
            <i class="fas fa-chart-pie text-success me-2"></i>
            Ringkasan Data Klinik
        </h5>
        <p class="dashboard-subtitle">
            Informasi keseluruhan data yang tersimpan dalam sistem.
        </p>
    </div>

    <div class="row g-3 mb-4">

        <?php
        $kartu = [
            ['Total Admin', $totalAdmin, 'fas fa-user-shield', 'primary'],
            ['Total Petugas', $totalPetugas, 'fas fa-user-tie', 'secondary'],
            ['Total Pasien', $totalPasien, 'fas fa-users', 'info'],
            ['Total Dokter', $totalDokter, 'fas fa-user-md', 'success'],
            ['Total Obat', $totalObat, 'fas fa-capsules', 'warning'],
            ['Jadwal Dokter', $totalJadwal, 'fas fa-calendar-alt', 'info'],
            ['Pendaftaran', $totalPendaftaran, 'fas fa-file-medical', 'primary'],
            ['Total Kunjungan', $totalKunjungan, 'fas fa-stethoscope', 'danger']
        ];

        foreach ($kartu as $item):
        ?>

            <div class="col-6 col-md-3">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="dashboard-subtitle mb-2">
                                    <?= $item[0]; ?>
                                </div>
                                <div class="dashboard-number">
                                    <?= $item[1]; ?>
                                </div>
                            </div>

                            <div class="dashboard-icon bg-<?= $item[3]; ?>-subtle text-<?= $item[3]; ?>">
                                <i class="<?= $item[2]; ?>"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>

    </div>

    <!-- STATUS PENDAFTARAN -->

    <div class="mb-3">
        <h5 class="dashboard-section-title">
            <i class="fas fa-clipboard-check text-success me-2"></i>
            Status Pendaftaran Checkup
        </h5>
    </div>

    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Menunggu Persetujuan</small>
                    <h3 class="fw-bold text-warning mb-0">
                        <?= $totalMenunggu; ?>
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Disetujui</small>
                    <h3 class="fw-bold text-success mb-0">
                        <?= $totalDisetujui; ?>
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Ditolak</small>
                    <h3 class="fw-bold text-danger mb-0">
                        <?= $totalDitolak; ?>
                    </h3>
                </div>
            </div>
        </div>

    </div>

    <!-- JADWAL DOKTER -->

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-success text-white fw-bold">
            <i class="fas fa-calendar-week me-2"></i>
            Jadwal Dokter Minggu Ini
        </div>

        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-bordered table-striped dashboard-table mb-0">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Dokter</th>
                            <th>Hari Praktik</th>
                            <th>Jam Praktik</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($queryJadwalMinggu && mysqli_num_rows($queryJadwalMinggu) > 0): ?>
                            <?php $no = 1; ?>
                            <?php while ($jadwal = mysqli_fetch_assoc($queryJadwalMinggu)): ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($jadwal['namadokter']); ?></td>
                                    <td><?= ucfirst(htmlspecialchars($jadwal['haripraktik'])); ?></td>
                                    <td>
                                        <?= date('H:i', strtotime($jadwal['jam_mulai'])); ?>
                                        -
                                        <?= date('H:i', strtotime($jadwal['jam_selesai'])); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Belum ada jadwal dokter minggu ini.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- AKSES CEPAT -->

    <div class="mb-3">
        <h5 class="dashboard-section-title">
            <i class="fas fa-bolt text-warning me-2"></i>
            Akses Cepat
        </h5>
    </div>

    <div class="row g-3 mb-4">

        <?php
        $menuCepat = [
            ['Data User', 'user', 'fas fa-users-cog'],
            ['Data Pasien', 'pasien', 'fas fa-user-injured'],
            ['Data Dokter', 'dokter', 'fas fa-user-md'],
            ['Data Obat', 'obat', 'fas fa-capsules'],
            ['Pendaftaran', 'pendaftarancheckup', 'fas fa-file-medical'],
            ['Antrian', 'antrian', 'fas fa-list-ol'],
            ['Kunjungan', 'kunjungan', 'fas fa-stethoscope'],
            ['Laporan', 'laporanharian', 'fas fa-chart-bar']
        ];

        foreach ($menuCepat as $menu):
        ?>

            <div class="col-6 col-md-3">
                <a href="index.php?page=<?= $menu[1]; ?>" class="dashboard-action shadow-sm">
                    <i class="<?= $menu[2]; ?> text-success me-2"></i>
                    <strong><?= $menu[0]; ?></strong>
                </a>
            </div>

        <?php endforeach; ?>

    </div>

    <!-- PENDAFTARAN TERBARU -->

    <div class="mb-3">
        <h5 class="dashboard-section-title">
            <i class="fas fa-file-medical text-success me-2"></i>
            Pendaftaran Checkup Terbaru
        </h5>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover dashboard-table mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th>Tanggal Periksa</th>
                            <th>Jenis</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($queryPendaftaran && mysqli_num_rows($queryPendaftaran) > 0): ?>
                            <?php $no = 1; ?>
                            <?php while ($data = mysqli_fetch_assoc($queryPendaftaran)): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($data['namapasien']); ?></td>
                                    <td><?= htmlspecialchars($data['namadokter']); ?></td>
                                    <td><?= date('d-m-Y', strtotime($data['tanggalperiksa'])); ?></td>
                                    <td><?= htmlspecialchars($data['jenispendaftaran']); ?></td>
                                    <td>
                                        <?php
                                        $warna = 'warning';
                                        if ($data['status'] == 'Disetujui') $warna = 'success';
                                        if ($data['status'] == 'Ditolak') $warna = 'danger';
                                        ?>
                                        <span class="badge bg-<?= $warna; ?>">
                                            <?= htmlspecialchars($data['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    Belum ada pendaftaran checkup.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- KUNJUNGAN TERAKHIR -->

    <div class="mb-3">
        <h5 class="dashboard-section-title">
            <i class="fas fa-stethoscope text-success me-2"></i>
            10 Kunjungan Terakhir
        </h5>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover dashboard-table mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th>Petugas</th>
                            <th>Total Biaya</th>
                            <th>Status Kunjungan</th>
                            <th>Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($queryKunjungan && mysqli_num_rows($queryKunjungan) > 0): ?>
                            <?php $no = 1; ?>
                            <?php while ($data = mysqli_fetch_assoc($queryKunjungan)): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= date('d-m-Y', strtotime($data['tanggalkunjungan'])); ?></td>
                                    <td><?= htmlspecialchars($data['namapasien']); ?></td>
                                    <td><?= htmlspecialchars($data['namadokter']); ?></td>
                                    <td><?= htmlspecialchars($data['namauser']); ?></td>
                                    <td class="text-nowrap">
                                        Rp <?= number_format((float)$data['totalkunjungan'], 0, ',', '.'); ?>
                                    </td>
                                    <td><?= htmlspecialchars($data['statuskunjungan']); ?></td>
                                    <td><?= htmlspecialchars($data['statuspembayaran']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">
                                    Belum ada kunjungan pasien.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
