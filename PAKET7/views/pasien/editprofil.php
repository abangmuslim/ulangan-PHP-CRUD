<?php
// Ambil data pasien yang sedang login
$idpasien = (int) $_SESSION['idpasien'];

$queryProfil = mysqli_prepare($koneksi, "
    SELECT *
    FROM pasien
    WHERE idpasien = ?
");

mysqli_stmt_bind_param($queryProfil, "i", $idpasien);
mysqli_stmt_execute($queryProfil);
$resultProfil = mysqli_stmt_get_result($queryProfil);
$pasien = mysqli_fetch_assoc($resultProfil);

if (!$pasien) {
    echo "<div class='alert alert-danger'>Data pasien tidak ditemukan.</div>";
    return;
}

$fotoPasien = !empty($pasien['foto']) ? $pasien['foto'] : 'default.png';
?>

<!-- Header Halaman -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Profil Pasien</h1>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="index.php?page=dashboardpasien">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="index.php?page=profilpasien">Profil Pasien</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Profil</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Konten Halaman -->
<section class="content">
    <div class="container-fluid">

        <!-- Pesan Notifikasi -->
        <?php if (isset($_GET['pesan'])) { ?>
            <?php if ($_GET['pesan'] == 'kosong') { ?>
                <div class="alert alert-warning">
                    Nama pasien wajib diisi.
                </div>
            <?php } elseif ($_GET['pesan'] == 'tipefoto') { ?>
                <div class="alert alert-warning">
                    Format foto harus JPG, JPEG, PNG, atau WEBP.
                </div>
            <?php } elseif ($_GET['pesan'] == 'fotogagal') { ?>
                <div class="alert alert-danger">
                    Foto gagal diunggah. Silakan coba kembali.
                </div>
            <?php } elseif ($_GET['pesan'] == 'gagal') { ?>
                <div class="alert alert-danger">
                    Profil gagal diperbarui.
                </div>
            <?php } ?>
        <?php } ?>

        <div class="row">

            <!-- Form Edit Profil -->
            <div class="col-md-8">
                <div class="card card-success card-outline">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-edit mr-2"></i>
                            Formulir Edit Profil
                        </h3>
                    </div>

                    <form action="index.php" method="POST" enctype="multipart/form-data">

                        <?= csrfField() ?>
                        <input type="hidden" name="page" value="profilpasien">

                        <div class="card-body">

                            <div class="form-group">
                                <label>Nama Pasien</label>
                                <input
                                    type="text"
                                    name="namapasien"
                                    class="form-control"
                                    value="<?= e($pasien['namapasien']) ?>"
                                    placeholder="Masukkan nama pasien"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label>Username</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="<?= e($pasien['username']) ?>"
                                    readonly
                                >
                                <small class="text-muted">
                                    Username tidak dapat diubah melalui halaman ini.
                                </small>
                            </div>

                            <div class="form-group">
                                <label>Nomor HP</label>
                                <input
                                    type="text"
                                    name="nohp"
                                    class="form-control"
                                    value="<?= e($pasien['nohp'] ?? '') ?>"
                                    placeholder="Masukkan nomor HP"
                                >
                            </div>

                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea
                                    name="alamat"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Masukkan alamat lengkap"
                                ><?= e($pasien['alamat'] ?? '') ?></textarea>
                            </div>

                            <!-- Foto Profil -->
                            <div class="form-group">
                                <label>Foto Profil</label>

                                <div class="mb-3">
                                    <img
                                        src="assets/images/pasien/<?= e($fotoPasien) ?>"
                                        alt="Preview Foto Pasien"
                                        id="previewFoto"
                                        class="img-thumbnail"
                                        style="width:150px;height:150px;object-fit:cover;"
                                    >
                                </div>

                                <div class="custom-file">
                                    <input
                                        type="file"
                                        name="foto"
                                        class="custom-file-input"
                                        id="foto"
                                        accept="image/jpeg,image/png,image/webp"
                                    >
                                    <label class="custom-file-label" for="foto">
                                        Pilih foto baru
                                    </label>
                                </div>

                                <small class="text-muted">
                                    Format JPG, JPEG, PNG, atau WEBP.
                                    Foto lama tetap digunakan jika tidak memilih foto baru.
                                </small>
                            </div>

                        </div>

                        <div class="card-footer">
                            <a href="index.php?page=profilpasien" class="btn btn-default">
                                <i class="fas fa-arrow-left mr-1"></i> Batal
                            </a>

                            <button type="submit" class="btn btn-success float-right">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Informasi -->
            <div class="col-md-4">
                <div class="card card-info card-outline">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-2"></i>
                            Informasi
                        </h3>
                    </div>

                    <div class="card-body">
                        <p>
                            Silakan periksa kembali data profil sebelum menyimpan perubahan.
                        </p>

                        <ul class="pl-3">
                            <li>Nama pasien wajib diisi.</li>
                            <li>Username tidak dapat diubah.</li>
                            <li>Foto baru bersifat opsional.</li>
                            <li>Foto lama tetap digunakan jika tidak memilih foto baru.</li>
                        </ul>

                        <div class="alert alert-light border mb-0">
                            <i class="fas fa-shield-alt text-success mr-1"></i>
                            Perubahan hanya berlaku untuk akun pasien yang sedang login.
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- JavaScript Preview Foto -->
<script>
document.getElementById('foto').addEventListener('change', function() {

    const file = this.files[0];

    if (file) {
        const tipeDiizinkan = ['image/jpeg', 'image/png', 'image/webp'];

        if (!tipeDiizinkan.includes(file.type)) {
            alert('Format foto harus JPG, JPEG, PNG, atau WEBP!');
            this.value = '';
            return;
        }

        // Tampilkan nama file
        document.querySelector('label[for="foto"]').textContent = file.name;

        // Preview foto baru
        const reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById('previewFoto').src = e.target.result;
        };

        reader.readAsDataURL(file);
    }
});
</script>