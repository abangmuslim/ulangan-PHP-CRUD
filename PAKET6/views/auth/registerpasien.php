<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Registrasi Pasien</h3>

                    <?php if (isset($_GET['status'])) { ?>
                        <?php if ($_GET['status'] === 'berhasil') { ?>
                            <div class="alert alert-success">
                                Registrasi berhasil. Silakan login.
                            </div>
                        <?php } elseif ($_GET['status'] === 'duplikat') { ?>
                            <div class="alert alert-danger">
                                Username sudah digunakan. Silakan pilih username lain.
                            </div>
                        <?php } elseif ($_GET['status'] === 'password') { ?>
                            <div class="alert alert-danger">
                                Konfirmasi password tidak sama.
                            </div>
                        <?php } elseif ($_GET['status'] === 'gagal') { ?>
                            <div class="alert alert-danger">
                                Registrasi gagal. Periksa kembali data Anda.
                            </div>
                        <?php } ?>
                    <?php } ?>

                    <form action="proses/prosesregisterpasien.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input
                                type="text"
                                name="namapasien"
                                class="form-control"
                                maxlength="100"
                                required
                                autocomplete="name"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input
                                type="text"
                                name="username"
                                class="form-control"
                                maxlength="50"
                                required
                                autocomplete="username"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                minlength="8"
                                required
                                autocomplete="new-password"
                            >
                            <small class="text-muted">
                                Gunakan minimal 8 karakter.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input
                                type="password"
                                name="konfirmasi_password"
                                class="form-control"
                                minlength="8"
                                required
                                autocomplete="new-password"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor HP</label>
                            <input
                                type="tel"
                                name="nohp"
                                class="form-control"
                                maxlength="15"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea
                                name="alamat"
                                class="form-control"
                                rows="3"
                            ></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            Daftar Sekarang
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        Sudah punya akun?
                        <a href="index.php?page=loginpasien">Login Pasien</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
