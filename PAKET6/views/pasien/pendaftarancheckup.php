<?php
$idpasien = $_SESSION['idpasien'];
$pesan = "";

// Proses pengajuan checkup.
if (isset($_POST['daftar'])) {

    $idjadwal = mysqli_real_escape_string(
        $koneksi, $_POST['idjadwal']
    );

    $tanggalperiksa = mysqli_real_escape_string(
        $koneksi, $_POST['tanggalperiksa']
    );

    $keluhan = mysqli_real_escape_string(
        $koneksi, trim($_POST['keluhan'])
    );

    if (
        empty($idjadwal) ||
        empty($tanggalperiksa) ||
        empty($keluhan)
    ) {

        $pesan = "
            <div class='alert alert-danger'>
                Semua data pendaftaran wajib diisi.
            </div>
        ";

    } elseif (strtotime($tanggalperiksa) < strtotime(date('Y-m-d'))) {

        $pesan = "
            <div class='alert alert-danger'>
                Tanggal pemeriksaan tidak boleh sebelum hari ini.
            </div>
        ";

    } else {

        // Pastikan jadwal yang dipilih tersedia.
        $cekjadwal = mysqli_query($koneksi, "
            SELECT idjadwal
            FROM jadwaldokter
            WHERE idjadwal = '$idjadwal'
        ");

        if (mysqli_num_rows($cekjadwal) == 0) {

            $pesan = "
                <div class='alert alert-danger'>
                    Jadwal dokter tidak ditemukan.
                </div>
            ";

        } else {

            // Cegah pengajuan ganda pada jadwal dan tanggal yang sama.
            $cekdaftar = mysqli_query($koneksi, "
                SELECT idpendaftaran
                FROM pendaftarancheckup
                WHERE idpasien = '$idpasien'
                AND idjadwal = '$idjadwal'
                AND tanggalperiksa = '$tanggalperiksa'
                AND status = 'Menunggu'
            ");

            if (mysqli_num_rows($cekdaftar) > 0) {

                $pesan = "
                    <div class='alert alert-warning'>
                        Anda sudah memiliki permohonan pada jadwal dan tanggal tersebut.
                    </div>
                ";

            } else {

                $simpan = mysqli_query($koneksi, "
                    INSERT INTO pendaftarancheckup
                    (
                        idpasien,
                        idjadwal,
                        tanggalperiksa,
                        keluhan
                    )
                    VALUES
                    (
                        '$idpasien',
                        '$idjadwal',
                        '$tanggalperiksa',
                        '$keluhan'
                    )
                ");

                if ($simpan) {

                    echo "
                        <script>
                            alert('Permohonan checkup berhasil dikirim. Silakan menunggu konfirmasi admin.');
                            window.location='?page=pendaftarancheckup';
                        </script>
                    ";

                    exit;

                } else {

                    $pesan = "
                        <div class='alert alert-danger'>
                            Permohonan gagal disimpan.
                        </div>
                    ";
                }
            }
        }
    }
}

// Ambil jadwal dokter.
$queryjadwal = mysqli_query($koneksi, "
    SELECT
        jadwaldokter.idjadwal,
        jadwaldokter.haripraktik,
        jadwaldokter.jam_mulai,
        jadwaldokter.jam_selesai,
        dokter.namadokter,
        dokter.spesialis
    FROM jadwaldokter
    INNER JOIN dokter
        ON jadwaldokter.iddokter = dokter.iddokter
    ORDER BY dokter.namadokter ASC
");

// Ambil riwayat permohonan pasien.
$querypermohonan = mysqli_query($koneksi, "
    SELECT
        pendaftarancheckup.*,
        dokter.namadokter,
        dokter.spesialis,
        jadwaldokter.haripraktik,
        jadwaldokter.jam_mulai,
        jadwaldokter.jam_selesai
    FROM pendaftarancheckup
    INNER JOIN jadwaldokter
        ON pendaftarancheckup.idjadwal = jadwaldokter.idjadwal
    INNER JOIN dokter
        ON jadwaldokter.iddokter = dokter.iddokter
    WHERE pendaftarancheckup.idpasien = '$idpasien'
    ORDER BY pendaftarancheckup.tanggalpengajuan DESC
");
?>

<div class="container-fluid py-4">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Pendaftaran Checkup</h4>
        <p class="text-muted mb-0">
            Ajukan pemeriksaan kesehatan melalui jadwal dokter yang tersedia.
        </p>
    </div>

    <?= $pesan; ?>

    <div class="row">

        <div class="col-lg-5 mb-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-plus"></i>
                        Formulir Pendaftaran
                    </h5>
                </div>

                <div class="card-body p-4">

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Pilih Jadwal Dokter
                            </label>

                            <select name="idjadwal"
                                    class="form-select"
                                    required>

                                <option value="">
                                    -- Pilih Dokter dan Jadwal --
                                </option>

                                <?php while ($jadwal = mysqli_fetch_assoc($queryjadwal)) { ?>

                                    <option value="<?= $jadwal['idjadwal']; ?>">

                                        <?= htmlspecialchars($jadwal['namadokter']); ?>
                                        -
                                        <?= htmlspecialchars($jadwal['spesialis']); ?>
                                        |
                                        <?= htmlspecialchars($jadwal['haripraktik']); ?>
                                        |
                                        <?= htmlspecialchars($jadwal['jam_mulai']); ?>
                                        -
                                        <?= htmlspecialchars($jadwal['jam_selesai']); ?>

                                    </option>

                                <?php } ?>

                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Tanggal Pemeriksaan
                            </label>

                            <input type="date"
                                   name="tanggalperiksa"
                                   class="form-control"
                                   min="<?= date('Y-m-d'); ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Keluhan
                            </label>

                            <textarea name="keluhan"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Tuliskan keluhan yang ingin diperiksa..."
                                      required></textarea>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            Permohonan akan diperiksa admin terlebih dahulu.
                            Pendaftaran ini belum berarti kunjungan telah dikonfirmasi.
                        </div>

                        <button type="submit"
                                name="daftar"
                                class="btn btn-primary w-100">
                            <i class="bi bi-send"></i>
                            Kirim Permohonan
                        </button>

                    </form>

                </div>
            </div>

        </div>

        <div class="col-lg-7 mb-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-clock-history"></i>
                        Riwayat Permohonan Saya
                    </h5>
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>No.</th>
                                    <th>Dokter</th>
                                    <th>Tanggal</th>
                                    <th>Jadwal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $no = 1;

                                if (mysqli_num_rows($querypermohonan) > 0) {

                                    while ($permohonan = mysqli_fetch_assoc($querypermohonan)) {
                                ?>

                                    <tr>

                                        <td><?= $no++; ?></td>

                                        <td>
                                            <strong>
                                                <?= htmlspecialchars($permohonan['namadokter']); ?>
                                            </strong>
                                            <br>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($permohonan['spesialis']); ?>
                                            </small>
                                        </td>

                                        <td>
                                            <?= date(
                                                'd-m-Y',
                                                strtotime($permohonan['tanggalperiksa'])
                                            ); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($permohonan['haripraktik']); ?>
                                            <br>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($permohonan['jam_mulai']); ?>
                                                -
                                                <?= htmlspecialchars($permohonan['jam_selesai']); ?>
                                            </small>
                                        </td>

                                        <td>

                                            <?php if ($permohonan['status'] == 'Menunggu') { ?>

                                                <span class="badge bg-warning text-dark">
                                                    Menunggu
                                                </span>

                                            <?php } elseif ($permohonan['status'] == 'Disetujui') { ?>

                                                <span class="badge bg-success">
                                                    Disetujui
                                                </span>

                                            <?php } else { ?>

                                                <span class="badge bg-danger">
                                                    Ditolak
                                                </span>

                                            <?php } ?>

                                            <?php if (!empty($permohonan['catatanadmin'])) { ?>
                                                <br>
                                                <small class="text-muted">
                                                    <?= htmlspecialchars($permohonan['catatanadmin']); ?>
                                                </small>
                                            <?php } ?>

                                        </td>

                                    </tr>

                                <?php
                                    }

                                } else {
                                ?>

                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            Belum ada permohonan checkup.
                                        </td>
                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                </div>
            </div>

        </div>

    </div>

</div>