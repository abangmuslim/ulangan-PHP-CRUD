
<div class="d-flex justify-content-center py-4">
    <div class="card shadow-sm" style="width:100%;max-width:420px;border:1px solid #7e7e7e;border-radius:10px;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width:60px;height:60px;">
                        <i class="fas fa-user fa-2x"></i>
                    </span>
                </div>
                <h4 class="fw-bold mb-1">Login Pasien</h4>
                <p class="text-muted small mb-0">Masuk ke akun pasien Klinik Ahmadi</p>
            </div>

            <?php if (isset($_SESSION['error_login'])) : ?>
                <div class="alert alert-danger py-2">
                    <?= htmlspecialchars($_SESSION['error_login']); ?>
                </div>
                <?php unset($_SESSION['error_login']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success_register'])) : ?>
                <div class="alert alert-success py-2">
                    <?= htmlspecialchars($_SESSION['success_register']); ?>
                </div>
                <?php unset($_SESSION['success_register']); ?>
            <?php endif; ?>

            <form action="index.php?page=loginpasien" method="POST">
                <?= csrfField() ?>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-user me-1 text-primary"></i> Username
                    </label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" autocomplete="username" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-lock me-1 text-primary"></i> Password
                    </label>
                    <div class="input-group">
                        <input type="password" name="password" id="passwordPasien" class="form-control" placeholder="Masukkan password" autocomplete="current-password" required>
                        <button type="button" class="btn btn-outline-secondary" id="togglePasswordPasien" title="Tampilkan password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" name="login" class="btn btn-primary w-100">
                    <i class="fas fa-sign-in-alt me-1"></i> Login Pasien
                </button>
            </form>

            <div class="text-center mt-3">
                <p class="text-muted small mb-1">Belum memiliki akun?</p>
                <a href="index.php?page=registerpasien" class="text-primary small fw-bold text-decoration-none">
                    <i class="fas fa-user-plus me-1"></i> Registrasi Pasien
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
document.getElementById('togglePasswordPasien').addEventListener('click', function() {
    const password = document.getElementById('passwordPasien');
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
