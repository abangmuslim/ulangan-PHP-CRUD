<?php
// 1. Mengambil jumlah dokter
$sqlDokter = "SELECT COUNT(*) AS total FROM dokter";
$resultDokter = mysqli_query($koneksi, $sqlDokter);
$dataDokter = mysqli_fetch_assoc($resultDokter);
$totalDokter = $dataDokter['total'];

// 2. Mengambil jumlah jadwal praktik
$sqlJadwal = "SELECT COUNT(*) AS total FROM jadwaldokter";
$resultJadwal = mysqli_query($koneksi, $sqlJadwal);
$dataJadwal = mysqli_fetch_assoc($resultJadwal);
$totalJadwal = $dataJadwal['total'];

// 3. Mengambil data dokter beserta jadwal praktik
$sql = "SELECT
            d.iddokter,
            d.namadokter,
            d.spesialis,
            d.nohp,
            d.foto,
            j.idjadwal,
            j.haripraktik,
            j.jam_mulai,
            j.jam_selesai
        FROM dokter d
        LEFT JOIN jadwaldokter j ON d.iddokter = j.iddokter
        ORDER BY d.namadokter ASC, j.haripraktik ASC";

$result = mysqli_query($koneksi, $sql);
?>

<div class="container-fluid">

    <!-- Judul Halaman -->
    <div class="mb-4">
        <h3 class="fw-bold mb-1">Daftar Dokter</h3>
        <p class="text-muted mb-0">
            Informasi dokter dan jadwal praktik yang tersedia di klinik.
        </p>
    </div>

    <!-- Ringkasan -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded p-3 me-3">
                            <i class="bi bi-person-badge fs-3"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Jumlah Dokter</h6>
                            <h4 class="fw-bold mb-0">
                                <?= $totalDokter; ?> Dokter
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 text-success rounded p-3 me-3">
                            <i class="bi bi-calendar2-week fs-3"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Jumlah Jadwal Praktik</h6>
                            <h4 class="fw-bold mb-0">
                                <?= $totalJadwal; ?> Jadwal
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Dokter -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-calendar2-week text-primary"></i>
                Dokter dan Jadwal Praktik
            </h5>
        </div>

        <div class="card-body">

            <?php if (mysqli_num_rows($result) > 0) : ?>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No.</th>
                                <th>Dokter</th>
                                <th>Spesialisasi</th>
                                <th>Hari Praktik</th>
                                <th>Jam Praktik</th>
                                <th>Kontak</th>
                                <th>Status Jadwal</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $no = 1;
                            while ($data = mysqli_fetch_assoc($result)) :
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>

                                    <!-- Identitas Dokter -->
                                    <td>
                                        <div class="d-flex align-items-center">

                                            <?php if (!empty($data['foto'])) : ?>
                                                <img
                                                    src="assets/images/dokter/<?= e($data['foto']); ?>"
                                                    alt="Foto dokter"
                                                    class="rounded-circle me-2"
                                                    style="width:45px;height:45px;object-fit:cover;"
                                                >
                                            <?php else : ?>
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2"
                                                     style="width:45px;height:45px;">
                                                    <i class="bi bi-person text-secondary fs-4"></i>
                                                </div>
                                            <?php endif; ?>

                                            <span class="fw-semibold">
                                                <?= e($data['namadokter']); ?>
                                            </span>

                                        </div>
                                    </td>

                                    <!-- Spesialisasi -->
                                    <td>
                                        <?= e($data['spesialis'] ?? '-'); ?>
                                    </td>

                                    <!-- Hari Praktik -->
                                    <td>
                                        <?php if (!empty($data['haripraktik'])) : ?>
                                            <span class="fw-semibold">
                                                <?= e($data['haripraktik']); ?>
                                            </span>
                                        <?php else : ?>
                                            <span class="text-muted">
                                                Belum diatur
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Jam Praktik -->
                                    <td>
                                        <?php if (!empty($data['jam_mulai']) && !empty($data['jam_selesai'])) : ?>
                                            <span class="text-nowrap">
                                                <?= date('H:i', strtotime($data['jam_mulai'])); ?>
                                                -
                                                <?= date('H:i', strtotime($data['jam_selesai'])); ?>
                                            </span>
                                        <?php else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Kontak -->
                                    <td>
                                        <?= !empty($data['nohp']) ? e($data['nohp']) : '-'; ?>
                                    </td>

                                    <!-- Status Jadwal -->
                                    <td>
                                        <?php if (!empty($data['idjadwal'])) : ?>
                                            <span class="badge bg-success">
                                                Terjadwal
                                            </span>
                                        <?php else : ?>
                                            <span class="badge bg-secondary">
                                                Belum Ada Jadwal
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Informasi Pendaftaran -->
                <div class="alert alert-info mt-3 mb-0">
                    <i class="bi bi-info-circle"></i>
                    <strong>Informasi:</strong>
                    Perhatikan hari dan jam praktik dokter sebelum mengajukan
                    pendaftaran pemeriksaan.

                    <a href="index.php?page=creatependaftaranpasien"
                       class="alert-link">
                        Daftar pemeriksaan di sini.
                    </a>
                </div>

            <?php else : ?>

                <div class="text-center py-5">
                    <i class="bi bi-person-x text-muted" style="font-size:55px;"></i>

                    <h5 class="fw-bold mt-3">
                        Belum Ada Data Dokter
                    </h5>

                    <p class="text-muted mb-0">
                        Informasi dokter belum tersedia.
                    </p>
                </div>

            <?php endif; ?>

        </div>
    </div>

</div>