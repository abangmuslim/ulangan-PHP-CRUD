
<?php
// ============================================
// PROSES REGISTRASI PASIEN
// ============================================

// Pastikan request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php?page=registerpasien');
}

// Validasi CSRF
csrfCheck();

// Ambil data form
$namapasien = trim($_POST['namapasien'] ?? '');
$username = trim($_POST['username'] ?? '');
$nohp = trim($_POST['nohp'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$password = $_POST['password'] ?? '';
$konfirmasi = $_POST['konfirmasi_password'] ?? '';

// Validasi data wajib
if ($namapasien === '' || $username === '' || $password === '' || $konfirmasi === '') {
    $_SESSION['error_register'] = 'Nama, username, dan password wajib diisi.';
    redirect('index.php?page=registerpasien');
}

// Validasi panjang data
if (strlen($namapasien) > 100 || strlen($username) > 50 || strlen($nohp) > 15) {
    $_SESSION['error_register'] = 'Panjang data yang dimasukkan melebihi batas.';
    redirect('index.php?page=registerpasien');
}

// Validasi username
if (!preg_match('/^[a-zA-Z0-9_.-]+$/', $username)) {
    $_SESSION['error_register'] = 'Username hanya boleh berisi huruf, angka, titik, garis bawah, dan tanda hubung.';
    redirect('index.php?page=registerpasien');
}

// Validasi password
if (strlen($password) < 6) {
    $_SESSION['error_register'] = 'Password minimal 6 karakter.';
    redirect('index.php?page=registerpasien');
}

if ($password !== $konfirmasi) {
    $_SESSION['error_register'] = 'Konfirmasi password tidak sesuai.';
    redirect('index.php?page=registerpasien');
}

// Periksa username yang sudah digunakan
$queryCek = "SELECT idpasien FROM pasien WHERE username = ? LIMIT 1";
$stmtCek = mysqli_prepare($koneksi, $queryCek);

if (!$stmtCek) {
    $_SESSION['error_register'] = 'Terjadi kesalahan pada sistem registrasi.';
    redirect('index.php?page=registerpasien');
}

mysqli_stmt_bind_param($stmtCek, "s", $username);
mysqli_stmt_execute($stmtCek);

$resultCek = mysqli_stmt_get_result($stmtCek);
$cekUsername = mysqli_fetch_assoc($resultCek);

mysqli_stmt_close($stmtCek);

if ($cekUsername) {
    $_SESSION['error_register'] = 'Username sudah digunakan. Silakan gunakan username lain.';
    redirect('index.php?page=registerpasien');
}

// ============================================
// UPLOAD FOTO PASIEN
// ============================================

$foto = '';
$fotoBaru = '';

if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {

    // Periksa error upload
    if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error_register'] = 'Foto gagal diunggah.';
        redirect('index.php?page=registerpasien');
    }

    // Periksa ukuran maksimal 2 MB
    if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
        $_SESSION['error_register'] = 'Ukuran foto maksimal 2 MB.';
        redirect('index.php?page=registerpasien');
    }

    // Periksa MIME file
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES['foto']['tmp_name']);
    finfo_close($finfo);

    $daftarFoto = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    if (!isset($daftarFoto[$mime])) {
        $_SESSION['error_register'] = 'Format foto harus JPG, JPEG, PNG, atau WEBP.';
        redirect('index.php?page=registerpasien');
    }

    // Pastikan file benar-benar gambar
    if (getimagesize($_FILES['foto']['tmp_name']) === false) {
        $_SESSION['error_register'] = 'File yang diunggah bukan gambar yang valid.';
        redirect('index.php?page=registerpasien');
    }

    // Tentukan folder penyimpanan
    $folderFoto = __DIR__ . '/../../assets/images/pasien/';

    if (!is_dir($folderFoto)) {
        mkdir($folderFoto, 0775, true);
    }

    if (!is_dir($folderFoto) || !is_writable($folderFoto)) {
        $_SESSION['error_register'] = 'Folder penyimpanan foto tidak tersedia.';
        redirect('index.php?page=registerpasien');
    }

    // Buat nama file otomatis
    $fotoBaru = 'pasien_' . bin2hex(random_bytes(8)) . '.' . $daftarFoto[$mime];

    if (!move_uploaded_file($_FILES['foto']['tmp_name'], $folderFoto . $fotoBaru)) {
        $_SESSION['error_register'] = 'Foto gagal disimpan.';
        redirect('index.php?page=registerpasien');
    }

    $foto = $fotoBaru;
}

// ============================================
// SIMPAN DATA PASIEN
// ============================================

// Hash password
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Query insert pasien
$query = "INSERT INTO pasien
          (namapasien, username, password, nohp, alamat, foto)
          VALUES (?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    if ($fotoBaru !== '') {
        unlink($folderFoto . $fotoBaru);
    }

    $_SESSION['error_register'] = 'Data pasien gagal disimpan.';
    redirect('index.php?page=registerpasien');
}

mysqli_stmt_bind_param(
    $stmt,
    "ssssss",
    $namapasien,
    $username,
    $passwordHash,
    $nohp,
    $alamat,
    $foto
);

$berhasil = mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

// Periksa hasil penyimpanan
if (!$berhasil) {

    // Hapus foto jika penyimpanan database gagal
    if ($fotoBaru !== '') {
        unlink($folderFoto . $fotoBaru);
    }

    $_SESSION['error_register'] = 'Registrasi pasien gagal. Silakan coba kembali.';
    redirect('index.php?page=registerpasien');
}

// Registrasi berhasil
$_SESSION['success_register'] = 'Registrasi berhasil. Silakan login menggunakan akun Anda.';

redirect('index.php?page=loginpasien');
