
<?php
// ============================================
// FORM PENDAFTARAN CHECKUP ONLINE
// views/pasien/pendaftaran/create.php
// ============================================

$idpasien = $_SESSION['idpasien'] ?? 0;

if (!$idpasien) {
    echo '<div class="alert alert-danger">Sesi pasien tidak ditemukan. Silakan login kembali.</div>';
    return;
}

// Data pasien yang sedang login
$idpasien = (int)$idpasien;

$queryPasien = mysqli_query($koneksi, "
    SELECT idpasien, namapasien, nohp, alamat
    FROM pasien
    WHERE idpasien = $idpasien
");

$pasien = mysqli_fetch_assoc($queryPasien);

if (!$pasien) {
    echo '<div class="alert alert-danger">Data pasien tidak ditemukan.</div>';
    return;
}

// Tanggal pemeriksaan
$tanggal = $_GET['tanggal'] ?? '';

$hariIndonesia = [
    1 => 'Senin',
    2 => 'Selasa',
    3 => 'Rabu',
    4 => 'Kamis',
    5 => 'Jumat',
    6 => 'Sabtu',
    7 => 'Minggu'
];

$hariPraktik = '';
$jadwal = false;

if ($tanggal !== '') {

    $tanggalValid = DateTime::createFromFormat('Y-m-d', $tanggal);

    if ($tanggalValid && $tanggalValid->format('Y-m-d') === $tanggal) {

        $hariAngka = (int)$tanggalValid->format('N');
        $hariPraktik = $hariIndonesia[$hariAngka];

        $tanggalSQL = mysqli_real_escape_string($koneksi, $tanggal);

        $jadwal = mysqli_query($koneksi, "
            SELECT
                j.idjadwal,
                j.hariPraktik,
                j.jam_mulai,
                j.jam_selesai,
                d.namadokter,
                d.spesialis
            FROM jadwaldokter j
            INNER JOIN dokter d ON j.iddokter = d.iddokter
            WHERE j.haripraktik = '$hariPraktik'
            ORDER BY j.jam_mulai ASC, d.namadokter ASC
        ");
    } else {
        $tanggal = '';
    }
}
?>

<div class="container-fluid">

    <div class="mb-3">
        <a href="index.php?page=pendaftaran" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-calendar-plus mr-1"></i>
                Form Pendaftaran Checkup Online
            </h3>
        </div>

        <form action="index.php" method="POST">

            <input type="hidden" name="page" value="prosespendaftaranpasien">
            <input type="hidden" name="aksi" value="daftar">
            <input type="hidden" name="jenispendaftaran" value="Online">

            <?php if (function_exists('csrfField')) {
                echo csrfField();
            } ?>

            <div class="card-body">

                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-1"></i>
                    Silakan pilih tanggal pemeriksaan dan jadwal dokter.
                    Pendaftaran akan menunggu persetujuan admin atau petugas
                    sebelum dapat masuk ke antrean.
                </div>

                <h5 class="mb-3">Data Pasien</h5>

                <div class="form-group">
                    <label>Nama Pasien</label>
                    <input type="text" class="form-control"
                           value="<?= htmlspecialchars($pasien['namapasien']); ?>"
                           readonly>
                </div>

                <div class="form-group">
                    <label>Nomor HP</label>
                    <input type="text" class="form-control"
                           value="<?= htmlspecialchars($pasien['nohp'] ?? '-'); ?>"
                           readonly>
                </div>

                <hr>

                <h5 class="mb-3">Jadwal Pemeriksaan</h5>

                <div class="form-group">
                    <label for="tanggal">Tanggal Pemeriksaan</label>

                    <div class="input-group">
                        <input type="date"
                               name="tanggalperiksa"
                               id="tanggal"
                               class="form-control"
                               min="<?= date('Y-m-d'); ?>"
                               value="<?= htmlspecialchars($tanggal); ?>"
                               required>

                        <div class="input-group-append">
                            <button type="button" class="btn btn-info"
                                    onclick="lihatJadwal()">
                                <i class="fas fa-search mr-1"></i>
                                Lihat Jadwal
                            </button>
                        </div>
                    </div>

                    <small class="text-muted">
                        Pilih tanggal terlebih dahulu untuk melihat dokter
                        yang memiliki jadwal praktik pada hari tersebut.
                    </small>
                </div>

                <?php if ($tanggal !== '' && $jadwal) { ?>

                    <div class="form-group">
                        <label>Hari Praktik</label>
                        <input type="text" class="form-control"
                               value="<?= htmlspecialchars($hariPraktik); ?>"
                               readonly>
                    </div>

                    <div class="form-group">
                        <label for="idjadwal">Pilih Dokter dan Jam Praktik</label>

                        <select name="idjadwal" id="idjadwal"
                                class="form-control" required>
                            <option value="">-- Pilih Jadwal Dokter --</option>

                            <?php if (mysqli_num_rows($jadwal) > 0) { ?>
                                <?php while ($dataJadwal = mysqli_fetch_assoc($jadwal)) { ?>

                                    <option value="<?= (int)$dataJadwal['idjadwal']; ?>">
                                        <?= htmlspecialchars($dataJadwal['namadokter']); ?>
                                        -
                                        <?= htmlspecialchars($dataJadwal['spesialis'] ?? 'Umum'); ?>
                                        |
                                        <?= htmlspecialchars(substr($dataJadwal['jam_mulai'], 0, 5)); ?>
                                        -
                                        <?= htmlspecialchars(substr($dataJadwal['jam_selesai'], 0, 5)); ?>
                                    </option>

                                <?php } ?>
                            <?php } else { ?>
                                <option value="" disabled>
                                    Tidak ada jadwal dokter pada hari ini.
                                </option>
                            <?php } ?>

                        </select>
                    </div>

                <?php } elseif ($tanggal !== '') { ?>

                    <div class="alert alert-warning">
                        Jadwal dokter gagal dimuat. Silakan coba kembali.
                    </div>

                <?php } ?>

                <hr>

                <h5 class="mb-3">Keluhan Pasien</h5>

                <div class="form-group">
                    <label for="keluhan">Keluhan yang Dirasakan</label>

                    <textarea name="keluhan" id="keluhan"
                              class="form-control" rows="4"
                              placeholder="Tuliskan keluhan atau alasan pemeriksaan Anda..."
                              required></textarea>
                </div>

                <div class="alert alert-warning">
                    <strong>Informasi:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Pendaftaran online belum langsung menjadi antrean.</li>
                        <li>Admin atau petugas akan memeriksa dan menyetujui pendaftaran.</li>
                        <li>Setelah disetujui, silakan datang ke klinik untuk proses check-in.</li>
                        <li>Nomor antrean diberikan oleh petugas sesuai proses antrean klinik.</li>
                    </ul>
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary"
                        <?= ($tanggal === '' || !$jadwal || mysqli_num_rows($jadwal) < 1) ? 'disabled' : ''; ?>>
                    <i class="fas fa-paper-plane mr-1"></i>
                    Kirim Pendaftaran
                </button>

                <a href="index.php?page=pendaftaranpasien"
                   class="btn btn-secondary">
                    Batal
                </a>
            </div>

        </form>
    </div>

</div>

<script>
function lihatJadwal() {
    var tanggal = document.getElementById('tanggal').value;

    if (tanggal === '') {
        alert('Silakan pilih tanggal pemeriksaan terlebih dahulu.');
        return;
    }

window.location.href =
    'index.php?page=creatependaftaranpasien&tanggal=' +
    encodeURIComponent(tanggal);
}
</script>
