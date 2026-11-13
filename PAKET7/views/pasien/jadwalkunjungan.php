
<?php
$iddokter = (int) ($_SESSION['iddokter'] ?? 0);

// ========================================
// 1. DATA JADWAL PRAKTIK DOKTER
// ========================================
$queryjadwal = mysqli_query($koneksi, "
    SELECT *
    FROM jadwaldokter
    WHERE iddokter = $iddokter
    ORDER BY FIELD(haripraktik,
        'Senin', 'Selasa', 'Rabu', 'Kamis',
        'Jumat', 'Sabtu', 'Minggu'
    ), jam_mulai ASC
");

// ========================================
// 2. DATA JADWAL KUNJUNGAN PASIEN
// ========================================
$querykunjungan = mysqli_query($koneksi, "
    SELECT
        pendaftarancheckup.*,
        pasien.namapasien,
        pasien.nohp,
        jadwaldokter.haripraktik,
        jadwaldokter.jam_mulai,
        jadwaldokter.jam_selesai
    FROM pendaftarancheckup
    INNER JOIN pasien
        ON pendaftarancheckup.idpasien = pasien.idpasien
    INNER JOIN jadwaldokter
        ON pendaftarancheckup.idjadwal = jadwaldokter.idjadwal
    WHERE jadwaldokter.iddokter = $iddokter
        AND pendaftarancheckup.status = 'Disetujui'
        AND pendaftarancheckup.tanggalperiksa >= CURDATE()
    ORDER BY
        pendaftarancheckup.tanggalperiksa ASC,
        pendaftarancheckup.nomorantrian ASC
");
?>

<div class="container-fluid pt-3">

    <!-- JUDUL HALAMAN -->
    <div class="row mb-3">
        <div class="col-md-8">
            <h4 class="font-weight-bold">
                <i class="fas fa-calendar-alt text-success mr-2"></i>
                Jadwal Kunjungan
            </h4>
            <p class="text-muted">
                Informasi jadwal praktik dan daftar kunjungan pasien Anda.
            </p>
        </div>
    </div>

    <!-- ================================= -->
    <!-- BAGIAN 1: JADWAL PRAKTIK DOKTER -->
    <!-- ================================= -->
    <div class="card card-outline card-success shadow-sm mb-4">

        <div class="card-header">
            <h5 class="card-title font-weight-bold">
                <i class="fas fa-clock mr-2 text-success"></i>
                Jadwal Praktik Saya
            </h5>
        </div>

        <div class="card-body">

            <?php if (mysqli_num_rows($queryjadwal) > 0) { ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th width="60">No.</th>
                                <th>Hari Praktik</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Durasi Praktik</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $no = 1;
                            while ($jadwal = mysqli_fetch_assoc($queryjadwal)) {
                                $mulai = strtotime($jadwal['jam_mulai']);
                                $selesai = strtotime($jadwal['jam_selesai']);
                                $durasi = ($selesai - $mulai) / 3600;
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>

                                    <td>
                                        <span class="badge badge-success">
                                            <?= e($jadwal['haripraktik']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?= date('H:i', $mulai); ?> WIB
                                    </td>

                                    <td>
                                        <?= date('H:i', $selesai); ?> WIB
                                    </td>

                                    <td>
                                        <?= number_format($durasi, 1); ?> Jam
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            <?php } else { ?>

                <div class="text-center text-muted py-4">
                    <i class="fas fa-calendar-times fa-3x mb-3"></i>
                    <p class="mb-0">
                        Belum ada jadwal praktik yang tersedia.
                    </p>
                </div>

            <?php } ?>

        </div>
    </div>


    <!-- ================================= -->
    <!-- BAGIAN 2: KUNJUNGAN PASIEN -->
    <!-- ================================= -->
    <div class="card card-outline card-success shadow-sm">

        <div class="card-header">
            <h5 class="card-title font-weight-bold">
                <i class="fas fa-user-clock mr-2 text-success"></i>
                Daftar Jadwal Kunjungan Pasien
            </h5>
        </div>

        <div class="card-body">

            <?php if (mysqli_num_rows($querykunjungan) > 0) { ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">

                        <thead class="bg-light">
                            <tr>
                                <th width="50">No.</th>
                                <th>Tanggal Periksa</th>
                                <th>Pasien</th>
                                <th>Nomor Antrean</th>
                                <th>Jam Praktik</th>
                                <th>Jenis Pendaftaran</th>
                                <th>Keluhan</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $no = 1;
                            while ($kunjungan = mysqli_fetch_assoc($querykunjungan)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>

                                    <td>
                                        <strong>
                                            <?= date(
                                                'd-m-Y',
                                                strtotime($kunjungan['tanggalperiksa'])
                                            ); ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <strong>
                                            <?= e($kunjungan['namapasien']); ?>
                                        </strong>
                                        <br>
                                        <small class="text-muted">
                                            <?= e($kunjungan['nohp'] ?? '-'); ?>
                                        </small>
                                    </td>

                                    <td class="text-center">
                                        <?php if ($kunjungan['nomorantrian'] != null) { ?>
                                            <span class="badge badge-primary">
                                                <?= e($kunjungan['nomorantrian']); ?>
                                            </span>
                                        <?php } else { ?>
                                            <span class="text-muted">-</span>
                                        <?php } ?>
                                    </td>

                                    <td>
                                        <?= date(
                                            'H:i',
                                            strtotime($kunjungan['jam_mulai'])
                                        ); ?>
                                        -
                                        <?= date(
                                            'H:i',
                                            strtotime($kunjungan['jam_selesai'])
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= e($kunjungan['jenispendaftaran']); ?>
                                    </td>

                                    <td>
                                        <?= e($kunjungan['keluhan']); ?>
                                    </td>

                                    <td>
                                        <span class="badge badge-success">
                                            <?= e($kunjungan['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>

                    </table>
                </div>

            <?php } else { ?>

                <div class="text-center text-muted py-4">
                    <i class="fas fa-calendar-check fa-3x mb-3"></i>
                    <p class="mb-0">
                        Belum ada jadwal kunjungan pasien yang disetujui.
                    </p>
                </div>

            <?php } ?>

        </div>
    </div>

</div>
