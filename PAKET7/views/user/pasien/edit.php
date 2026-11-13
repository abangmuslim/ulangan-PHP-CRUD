<?php
// ============================================
// AMBIL DATA PASIEN
// ============================================
$idpasien = (int) ($_GET['idpasien'] ?? 0);

$stmt = mysqli_prepare($koneksi, "SELECT * FROM pasien WHERE idpasien = ?");
mysqli_stmt_bind_param($stmt, "i", $idpasien);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pasien = mysqli_fetch_assoc($result);

if (!$pasien) {
    echo '<div class="alert alert-danger">Data pasien tidak ditemukan.</div>';
    return;
}

$fotolama = !empty($pasien['foto'])
    ? 'assets/images/pasien/' . $pasien['foto']
    : 'assets/images/pasien/default.png';
?>

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

        <div class="card card-warning card-outline shadow-sm">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit me-1"></i>
                    Form Edit Pasien
                </h3>
            </div>

            <form action="index.php"
                  method="POST"
                  enctype="multipart/form-data">

                <input type="hidden" name="page" value="prosespasien">
                <input type="hidden" name="aksi" value="edit">
                <input type="hidden" name="idpasien" value="<?= $pasien['idpasien'] ?>">
                <input type="hidden" name="fotolama" value="<?= e($pasien['foto']) ?>">

                <div class="card-body">
                    <div class="row">

                        <!-- DATA PASIEN -->
                        <div class="col-md-7">

                            <div class="mb-3">
                                <label class="form-label">Nama Pasien</label>
                                <input type="text"
                                       name="namapasien"
                                       class="form-control"
                                       value="<?= e($pasien['namapasien']) ?>"
                                       maxlength="100"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text"
                                       name="username"
                                       class="form-control"
                                       value="<?= e($pasien['username']) ?>"
                                       maxlength="50"
                                       placeholder="Masukkan username pasien">
                                <small class="text-muted">
                                    Kosongkan jika pasien belum memiliki akun.
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>

                                <div class="input-group">
                                    <input type="password"
                                           name="password"
                                           id="password"
                                           class="form-control"
                                           placeholder="Kosongkan jika tidak diganti">

                                    <button type="button"
                                            class="btn btn-secondary"
                                            onclick="togglePassword()">
                                        <i class="fas fa-eye" id="iconPassword"></i>
                                    </button>
                                </div>

                                <small class="text-muted">
                                    Kosongkan apabila tetap menggunakan password lama.
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">No HP</label>
                                <input type="text"
                                       name="nohp"
                                       class="form-control"
                                       maxlength="15"
                                       value="<?= e($pasien['nohp']) ?>"
                                       placeholder="Masukkan nomor HP">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Masukkan alamat pasien"><?= e($pasien['alamat']) ?></textarea>
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

                                    <img src="<?= e($fotolama) ?>"
                                         id="preview"
                                         class="preview-foto mt-2"
                                         alt="Preview Foto">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Ganti Foto</label>

                                    <input type="file"
                                           name="foto"
                                           class="form-control"
                                           id="foto"
                                           accept=".jpg,.jpeg,.png,.webp"
                                           onchange="previewFoto(this)">

                                    <small class="text-muted d-block text-center mt-2">
                                        Format JPG, JPEG, PNG, WEBP.
                                        Kosongkan jika tidak diganti.
                                    </small>
                                </div>

                                <div class="text-center mt-4">

                                    <button type="submit"
                                            class="btn btn-warning mb-2">
                                        <i class="fas fa-save"></i>
                                        Simpan Perubahan
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
    const fotoLama = <?= json_encode($fotolama) ?>;

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
        document.getElementById('preview').src = fotoLama;
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