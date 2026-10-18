<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tambah Dokter</h5>
            </div>
            <div class="card-body">
                <form action="proses/prosesdokter.php?proses=insert" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Dokter</label>
                        <input type="text" name="namadokter" class="form-control" maxlength="100" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Spesialis</label>
                        <input type="text" name="spesialis" class="form-control" maxlength="100" placeholder="Contoh: Penyakit Dalam">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor HP</label>
                        <input type="text" name="nohp" class="form-control" maxlength="15" placeholder="Masukkan nomor HP dokter">
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.php?page=dokter" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Dokter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>