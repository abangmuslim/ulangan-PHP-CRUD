
<?php
// ============================================
// PROSES LOGIN USER - ADMIN DAN PETUGAS
// ============================================

// Pastikan request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php?page=loginuser');
}

// Validasi CSRF
csrfCheck();

// Ambil data form
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Validasi input
if ($username === '' || $password === '') {
    $_SESSION['error_login'] = 'Username dan password wajib diisi.';
    redirect('index.php?page=loginuser');
}

// Cari akun user
$query = "SELECT iduser, namauser, username, password, level
          FROM user
          WHERE username = ?
          LIMIT 1";

$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    $_SESSION['error_login'] = 'Terjadi kesalahan pada sistem login.';
    redirect('index.php?page=loginuser');
}

mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// Periksa akun dan password
if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['error_login'] = 'Username atau password salah.';
    redirect('index.php?page=loginuser');
}

// Periksa hak akses
$level = strtolower(trim($user['level']));

if (!in_array($level, ['admin', 'petugas'], true)) {
    $_SESSION['error_login'] = 'Level akun tidak memiliki akses.';
    redirect('index.php?page=loginuser');
}

// Buat session baru
session_regenerate_id(true);

// Simpan identitas user
$_SESSION['iduser'] = $user['iduser'];
$_SESSION['namauser'] = $user['namauser'];
$_SESSION['username'] = $user['username'];
$_SESSION['level'] = $level;

// Redirect sesuai level
if ($level === 'admin') {
    redirect('index.php?page=dashboardadmin');
} else {
    redirect('index.php?page=dashboardpetugas');
}
