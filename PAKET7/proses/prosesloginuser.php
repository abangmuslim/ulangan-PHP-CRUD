<?php
// Proses ini hanya boleh dipanggil melalui index.php
if (basename($_SERVER['SCRIPT_NAME']) !== 'index.php') {
    http_response_code(403);
    exit('Akses langsung tidak diizinkan.');
}

// Login hanya menerima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php?page=loginuser');
    exit;
}

// Validasi CSRF
if (!csrfValid()) {
    http_response_code(403);
    exit('Permintaan login tidak valid. Silakan muat ulang halaman.');
}

// Ambil data formulir
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    redirect('index.php?page=loginuser&status=kosong');
    exit;
}

// Cari akun berdasarkan username
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT iduser, namauser, username, password, role, foto
     FROM user
     WHERE username = ?
     LIMIT 1"
);

if (!$stmt) {
    error_log('Gagal menyiapkan query login user: ' . mysqli_error($koneksi));
    redirect('index.php?page=loginuser&status=gagal');
    exit;
}

mysqli_stmt_bind_param($stmt, 's', $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$dataUser = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Verifikasi akun dan password
if (
    !$dataUser ||
    !password_verify($password, $dataUser['password']) ||
    !in_array($dataUser['role'], ['admin', 'petugas'], true)
) {
    redirect('index.php?page=loginuser&status=gagal');
    exit;
}

// Buat ID sesi baru setelah login berhasil
if (!session_regenerate_id(true)) {
    error_log('Gagal memperbarui ID sesi login user.');
    http_response_code(500);
    exit('Login belum dapat diproses. Silakan coba kembali.');
}

// Simpan identitas akun ke sesi
$_SESSION['login'] = true;
$_SESSION['jenis_akun'] = 'user';
$_SESSION['iduser'] = (int) $dataUser['iduser'];
$_SESSION['namauser'] = $dataUser['namauser'];
$_SESSION['username'] = $dataUser['username'];
$_SESSION['role'] = $dataUser['role'];
$_SESSION['foto'] = $dataUser['foto'] ?? '';
$_SESSION['aktivitas_terakhir'] = time();

// Arahkan sesuai role
if ($dataUser['role'] === 'admin') {
    redirect('index.php?page=dashboardadmin');
} else {
    redirect('index.php?page=dashboardpetugas');
}
exit;