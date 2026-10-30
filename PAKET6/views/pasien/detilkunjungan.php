
<?php
// Mengambil ID pasien dari session
$idpasien = $_SESSION['idpasien'];

// Mengambil ID kunjungan dari URL
$idkunjungan = isset($_GET['idkunjungan']) ? (int) $_GET['idkunjungan'] : 0;

// Mengambil data kunjungan berdasarkan ID kunjungan dan ID pasien
$sql = "SELECT
            k.*,
            p.namapasien,
            d.namadokter,
            d.spesialis,
            j.hari praktik
        FROM kunjungan k
        INNER JOIN pasien p ON k.idpasien = p.idpasien
        INNER JOIN dokter d ON k.iddokter = d.iddokter
        INNER JOIN jadwaldokter j ON k.idjadwal = j.idjadwal
        WHERE k.idkunjungan = ?
        AND k.idpasien = ?
        LIMIT 1";

$stmt = mysqli_prepare($koneksi, $sql);

// Catatan: nama kolom jadwal praktik menggunakan haripraktik.
// Query diperbaiki dengan alias agar dapat digunakan di PHP.
$sql = "SELECT
            k.*,
            p.namapasien,
            d.namadokter,
            d.spesialis,
            j.haripraktik,
            j.jam_mulai,
            j.jam_selesai
        FROM kunjungan k
        INNER JOIN pasien p ON k.idpasien = p.idpasien
        INNER JOIN dokter d ON k.iddokter = d.iddokter
        INNER JOIN jadwaldokter j ON k.idjadwal = j.idjadwal
        WHERE k.idkunjungan = ?
        AND k.idpasien = ?
        LIMIT 1";

$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "ii", $idkunjungan, $idpasien);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

// Jika data tidak ditemukan atau bukan milik pasien yang login
if (!$data) :
?>

    <div class="container-fluid">
        <div class="alert alert-warning shadow-sm">
            <h5 class="alert-heading">
                <i class="bi bi-exclamation-triangle"></i>
                Data Kunjungan Tidak Ditemukan
            </h5>

            <p class="mb-3">
                Data kunjungan tidak tersedia atau Anda tidak memiliki akses
                untuk melihat data tersebut.
            </p>

            <a href="index.php?page=riwayatkunjungan"
               class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Riwayat
            </a>
        </div>
    </div>

<?php
    mysqli_stmt_close($stmt);
    return;
endif;

// Mengambil daftar obat dalam kunjungan
$sqlObat = "SELECT
                dk.*,
                o.namaobat
            FROM detilkunjungan dk
            INNER JOIN obat o ON dk.idobat = o.idobat
            WHERE dk.idkunjungan = ?
            ORDER BY dk.iddetilkunjungan ASC";

$stmtObat = mysqli_prepare($koneksi, $sqlObat);
mysqli_stmt_bind_param($stmtObat, "i", $idkunjungan);
mysqli_stmt_execute($stmtObat);

$resultObat = mysqli_stmt_get_result($stmtObat);
?>

<div class="container-fluid">

    <!-- Judul Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Detail Kunjungan</h3>
            <p class="text-muted mb-0">
                Informasi lengkap pemeriksaan dan obat Anda.
            </p>
        </div>

        <a href="index.php?page=riwayatkunjungan"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Informasi Kunjungan -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-clipboard2-pulse text-primary"></i>
                Informasi Kunjungan
            </h5>
        </div>

        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-6">
                    <small class="text-muted">Nomor Kunjungan</small>
                    <h6 class="fw-bold">
                        #<?= $data['idkunjungan']; ?>
                    </h6>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Tanggal Kunjungan</small>
                    <h6 class="fw-bold">
                        <?= date('d-m-Y', strtotime($data['tanggalkunjungan'])); ?>
                    </h6>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Nama Pasien</small>
                    <h6 class="fw-bold">
                        <?= htmlspecialchars($data['namapasien']); ?>
                    </h6>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Dokter Pemeriksa</small>
                    <h6 class="fw-bold">
                        <?= htmlspecialchars($data['namadokter']); ?>
                    </h6>
                    <small class="text-muted">
                        <?= htmlspecialchars($data['spesialis']); ?>
                    </small>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Hari Praktik</small>
                    <h6 class="fw-bold">
                        <?= htmlspecialchars($data['haripraktik']); ?>
                    </h6>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Jam Praktik</small>
                    <h6 class="fw-bold">
                        <?= htmlspecialchars($data['jam_mulai']); ?>
                        -
                        <?= htmlspecialchars($data['jam_selesai']); ?>
                    </h6>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Status Kunjungan</small>
                    <div>
                        <?php if ($data['statuskunjungan'] == 'Selesai') : ?>
                            <span class="badge bg-success">
                                Selesai
                            </span>
                        <?php else : ?>
                            <span class="badge bg-info text-dark">
                                Proses
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Status Pembayaran</small>
                    <div>
                        <?php if ($data['statuspembayaran'] == 'Lunas') : ?>
                            <span class="badge bg-success">
                                Lunas
                            </span>
                        <?php else : ?>
                            <span class="badge bg-warning text-dark">
                                Belum Bayar
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Keluhan dan Diagnosis -->
    <div class="row g-4 mb-4">

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-chat-left-text text-primary"></i>
                        Keluhan Pasien
                    </h5>
                </div>

                <div class="card-body">
                    <?= nl2br(htmlspecialchars($data['keluhan'])); ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-clipboard-check text-success"></i>
                        Diagnosis Dokter
                    </h5>
                </div>

                <div class="card-body">
                    <?php if (!empty($data['diagnosis'])) : ?>
                        <?= nl2br(htmlspecialchars($data['diagnosis'])); ?>
                    <?php else : ?>
                        <span class="text-muted">
                            Diagnosis belum dicatat.
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <!-- Daftar Obat -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-capsule text-primary"></i>
                Daftar Obat
            </h5>
        </div>

        <div class="card-body">

            <?php if (mysqli_num_rows($resultObat) > 0) : ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No.</th>
                                <th>Nama Obat</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                                <th>Aturan Pakai</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $no = 1;

                            while ($obat = mysqli_fetch_assoc($resultObat)) :
                            ?>

                                <tr>
                                    <td><?= $no++; ?></td>

                                    <td>
                                        <?= htmlspecialchars($obat['namaobat']); ?>
                                    </td>

                                    <td>
                                        <?= $obat['jumlah']; ?>
                                    </td>

                                    <td class="text-nowrap">
                                        Rp <?= number_format($obat['harga'], 0, ',', '.'); ?>
                                    </td>

                                    <td class="text-nowrap">
                                        Rp <?= number_format($obat['subtotal'], 0, ',', '.'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($obat['aturanpakai']); ?>
                                    </td>
                                </tr>

                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php else : ?>

                <div class="text-center py-4">
                    <i class="bi bi-capsule text-muted" style="font-size: 45px;"></i>

                    <p class="text-muted mt-2 mb-0">
                        Tidak ada obat yang dicatat dalam kunjungan ini.
                    </p>
                </div>

            <?php endif; ?>

        </div>
    </div>

    <!-- Rincian Pembayaran -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-receipt text-primary"></i>
                Rincian Pembayaran
            </h5>
        </div>

        <div class="card-body">

            <div class="row justify-content-end">
                <div class="col-md-6 col-lg-5">

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Biaya</span>
                        <strong>
                            Rp <?= number_format($data['totalkunjungan'], 0, ',', '.'); ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Jumlah Bayar</span>
                        <strong>
                            Rp <?= number_format($data['jumlahbayar'], 0, ',', '.'); ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Kembalian</span>
                        <strong>
                            Rp <?= number_format($data['kembalian'], 0, ',', '.'); ?>
                        </strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Status Pembayaran</span>

                        <?php if ($data['statuspembayaran'] == 'Lunas') : ?>
                            <span class="badge bg-success fs-6">
                                LUNAS
                            </span>
                        <?php else : ?>
                            <span class="badge bg-warning text-dark fs-6">
                                BELUM BAYAR
                            </span>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Tombol Kembali -->
    <div class="mb-4">
        <a href="index.php?page=riwayatkunjungan"
           class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Riwayat Kunjungan
        </a>
    </div>

</div>

<?php
mysqli_stmt_close($stmt);
mysqli_stmt_close($stmtObat);
?>
