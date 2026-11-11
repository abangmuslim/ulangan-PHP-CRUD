
<div class="d-flex justify-content-center py-4">
    <div class="card shadow-sm" style="width:100%;max-width:500px;border:1px solid #7e7e7e;border-radius:10px;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle" style="width:60px;height:60px;">
                        <i class="fas fa-user-plus fa-2x"></i>
                    </span>
                </div>
                <h4 class="fw-bold mb-1">Registrasi Pasien</h4>
                <p class="text-muted small mb-0">Buat akun pasien Klinik Ahmadi</p>
            </div>

            <?php if (isset($_SESSION['error_register'])) : ?>
                <div class="alert alert-danger py-2">
                    <?= htmlspecialchars($_SESSION['error_register']); ?>
                </div>
                <?php unset($_SESSION['error_register']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success_register'])) : ?>
                <div class="alert alert-success py-2">
                    <?= htmlspecialchars($_SESSION['success_register']); ?>
                </div>
                <?php unset($_SESSION['success_register']); ?>
            <?php endif; ?>

            <form action="index.php?page=registerpasien" method="POST" enctype="multipart/form-data">
                <?= csrfField() ?>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-user me-1 text-success"></i> Nama Lengkap
                    </label>
                    <input type="text" name="namapasien" class="form-control" placeholder="Masukkan nama lengkap" maxlength="100" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-at me-1 text-success"></i> Username
                    </label>
                    <input type="text" name="username" class="form-control" placeholder="Buat username" maxlength="50" autocomplete="username" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-phone me-1 text-success"></i> No. HP
                    </label>
                    <input type="tel" name="nohp" class="form-control" placeholder="08xxxxxxxxxx" maxlength="15">
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-map-marker-alt me-1 text-success"></i> Alamat
                    </label>
                    <textarea name="alamat" rows="3" class="form-control" placeholder="Masukkan alamat"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-image me-1 text-success"></i> Foto
                    </label>
                    <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                    <small class="text-muted">JPG, JPEG, PNG atau WEBP. Maksimal 2 MB.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-lock me-1 text-success"></i> Password
                    </label>
                    <div class="input-group">
                        <input type="password" name="password" id="passwordRegisterPasien" class="form-control" placeholder="Buat password" autocomplete="new-password" required>
                        <button type="button" class="btn btn-outline-secondary" id="togglePasswordRegisterPasien" title="Tampilkan password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-lock me-1 text-success"></i> Konfirmasi Password
                    </label>
                    <div class="input-group">
                        <input type="password" name="konfirmasi_password" id="konfirmasiPasswordPasien" class="form-control" placeholder="Ulangi password" autocomplete="new-password" required>
                        <button type="button" class="btn btn-outline-secondary" id="toggleKonfirmasiPasswordPasien" title="Tampilkan password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" name="register" class="btn btn-success w-100">
                    <i class="fas fa-user-plus me-1"></i> Registrasi Pasien
                </button>
            </form>

            <div class="text-center mt-3">
                <p class="text-muted small mb-1">Sudah memiliki akun?</p>
                <a href="index.php?page=loginpasien" class="text-primary small fw-bold text-decoration-none">
                    <i class="fas fa-sign-in-alt me-1"></i> Login Pasien
                </a>
            </div>

            <div class="text-center mt-2">
                <a href="index.php?page=home" class="text-muted small text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Home
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('togglePasswordRegisterPasien').addEventListener('click', function() {
    const password = document.getElementById('passwordRegisterPasien');
    const icon = this.querySelector('i');

    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
        this.title = 'Sembunyikan password';
    } else {
        password.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
        this.title = 'Tampilkan password';
    }
});

document.getElementById('toggleKonfirmasiPasswordPasien').addEventListener('click', function() {
    const password = document.getElementById('konfirmasiPasswordPasien');
    const icon = this.querySelector('i');

    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
        this.title = 'Sembunyikan password';
    } else {
        password.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
        this.title = 'Tampilkan password';
    }
});
</script>
