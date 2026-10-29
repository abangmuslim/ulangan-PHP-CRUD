<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Tambah Obat</h5>
    </div>
    <div class="card-body">
        <form action="proses/prosesobat.php?proses=insert" method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Obat</label>
                <input type="text" name="namaobat" class="form-control" placeholder="Masukkan nama obat" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis Obat</label>
                <input type="text" name="jenisobat" class="form-control" placeholder="Contoh: Tablet, Kapsul, Sirup">
            </div>
            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" min="0" value="0" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" min="0" value="0" required>
            </div>
            <a href="index.php?page=obat" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Obat</button>
        </form>
    </div>
</div>