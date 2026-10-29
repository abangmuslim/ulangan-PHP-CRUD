<?php
$id = (int) ($_GET['id'] ?? 0);
$data = mysqli_query($koneksi, "SELECT * FROM kunjungan WHERE idkunjungan='$id'");
$kunjungan = mysqli_fetch_assoc($data);

if (!$kunjungan) {
    echo '<div class="alert alert-warning">Data kunjungan tidak ditemukan.</div>';
    echo '<a href="index.php?page=kunjungan" class="btn btn-secondary">Kembali</a>';
    return;
}

$pasien = mysqli_query($koneksi, "SELECT * FROM pasien ORDER BY namapasien ASC");
$dokter = mysqli_query($koneksi, "SELECT * FROM dokter ORDER BY namadokter ASC");
$obat = mysqli_query($koneksi, "SELECT * FROM obat ORDER BY namaobat ASC");
?>
<div class="card shadow-sm">
    <div class="card-header bg-warning">
        <h5 class="mb-0">Edit Kunjungan</h5>
    </div>
    <div class="card-body">
        <form action="proses/proseskunjungan.php?proses=update" method="POST">
            <input type="hidden" name="idkunjungan" value="<?= $kunjungan['idkunjungan']; ?>">

            <div class="mb-3">
                <label class="form-label">Pasien</label>
                <select name="idpasien" class="form-select" required>
                    <?php while ($p = mysqli_fetch_assoc($pasien)) { ?>
                    <option value="<?= $p['idpasien']; ?>" <?= $p['idpasien'] == $kunjungan['idpasien'] ? 'selected' : ''; ?>><?= $p['namapasien']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Dokter</label>
                <select name="iddokter" class="form-select" required>
                    <?php while ($d = mysqli_fetch_assoc($dokter)) { ?>
                    <option value="<?= $d['iddokter']; ?>" <?= $d['iddokter'] == $kunjungan['iddokter'] ? 'selected' : ''; ?>><?= $d['namadokter']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Obat</label>
                <select name="idobat" class="form-select" required>
                    <?php while ($o = mysqli_fetch_assoc($obat)) { ?>
                    <option value="<?= $o['idobat']; ?>" <?= $o['idobat'] == $kunjungan['idobat'] ? 'selected' : ''; ?>><?= $o['namaobat']; ?> (Stok: <?= $o['stok']; ?>)</option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Kunjungan</label>
                <input type="date" name="tanggal" class="form-control" value="<?= $kunjungan['tanggal']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Keluhan Pasien</label>
                <textarea name="keluhan" class="form-control" rows="3"><?= $kunjungan['keluhan'] ?? ''; ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Biaya (Rp)</label>
                <input type="number" name="biaya" class="form-control" min="0" value="<?= $kunjungan['biaya']; ?>" required>
            </div>
            <a href="index.php?page=kunjungan" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update Kunjungan</button>
        </form>
    </div>
</div>