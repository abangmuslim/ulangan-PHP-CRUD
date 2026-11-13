<?php
// ============================================
// AMBIL DATA DOKTER
// ============================================
$iddokter = (int) ($_GET['iddokter'] ?? 0);

$stmt = mysqli_prepare($koneksi, "SELECT * FROM dokter WHERE iddokter = ?");
mysqli_stmt_bind_param($stmt, "i", $iddokter);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$dokter = mysqli_fetch_assoc($result);

if (!$dokter) {
    echo '<div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-1"></i>
            Data dokter tidak ditemukan.
          </div>';
    return;
}

$fotolama = !empty($dokter['foto'])
    ? 'assets/images/dokter/' . $dokter['foto']
    : 'assets/images/dokter/default.png';
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
                    Form Edit Dokter
                </h3>
            </div>

            <form action="index.php"
                  method="POST"
                  enctype="multipart/form-data">

                <input type="hidden" name="page" value="prosesdokter">
                <input type="hidden" name="aksi" value="edit">
                <input type="hidden" name="iddokter" value="<?= $dokter['iddokter'] ?>">
                <input type="hidden" name="fotolama" value="<?= e($dokter['foto']) ?>">

                <div class="card-body">
                    <div class="row">

                        <!-- DATA DOKTER -->
                        <div class="col-md-7">

                            <div class="mb-3">
                                <label class="form-label">Nama Dokter</label>
                                <input type="text"
                                       name="namadokter"
                                       class="form-control"
                                       value="<?= e($dokter['namadokter']) ?>"
                                       maxlength="100"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Spesialis</label>
                                <input type="text"
                                       name="spesialis"
                                       class="form-control"
                                       value="<?= e($dokter['spesialis']) ?>"
                                       maxlength="100"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text"
                                       name="username"
                                       class="form-control"
                                       value="<?= e($dokter['username']) ?>"
                                       maxlength="50"
                                       required>
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
                                       value="<?= e($dokter['nohp']) ?>"
                                       placeholder="Masukkan nomor HP">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Masukkan alamat dokter"><?= e($dokter['alamat']) ?></textarea>
                            </div>

                        </div>

                        <!-- FOTO DOKTER -->
                        <div class="col-md-5">

                            <div class="panel-foto">

                                <div class="text-center mb-4">
                                    <h5>
                                        <i class="fas fa-camera me-1"></i>
                                        Foto Dokter
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

                                    <a href="index.php?page=dokter"
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