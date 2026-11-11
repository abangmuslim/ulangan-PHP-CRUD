
<?php
// ============================================
// PROSES LOGIN PASIEN
// ============================================

// Pastikan request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php?page=loginpasien');
}

// Validasi CSRF
csrfCheck();

// Ambil data form
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Validasi input
if ($username === '' || $password === '') {
    $_SESSION['error_login'] = 'Username dan password wajib diisi.';
    redirect('index.php?page=loginpasien');
}

// Cari akun pasien
$query = "SELECT idpasien, namapasien, username, password
          FROM pasien
          WHERE username = ?
          LIMIT 1";

$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    $_SESSION['error_login'] = 'Terjadi kesalahan pada sistem login.';
    redirect('index.php?page=loginpasien');
}

mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$pasien = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// Periksa akun dan password
if (!$pasien || !password_verify($password, $pasien['password'])) {
    $_SESSION['error_login'] = 'Username atau password salah.';
    redirect('index.php?page=loginpasien');
}

// Buat session baru
session_regenerate_id(true);

// Simpan identitas pasien
$_SESSION['idpasien'] = $pasien['idpasien'];
$_SESSION['namapasien'] = $pasien['namapasien'];
$_SESSION['username'] = $pasien['username'];

// Redirect ke dashboard pasien
redirect('index.php?page=dashboardpasien');
