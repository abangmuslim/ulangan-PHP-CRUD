
<?php
// ============================================
// PROSES CRUD KATEGORI OBAT
// ============================================

// Hanya menerima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php?page=kategori");
    exit;
}

// ============================================
// 1. CEK HAK AKSES
// ============================================

if (
    !isset($_SESSION['iduser']) ||
    !isset($_SESSION['level']) ||
    !in_array($_SESSION['level'], ['admin', 'petugas'])
) {
    echo "<script>
        alert('Anda tidak memiliki akses!');
        window.location='index.php';
    </script>";
    exit;
}

// ============================================
// 2. CEK CSRF TOKEN
// ============================================

csrfCheck();

// ============================================
// 3. FUNGSI NOTIFIKASI
// ============================================

if (!function_exists('pesanKategori')) {
    function pesanKategori($pesan, $halaman = 'kategori')
    {
        echo "<script>
            alert(" . json_encode($pesan) . ");
            window.location='index.php?page=" . $halaman . "';
        </script>";
        exit;
    }
}

// ============================================
// 4. AMBIL AKSI
// ============================================

$aksi = $_POST['aksi'] ?? '';


// ============================================
// 5. TAMBAH KATEGORI
// ============================================

if ($aksi == 'tambah') {

    $namakategori = trim($_POST['namakategori'] ?? '');

    // Validasi nama kategori
    if ($namakategori == '') {
        pesanKategori('Nama kategori tidak boleh kosong!');
    }

    if (strlen($namakategori) > 100) {
        pesanKategori('Nama kategori maksimal 100 karakter!');
    }

    // Cek nama kategori yang sama
    $stmt = mysqli_prepare($koneksi, "
        SELECT idkategori
        FROM kategori
        WHERE namakategori = ?
    ");

    mysqli_stmt_bind_param($stmt, "s", $namakategori);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        pesanKategori('Nama kategori sudah terdaftar!');
    }

    mysqli_stmt_close($stmt);

    // Simpan kategori
    $stmt = mysqli_prepare($koneksi, "
        INSERT INTO kategori SET
        namakategori = ?
    ");

    mysqli_stmt_bind_param($stmt, "s", $namakategori);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        pesanKategori('Kategori obat berhasil ditambahkan!');
    } else {
        mysqli_stmt_close($stmt);
        pesanKategori('Kategori obat gagal ditambahkan!');
    }
}


// ============================================
// 6. UBAH KATEGORI
// ============================================

elseif ($aksi == 'ubah') {

    $idkategori = intval($_POST['idkategori'] ?? 0);
    $namakategori = trim($_POST['namakategori'] ?? '');

    // Validasi ID
    if ($idkategori <= 0) {
        pesanKategori('ID kategori tidak valid!');
    }

    // Validasi nama kategori
    if ($namakategori == '') {
        pesanKategori('Nama kategori tidak boleh kosong!');
    }

    if (strlen($namakategori) > 100) {
        pesanKategori('Nama kategori maksimal 100 karakter!');
    }

    // Cek apakah kategori tersedia
    $stmt = mysqli_prepare($koneksi, "
        SELECT idkategori
        FROM kategori
        WHERE idkategori = ?
    ");

    mysqli_stmt_bind_param($stmt, "i", $idkategori);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        mysqli_stmt_close($stmt);
        pesanKategori('Data kategori tidak ditemukan!');
    }

    mysqli_stmt_close($stmt);

    // Cek nama kategori duplikat
    $stmt = mysqli_prepare($koneksi, "
        SELECT idkategori
        FROM kategori
        WHERE namakategori = ?
        AND idkategori != ?
    ");

    mysqli_stmt_bind_param($stmt, "si", $namakategori, $idkategori);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        mysqli_stmt_close($stmt);
        pesanKategori('Nama kategori sudah digunakan!');
    }

    mysqli_stmt_close($stmt);

    // Update kategori
    $stmt = mysqli_prepare($koneksi, "
        UPDATE kategori SET
        namakategori = ?
        WHERE idkategori = ?
    ");

    mysqli_stmt_bind_param($stmt, "si", $namakategori, $idkategori);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        pesanKategori('Kategori obat berhasil diperbarui!');
    } else {
        mysqli_stmt_close($stmt);
        pesanKategori('Kategori obat gagal diperbarui!');
    }
}


// ============================================
// 7. HAPUS KATEGORI
// ============================================

elseif ($aksi == 'hapus') {

    $idkategori = intval($_POST['idkategori'] ?? 0);

    // Validasi ID
    if ($idkategori <= 0) {
        pesanKategori('ID kategori tidak valid!');
    }

    // Cek apakah kategori tersedia
    $stmt = mysqli_prepare($koneksi, "
        SELECT idkategori
        FROM kategori
        WHERE idkategori = ?
    ");

    mysqli_stmt_bind_param($stmt, "i", $idkategori);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        mysqli_stmt_close($stmt);
        pesanKategori('Data kategori tidak ditemukan!');
    }

    mysqli_stmt_close($stmt);

    // Cek apakah kategori digunakan oleh data obat
    $stmt = mysqli_prepare($koneksi, "
        SELECT COUNT(idobat) AS jumlahobat
        FROM obat
        WHERE idkategori = ?
    ");

    mysqli_stmt_bind_param($stmt, "i", $idkategori);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    // Kategori tidak boleh dihapus jika masih digunakan
    if ($data['jumlahobat'] > 0) {
        pesanKategori(
            'Kategori tidak dapat dihapus karena masih digunakan oleh ' .
            $data['jumlahobat'] . ' data obat!'
        );
    }

    // Hapus kategori
    $stmt = mysqli_prepare($koneksi, "
        DELETE FROM kategori
        WHERE idkategori = ?
    ");

    mysqli_stmt_bind_param($stmt, "i", $idkategori);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        pesanKategori('Kategori obat berhasil dihapus!');
    } else {
        mysqli_stmt_close($stmt);
        pesanKategori('Kategori obat gagal dihapus!');
    }
}


// ============================================
// 8. AKSI TIDAK DIKENALI
// ============================================

else {
    pesanKategori('Aksi tidak dikenali!');
}
