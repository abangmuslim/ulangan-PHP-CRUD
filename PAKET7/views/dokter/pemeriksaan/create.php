
<?php
$iddokter = (int) ($_SESSION['iddokter'] ?? 0);
$idkunjungan = (int) ($_GET['idkunjungan'] ?? 0);

$query = mysqli_query($koneksi, "
    SELECT
        k.*,
        p.namapasien,
        p.nohp,
        p.alamat,
        pc.nomorantrian,
        pc.statusantrian
    FROM kunjungan k
    JOIN pasien p ON k.idpasien = p.idpasien
    LEFT JOIN pendaftarancheckup pc ON k.idkunjungan = pc.idkunjungan
    WHERE k.idkunjungan = '$idkunjungan'
    AND k.iddokter = '$iddokter'
    LIMIT 1
");

$data = mysqli_fetch_assoc($query);
?>


    <section class="content-header">
        <div class="container-fluid">
            <h1>Pemeriksaan Pasien</h1>
            <p class="text-muted">Formulir hasil pemeriksaan dan diagnosis pasien.</p>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <?php if (!$data): ?>
                <div class="alert alert-danger">
                    Data kunjungan tidak ditemukan atau bukan merupakan tanggung jawab Anda.
                </div>
                <a href="index.php?page=pemeriksaan" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>

            <?php elseif ($data['statuskunjungan'] == 'Selesai'): ?>
                <div class="alert alert-warning">
                    Pemeriksaan ini sudah selesai dan tidak dapat diubah lagi.
                </div>
                <a href="index.php?page=showpemeriksaan&idkunjungan=<?= $data['idkunjungan']; ?>"
                   class="btn btn-info">
                    <i class="fas fa-eye mr-1"></i> Lihat Pemeriksaan
                </a>

            <?php else: ?>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-injured mr-1"></i> Identitas Pasien
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="140">Nama Pasien</th>
                                        <td>: <?= e($data['namapasien']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>No. HP</th>
                                        <td>: <?= e($data['nohp'] ?: '-'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td>: <?= e($data['alamat'] ?: '-'); ?></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="140">Tanggal</th>
                                        <td>: <?= date('d-m-Y', strtotime($data['tanggalkunjungan'])); ?></td>
                                    </tr>
                                    <tr>
                                        <th>No. Antrean</th>
                                        <td>: <?= $data['nomorantrian'] ? e($data['nomorantrian']) : '-'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>: <span class="badge badge-warning">Proses</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-notes-medical mr-1"></i> Formulir Pemeriksaan
                        </h3>
                    </div>

                    <form action="index.php" method="POST">
                        <?= csrfField(); ?>
                        <input type="hidden" name="page" value="prosespemeriksaan">
                        <input type="hidden" name="idkunjungan" value="<?= $data['idkunjungan']; ?>">

                        <div class="card-body">
                            <div class="form-group">
                                <label>Keluhan Pasien</label>
                                <textarea class="form-control" rows="4" readonly><?= e($data['keluhan']); ?></textarea>
                                <small class="text-muted">Keluhan diambil dari data pendaftaran pasien.</small>
                            </div>

                            <div class="form-group">
                                <label>Diagnosis / Hasil Pemeriksaan</label>
                                <textarea name="diagnosis" class="form-control" rows="5"
                                          placeholder="Tuliskan hasil pemeriksaan dan diagnosis pasien..."><?= e($data['diagnosis']); ?></textarea>
                            </div>

                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle mr-1"></i>
                                Setelah pemeriksaan diselesaikan, dokter dapat melanjutkan pembuatan resep obat melalui menu Resep.
                            </div>
                        </div>

                        <div class="card-footer">
                            <a href="index.php?page=pemeriksaan" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>

                            <button type="submit" name="aksi" value="simpan" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Simpan Pemeriksaan
                            </button>

                            <button type="submit" name="aksi" value="selesai" class="btn btn-success"
                                    onclick="return confirm('Apakah Anda yakin ingin menyelesaikan pemeriksaan ini?');">
                                <i class="fas fa-check mr-1"></i> Selesaikan Pemeriksaan
                            </button>
                        </div>
                    </form>
                </div>

            <?php endif; ?>

        </div>
    </section>

