
<?php
// ============================================
// PROSES LOGIN DOKTER
// ============================================

// Pastikan request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php?page=logindokter');
}

// Validasi CSRF
csrfCheck();

// Ambil data form
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Validasi input
if ($username === '' || $password === '') {
    $_SESSION['error_login'] = 'Username dan password wajib diisi.';
    redirect('index.php?page=logindokter');
}

// Cari akun dokter
$query = "SELECT iddokter, namadokter, username, password
          FROM dokter
          WHERE username = ?
          LIMIT 1";

$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    $_SESSION['error_login'] = 'Terjadi kesalahan pada sistem login.';
    redirect('index.php?page=logindokter');
}

mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$dokter = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// Periksa akun dan password
if (!$dokter || !password_verify($password, $dokter['password'])) {
    $_SESSION['error_login'] = 'Username atau password salah.';
    redirect('index.php?page=logindokter');
}

// Buat session baru
session_regenerate_id(true);

// Simpan identitas dokter
$_SESSION['iddokter'] = $dokter['iddokter'];
$_SESSION['namadokter'] = $dokter['namadokter'];
$_SESSION['username'] = $dokter['username'];

// Redirect ke dashboard dokter
redirect('index.php?page=dashboarddokter');
