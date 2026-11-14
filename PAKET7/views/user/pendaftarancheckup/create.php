
<?php
// ============================================
// HALAMAN TAMBAH PENDAFTARAN CHECKUP
// ZONA ADMIN DAN PETUGAS
// ============================================

// ============================================
// 1. DATA PASIEN
// ============================================

$queryPasien = mysqli_query($koneksi, "
    SELECT
        idpasien,
        namapasien,
        nohp
    FROM pasien
    ORDER BY namapasien ASC
");

if (!$queryPasien) {
    echo '<div class="alert alert-danger">Gagal mengambil data pasien: ' .
         e(mysqli_error($koneksi)) . '</div>';
    return;
}

// ============================================
// 2. DATA JADWAL DOKTER
// ============================================

$queryJadwal = mysqli_query($koneksi, "
    SELECT
        j.idjadwal,
        j.iddokter,
        j.haripraktik,
        j.jam_mulai,
        j.jam_selesai,

        d.namadokter,
        d.spesialis

    FROM jadwaldokter j

    INNER JOIN dokter d
        ON j.iddokter = d.iddokter

    ORDER BY
        d.namadokter ASC,
        FIELD(
            j.haripraktik,
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        ),
        j.jam_mulai ASC
");

if (!$queryJadwal) {
    echo '<div class="alert alert-danger">Gagal mengambil jadwal dokter: ' .
         e(mysqli_error($koneksi)) . '</div>';
    return;
}

// ============================================
// 3. TANGGAL MINIMAL
// ============================================

$tanggalMinimal = date('Y-m-d');
?>

<div class="container-fluid">

    <!-- ============================================
         JUDUL HALAMAN
    ============================================= -->

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1 font-weight-bold">
                <i class="fas fa-calendar-plus text-primary mr-2"></i>
                Pendaftaran Checkup Langsung
            </h3>

            <p class="text-muted mb-0">
                Tambahkan pendaftaran pemeriksaan pasien melalui petugas klinik.
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="index.php?page=pendaftarancheckup" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i>
                Kembali
            </a>
        </div>

    </div>

    <!-- ============================================
         FORM PENDAFTARAN
    ============================================= -->

    <div class="row">

        <div class="col-lg-9">

            <div class="card card-outline card-primary shadow-sm">

                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-medical mr-1"></i>
                        Formulir Pendaftaran Pasien
                    </h3>
                </div>

                <form action="index.php" method="POST">

                    <?= csrfField(); ?>

                    <input
                        type="hidden"
                        name="page"
                        value="prosespendaftarancheckup">

                    <input
                        type="hidden"
                        name="aksi"
                        value="tambah">

                    <div class="card-body">

                        <!-- PASIEN -->
                        <div class="form-group">

                            <label for="idpasien">
                                Pilih Pasien
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="idpasien"
                                id="idpasien"
                                class="form-control"
                                required>

                                <option value="">
                                    -- Pilih Pasien --
                                </option>

                                <?php while ($pasien = mysqli_fetch_assoc($queryPasien)): ?>

                                    <option value="<?= (int)$pasien['idpasien']; ?>">
                                        <?= e($pasien['namapasien']); ?>
                                        <?= !empty($pasien['nohp'])
                                            ? ' - ' . e($pasien['nohp'])
                                            : ''; ?>
                                    </option>

                                <?php endwhile; ?>

                            </select>

                            <small class="form-text text-muted">
                                Pilih pasien yang sudah terdaftar dalam sistem klinik.
                            </small>

                        </div>

                        <!-- JADWAL DOKTER -->
                        <div class="form-group">

                            <label for="idjadwal">
                                Pilih Dokter dan Jadwal Praktik
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="idjadwal"
                                id="idjadwal"
                                class="form-control"
                                required>

                                <option value="">
                                    -- Pilih Dokter dan Jadwal --
                                </option>

                                <?php while ($jadwal = mysqli_fetch_assoc($queryJadwal)): ?>

                                    <option value="<?= (int)$jadwal['idjadwal']; ?>">

                                        <?= e($jadwal['namadokter']); ?>

                                        <?= !empty($jadwal['spesialis'])
                                            ? ' (' . e($jadwal['spesialis']) . ')'
                                            : ''; ?>

                                        - <?= e($jadwal['haripraktik']); ?>

                                        (<?= date('H:i', strtotime($jadwal['jam_mulai'])); ?>
                                        -
                                        <?= date('H:i', strtotime($jadwal['jam_selesai'])); ?>)

                                    </option>

                                <?php endwhile; ?>

                            </select>

                            <small class="form-text text-muted">
                                Pastikan jadwal yang dipilih sesuai dengan hari pemeriksaan.
                            </small>

                        </div>

                        <!-- TANGGAL PERIKSA -->
                        <div class="form-group">

                            <label for="tanggalperiksa">
                                Tanggal Pemeriksaan
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="date"
                                name="tanggalperiksa"
                                id="tanggalperiksa"
                                class="form-control"
                                min="<?= e($tanggalMinimal); ?>"
                                required>

                            <small class="form-text text-muted">
                                Tanggal pemeriksaan harus sesuai dengan hari praktik dokter.
                            </small>

                        </div>

                        <!-- KELUHAN -->
                        <div class="form-group">

                            <label for="keluhan">
                                Keluhan Pasien
                                <span class="text-danger">*</span>
                            </label>

                            <textarea
                                name="keluhan"
                                id="keluhan"
                                class="form-control"
                                rows="4"
                                required
                                placeholder="Tuliskan keluhan atau gejala yang dirasakan pasien..."></textarea>

                            <small class="form-text text-muted">
                                Isi keluhan pasien secara jelas untuk membantu dokter saat pemeriksaan.
                            </small>

                        </div>

                        <!-- JENIS PENDAFTARAN -->
                        <div class="form-group">

                            <label>
                                Jenis Pendaftaran
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="Langsung (Melalui Petugas Klinik)"
                                readonly>

                            <small class="form-text text-muted">
                                Pendaftaran ini dicatat sebagai pendaftaran langsung.
                            </small>

                        </div>

                    </div>

                    <!-- TOMBOL -->
                    <div class="card-footer">

                        <button
                            type="submit"
                            class="btn btn-primary"
                            onclick="return confirm('Simpan pendaftaran checkup pasien ini?');">

                            <i class="fas fa-save mr-1"></i>
                            Simpan Pendaftaran

                        </button>

                        <a
                            href="index.php?page=pendaftarancheckup"
                            class="btn btn-secondary">

                            <i class="fas fa-times mr-1"></i>
                            Batal

                        </a>

                    </div>

                </form>

            </div>

        </div>

        <!-- INFORMASI -->
        <div class="col-lg-3">

            <div class="card card-info card-outline shadow-sm">

                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-1"></i>
                        Informasi
                    </h3>
                </div>

                <div class="card-body">

                    <p>
                        <strong>Alur Pendaftaran</strong>
                    </p>

                    <ol class="pl-3">
                        <li>Pilih pasien.</li>
                        <li>Pilih dokter dan jadwal praktik.</li>
                        <li>Tentukan tanggal pemeriksaan.</li>
                        <li>Masukkan keluhan pasien.</li>
                        <li>Simpan pendaftaran.</li>
                    </ol>

                    <hr>

                    <p class="mb-2">
                        <strong>Status Awal</strong>
                    </p>

                    <span class="badge badge-warning">
                        Menunggu
                    </span>

                    <p class="text-muted mt-2 mb-0">
                        Pendaftaran harus disetujui terlebih dahulu oleh petugas sebelum pasien memperoleh nomor antrean.
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>
