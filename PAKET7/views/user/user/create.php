
<style>
    .preview-foto {
        width: 190px;
        height: 250px;
        object-fit: cover;
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 4px;
        background: #fff;
    }

    .panel-foto {
        height: 100%;
        padding: 25px;
        border-left: 1px solid #dee2e6;
    }

    @media (max-width: 767px) {
        .panel-foto {
            border-left: none;
            border-top: 1px solid #dee2e6;
            margin-top: 20px;
        }
    }
</style>

<section class="content">
    <div class="container-fluid">

        <div class="card card-primary card-outline shadow-sm">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit me-1"></i>
                    Form Tambah User
                </h3>
            </div>

            <form action="index.php"
                  method="POST"
                  enctype="multipart/form-data">

                <input type="hidden" name="page" value="prosesuser">
                <input type="hidden" name="aksi" value="tambah">

                <div class="card-body">
                    <div class="row">

                        <!-- DATA USER -->
                        <div class="col-md-7">

                            <div class="mb-3">
                                <label class="form-label">Nama User</label>
                                <input type="text"
                                       name="namauser"
                                       class="form-control"
                                       placeholder="Masukkan nama lengkap"
                                       maxlength="100"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text"
                                       name="username"
                                       class="form-control"
                                       placeholder="Masukkan username"
                                       maxlength="50"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>

                                <div class="input-group">
                                    <input type="password"
                                           name="password"
                                           id="password"
                                           class="form-control"
                                           placeholder="Masukkan password"
                                           required>

                                    <button type="button"
                                            class="btn btn-secondary"
                                            onclick="togglePassword()">
                                        <i class="fas fa-eye" id="iconPassword"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Level User</label>
                                <select name="level" class="form-select" required>
                                    <option value="">-- Pilih Level --</option>
                                    <option value="admin">Admin</option>
                                    <option value="petugas">Petugas</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">No HP</label>
                                <input type="text"
                                       name="nohp"
                                       class="form-control"
                                       maxlength="15"
                                       placeholder="Masukkan nomor HP">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Masukkan alamat user"></textarea>
                            </div>

                        </div>

                        <!-- FOTO USER -->
                        <div class="col-md-5">

                            <div class="panel-foto">

                                <div class="text-center mb-4">
                                    <h5>
                                        <i class="fas fa-camera me-1"></i>
                                        Foto User
                                    </h5>

                                    <img src="assets/images/user/default.png"
                                         id="preview"
                                         class="preview-foto mt-2"
                                         alt="Preview Foto">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Pilih Foto</label>

                                    <input type="file"
                                           name="foto"
                                           class="form-control"
                                           id="foto"
                                           accept=".jpg,.jpeg,.png,.webp"
                                           onchange="previewFoto(this)">

                                    <small class="text-muted d-block text-center mt-2">
                                        Format JPG, JPEG, PNG, WEBP
                                    </small>
                                </div>

                                <div class="text-center mt-4">

                                    <button type="submit"
                                            class="btn btn-primary mb-2">
                                        <i class="fas fa-save"></i>
                                        Simpan User
                                    </button>

                                    <button type="reset"
                                            class="btn btn-secondary mb-2"
                                            onclick="resetFoto()">
                                        <i class="fas fa-sync"></i>
                                        Reset
                                    </button>

                                    <a href="index.php?page=user"
                                       class="btn btn-dark mb-2">
                                        <i class="fas fa-arrow-left"></i>
                                        Kembali
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>
                </div>

            </form>

        </div>

    </div>
</section>

<script>
    function previewFoto(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function resetFoto() {
        document.getElementById('preview').src =
            'assets/images/user/default.png';

        document.getElementById('foto').value = '';
        document.getElementById('password').value = '';
        document.getElementById('password').type = 'password';

        document.getElementById('iconPassword').className = 'fas fa-eye';
    }

    function togglePassword() {
        let input = document.getElementById('password');
        let icon = document.getElementById('iconPassword');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
