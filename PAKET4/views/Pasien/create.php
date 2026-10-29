<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Tambah Pasien</h5>
    </div>
    <div class="card-body">
        <form action="proses/prosespasien.php?proses=insert" method="post">
            <div class="mb-3">
                <label class="form-label">Nama Pasien</label>
                <input type="text" name="namapasien" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jeniskelamin" class="form-select">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">No HP</label>
                <input type="text" name="nohp" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php?page=pasien" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>