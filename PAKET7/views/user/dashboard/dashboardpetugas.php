

<?php
// =====================================================
// IDENTITAS PETUGAS
// =====================================================

$namauser = htmlspecialchars(
    $_SESSION['namauser'] ?? 'Petugas',
    ENT_QUOTES,
    'UTF-8'
);

// =====================================================
// DATA DASHBOARD PETUGAS
// DATA DISEDIAKAN OLEH dashboarduser.php
// =====================================================

// Ringkasan pelayanan
$totalPasien     = $totalPasien ?? 0;
$totalDokter     = $totalDokter ?? 0;
$totalMenunggu   = $totalMenunggu ?? 0;
$totalDisetujui  = $totalDisetujui ?? 0;
$totalAntrian    = $totalAntrian ?? 0;
$totalKunjungan  = $totalKunjungan ?? 0;
$totalBelumLunas = $totalBelumLunas ?? 0;

// Data tabel dashboard
$queryPendaftaran = $queryPendaftaran ?? false;
$queryKunjungan   = $queryKunjungan ?? false;
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
.dashboard-number {
    font-size: 26px;
    font-weight: 700;
    color: #263238;
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
.dashboard-action {
    display: block;
    padding: 17px;
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
</style>

<div class="container-fluid p-0">

    <!-- SAMBUTAN PETUGAS -->

    <div class="dashboard-welcome shadow-sm mb-4">
        <div class="row align-items-center">
            <div class="col-md-9">
                <span class="badge bg-light text-success mb-2">
                    Petugas Panel
                </span>

                <h3 class="fw-bold mb-2">
                    Selamat Datang, <?= $namauser; ?>!
                </h3>

                <p class="mb-2">
                    Selamat bekerja di Sistem Informasi Klinik Ahmadi.
                    Pantau aktivitas pendaftaran, antrian, dan pelayanan
                    pasien melalui dashboard petugas.
                </p>

                <small>
                    <i class="fas fa-calendar-alt me-2"></i>
                    <?= date('d-m-Y'); ?>

                    <span class="mx-2">|</span>

                    <i class="fas fa-user-tie me-1"></i>
                    Petugas Klinik
                </small>
            </div>

            <div class="col-md-3 text-center d-none d-md-block">
                <i class="fas fa-user-md fa-5x" style="opacity:.2;"></i>
            </div>
        </div>
    </div>

    <!-- RINGKASAN PELAYANAN -->

    <div class="mb-3">
        <h5 class="dashboard-section-title mb-1">
            <i class="fas fa-chart-pie text-success me-2"></i>
            Ringkasan Pelayanan
        </h5>

        <p class="dashboard-subtitle">
            Informasi aktivitas pelayanan pasien di klinik.
        </p>
    </div>

    <div class="row g-3 mb-4">

        <?php
        $kartu = [
            ['Total Pasien', $totalPasien, 'fas fa-users', 'primary'],
            ['Total Dokter', $totalDokter, 'fas fa-user-md', 'success'],
            ['Menunggu Persetujuan', $totalMenunggu, 'fas fa-clock', 'warning'],
            ['Pendaftaran Disetujui', $totalDisetujui, 'fas fa-check-circle', 'success'],
            ['Total Antrian', $totalAntrian, 'fas fa-list-ol', 'info'],
            ['Total Kunjungan', $totalKunjungan, 'fas fa-stethoscope', 'primary'],
            ['Belum Bayar', $totalBelumLunas, 'fas fa-money-bill-wave', 'danger']
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

    <!-- AKSES CEPAT -->

    <div class="mb-3">
        <h5 class="dashboard-section-title">
            <i class="fas fa-bolt text-warning me-2"></i>
            Aktivitas Pelayanan
        </h5>
        <p class="dashboard-subtitle">
            Pilih menu untuk menjalankan aktivitas pelayanan klinik.
        </p>
    </div>

    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3">
            <a href="index.php?page=pendaftarancheckup" class="dashboard-action shadow-sm">
                <i class="fas fa-file-medical text-success fa-2x mb-2"></i>
                <div class="fw-bold">Pendaftaran Checkup</div>
                <small class="text-muted">Kelola pendaftaran pasien</small>
            </a>
        </div>

        <div class="col-6 col-md-3">
            <a href="index.php?page=antrian" class="dashboard-action shadow-sm">
                <i class="fas fa-list-ol text-primary fa-2x mb-2"></i>
                <div class="fw-bold">Antrian Pasien</div>
                <small class="text-muted">Kelola antrian pelayanan</small>
            </a>
        </div>

        <div class="col-6 col-md-3">
            <a href="index.php?page=pasien" class="dashboard-action shadow-sm">
                <i class="fas fa-users text-info fa-2x mb-2"></i>
                <div class="fw-bold">Data Pasien</div>
                <small class="text-muted">Lihat data pasien</small>
            </a>
        </div>

        <div class="col-6 col-md-3">
            <a href="index.php?page=kunjungan" class="dashboard-action shadow-sm">
                <i class="fas fa-stethoscope text-danger fa-2x mb-2"></i>
                <div class="fw-bold">Kunjungan</div>
                <small class="text-muted">Kelola pemeriksaan pasien</small>
            </a>
        </div>

    </div>

    <!-- PENDAFTARAN TERBARU -->

    <div class="mb-3">
        <h5 class="dashboard-section-title">
            <i class="fas fa-file-medical text-success me-2"></i>
            10 Pendaftaran Terbaru
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
                            <th>No. Antrian</th>
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

                                    <td class="text-center">
                                        <?= $data['nomorantrian'] !== null
                                            ? htmlspecialchars($data['nomorantrian'])
                                            : '-'; ?>
                                    </td>

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
                                <td colspan="7" class="text-center text-muted py-3">
                                    Belum ada pendaftaran pasien.
                                </td>
                            </tr>

                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white text-end">
            <a href="index.php?page=pendaftarancheckup" class="btn btn-sm btn-success">
                Lihat Semua Pendaftaran
            </a>
        </div>
    </div>

    <!-- KUNJUNGAN TERBARU -->

    <div class="mb-3">
        <h5 class="dashboard-section-title">
            <i class="fas fa-stethoscope text-success me-2"></i>
            Kunjungan Terbaru
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
                            <th>Status Kunjungan</th>
                            <th>Status Pembayaran</th>
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
                                    <td><?= htmlspecialchars($data['statuskunjungan']); ?></td>
                                    <td><?= htmlspecialchars($data['statuspembayaran']); ?></td>
                                </tr>
                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
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
