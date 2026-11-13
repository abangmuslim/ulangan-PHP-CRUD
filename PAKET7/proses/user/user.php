
<?php
// ============================================
// PROSES CRUD USER
// ============================================

// Folder foto user
$folderFoto = dirname(__DIR__, 2) . '/assets/images/user/';

// ============================================
// FUNGSI REDIRECT DAN PESAN
// ============================================
function userRedirect($url)
{
    header("Location: $url");
    exit;
}

function userPesan($jenis, $pesan)
{
    $_SESSION[$jenis . '_user'] = $pesan;
}

// ============================================
// FUNGSI UPLOAD FOTO USER
// ============================================
function uploadFotoUser($namaInput)
{
    global $folderFoto;

    if (!isset($_FILES[$namaInput]) || $_FILES[$namaInput]['error'] == UPLOAD_ERR_NO_FILE) {
        return [
            'status' => true,
            'nama' => null,
            'pesan' => ''
        ];
    }

    $file = $_FILES[$namaInput];

    if ($file['error'] != UPLOAD_ERR_OK) {
        return [
            'status' => false,
            'nama' => null,
            'pesan' => 'Terjadi kesalahan saat mengunggah foto.'
        ];
    }

    // Maksimal 2 MB
    if ($file['size'] > 2 * 1024 * 1024) {
        return [
            'status' => false,
            'nama' => null,
            'pesan' => 'Ukuran foto maksimal 2 MB.'
        ];
    }

    // Validasi ekstensi
    $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $daftarEkstensi = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ekstensi, $daftarEkstensi)) {
        return [
            'status' => false,
            'nama' => null,
            'pesan' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.'
        ];
    }

    // Validasi MIME dan gambar
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $daftarMime = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'webp' => 'image/webp'
    ];

    $gambar = getimagesize($file['tmp_name']);

    if (
        !isset($daftarMime[$ekstensi]) ||
        $mime != $daftarMime[$ekstensi] ||
        !$gambar ||
        $gambar['mime'] != $daftarMime[$ekstensi]
    ) {
        return [
            'status' => false,
            'nama' => null,
            'pesan' => 'File yang diunggah bukan gambar yang valid.'
        ];
    }

    // Pastikan folder tersedia
    if (!is_dir($folderFoto)) {
        mkdir($folderFoto, 0755, true);
    }

    // Nama file dibuat unik
    $namaFoto = 'user_' . date('YmdHis') . '_' . bin2hex(random_bytes(5)) . '.' . $ekstensi;

    if (!move_uploaded_file($file['tmp_name'], $folderFoto . $namaFoto)) {
        return [
            'status' => false,
            'nama' => null,
            'pesan' => 'Foto gagal disimpan.'
        ];
    }

    return [
        'status' => true,
        'nama' => $namaFoto,
        'pesan' => ''
    ];
}

// ============================================
// FUNGSI HAPUS FOTO LAMA
// ============================================
function hapusFotoUser($namaFoto)
{
    global $folderFoto;

    if (
        !empty($namaFoto) &&
        $namaFoto != 'default.png' &&
        basename($namaFoto) == $namaFoto
    ) {
        $pathFoto = $folderFoto . $namaFoto;

        if (is_file($pathFoto)) {
            unlink($pathFoto);
        }
    }
}

// ============================================
// VALIDASI AKSES ADMIN
// ============================================
if (
    !isset($_SESSION['iduser']) ||
    ($_SESSION['level'] ?? '') != 'admin'
) {
    userPesan('error', 'Anda tidak memiliki hak akses untuk mengelola user.');
    userRedirect('index.php?page=home');
}

// Hanya menerima POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    userRedirect('index.php?page=user');
}

// Ambil aksi
$aksi = $_POST['aksi'] ?? '';

// ============================================
// 1. TAMBAH USER
// ============================================
if ($aksi == 'tambah') {

    $namauser = trim($_POST['namauser'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $nohp = trim($_POST['nohp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $level = $_POST['level'] ?? '';

    // Validasi input
    if (
        $namauser == '' ||
        $username == '' ||
        $password == '' ||
        !in_array($level, ['admin', 'petugas'])
    ) {
        userPesan('error', 'Lengkapi data user dengan benar.');
        userRedirect('index.php?page=createuser');
    }

    if (strlen($nohp) > 15) {
        userPesan('error', 'Nomor HP maksimal 15 karakter.');
        userRedirect('index.php?page=createuser');
    }

    // Periksa username
    $cek = mysqli_prepare($koneksi, "SELECT iduser FROM user WHERE username = ?");
    mysqli_stmt_bind_param($cek, "s", $username);
    mysqli_stmt_execute($cek);
    $hasil = mysqli_stmt_get_result($cek);

    if (mysqli_num_rows($hasil) > 0) {
        userPesan('error', 'Username sudah digunakan.');
        userRedirect('index.php?page=createuser');
    }

    // Upload foto
    $upload = uploadFotoUser('foto');

    if (!$upload['status']) {
        userPesan('error', $upload['pesan']);
        userRedirect('index.php?page=createuser');
    }

    $foto = $upload['nama'];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Simpan user
    $stmt = mysqli_prepare($koneksi, "
        INSERT INTO user
        (namauser, username, password, nohp, alamat, foto, level)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    mysqli_stmt_bind_param(
        $stmt,
        "sssssss",
        $namauser,
        $username,
        $passwordHash,
        $nohp,
        $alamat,
        $foto,
        $level
    );

    if (mysqli_stmt_execute($stmt)) {
        userPesan('success', 'User berhasil ditambahkan.');
    } else {
        // Hapus foto jika penyimpanan data gagal
        hapusFotoUser($foto);
        userPesan('error', 'User gagal ditambahkan. Periksa kembali data.');
    }

    userRedirect('index.php?page=user');
}

// ============================================
// 2. EDIT USER
// ============================================
elseif ($aksi == 'edit') {

    $iduser = (int) ($_POST['iduser'] ?? 0);
    $namauser = trim($_POST['namauser'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $nohp = trim($_POST['nohp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $level = $_POST['level'] ?? '';

    // Validasi data
    if (
        $iduser <= 0 ||
        $namauser == '' ||
        $username == '' ||
        !in_array($level, ['admin', 'petugas'])
    ) {
        userPesan('error', 'Data user tidak valid.');
        userRedirect('index.php?page=user');
    }

    if (strlen($nohp) > 15) {
        userPesan('error', 'Nomor HP maksimal 15 karakter.');
        userRedirect('index.php?page=edituser&id=' . $iduser);
    }

    // Ambil data user lama
    $cekUser = mysqli_prepare($koneksi, "SELECT * FROM user WHERE iduser = ?");
    mysqli_stmt_bind_param($cekUser, "i", $iduser);
    mysqli_stmt_execute($cekUser);
    $resultUser = mysqli_stmt_get_result($cekUser);
    $userLama = mysqli_fetch_assoc($resultUser);

    if (!$userLama) {
        userPesan('error', 'Data user tidak ditemukan.');
        userRedirect('index.php?page=user');
    }

    // Admin tidak boleh menurunkan level akunnya sendiri
    if (
        $iduser == $_SESSION['iduser'] &&
        $level != 'admin'
    ) {
        userPesan('error', 'Anda tidak dapat mengubah level akun sendiri menjadi petugas.');
        userRedirect('index.php?page=edituser&id=' . $iduser);
    }

    // Periksa username agar tidak sama dengan user lain
    $cek = mysqli_prepare($koneksi, "
        SELECT iduser FROM user
        WHERE username = ? AND iduser != ?
    ");

    mysqli_stmt_bind_param($cek, "si", $username, $iduser);
    mysqli_stmt_execute($cek);
    $hasil = mysqli_stmt_get_result($cek);

    if (mysqli_num_rows($hasil) > 0) {
        userPesan('error', 'Username sudah digunakan oleh user lain.');
        userRedirect('index.php?page=edituser&id=' . $iduser);
    }

    // Upload foto baru jika dipilih
    $upload = uploadFotoUser('foto');

    if (!$upload['status']) {
        userPesan('error', $upload['pesan']);
        userRedirect('index.php?page=edituser&id=' . $iduser);
    }

    $fotoLama = $userLama['foto'];
    $fotoBaru = $upload['nama'] ?? $fotoLama;

    // Jika password diisi, password ikut diperbarui
    if ($password != '') {

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($koneksi, "
            UPDATE user SET
                namauser = ?,
                username = ?,
                password = ?,
                nohp = ?,
                alamat = ?,
                foto = ?,
                level = ?
            WHERE iduser = ?
        ");

        mysqli_stmt_bind_param(
            $stmt,
            "sssssssi",
            $namauser,
            $username,
            $passwordHash,
            $nohp,
            $alamat,
            $fotoBaru,
            $level,
            $iduser
        );

    } else {

        $stmt = mysqli_prepare($koneksi, "
            UPDATE user SET
                namauser = ?,
                username = ?,
                nohp = ?,
                alamat = ?,
                foto = ?,
                level = ?
            WHERE iduser = ?
        ");

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssi",
            $namauser,
            $username,
            $nohp,
            $alamat,
            $fotoBaru,
            $level,
            $iduser
        );
    }

    if (mysqli_stmt_execute($stmt)) {

        // Hapus foto lama setelah update berhasil
        if ($upload['nama'] !== null) {
            hapusFotoUser($fotoLama);
        }

        userPesan('success', 'Data user berhasil diperbarui.');

        // Jika username sendiri berubah, session username ikut diperbarui
        if ($iduser == $_SESSION['iduser']) {
            $_SESSION['namauser'] = $namauser;
            $_SESSION['username'] = $username;
        }

    } else {

        // Hapus foto baru jika update gagal
        if ($upload['nama'] !== null) {
            hapusFotoUser($upload['nama']);
        }

        userPesan('error', 'Data user gagal diperbarui.');
    }

    userRedirect('index.php?page=user');
}

// ============================================
// 3. HAPUS USER
// ============================================
elseif ($aksi == 'hapus') {

    $iduser = (int) ($_POST['iduser'] ?? 0);

    if ($iduser <= 0) {
        userPesan('error', 'ID user tidak valid.');
        userRedirect('index.php?page=user');
    }

    // Cegah admin menghapus akunnya sendiri
    if ($iduser == $_SESSION['iduser']) {
        userPesan('error', 'Anda tidak dapat menghapus akun sendiri.');
        userRedirect('index.php?page=user');
    }

    // Ambil data user
    $cekUser = mysqli_prepare($koneksi, "
        SELECT iduser, foto, level FROM user WHERE iduser = ?
    ");

    mysqli_stmt_bind_param($cekUser, "i", $iduser);
    mysqli_stmt_execute($cekUser);
    $resultUser = mysqli_stmt_get_result($cekUser);
    $userLama = mysqli_fetch_assoc($resultUser);

    if (!$userLama) {
        userPesan('error', 'Data user tidak ditemukan.');
        userRedirect('index.php?page=user');
    }

    // Cegah penghapusan admin terakhir
    if ($userLama['level'] == 'admin') {

        $hitungAdmin = mysqli_query(
            $koneksi,
            "SELECT COUNT(*) AS total FROM user WHERE level = 'admin'"
        );

        $jumlahAdmin = mysqli_fetch_assoc($hitungAdmin)['total'];

        if ($jumlahAdmin <= 1) {
            userPesan('error', 'Admin terakhir tidak dapat dihapus.');
            userRedirect('index.php?page=user');
        }
    }

    // Hapus data user
    $stmt = mysqli_prepare($koneksi, "DELETE FROM user WHERE iduser = ?");
    mysqli_stmt_bind_param($stmt, "i", $iduser);

    if (mysqli_stmt_execute($stmt)) {

        // Hapus foto setelah data berhasil dihapus
        hapusFotoUser($userLama['foto']);

        userPesan('success', 'User berhasil dihapus.');

    } else {

        userPesan('error', 'User gagal dihapus. Data mungkin masih digunakan oleh tabel lain.');
    }

    userRedirect('index.php?page=user');
}

// ============================================
// AKSI TIDAK DIKENALI
// ============================================
else {
    userPesan('error', 'Aksi user tidak dikenali.');
    userRedirect('index.php?page=user');
}
