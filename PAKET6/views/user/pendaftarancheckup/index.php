
<?php
// ========================================
// DATA PENDAFTARAN CHECKUP
// ========================================

// Filter status
$filter = $_GET['status'] ?? '';

if (!in_array($filter, ['Menunggu', 'Disetujui', 'Ditolak'])) {
    $filter = '';
}

// ========================================
// MENGHITUNG JUMLAH PENDAFTARAN
// ========================================

$queryJumlah = mysqli_query($koneksi, "
    SELECT
        COUNT(*) AS total,
        SUM(status = 'Menunggu') AS menunggu,
        SUM(status = 'Disetujui') AS disetujui,
        SUM(status = 'Ditolak') AS ditolak
    FROM pendaftarancheckup
");

$jumlah = mysqli_fetch_assoc($queryJumlah);

// ========================================
// MENAMPILKAN DATA PENDAFTARAN
// ========================================

$sql = "
    SELECT
        pendaftarancheckup.*,
        pasien.namapasien,
        pasien.nohp,
        dokter.namadokter,
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
";

if ($filter != '') {
    $sql .= " WHERE pendaftarancheckup.status = ?";
}

$sql .= " ORDER BY pendaftarancheckup.tanggalpengajuan DESC";

$stmt = mysqli_prepare($koneksi, $sql);

if ($filter != '') {
    mysqli_stmt_bind_param($stmt, "s", $filter);
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<div class="container-fluid">

    <!-- JUDUL HALAMAN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-clipboard2-pulse me-2"></i>
                Pendaftaran Checkup
            </h4>
            <p class="text-muted mb-0">
                Kelola permohonan pendaftaran pemeriksaan pasien.
            </p>
        </div>
    </div>

    <!-- RINGKASAN PENDAFTARAN -->
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Total Pendaftaran</p>
                            <h3 class="fw-bold mb-0">
                                <?= $jumlah['total'] ?? 0; ?>
                            </h3>
                        </div>
                        <i class="bi bi-clipboard-data fs-2 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Menunggu</p>
                            <h3 class="fw-bold text-warning mb-0">
                                <?= $jumlah['menunggu'] ?? 0; ?>
                            </h3>
                        </div>
                        <i class="bi bi-clock-history fs-2 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Disetujui</p>
                            <h3 class="fw-bold text-success mb-0">
                                <?= $jumlah['disetujui'] ?? 0; ?>
                            </h3>
                        </div>
                        <i class="bi bi-check-circle fs-2 text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Ditolak</p>
                            <h3 class="fw-bold text-danger mb-0">
                                <?= $jumlah['ditolak'] ?? 0; ?>
                            </h3>
                        </div>
                        <i class="bi bi-x-circle fs-2 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- TABEL PENDAFTARAN -->
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <h5 class="fw-bold mb-0">
                    <i class="bi bi-list-ul me-2"></i>
                    Daftar Permohonan Pasien
                </h5>

                <form method="GET" action="index.php">
                    <input type="hidden" name="page" value="pendaftarancheckup">

                    <select name="status"
                            class="form-select form-select-sm"
                            onchange="this.form.submit()">

                        <option value="">Semua Status</option>

                        <option value="Menunggu"
                            <?= $filter == 'Menunggu' ? 'selected' : ''; ?>>
                            Menunggu
                        </option>

                        <option value="Disetujui"
                            <?= $filter == 'Disetujui' ? 'selected' : ''; ?>>
                            Disetujui
                        </option>

                        <option value="Ditolak"
                            <?= $filter == 'Ditolak' ? 'selected' : ''; ?>>
                            Ditolak
                        </option>

                    </select>
                </form>

            </div>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-success text-center">
                        <tr>
                            <th width="50">No.</th>
                            <th>Nama Pasien</th>
                            <th>Dokter</th>
                            <th>Jadwal Praktik</th>
                            <th>Tanggal Periksa</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th width="190">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (mysqli_num_rows($result) > 0) : ?>

                            <?php $no = 1; ?>

                            <?php while ($data = mysqli_fetch_assoc($result)) : ?>

                                <tr>

                                    <td class="text-center">
                                        <?= $no++; ?>
                                    </td>

                                    <td>
                                        <div class="fw-semibold">
                                            <?= htmlspecialchars($data['namapasien']); ?>
                                        </div>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($data['nohp'] ?? '-'); ?>
                                        </small>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($data['namadokter']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($data['haripraktik']); ?>
                                        <br>
                                        <small class="text-muted">
                                            <?= date('H:i', strtotime($data['jam_mulai'])); ?>
                                            -
                                            <?= date('H:i', strtotime($data['jam_selesai'])); ?>
                                        </small>
                                    </td>

                                    <td class="text-center">
                                        <?= date('d-m-Y', strtotime($data['tanggalperiksa'])); ?>
                                    </td>

                                    <td class="text-center">
                                        <?= date('d-m-Y H:i', strtotime($data['tanggalpengajuan'])); ?>
                                    </td>

                                    <td class="text-center">

                                        <?php if ($data['status'] == 'Menunggu') : ?>

                                            <span class="badge bg-warning text-dark">
                                                Menunggu
                                            </span>

                                        <?php elseif ($data['status'] == 'Disetujui') : ?>

                                            <span class="badge bg-success">
                                                Disetujui
                                            </span>

                                        <?php else : ?>

                                            <span class="badge bg-danger">
                                                Ditolak
                                            </span>

                                        <?php endif; ?>

                                        <?php if (!empty($data['idkunjungan'])) : ?>
                                            <br>
                                            <small class="text-primary">
                                                Sudah diproses
                                            </small>
                                        <?php endif; ?>

                                    </td>

                                    <td class="text-center">

                                        <div class="d-flex justify-content-center flex-wrap gap-1">

                                            <!-- DETAIL -->
                                            <a href="index.php?page=showpendaftarancheckupadmin&idpendaftaran=<?= $data['idpendaftaran']; ?>"
                                               class="btn btn-sm btn-info text-white"
                                               title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <?php if ($data['status'] == 'Menunggu') : ?>

                                                <!-- SETUJUI -->
                                                <form action="proses/prosespendaftarancheckup.php?aksi=setujui"
                                                      method="POST"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pendaftaran ini?')">

                                                    <input type="hidden"
                                                           name="idpendaftaran"
                                                           value="<?= $data['idpendaftaran']; ?>">

                                                    <button type="submit"
                                                            class="btn btn-sm btn-success"
                                                            title="Setujui">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>

                                                </form>

                                                <!-- TOLAK -->
                                                <a href="index.php?page=showpendaftarancheckupadmin&idpendaftaran=<?= $data['idpendaftaran']; ?>#form-penolakan"
                                                   class="btn btn-sm btn-danger"
                                                   title="Tolak">
                                                    <i class="bi bi-x-lg"></i>
                                                </a>

                                            <?php elseif ($data['status'] == 'Disetujui' && empty($data['idkunjungan'])) : ?>

                                                <!-- PROSES KUNJUNGAN -->
                                                <a href="index.php?page=createkunjungan&idpendaftaran=<?= $data['idpendaftaran']; ?>"
                                                   class="btn btn-sm btn-primary"
                                                   title="Proses Kunjungan">
                                                    <i class="bi bi-arrow-right-circle"></i>
                                                </a>

                                            <?php elseif (!empty($data['idkunjungan'])) : ?>

                                                <!-- LIHAT KUNJUNGAN -->
                                                <a href="index.php?page=showkunjungan&idkunjungan=<?= $data['idkunjungan']; ?>"
                                                   class="btn btn-sm btn-primary"
                                                   title="Lihat Kunjungan">
                                                    <i class="bi bi-journal-medical"></i>
                                                </a>

                                            <?php endif; ?>

                                        </div>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else : ?>

                            <tr>
                                <td colspan="8" class="text-center py-4">

                                    <i class="bi bi-inbox fs-1 text-muted"></i>

                                    <p class="text-muted mb-0 mt-2">
                                        Belum ada data pendaftaran checkup.
                                    </p>

                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php
mysqli_stmt_close($stmt);
?>
