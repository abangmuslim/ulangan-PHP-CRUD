
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Tambah Merk Obat</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="index.php?page=dashboarduser">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="index.php?page=merk">Merk Obat</a>
                    </li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <form action="proses/prosesmerk.php?aksi=tambah" method="POST">

            <div class="card card-primary card-outline">

                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-plus-circle me-1"></i>
                        Form Tambah Merk
                    </h3>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-7">

                            <div class="mb-3">
                                <label for="namamerk" class="form-label">
                                    Nama Merk
                                </label>

                                <input type="text"
                                    name="namamerk"
                                    id="namamerk"
                                    class="form-control"
                                    placeholder="Masukkan nama merk obat"
                                    maxlength="100"
                                    required>
                            </div>

                        </div>

                        <div class="col-md-5 border-start">

                            <div class="text-center p-4">

                                <i class="bi bi-tags-fill text-primary"
                                    style="font-size: 80px;"></i>

                                <h5 class="mt-3">Informasi Merk Obat</h5>

                                <p class="text-muted">
                                    Data merk digunakan untuk mengelompokkan
                                    obat berdasarkan merek yang dimiliki.
                                </p>

                                <div class="alert alert-light border text-start">
                                    <i class="bi bi-info-circle text-primary me-1"></i>
                                    Pastikan nama merk ditulis dengan benar.
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card-footer">
                    <a href="index.php?page=merk" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>

                    <button type="reset" class="btn btn-warning">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset
                    </button>

                    <button type="submit" class="btn btn-primary float-end">
                        <i class="bi bi-save me-1"></i>
                        Simpan
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>
