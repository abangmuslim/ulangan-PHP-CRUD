
<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$queryedit = mysqli_query($koneksi, "
    SELECT * FROM obat WHERE idobat = '$id'
");

$dataedit = mysqli_fetch_assoc($queryedit);

if (!$dataedit) {
    echo "<script>window.location='index.php?page=obat';</script>";
    exit;
}

$querymerk = mysqli_query($koneksi, "
    SELECT * FROM merk ORDER BY namamerk ASC
");

$querykategori = mysqli_query($koneksi, "
    SELECT * FROM kategori ORDER BY namakategori ASC
");

$pathfoto = "assets/images/obat/" . $dataedit['foto'];
$fotoada = !empty($dataedit['foto']) && file_exists($pathfoto);
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">

            <div class="col-sm-6">
                <h1 class="m-0">Edit Obat</h1>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="index.php?page=dashboarduser">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="index.php?page=obat">Data Obat</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Obat</li>
                </ol>
            </div>

        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <form action="proses/prosesobat.php?aksi=ubah"
            method="POST"
            enctype="multipart/form-data">

            <input type="hidden"
                name="idobat"
                value="<?= $dataedit['idobat']; ?>">

            <input type="hidden"
                name="fotolama"
                value="<?= htmlspecialchars($dataedit['foto']); ?>">

            <div class="card card-primary card-outline">

                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-pencil-square me-1"></i>
                        Form Edit Obat
                    </h3>
                </div>

                <div class="card-body">

                    <div class="row">

                        <!-- KOLOM KIRI: DATA OBAT -->
                        <div class="col-md-7">

                            <div class="mb-3">
                                <label for="namaobat" class="form-label">
                                    Nama Obat
                                </label>

                                <input type="text"
                                    name="namaobat"
                                    id="namaobat"
                                    class="form-control"
                                    value="<?= htmlspecialchars($dataedit['namaobat']); ?>"
                                    maxlength="100"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="idmerk" class="form-label">
                                    Merk Obat
                                </label>

                                <select name="idmerk"
                                    id="idmerk"
                                    class="form-select"
                                    required>

                                    <option value="">-- Pilih Merk --</option>

                                    <?php while ($merk = mysqli_fetch_assoc($querymerk)) { ?>
                                        <option value="<?= $merk['idmerk']; ?>"
                                            <?= ($dataedit['idmerk'] == $merk['idmerk']) ? 'selected' : ''; ?>>

                                            <?= htmlspecialchars($merk['namamerk']); ?>

                                        </option>
                                    <?php } ?>

                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="idkategori" class="form-label">
                                    Kategori Obat
                                </label>

                                <select name="idkategori"
                                    id="idkategori"
                                    class="form-select"
                                    required>

                                    <option value="">-- Pilih Kategori --</option>

                                    <?php while ($kategori = mysqli_fetch_assoc($querykategori)) { ?>
                                        <option value="<?= $kategori['idkategori']; ?>"
                                            <?= ($dataedit['idkategori'] == $kategori['idkategori']) ? 'selected' : ''; ?>>

                                            <?= htmlspecialchars($kategori['namakategori']); ?>

                                        </option>
                                    <?php } ?>

                                </select>
                            </div>

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="harga" class="form-label">
                                            Harga Obat (Rp)
                                        </label>

                                        <input type="number"
                                            name="harga"
                                            id="harga"
                                            class="form-control"
                                            value="<?= $dataedit['harga']; ?>"
                                            min="0"
                                            required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="stok" class="form-label">
                                            Jumlah Stok
                                        </label>

                                        <input type="number"
                                            name="stok"
                                            id="stok"
                                            class="form-control"
                                            value="<?= $dataedit['stok']; ?>"
                                            min="0"
                                            required>
                                    </div>
                                </div>

                            </div>

                            <div class="mb-3">
                                <label for="tanggalstok" class="form-label">
                                    Tanggal Stok
                                </label>

                                <input type="date"
                                    name="tanggalstok"
                                    id="tanggalstok"
                                    class="form-control"
                                    value="<?= htmlspecialchars($dataedit['tanggalstok'] ?? ''); ?>">
                            </div>

                        </div>

                        <!-- KOLOM KANAN: FOTO OBAT -->
                        <div class="col-md-5 border-start">

                            <div class="text-center p-3">

                                <label class="form-label d-block">
                                    Foto Obat
                                </label>

                                <div class="mb-3">

                                    <img id="previewFoto"
                                        src="<?= $fotoada ? $pathfoto : ''; ?>"
                                        alt="Foto Obat"
                                        class="img-thumbnail"
                                        style="width: 190px; height: 220px; object-fit: contain; <?= $fotoada ? '' : 'display: none;'; ?>">

                                    <div id="placeholderFoto"
                                        class="border rounded d-flex align-items-center justify-content-center mx-auto"
                                        style="width: 190px; height: 220px; <?= $fotoada ? 'display: none;' : ''; ?>">

                                        <i class="bi bi-capsule text-secondary"
                                            style="font-size: 80px;"></i>

                                    </div>

                                </div>

                                <input type="file"
                                    name="foto"
                                    id="foto"
                                    class="form-control"
                                    accept=".jpg,.jpeg,.png,.webp">

                                <small class="text-muted d-block mt-2">
                                    Format JPG, JPEG, PNG, atau WEBP.
                                    Kosongkan jika tidak ingin mengganti foto.
                                </small>

                                <button type="button"
                                    class="btn btn-outline-secondary btn-sm mt-2"
                                    onclick="resetFoto()">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                                    Batalkan Foto Baru
                                </button>

                                <div class="alert alert-light border text-start mt-3">
                                    <i class="bi bi-info-circle text-primary me-1"></i>
                                    Foto lama akan tetap digunakan jika tidak
                                    memilih foto pengganti.
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card-footer">

                    <a href="index.php?page=obat" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>

                    <button type="reset"
                        class="btn btn-warning"
                        onclick="setTimeout(resetFoto, 10)">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset Form
                    </button>

                    <button type="submit" class="btn btn-primary float-end">
                        <i class="bi bi-save me-1"></i>
                        Update Obat
                    </button>

                </div>

            </div>

        </form>

    </div>
</div>

<script>
    const inputFoto = document.getElementById('foto');
    const previewFoto = document.getElementById('previewFoto');
    const placeholderFoto = document.getElementById('placeholderFoto');

    const fotoLama = <?= json_encode($fotoada ? $pathfoto : ''); ?>;

    inputFoto.addEventListener('change', function() {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewFoto.src = e.target.result;
                previewFoto.style.display = 'inline-block';
                placeholderFoto.style.display = 'none';
            };

            reader.readAsDataURL(file);
        }
    });

    function resetFoto() {
        inputFoto.value = '';

        if (fotoLama !== '') {
            previewFoto.src = fotoLama;
            previewFoto.style.display = 'inline-block';
            placeholderFoto.style.display = 'none';
        } else {
            previewFoto.src = '';
            previewFoto.style.display = 'none';
            placeholderFoto.style.display = 'flex';
        }
    }
</script>
