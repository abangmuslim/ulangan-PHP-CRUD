
<?php
// ============================================
// PROSES CRUD DOKTER
// ============================================

// Pastikan hanya menerima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php?page=dokter");
    exit;
}

// ============================================
// VALIDASI HAK AKSES
// ============================================
$level = $_SESSION['level'] ?? '';

if (!in_array($level, ['admin', 'petugas'])) {
    $_SESSION['error_dokter'] = "Anda tidak memiliki hak akses.";
    header("Location: index.php");
    exit;
}

// ============================================
// PENGATURAN PROSES
// ============================================
$aksi = $_POST['aksi'] ?? '';
$folderFoto = 'assets/images/dokter/';

// Redirect dan pesan
$redirect = function($jenis, $pesan) {
    $_SESSION[$jenis . '_dokter'] = $pesan;
    header("Location: index.php?page=dokter");
    exit;
};

// ============================================
// FUNGSI UPLOAD FOTO DOKTER
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
        $pesanUpload = "Folder penyimpanan foto dokter tidak ditemukan.";
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
    $namaFoto = 'dokter_' . bin2hex(random_bytes(8)) . '.' . $daftarMime[$mime];

    if (!move_uploaded_file($file['tmp_name'], $folderFoto . $namaFoto)) {
        $pesanUpload = "Foto gagal disimpan.";
        return false;
    }

    return $namaFoto;
};

// ============================================
// 1. TAMBAH DOKTER
// ============================================
if ($aksi == 'tambah') {

    $namadokter = trim($_POST['namadokter'] ?? '');
    $spesialis = trim($_POST['spesialis'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $nohp = trim($_POST['nohp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');

    // Validasi data wajib
    if ($namadokter == '' || $username == '' || $password == '') {
        $redirect('error', "Nama dokter, username, dan password wajib diisi.");
    }

    // Spesialis boleh kosong
    $spesialis = $spesialis != '' ? $spesialis : null;

    // Validasi username unik
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT iddokter FROM dokter WHERE username = ?"
    );

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $redirect('error', "Username sudah digunakan dokter lain.");
    }

    mysqli_stmt_close($stmt);

    // Enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Upload foto
    $foto = $uploadFoto();

    if ($foto === false) {
        $redirect('error', $pesanUpload);
    }

    // Simpan data dokter
    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO dokter
        (namadokter, spesialis, username, password, nohp, alamat, foto)
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssssss",
        $namadokter,
        $spesialis,
        $username,
        $password,
        $nohp,
        $alamat,
        $foto
    );

    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);

        $redirect('success', "Data dokter berhasil ditambahkan.");

    } else {

        // Hapus foto jika penyimpanan database gagal
        if ($foto && file_exists($folderFoto . $foto)) {
            unlink($folderFoto . $foto);
        }

        mysqli_stmt_close($stmt);

        $redirect('error', "Data dokter gagal ditambahkan.");
    }
}

// ============================================
// 2. EDIT DOKTER
// ============================================
elseif ($aksi == 'edit') {

    $iddokter = (int) ($_POST['iddokter'] ?? 0);
    $namadokter = trim($_POST['namadokter'] ?? '');
    $spesialis = trim($_POST['spesialis'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $passwordBaru = $_POST['password'] ?? '';
    $nohp = trim($_POST['nohp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');

    if ($iddokter <= 0 || $namadokter == '' || $username == '') {
        $redirect('error', "Data dokter tidak valid.");
    }

    // Spesialis boleh kosong
    $spesialis = $spesialis != '' ? $spesialis : null;

    // Ambil data dokter lama
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT * FROM dokter WHERE iddokter = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $iddokter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dokterLama = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$dokterLama) {
        $redirect('error', "Data dokter tidak ditemukan.");
    }

    // Periksa username milik dokter lain
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT iddokter FROM dokter
         WHERE username = ? AND iddokter != ?"
    );

    mysqli_stmt_bind_param($stmt, "si", $username, $iddokter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $redirect('error', "Username sudah digunakan dokter lain.");
    }

    mysqli_stmt_close($stmt);

    // Password baru atau pertahankan password lama
    if ($passwordBaru != '') {
        $password = password_hash($passwordBaru, PASSWORD_DEFAULT);
    } else {
        $password = $dokterLama['password'];
    }

    // Foto lama diambil dari database
    $fotoLama = $dokterLama['foto'];

    // Upload foto baru jika tersedia
    $fotoBaru = $uploadFoto();

    if ($fotoBaru === false) {
        $redirect('error', $pesanUpload);
    }

    $foto = $fotoBaru ?: $fotoLama;

    // Update data dokter
    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE dokter SET
            namadokter = ?,
            spesialis = ?,
            username = ?,
            password = ?,
            nohp = ?,
            alamat = ?,
            foto = ?
         WHERE iddokter = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssssssi",
        $namadokter,
        $spesialis,
        $username,
        $password,
        $nohp,
        $alamat,
        $foto,
        $iddokter
    );

    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);

        // Hapus foto lama setelah database berhasil diperbarui
        if ($fotoBaru && !empty($fotoLama) && file_exists($folderFoto . $fotoLama)) {
            unlink($folderFoto . $fotoLama);
        }

        $redirect('success', "Data dokter berhasil diperbarui.");

    } else {

        // Hapus foto baru jika update database gagal
        if ($fotoBaru && file_exists($folderFoto . $fotoBaru)) {
            unlink($folderFoto . $fotoBaru);
        }

        mysqli_stmt_close($stmt);

        $redirect('error', "Data dokter gagal diperbarui.");
    }
}

// ============================================
// 3. HAPUS DOKTER
// ============================================
elseif ($aksi == 'hapus') {

    $iddokter = (int) ($_POST['iddokter'] ?? 0);

    if ($iddokter <= 0) {
        $redirect('error', "ID dokter tidak valid.");
    }

    // Ambil data dokter
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT * FROM dokter WHERE iddokter = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $iddokter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dokter = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$dokter) {
        $redirect('error', "Data dokter tidak ditemukan.");
    }

    // Cek jadwal praktik dokter
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT COUNT(*) AS total
         FROM jadwaldokter
         WHERE iddokter = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $iddokter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $jadwal = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    // Cek riwayat kunjungan dokter
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT COUNT(*) AS total
         FROM kunjungan
         WHERE iddokter = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $iddokter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $kunjungan = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    // Dokter yang masih memiliki relasi tidak boleh dihapus
    if ($jadwal['total'] > 0 || $kunjungan['total'] > 0) {
        $redirect(
            'error',
            "Dokter tidak dapat dihapus karena masih memiliki jadwal praktik atau riwayat kunjungan."
        );
    }

    // Hapus data dokter
    $stmt = mysqli_prepare(
        $koneksi,
        "DELETE FROM dokter WHERE iddokter = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $iddokter);

    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);

        // Hapus foto setelah data berhasil dihapus
        if (!empty($dokter['foto']) && file_exists($folderFoto . $dokter['foto'])) {
            unlink($folderFoto . $dokter['foto']);
        }

        $redirect('success', "Data dokter berhasil dihapus.");

    } else {

        mysqli_stmt_close($stmt);

        $redirect('error', "Data dokter gagal dihapus.");
    }
}

// ============================================
// 4. AKSI TIDAK DIKENAL
// ============================================
else {
    $redirect('error', "Aksi pemrosesan tidak dikenal.");
}
