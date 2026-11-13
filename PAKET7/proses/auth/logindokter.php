
<?php
// PROSES LOGIN DOKTER

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect("index.php?page=logindokter");
}

// Validasi CSRF
csrfCheck();

// Ambil data dari form
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Validasi input kosong
if ($username == '' || $password == '') {
    redirect("index.php?page=logindokter&pesan=kosong");
}

// Cari akun dokter berdasarkan username
$query = mysqli_prepare($koneksi, "SELECT * FROM dokter WHERE username = ?");
mysqli_stmt_bind_param($query, "s", $username);
mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);
$dokter = mysqli_fetch_assoc($result);

mysqli_stmt_close($query);

// Periksa akun dan password
if ($dokter && password_verify($password, $dokter['password'])) {

    // Regenerasi session ID
    session_regenerate_id(true);

    // Bersihkan session identitas sebelumnya
    unset(
        $_SESSION['iduser'],
        $_SESSION['namauser'],
        $_SESSION['level'],
        $_SESSION['idpasien'],
        $_SESSION['namapasien']
    );

    // Simpan session dokter
    $_SESSION['iddokter'] = $dokter['iddokter'];
    $_SESSION['namadokter'] = $dokter['namadokter'];
    $_SESSION['username'] = $dokter['username'];
    $_SESSION['foto'] = $dokter['foto'] ?? 'default.png';

    // Arahkan ke dashboard dokter
    redirect("index.php?page=dashboarddokter");

} else {

    // Login gagal
    redirect("index.php?page=logindokter&pesan=gagal");
}
?>
