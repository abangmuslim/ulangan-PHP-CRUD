
<?php
// ============================================
// PROSES CRUD PASIEN
// ============================================

// Pastikan hanya menerima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php?page=pasien");
    exit;
}

// ============================================
// VALIDASI HAK AKSES
// ============================================
$level = $_SESSION['level'] ?? '';

if (!in_array($level, ['admin', 'petugas'])) {
    $_SESSION['error_pasien'] = "Anda tidak memiliki hak akses.";
    header("Location: index.php");
    exit;
}

// ============================================
// PENGATURAN PROSES
// ============================================
$aksi = $_POST['aksi'] ?? '';

$folderFoto = 'assets/images/pasien/';

// Redirect dan pesan
$redirect = function($jenis, $pesan) {
    $_SESSION[$jenis . '_pasien'] = $pesan;
    header("Location: index.php?page=pasien");
    exit;
};

// ============================================
// FUNGSI UPLOAD FOTO PASIEN
// ============================================
$pesanUpload = '';

$uploadFoto = function() use ($folderFoto, &$pesanUpload) {

    // Tidak ada foto yang diupload
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    $file = $_FILES['foto'];

    // Validasi upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $pesanUpload = "Upload foto gagal.";
        return false;
    }

    // Maksimal 2 MB
    if ($file['size'] > 2 * 1024 * 1024) {
        $pesanUpload = "Ukuran foto maksimal 2 MB.";
        return false;
    }

    // Pastikan folder tersedia
    if (!is_dir($folderFoto)) {
        $pesanUpload = "Folder penyimpanan foto pasien tidak ditemukan.";
        return false;
    }

    // Validasi MIME dan gambar
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $daftarMime = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    if (!isset($daftarMime[$mime]) || getimagesize($file['tmp_name']) === false) {
        $pesanUpload = "Format foto harus JPG, JPEG, PNG, atau WEBP.";
        return false;
    }

    // Nama file dibuat unik
    $namaFoto = 'pasien_' . bin2hex(random_bytes(8)) . '.' . $daftarMime[$mime];

    if (!move_uploaded_file($file['tmp_name'], $folderFoto . $namaFoto)) {
        $pesanUpload = "Foto gagal disimpan.";
        return false;
    }

    return $namaFoto;
};

// ============================================
// 1. TAMBAH PASIEN
// ============================================
if ($aksi == 'tambah') {

    $namapasien = trim($_POST['namapasien'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $nohp = trim($_POST['nohp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');

    // Validasi nama
    if ($namapasien == '') {
        $redirect('error', "Nama pasien wajib diisi.");
    }

    // Validasi pasangan username dan password
    if ($username == '' && $password != '') {
        $redirect('error', "Username harus diisi jika menggunakan password.");
    }

    if ($username != '' && $password == '') {
        $redirect('error', "Password wajib diisi jika pasien dibuatkan akun.");
    }

    // Validasi username unik
    if ($username != '') {

        $stmt = mysqli_prepare($koneksi, "SELECT idpasien FROM pasien WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $redirect('error', "Username sudah digunakan pasien lain.");
        }

        mysqli_stmt_close($stmt);

        $password = password_hash($password, PASSWORD_DEFAULT);

    } else {
        $username = null;
        $password = null;
    }

    // Upload foto
    $foto = $uploadFoto();

    if ($foto === false) {
        $redirect('error', $pesanUpload);
    }

    // Simpan data pasien
    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO pasien
        (namapasien, username, password, nohp, alamat, foto)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssssss",
        $namapasien,
        $username,
        $password,
        $nohp,
        $alamat,
        $foto
    );

    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);

        $redirect('success', "Data pasien berhasil ditambahkan.");

    } else {

        // Hapus foto jika penyimpanan database gagal
        if ($foto && file_exists($folderFoto . $foto)) {
            unlink($folderFoto . $foto);
        }

        mysqli_stmt_close($stmt);

        $redirect('error', "Data pasien gagal ditambahkan.");
    }
}

// ============================================
// 2. EDIT PASIEN
// ============================================
elseif ($aksi == 'edit') {

    $idpasien = (int) ($_POST['idpasien'] ?? 0);
    $namapasien = trim($_POST['namapasien'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $passwordBaru = $_POST['password'] ?? '';
    $nohp = trim($_POST['nohp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');

    if ($idpasien <= 0 || $namapasien == '') {
        $redirect('error', "Data pasien tidak valid.");
    }

    // Ambil data pasien lama dari database
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM pasien WHERE idpasien = ?");
    mysqli_stmt_bind_param($stmt, "i", $idpasien);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $pasienLama = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$pasienLama) {
        $redirect('error', "Data pasien tidak ditemukan.");
    }

    // Validasi username dan password
    if ($username == '' && $passwordBaru != '') {
        $redirect('error', "Username harus diisi jika menggunakan password.");
    }

    if ($username != '') {

        // Periksa username milik pasien lain
        $stmt = mysqli_prepare(
            $koneksi,
            "SELECT idpasien FROM pasien WHERE username = ? AND idpasien != ?"
        );

        mysqli_stmt_bind_param($stmt, "si", $username, $idpasien);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $redirect('error', "Username sudah digunakan pasien lain.");
        }

        mysqli_stmt_close($stmt);

        if ($passwordBaru != '') {

            $password = password_hash($passwordBaru, PASSWORD_DEFAULT);

        } else {

            // Jika pasien sudah memiliki akun, pertahankan password lama
            if (empty($pasienLama['username'])) {
                $redirect('error', "Password wajib diisi untuk membuat akun pasien.");
            }

            $password = $pasienLama['password'];
        }

    } else {

        // Mengosongkan username berarti menghapus akses akun pasien
        $username = null;
        $password = null;
    }

    // Foto lama diambil dari database, bukan dari input hidden
    $fotoLama = $pasienLama['foto'];

    // Upload foto baru jika tersedia
    $fotoBaru = $uploadFoto();

    if ($fotoBaru === false) {
        $redirect('error', $pesanUpload);
    }

    $foto = $fotoBaru ?: $fotoLama;

    // Update data pasien
    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE pasien SET
            namapasien = ?,
            username = ?,
            password = ?,
            nohp = ?,
            alamat = ?,
            foto = ?
        WHERE idpasien = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssi",
        $namapasien,
        $username,
        $password,
        $nohp,
        $alamat,
        $foto,
        $idpasien
    );

    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);

        // Hapus foto lama setelah database berhasil diperbarui
        if ($fotoBaru && !empty($fotoLama) && file_exists($folderFoto . $fotoLama)) {
            unlink($folderFoto . $fotoLama);
        }

        $redirect('success', "Data pasien berhasil diperbarui.");

    } else {

        // Hapus foto baru jika update database gagal
        if ($fotoBaru && file_exists($folderFoto . $fotoBaru)) {
            unlink($folderFoto . $fotoBaru);
        }

        mysqli_stmt_close($stmt);

        $redirect('error', "Data pasien gagal diperbarui.");
    }
}

// ============================================
// 3. HAPUS PASIEN
// ============================================
elseif ($aksi == 'hapus') {

    $idpasien = (int) ($_POST['idpasien'] ?? 0);

    if ($idpasien <= 0) {
        $redirect('error', "ID pasien tidak valid.");
    }

    // Ambil data pasien
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM pasien WHERE idpasien = ?");
    mysqli_stmt_bind_param($stmt, "i", $idpasien);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $pasien = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$pasien) {
        $redirect('error', "Data pasien tidak ditemukan.");
    }

    // Cek relasi pendaftaran checkup
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT COUNT(*) AS total FROM pendaftarancheckup WHERE idpasien = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $idpasien);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $pendaftaran = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    // Cek relasi kunjungan
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT COUNT(*) AS total FROM kunjungan WHERE idpasien = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $idpasien);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $kunjungan = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    // Pasien yang memiliki riwayat tidak boleh dihapus
    if ($pendaftaran['total'] > 0 || $kunjungan['total'] > 0) {
        $redirect(
            'error',
            "Pasien tidak dapat dihapus karena sudah memiliki riwayat pendaftaran atau kunjungan."
        );
    }

    // Hapus data pasien
    $stmt = mysqli_prepare($koneksi, "DELETE FROM pasien WHERE idpasien = ?");
    mysqli_stmt_bind_param($stmt, "i", $idpasien);

    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);

        // Hapus foto pasien setelah data berhasil dihapus
        if (!empty($pasien['foto']) && file_exists($folderFoto . $pasien['foto'])) {
            unlink($folderFoto . $pasien['foto']);
        }

        $redirect('success', "Data pasien berhasil dihapus.");

    } else {

        mysqli_stmt_close($stmt);

        $redirect('error', "Data pasien gagal dihapus.");
    }
}

// ============================================
// 4. AKSI TIDAK DIKENAL
// ============================================
else {
    $redirect('error', "Aksi pemrosesan tidak dikenal.");
}
