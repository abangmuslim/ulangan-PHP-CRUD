<div class="card shadow-sm">
    <div class="card-header bg-primary text-white text-center py-4">
        <h3 class="mb-2">Selamat Datang</h3>
        <p class="mb-0">Aplikasi Pendataan Pasien dan Kunjungan Rumah Sakit Ahmadi</p>
        <p class="mb-0">Silakan login untuk menggunakan aplikasi.</p>
    </div>

    <div class="card-body p-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-5">
                <h5 class="text-center mb-3">Login Admin</h5>

                <?php if (isset($_GET['pesan'])) { ?>
                    <div class="alert alert-danger">
                        Username atau password salah.
                    </div>
                <?php } ?>

                <form action="proses/proseslogin.php" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>