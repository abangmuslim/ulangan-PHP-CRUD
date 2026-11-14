
<?php
// ============================================
// PROSES CRUD MERK OBAT
// ============================================

// Pastikan hanya menerima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>window.location='index.php?page=merk';</script>";
    exit;
}

// ============================================
// 1. VALIDASI SESSION DAN HAK AKSES
// ============================================

if (
    !isset($_SESSION['iduser']) ||
    !isset($_SESSION['level']) ||
    !in_array($_SESSION['level'], ['admin', 'petugas'])
) {
    echo "<script>window.location='index.php?page=403';</script>";
    exit;
}

// ============================================
// 2. VALIDASI CSRF
// ============================================

if (!csrfCheck()) {
    echo "<script>
        alert('Token keamanan tidak valid. Silakan ulangi proses.');
        window.location='index.php?page=merk';
    </script>";
    exit;
}

// ============================================
// 3. AMBIL DATA POST
// ============================================

$aksi = $_POST['aksi'] ?? '';

$iduser = intval($_SESSION['iduser']);

// ============================================
// 4. FUNGSI PESAN DAN REDIRECT
// ============================================

function pesanMerk($pesan, $halaman = 'merk')
{
    $pesan = json_encode($pesan);
    $halaman = json_encode($halaman);

    echo "<script>
        alert($pesan);
        window.location='index.php?page=' + $halaman;
    </script>";

    exit;
}

// ============================================
// 5. PROSES TAMBAH MERK
// ============================================

if ($aksi === 'tambah') {

    $namamerk = trim($_POST['namamerk'] ?? '');

    // Validasi input
    if ($namamerk === '') {
        pesanMerk('Nama merk tidak boleh kosong.');
    }

    if (strlen($namamerk) > 100) {
        pesanMerk('Nama merk maksimal 100 karakter.');
    }

    // Periksa duplikasi nama merk
    $cek = mysqli_prepare($koneksi, "
        SELECT idmerk
        FROM merk
        WHERE namamerk = ?
        LIMIT 1
    ");

    mysqli_stmt_bind_param($cek, "s", $namamerk);
    mysqli_stmt_execute($cek);

    $hasilcek = mysqli_stmt_get_result($cek);
    $jumlahcek = mysqli_num_rows($hasilcek);

    mysqli_stmt_close($cek);

    if ($jumlahcek > 0) {
        pesanMerk('Nama merk tersebut sudah terdaftar.');
    }

    // Simpan data merk
    $query = mysqli_prepare($koneksi, "
        INSERT INTO merk (namamerk)
        VALUES (?)
    ");

    mysqli_stmt_bind_param($query, "s", $namamerk);

    if (mysqli_stmt_execute($query)) {

        mysqli_stmt_close($query);

        pesanMerk('Data merk berhasil ditambahkan.');

    } else {

        mysqli_stmt_close($query);

        pesanMerk('Data merk gagal ditambahkan.');
    }
}

// ============================================
// 6. PROSES UBAH MERK
// ============================================

elseif ($aksi === 'ubah') {

    $idmerk = intval($_POST['idmerk'] ?? 0);
    $namamerk = trim($_POST['namamerk'] ?? '');

    // Validasi input
    if ($idmerk <= 0) {
        pesanMerk('ID merk tidak valid.');
    }

    if ($namamerk === '') {
        pesanMerk('Nama merk tidak boleh kosong.');
    }

    if (strlen($namamerk) > 100) {
        pesanMerk('Nama merk maksimal 100 karakter.');
    }

    // Periksa keberadaan merk
    $cek = mysqli_prepare($koneksi, "
        SELECT idmerk
        FROM merk
        WHERE idmerk = ?
        LIMIT 1
    ");

    mysqli_stmt_bind_param($cek, "i", $idmerk);
    mysqli_stmt_execute($cek);

    $hasilcek = mysqli_stmt_get_result($cek);
    $datacek = mysqli_fetch_assoc($hasilcek);

    mysqli_stmt_close($cek);

    if (!$datacek) {
        pesanMerk('Data merk tidak ditemukan.');
    }

    // Periksa duplikasi nama merk
    $cek = mysqli_prepare($koneksi, "
        SELECT idmerk
        FROM merk
        WHERE namamerk = ?
        AND idmerk != ?
        LIMIT 1
    ");

    mysqli_stmt_bind_param($cek, "si", $namamerk, $idmerk);
    mysqli_stmt_execute($cek);

    $hasilcek = mysqli_stmt_get_result($cek);
    $jumlahcek = mysqli_num_rows($hasilcek);

    mysqli_stmt_close($cek);

    if ($jumlahcek > 0) {
        pesanMerk('Nama merk tersebut sudah digunakan.');
    }

    // Update data merk
    $query = mysqli_prepare($koneksi, "
        UPDATE merk
        SET namamerk = ?
        WHERE idmerk = ?
    ");

    mysqli_stmt_bind_param($query, "si", $namamerk, $idmerk);

    if (mysqli_stmt_execute($query)) {

        mysqli_stmt_close($query);

        pesanMerk('Data merk berhasil diperbarui.');

    } else {

        mysqli_stmt_close($query);

        pesanMerk('Data merk gagal diperbarui.');
    }
}

// ============================================
// 7. PROSES HAPUS MERK
// ============================================

elseif ($aksi === 'hapus') {

    $idmerk = intval($_POST['idmerk'] ?? 0);

    if ($idmerk <= 0) {
        pesanMerk('ID merk tidak valid.');
    }

    // Periksa keberadaan merk
    $cek = mysqli_prepare($koneksi, "
        SELECT idmerk, namamerk
        FROM merk
        WHERE idmerk = ?
        LIMIT 1
    ");

    mysqli_stmt_bind_param($cek, "i", $idmerk);
    mysqli_stmt_execute($cek);

    $hasilcek = mysqli_stmt_get_result($cek);
    $datacek = mysqli_fetch_assoc($hasilcek);

    mysqli_stmt_close($cek);

    if (!$datacek) {
        pesanMerk('Data merk tidak ditemukan.');
    }

    // Periksa apakah merk masih digunakan obat
    $cek = mysqli_prepare($koneksi, "
        SELECT COUNT(*) AS jumlahobat
        FROM obat
        WHERE idmerk = ?
    ");

    mysqli_stmt_bind_param($cek, "i", $idmerk);
    mysqli_stmt_execute($cek);

    $hasilcek = mysqli_stmt_get_result($cek);
    $datacek = mysqli_fetch_assoc($hasilcek);

    mysqli_stmt_close($cek);

    $jumlahobat = intval($datacek['jumlahobat']);

    if ($jumlahobat > 0) {
        pesanMerk(
            'Merk tidak dapat dihapus karena masih digunakan oleh ' .
            $jumlahobat . ' data obat.'
        );
    }

    // Hapus data merk
    $query = mysqli_prepare($koneksi, "
        DELETE FROM merk
        WHERE idmerk = ?
    ");

    mysqli_stmt_bind_param($query, "i", $idmerk);

    if (mysqli_stmt_execute($query)) {

        mysqli_stmt_close($query);

        pesanMerk('Data merk berhasil dihapus.');

    } else {

        mysqli_stmt_close($query);

        pesanMerk('Data merk gagal dihapus.');
    }
}

// ============================================
// 8. AKSI TIDAK DIKENALI
// ============================================

else {

    pesanMerk('Aksi yang diminta tidak dikenali.');
}
?>
