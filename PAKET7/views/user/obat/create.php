
<?php
// ============================================
// DATA AWAL OBAT
// ============================================

$fotobaru = 'assets/images/obat/default.png';

// Ambil data merk
$queryMerk = mysqli_query($koneksi, "
    SELECT idmerk, namamerk
    FROM merk
    ORDER BY namamerk ASC
");

// Ambil data kategori
$queryKategori = mysqli_query($koneksi, "
    SELECT idkategori, namakategori
    FROM kategori
    ORDER BY namakategori ASC
");
?>

<style>
    .preview-foto {
        width: 220px;
        height: 250px;
        object-fit: contain;
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 8px;
        background: #fff;
    }

    .panel-foto {
        height: 100%;
        padding: 25px;
        border-left: 1px solid #dee2e6;
    }

    .info-form {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 12px;
    }

    @media (max-width: 767px) {
        .panel-foto {
            border-left: none;
            border-top: 1px solid #dee2e6;
            margin-top: 20px;
        }
    }
</style>

<div class="card card-primary card-outline shadow-sm">

    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-plus-circle me-1"></i>
            Form Tambah Obat
        </h3>
    </div>

    <form action="index.php"
          method="POST"
          enctype="multipart/form-data">

        <input type="hidden" name="page" value="prosesobat">
        <input type="hidden" name="aksi" value="tambah">

        <?= csrfField(); ?>

        <div class="card-body">

            <div class="row">

                <!-- DATA OBAT -->
                <div class="col-md-7">

                    <div class="mb-3">
                        <label class="form-label">
                            Nama Obat
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="namaobat"
                               class="form-control"
                               maxlength="100"
                               placeholder="Masukkan nama obat"
                               required>
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Merk Obat
                                <span class="text-danger">*</span>
                            </label>

                            <select name="idmerk"
                                    class="form-select"
                                    required>

                                <option value="">-- Pilih Merk --</option>

                                <?php while ($merk = mysqli_fetch_assoc($queryMerk)) { ?>
                                    <option value="<?= $merk['idmerk']; ?>">
                                        <?= e($merk['namamerk']); ?>
                                    </option>
                                <?php } ?>

                            </select>

                            <small class="text-muted">
                                Data diambil dari master merk.
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Kategori Obat
                                <span class="text-danger">*</span>
                            </label>

                            <select name="idkategori"
                                    class="form-select"
                                    required>

                                <option value="">-- Pilih Kategori --</option>

                                <?php while ($kategori = mysqli_fetch_assoc($queryKategori)) { ?>
                                    <option value="<?= $kategori['idkategori']; ?>">
                                        <?= e($kategori['namakategori']); ?>
                                    </option>
                                <?php } ?>

                            </select>

                            <small class="text-muted">
                                Data diambil dari master kategori.
                            </small>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Harga Obat
                                <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">Rp</span>

                                <input type="number"
                                       name="harga"
                                       class="form-control"
                                       min="0"
                                       step="1"
                                       placeholder="0"
                                       required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Stok Awal
                                <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <input type="number"
                                       name="stok"
                                       class="form-control"
                                       min="0"
                                       step="1"
                                       placeholder="0"
                                       required>

                                <span class="input-group-text">Unit</span>
                            </div>
                        </div>

                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Tanggal Stok
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="tanggalstok"
                               class="form-control"
                               value="<?= date('Y-m-d'); ?>"
                               required>

                        <small class="text-muted">
                            Tanggal pencatatan stok awal obat.
                        </small>
                    </div>

                    <div class="info-form mt-4">
                        <div class="d-flex">
                            <i class="bi bi-info-circle text-primary me-2 mt-1"></i>
                            <div>
                                <strong>Informasi Pengisian</strong>
                                <p class="mb-0 small text-muted">
                                    Pastikan nama obat, merk, kategori,
                                    harga, dan stok sudah benar sebelum
                                    menyimpan data.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- FOTO OBAT -->
                <div class="col-md-5">

                    <div class="panel-foto">

                        <div class="text-center mb-4">

                            <h5>
                                <i class="bi bi-camera me-1"></i>
                                Foto Obat
                            </h5>

                            <img src="<?= e($fotobaru); ?>"
                                 id="preview"
                                 class="preview-foto mt-2"
                                 alt="Preview Foto Obat">

                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Upload Foto
                            </label>

                            <input type="file"
                                   name="foto"
                                   class="form-control"
                                   id="foto"
                                   accept=".jpg,.jpeg,.png,.webp"
                                   onchange="previewFoto(this)">

                            <small class="text-muted d-block text-center mt-2">
                                Format JPG, JPEG, PNG, WEBP.
                                Maksimal 2 MB.
                            </small>
                        </div>

                        <div class="alert alert-light border mt-3">
                            <i class="bi bi-image text-primary me-1"></i>
                            Foto digunakan untuk membantu identifikasi
                            obat pada daftar dan detail obat.
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="card-footer">

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                Simpan Obat
            </button>

            <button type="reset"
                    class="btn btn-secondary"
                    onclick="resetFoto()">
                <i class="bi bi-arrow-counterclockwise me-1"></i>
                Reset
            </button>

            <a href="index.php?page=obat"
               class="btn btn-dark">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali
            </a>

        </div>

    </form>

</div>

<script>
    const fotoDefault = <?= json_encode($fotobaru); ?>;

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
        document.getElementById('preview').src = fotoDefault;
        document.getElementById('foto').value = '';
    }
</script>
