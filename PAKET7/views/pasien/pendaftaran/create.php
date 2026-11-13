
<?php
$idpasien = $_SESSION['idpasien'];
$pesan = "";

// Proses pengajuan pendaftaran.
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

    if (empty($idjadwal) || empty($tanggalperiksa) || empty($keluhan)) {

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

        // Pastikan jadwal dokter tersedia.
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

            // Cegah pendaftaran ganda pada jadwal dan tanggal yang sama.
            $cekdaftar = mysqli_query($koneksi, "
                SELECT idpendaftaran
                FROM pendaftarancheckup
                WHERE idpasien = '$idpasien'
                AND idjadwal = '$idjadwal'
                AND tanggalperiksa = '$tanggalperiksa'
                AND status IN ('Menunggu', 'Disetujui')
            ");

            if (mysqli_num_rows($cekdaftar) > 0) {

                $pesan = "
                    <div class='alert alert-warning'>
                        Anda sudah memiliki permohonan pada jadwal dan tanggal tersebut.
                    </div>
                ";

            } else {

                // Simpan permohonan pendaftaran.
                $simpan = mysqli_query($koneksi, "
                    INSERT INTO pendaftarancheckup
                    (
                        idpasien,
                        idjadwal,
                        tanggalperiksa,
                        keluhan,
                        jenispendaftaran,
                        status,
                        statusantrian
                    )
                    VALUES
                    (
                        '$idpasien',
                        '$idjadwal',
                        '$tanggalperiksa',
                        '$keluhan',
                        'Online',
                        'Menunggu',
                        'Belum Antri'
                    )
                ");

                if ($simpan) {

                    echo "
                        <script>
                            alert('Pendaftaran berhasil dikirim. Silakan menunggu konfirmasi admin.');
                            window.location='index.php?page=pendaftaranpasien';
                        </script>
                    ";

                    exit;

                } else {

                    $pesan = "
                        <div class='alert alert-danger'>
                            Pendaftaran gagal disimpan.
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
    ORDER BY dokter.namadokter ASC, jadwaldokter.haripraktik ASC
");
?>

<div class="mb-4">
    <h4 class="fw-bold mb-1">Formulir Pendaftaran Checkup</h4>
    <p class="text-muted mb-0">
        Silakan pilih dokter dan jadwal pemeriksaan yang tersedia.
    </p>
</div>

<?= $pesan; ?>

<div class="row">

    <div class="col-lg-8">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-plus me-2"></i>
                    Formulir Pengajuan Pemeriksaan
                </h5>
            </div>

            <div class="card-body p-4">

                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Pilih Dokter dan Jadwal
                        </label>

                        <select name="idjadwal"
                                class="form-select"
                                required>

                            <option value="">
                                -- Pilih Dokter dan Jadwal --
                            </option>

                            <?php while ($jadwal = mysqli_fetch_assoc($queryjadwal)) { ?>

                                <option value="<?= $jadwal['idjadwal']; ?>">

                                    <?= e($jadwal['namadokter']); ?>
                                    -
                                    <?= e($jadwal['spesialis']); ?>
                                    |
                                    <?= e($jadwal['haripraktik']); ?>
                                    |
                                    <?= e($jadwal['jam_mulai']); ?>
                                    -
                                    <?= e($jadwal['jam_selesai']); ?>

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
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Informasi Pendaftaran:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Permohonan akan dikonfirmasi oleh admin atau petugas.</li>
                            <li>Anda harus menunggu sampai pendaftaran disetujui.</li>
                            <li>Nomor antrean diberikan petugas ketika Anda melakukan check-in di klinik.</li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between">

                        <a href="index.php?page=pendaftaranpasien"
                           class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>

                        <button type="submit"
                                name="daftar"
                                class="btn btn-success">
                            <i class="fas fa-paper-plane me-1"></i>
                            Kirim Pendaftaran
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>

    <div class="col-lg-4">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-info-circle me-2 text-success"></i>
                    Petunjuk
                </h5>
            </div>

            <div class="card-body">

                <div class="mb-3">
                    <h6 class="fw-bold">1. Pilih Dokter</h6>
                    <p class="text-muted small mb-0">
                        Pilih dokter sesuai spesialisasi dan hari praktik.
                    </p>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="fw-bold">2. Tentukan Tanggal</h6>
                    <p class="text-muted small mb-0">
                        Tentukan tanggal pemeriksaan sesuai jadwal dokter.
                    </p>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="fw-bold">3. Tuliskan Keluhan</h6>
                    <p class="text-muted small mb-0">
                        Jelaskan keluhan kesehatan yang ingin diperiksa.
                    </p>
                </div>

                <hr>

                <div>
                    <h6 class="fw-bold">4. Tunggu Konfirmasi</h6>
                    <p class="text-muted small mb-0">
                        Pantau status permohonan melalui menu Pendaftaran Checkup.
                    </p>
                </div>

            </div>
        </div>

    </div>

</div>
