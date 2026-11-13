<?php
$iddokter = (int) $_SESSION['iddokter'];
$idkunjungan = (int) ($_GET['idkunjungan'] ?? 0);
$iddetilkunjungan = (int) ($_GET['iddetilkunjungan'] ?? 0);

$edit = $iddetilkunjungan > 0;

// Ambil data kunjungan
$stmt = mysqli_prepare($koneksi, "
    SELECT
        k.idkunjungan,
        k.tanggalkunjungan,
        k.keluhan,
        k.diagnosis,
        k.statuskunjungan,
        p.namapasien
    FROM kunjungan k
    JOIN pasien p ON k.idpasien = p.idpasien
    WHERE k.idkunjungan = ?
    AND k.iddokter = ?
");

mysqli_stmt_bind_param($stmt, "ii", $idkunjungan, $iddokter);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$kunjungan = mysqli_fetch_assoc($result);

if (!$kunjungan) {
    echo "<section class='content'><div class='alert alert-danger'>Kunjungan tidak ditemukan atau bukan milik Anda.</div></section>";
    return;
}

if ($kunjungan['statuskunjungan'] != 'Proses') {
    echo "<section class='content'><div class='alert alert-warning'>Pemeriksaan sudah selesai. Resep tidak dapat diubah.</div></section>";
    return;
}

// Data detail resep jika sedang edit
$detail = null;

if ($edit) {
    $stmt = mysqli_prepare($koneksi, "
        SELECT *
        FROM detilkunjungan
        WHERE iddetilkunjungan = ?
        AND idkunjungan = ?
    ");

    mysqli_stmt_bind_param($stmt, "ii", $iddetilkunjungan, $idkunjungan);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $detail = mysqli_fetch_assoc($result);

    if (!$detail) {
        echo "<section class='content'><div class='alert alert-danger'>Detail resep tidak ditemukan.</div></section>";
        return;
    }
}

// Daftar obat
$queryObat = mysqli_query($koneksi, "
    SELECT idobat, namaobat, harga, stok
    FROM obat
    ORDER BY namaobat ASC
");
?>

<section class="content-header">
    <div class="container-fluid">
        <h1><?= $edit ? 'Ubah Resep Obat' : 'Tambah Resep Obat'; ?></h1>
        <p class="text-muted mb-0">Data resep untuk kunjungan pasien.</p>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal') { ?>
            <div class="alert alert-danger">
                Data resep gagal diproses. Periksa kembali data yang dimasukkan.
            </div>
        <?php } ?>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Kunjungan</h3>
            </div>

            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th width="180">Nama Pasien</th>
                        <td>: <?= e($kunjungan['namapasien']); ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Kunjungan</th>
                        <td>: <?= date('d-m-Y', strtotime($kunjungan['tanggalkunjungan'])); ?></td>
                    </tr>
                    <tr>
                        <th>Keluhan</th>
                        <td>: <?= e($kunjungan['keluhan']); ?></td>
                    </tr>
                    <tr>
                        <th>Diagnosis</th>
                        <td>: <?= e($kunjungan['diagnosis']); ?></td>
                    </tr>
                    <tr>
                        <th>Status Pemeriksaan</th>
                        <td>: <span class="badge badge-warning">Proses</span></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?= $edit ? 'Form Ubah Resep' : 'Form Tambah Resep'; ?>
                </h3>
            </div>

            <form action="index.php" method="POST">
                <div class="card-body">

                    <input type="hidden" name="page" value="prosesresep">
                    <input type="hidden" name="aksi" value="<?= $edit ? 'ubah' : 'tambah'; ?>">
                    <input type="hidden" name="idkunjungan" value="<?= $idkunjungan; ?>">
                    <input type="hidden" name="iddetilkunjungan" value="<?= $iddetilkunjungan; ?>">

                    <?= csrfField(); ?>

                    <div class="form-group">
                        <label>Nama Obat</label>
                        <select name="idobat" class="form-control" required>
                            <option value="">-- Pilih Obat --</option>

                            <?php while ($obat = mysqli_fetch_assoc($queryObat)) { ?>
                                <option value="<?= $obat['idobat']; ?>"
                                    <?= ($edit && $detail['idobat'] == $obat['idobat']) ? 'selected' : ''; ?>>
                                    <?= e($obat['namaobat']); ?>
                                    - Rp <?= number_format($obat['harga'], 0, ',', '.'); ?>
                                    (Stok: <?= (int) $obat['stok']; ?>)
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Obat</label>
                        <input type="number"
                               name="jumlah"
                               class="form-control"
                               min="1"
                               required
                               value="<?= $edit ? (int) $detail['jumlah'] : 1; ?>">
                    </div>

                    <div class="form-group">
                        <label>Aturan Pakai</label>
                        <textarea name="aturanpakai"
                                  class="form-control"
                                  rows="3"
                                  required
                                  placeholder="Contoh: 3 x sehari sesudah makan"><?= $edit ? e($detail['aturanpakai']) : ''; ?></textarea>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Harga dan subtotal akan dihitung otomatis berdasarkan harga obat di database.
                        Stok obat tidak dikurangi saat resep dibuat.
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Resep
                    </button>

                    <a href="index.php?page=showresep&idkunjungan=<?= $idkunjungan; ?>"
                       class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </form>
        </div>

    </div>
</section>