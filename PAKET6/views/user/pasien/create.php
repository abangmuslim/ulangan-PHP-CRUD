
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

<section class="content-header">
    <div class="container-fluid">

        <div class="row mb-2">

            <div class="col-sm-6">
                <h1>
                    <i class="fas fa-user-plus"></i> Tambah Pasien
                </h1>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">

                    <li class="breadcrumb-item">
                        <a href="index.php?page=dashboarduser">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="index.php?page=pasien">
                            Pasien
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        Tambah
                    </li>

                </ol>
            </div>

        </div>

    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="card card-primary card-outline">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit me-1"></i>
                    Form Tambah Pasien
                </h3>
            </div>

            <form action="proses/prosespasien.php?aksi=tambah"
                method="POST"
                enctype="multipart/form-data">

                <div class="card-body">

                    <div class="row">

                        <!-- DATA PASIEN -->
                        <div class="col-md-7">

                            <div class="mb-3">

                                <label class="form-label">
                                    Nama Pasien
                                </label>

                                <input type="text"
                                    name="namapasien"
                                    class="form-control"
                                    placeholder="Masukkan nama lengkap pasien"
                                    maxlength="100"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Username
                                </label>

                                <input type="text"
                                    name="username"
                                    class="form-control"
                                    placeholder="Masukkan username"
                                    maxlength="50"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Password
                                </label>

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

                                        <i class="fas fa-eye"
                                            id="iconPassword"></i>

                                    </button>

                                </div>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    No HP
                                </label>

                                <input type="text"
                                    name="nohp"
                                    class="form-control"
                                    maxlength="15"
                                    placeholder="Masukkan nomor HP">

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Alamat
                                </label>

                                <textarea name="alamat"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Masukkan alamat pasien"></textarea>

                            </div>

                        </div>

                        <!-- FOTO PASIEN -->
                        <div class="col-md-5">

                            <div class="panel-foto">

                                <div class="text-center mb-4">

                                    <h5>
                                        <i class="fas fa-camera me-1"></i>
                                        Foto Pasien
                                    </h5>

                                    <img
                                        src="assets/images/pasien/default.png"
                                        id="preview"
                                        class="preview-foto mt-2"
                                        alt="Preview Foto">

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Pilih Foto
                                    </label>

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
                                        Simpan Pasien
                                    </button>

                                    <button type="reset"
                                        class="btn btn-secondary mb-2"
                                        onclick="resetFoto()">
                                        <i class="fas fa-sync"></i>
                                        Reset
                                    </button>

                                    <a href="index.php?page=pasien"
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

                document.getElementById('preview').src =
                    e.target.result;

            }

            reader.readAsDataURL(input.files[0]);

        }

    }

    function resetFoto() {

        document.getElementById('preview').src =
            'assets/images/pasien/default.png';

        document.getElementById('foto').value = '';

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
