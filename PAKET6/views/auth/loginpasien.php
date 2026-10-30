<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Login Pasien</h3>

                    <?php if (isset($_GET['error'])) { ?>
                        <div class="alert alert-danger">
                            <?php
                            if ($_GET['error'] === 'kosong') {
                                echo "Username dan password wajib diisi.";
                            } else {
                                echo "Username atau password salah.";
                            }
                            ?>
                        </div>
                    <?php } ?>

                    <form action="proses/prosesloginpasien.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input
                                type="text"
                                name="username"
                                class="form-control"
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
                                required
                                autocomplete="current-password"
                            >
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            Login Pasien
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        Belum punya akun?
                        <a href="index.php?page=registerpasien">Registrasi</a>
                    </div>

                    <div class="text-center mt-2">
                        <a href="index.php?page=home">Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
