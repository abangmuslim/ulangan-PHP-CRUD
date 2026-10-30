
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
                    <i class="bi bi-person-plus"></i> Tambah Dokter
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
                        <a href="index.php?page=dokter">
                            Dokter
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
                    <i class="bi bi-person-plus me-1"></i>
                    Form Tambah Dokter
                </h3>
            </div>

            <form action="proses/prosesdokter.php?aksi=tambah"
                method="POST"
                enctype="multipart/form-data">

                <div class="card-body">

                    <div class="row">

                        <!-- DATA DOKTER -->
                        <div class="col-md-7">

                            <div class="mb-3">

                                <label class="form-label">
                                    Nama Dokter
                                </label>

                                <input type="text"
                                    name="namadokter"
                                    class="form-control"
                                    placeholder="Masukkan nama dokter"
                                    maxlength="100"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Spesialis
                                </label>

                                <input type="text"
                                    name="spesialis"
                                    class="form-control"
                                    placeholder="Contoh: Dokter Umum, Anak, Gigi"
                                    maxlength="100">

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    No HP
                                </label>

                                <input type="text"
                                    name="nohp"
                                    class="form-control"
                                    placeholder="Masukkan nomor HP"
                                    maxlength="15">

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Alamat
                                </label>

                                <textarea name="alamat"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Masukkan alamat dokter"></textarea>

                            </div>

                        </div>

                        <!-- FOTO DOKTER -->
                        <div class="col-md-5">

                            <div class="panel-foto">

                                <div class="text-center mb-4">

                                    <h5>
                                        <i class="bi bi-camera me-1"></i>
                                        Foto Dokter
                                    </h5>

                                    <img
                                        src="assets/images/dokter/default.png"
                                        id="preview"
                                        class="preview-foto mt-2"
                                        alt="Preview Foto">

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Pilih Foto Dokter
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
                                        <i class="bi bi-save"></i>
                                        Simpan Dokter
                                    </button>

                                    <button type="reset"
                                        class="btn btn-secondary mb-2"
                                        onclick="resetFoto()">
                                        <i class="bi bi-arrow-clockwise"></i>
                                        Reset
                                    </button>

                                    <a href="index.php?page=dokter"
                                        class="btn btn-dark mb-2">
                                        <i class="bi bi-arrow-left"></i>
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
    const fotoAwal = 'assets/images/dokter/default.png';

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

        document.getElementById('preview').src = fotoAwal;

        document.getElementById('foto').value = '';

    }
</script>
