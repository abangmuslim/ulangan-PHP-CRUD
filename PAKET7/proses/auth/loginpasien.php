
<?php
// ============================================
// PROSES LOGIN PASIEN
// ============================================

// Hanya menerima request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=loginpasien');
    exit;
}

// Validasi CSRF
csrfCheck();

// Ambil input
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Validasi input kosong
if ($username === '' || $password === '') {
    $_SESSION['error_login'] = 'Username dan password wajib diisi.';
    header('Location: index.php?page=loginpasien');
    exit;
}

// Cari akun pasien berdasarkan username
$stmt = mysqli_prepare($koneksi, "
    SELECT
        idpasien,
        namapasien,
        username,
        password,
        foto
    FROM pasien
    WHERE username = ?
    LIMIT 1
");

if (!$stmt) {
    $_SESSION['error_login'] = 'Terjadi kesalahan sistem. Silakan coba kembali.';
    header('Location: index.php?page=loginpasien');
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$pasien = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// Verifikasi akun dan password
if (!$pasien || !password_verify($password, $pasien['password'])) {
    $_SESSION['error_login'] = 'Username atau password salah.';
    header('Location: index.php?page=loginpasien');
    exit;
}

// Regenerasi session untuk keamanan
session_regenerate_id(true);

// Bersihkan identitas role sebelumnya
unset(
    $_SESSION['iduser'],
    $_SESSION['namauser'],
    $_SESSION['level'],
    $_SESSION['iddokter'],
    $_SESSION['namadokter'],
    $_SESSION['idpasien'],
    $_SESSION['namapasien'],
    $_SESSION['username'],
    $_SESSION['foto']
);

// Simpan identitas pasien ke session
$_SESSION['idpasien'] = $pasien['idpasien'];
$_SESSION['namapasien'] = $pasien['namapasien'];
$_SESSION['username'] = $pasien['username'];
$_SESSION['foto'] = !empty($pasien['foto'])
    ? $pasien['foto']
    : 'default.png';

// Arahkan ke dashboard pasien
header('Location: index.php?page=dashboardpasien');
exit;
