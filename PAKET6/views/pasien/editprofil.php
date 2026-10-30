<?php
$idpasien = $_SESSION['idpasien'];

$query = mysqli_query($koneksi, "
    SELECT * FROM pasien
    WHERE idpasien = '$idpasien'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<div class='alert alert-danger'>Data pasien tidak ditemukan.</div>";
    return;
}

$pesan = "";

if (isset($_POST['simpan'])) {

    $namapasien = mysqli_real_escape_string(
        $koneksi, trim($_POST['namapasien'])
    );

    $username = mysqli_real_escape_string(
        $koneksi, trim($_POST['username'])
    );

    $nohp = mysqli_real_escape_string(
        $koneksi, trim($_POST['nohp'])
    );

    $alamat = mysqli_real_escape_string(
        $koneksi, trim($_POST['alamat'])
    );

    $password = trim($_POST['password']);

    // Periksa username agar tidak digunakan pasien lain.
    $cekusername = mysqli_query($koneksi, "
        SELECT idpasien FROM pasien
        WHERE username = '$username'
        AND idpasien != '$idpasien'
    ");

    if (mysqli_num_rows($cekusername) > 0) {

        $pesan = "
            <div class='alert alert-danger'>
                Username sudah digunakan pasien lain.
            </div>
        ";

    } else {

        $foto = $data['foto'];

        // Upload foto jika pasien memilih foto baru.
        if (!empty($_FILES['foto']['name'])) {

            $namaFile = $_FILES['foto']['name'];
            $tmpFile = $_FILES['foto']['tmp_name'];
            $ukuranFile = $_FILES['foto']['size'];

            $ekstensi = strtolower(
                pathinfo($namaFile, PATHINFO_EXTENSION)
            );

            $ekstensiDiizinkan = ['jpg', 'jpeg', 'png'];

            if (!in_array($ekstensi, $ekstensiDiizinkan)) {

                $pesan = "
                    <div class='alert alert-danger'>
                        Foto harus berformat JPG, JPEG, atau PNG.
                    </div>
                ";

            } elseif ($ukuranFile > 2 * 1024 * 1024) {

                $pesan = "
                    <div class='alert alert-danger'>
                        Ukuran foto maksimal 2 MB.
                    </div>
                ";

            } else {

                $namaFotoBaru = uniqid('pasien_') . '.' . $ekstensi;
                $folderFoto = "assets/images/pasien/";

                if (move_uploaded_file(
                    $tmpFile,
                    $folderFoto . $namaFotoBaru
                )) {

                    if (!empty($foto) &&
                        file_exists($folderFoto . $foto)) {
                        unlink($folderFoto . $foto);
                    }

                    $foto = $namaFotoBaru;

                } else {

                    $pesan = "
                        <div class='alert alert-danger'>
                            Foto gagal diunggah.
                        </div>
                    ";
                }
            }
        }

        if ($pesan == "") {

            $namapasienDB = mysqli_real_escape_string(
                $koneksi, $namapasien
            );

            $usernameDB = mysqli_real_escape_string(
                $koneksi, $username
            );

            $nohpDB = mysqli_real_escape_string(
                $koneksi, $nohp
            );

            $alamatDB = mysqli_real_escape_string(
                $koneksi, $alamat
            );

            $fotoDB = mysqli_real_escape_string(
                $koneksi, $foto
            );

            if ($password != "") {

                // Sesuaikan mekanisme penyimpanan password
                // dengan proses login pasien yang digunakan.
                $passwordDB = mysqli_real_escape_string(
                    $koneksi, $password
                );

                $update = mysqli_query($koneksi, "
                    UPDATE pasien SET
                        namapasien = '$namapasienDB',
                        username = '$usernameDB',
                        password = '$passwordDB',
                        nohp = '$nohpDB',
                        alamat = '$alamatDB',
                        foto = '$fotoDB'
                    WHERE idpasien = '$idpasien'
                ");

            } else {

                $update = mysqli_query($koneksi, "
                    UPDATE pasien SET
                        namapasien = '$namapasienDB',
                        username = '$usernameDB',
                        nohp = '$nohpDB',
                        alamat = '$alamatDB',
                        foto = '$fotoDB'
                    WHERE idpasien = '$idpasien'
                ");
            }

            if ($update) {

                $_SESSION['namapasien'] = $namapasien;

                echo "
                    <script>
                        alert('Profil berhasil diperbarui!');
                        window.location='?page=profil';
                    </script>
                ";

                exit;

            } else {

                $pesan = "
                    <div class='alert alert-danger'>
                        Profil gagal diperbarui: " .
                        htmlspecialchars(mysqli_error($koneksi)) . "
                    </div>
                ";
            }
        }
    }
}
?>

<div class="container-fluid py-4">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Edit Profil</h4>
        <p class="text-muted mb-0">
            Perbarui informasi akun pasien.
        </p>
    </div>

    <?= $pesan; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form method="POST" enctype="multipart/form-data">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-semibold">
                            Foto Profil
                        </label>

                        <?php
                        $fotoSekarang = !empty($data['foto'])
                            ? "assets/images/pasien/" . $data['foto']
                            : "assets/images/pasien/default.png";
                        ?>

                        <div class="mb-3">
                            <img src="<?= htmlspecialchars($fotoSekarang); ?>"
                                 width="150"
                                 height="150"
                                 class="rounded-circle img-thumbnail"
                                 style="object-fit: cover;"
                                 alt="Foto Profil">
                        </div>

                        <input type="file"
                               name="foto"
                               class="form-control"
                               accept=".jpg,.jpeg,.png">

                        <small class="text-muted">
                            Format JPG, JPEG, PNG. Maksimal 2 MB.
                        </small>

                    </div>

                    <div class="col-md-8">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Nama Lengkap
                            </label>

                            <input type="text"
                                   name="namapasien"
                                   class="form-control"
                                   value="<?= htmlspecialchars($data['namapasien']); ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Username
                            </label>

                            <input type="text"
                                   name="username"
                                   class="form-control"
                                   value="<?= htmlspecialchars($data['username']); ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Nomor HP
                            </label>

                            <input type="text"
                                   name="nohp"
                                   class="form-control"
                                   value="<?= htmlspecialchars($data['nohp']); ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Alamat
                            </label>

                            <textarea name="alamat"
                                      class="form-control"
                                      rows="3"
                                      required><?= htmlspecialchars($data['alamat']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Password Baru
                            </label>

                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="Kosongkan jika tidak ingin mengubah password">

                            <small class="text-muted">
                                Isi hanya jika ingin mengganti password.
                            </small>
                        </div>

                    </div>

                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">

                    <a href="?page=profil"
                       class="btn btn-secondary">
                        Batal
                    </a>

                    <button type="submit"
                            name="simpan"
                            class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>