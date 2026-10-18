
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tambah User</h5>
            </div>
            <div class="card-body">
                <form action="proses/prosesuser.php?proses=insert" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama User</label>
                        <input type="text" name="namauser" class="form-control" maxlength="100" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" maxlength="50" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.php?page=user" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
