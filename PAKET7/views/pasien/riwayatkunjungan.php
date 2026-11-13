
<?php
// Mengambil ID pasien yang sedang login
$idpasien = (int) $_SESSION['idpasien'];

// Mengambil riwayat kunjungan pasien
$sql = "SELECT
            k.idkunjungan,
            k.tanggalkunjungan,
            k.keluhan,
            k.diagnosis,
            k.totalkunjungan,
            k.statuspembayaran,
            k.statuskunjungan,
            d.namadokter,
            d.spesialis
        FROM kunjungan k
        INNER JOIN dokter d ON k.iddokter = d.iddokter
        WHERE k.idpasien = ?
        ORDER BY k.tanggalkunjungan DESC, k.idkunjungan DESC";

$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "i", $idpasien);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$totalKunjungan = mysqli_num_rows($result);
?>

<div class="container-fluid">

    <!-- Judul Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Riwayat Kunjungan</h3>
            <p class="text-muted mb-0">
                Daftar riwayat pemeriksaan kesehatan Anda.
            </p>
        </div>

        <a href="index.php?page=dashboardpasien" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Informasi Jumlah Riwayat -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 text-primary rounded p-3 me-3">
                    <i class="bi bi-clipboard2-pulse fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Riwayat Kunjungan</h6>
                    <h4 class="fw-bold mb-0">
                        <?= $totalKunjungan; ?> Kunjungan
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat Kunjungan -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Daftar Pemeriksaan</h5>
        </div>

        <div class="card-body">
            <?php if ($totalKunjungan > 0) : ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No.</th>
                                <th>Tanggal</th>
                                <th>Dokter</th>
                                <th>Keluhan</th>
                                <th>Diagnosis</th>
                                <th>Total Biaya</th>
                                <th>Pembayaran</th>
                                <th>Kunjungan</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($data = mysqli_fetch_assoc($result)) :
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td class="text-nowrap">
                                        <?= date('d-m-Y', strtotime($data['tanggalkunjungan'])); ?>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">
                                            <?= e($data['namadokter']); ?>
                                        </div>
                                        <small class="text-muted">
                                            <?= e($data['spesialis'] ?? '-'); ?>
                                        </small>
                                    </td>
                                    <td><?= e($data['keluhan'] ?? '-'); ?></td>
                                    <td><?= e($data['diagnosis'] ?? '-'); ?></td>
                                    <td class="text-nowrap">
                                        Rp <?= number_format($data['totalkunjungan'], 0, ',', '.'); ?>
                                    </td>
                                    <td>
                                        <?php if ($data['statuspembayaran'] == 'Lunas') : ?>
                                            <span class="badge bg-success">Lunas</span>
                                        <?php else : ?>
                                            <span class="badge bg-warning text-dark">Belum Bayar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($data['statuskunjungan'] == 'Selesai') : ?>
                                            <span class="badge bg-success">Selesai</span>
                                        <?php else : ?>
                                            <span class="badge bg-info text-dark">Proses</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="index.php?page=detilkunjunganpasien&idkunjungan=<?= (int) $data['idkunjungan']; ?>"
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div class="text-center py-5">
                    <i class="bi bi-clipboard-x text-muted" style="font-size: 60px;"></i>
                    <h5 class="fw-bold mt-3">Belum Ada Riwayat Kunjungan</h5>
                    <p class="text-muted">
                        Anda belum memiliki riwayat pemeriksaan.
                    </p>
                    <a href="index.php?page=pendaftaranpasien" class="btn btn-primary">
                        <i class="bi bi-calendar-plus"></i>
                        Lihat Pendaftaran Pemeriksaan
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
mysqli_stmt_close($stmt);
?>
