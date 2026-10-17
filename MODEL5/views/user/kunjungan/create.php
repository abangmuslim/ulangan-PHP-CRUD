<?php
$pasien = mysqli_query($koneksi, "SELECT * FROM pasien ORDER BY namapasien ASC");
$dokter = mysqli_query($koneksi, "SELECT * FROM dokter ORDER BY namadokter ASC");
$obat = mysqli_query($koneksi, "SELECT * FROM obat ORDER BY namaobat ASC");
?>
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Tambah Kunjungan</h5>
    </div>
    <div class="card-body">
        <form action="proses/proseskunjungan.php?proses=insert" method="POST">
            <div class="mb-3">
                <label class="form-label">Pasien</label>
                <select name="idpasien" class="form-select" required>
                    <option value="">-- Pilih Pasien --</option>
                    <?php while ($p = mysqli_fetch_assoc($pasien)) { ?>
                    <option value="<?= $p['idpasien']; ?>"><?= $p['namapasien']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Dokter</label>
                <select name="iddokter" class="form-select" required>
                    <option value="">-- Pilih Dokter --</option>
                    <?php while ($d = mysqli_fetch_assoc($dokter)) { ?>
                    <option value="<?= $d['iddokter']; ?>"><?= $d['namadokter']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Obat</label>
                <select name="idobat" class="form-select" required>
                    <option value="">-- Pilih Obat --</option>
                    <?php while ($o = mysqli_fetch_assoc($obat)) { ?>
                    <option value="<?= $o['idobat']; ?>"><?= $o['namaobat']; ?> (Stok: <?= $o['stok']; ?>)</option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Kunjungan</label>
                <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Keluhan Pasien</label>
                <textarea name="keluhan" class="form-control" rows="3" placeholder="Tuliskan keluhan pasien"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Biaya (Rp)</label>
                <input type="number" name="biaya" class="form-control" min="0" value="0" required>
            </div>
            <a href="index.php?page=kunjungan" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Kunjungan</button>
        </form>
    </div>
</div>