
<?php
// ============================================
// PROSES CRUD OBAT
// ADMIN & PETUGAS
// ============================================

// Pastikan hanya POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php?page=obat");
    exit;
}

// Pastikan login sebagai Admin atau Petugas
if (
    !isset($_SESSION['iduser']) ||
    !in_array($_SESSION['level'] ?? '', ['admin', 'petugas'])
) {
    header("Location: index.php?page=loginuser");
    exit;
}

// Validasi CSRF
csrfCheck();

// Ambil data utama
$iduser = (int) $_SESSION['iduser'];
$aksi = $_POST['aksi'] ?? '';

// ============================================
// KONFIGURASI FOTO OBAT
// ============================================

$folderFoto = __DIR__ . '/../../assets/images/obat/';
$folderRelatif = 'assets/images/obat/';

if (!is_dir($folderFoto)) {
    mkdir($folderFoto, 0755, true);
}

// Fungsi upload foto
function uploadFotoObat($file, $folderFoto)
{
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return [
            'status' => true,
            'nama' => null
        ];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [
            'status' => false,
            'pesan' => 'Terjadi kesalahan saat mengunggah foto.'
        ];
    }

    // Maksimal 2 MB
    if ($file['size'] > 2 * 1024 * 1024) {
        return [
            'status' => false,
            'pesan' => 'Ukuran foto maksimal 2 MB.'
        ];
    }

    // Validasi MIME
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $daftarMime = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    if (!isset($daftarMime[$mime])) {
        return [
            'status' => false,
            'pesan' => 'Format foto harus JPG, PNG, atau WEBP.'
        ];
    }

    // Buat nama file unik
    $namaFile = 'obat_' . uniqid() . '.' . $daftarMime[$mime];

    if (!move_uploaded_file(
        $file['tmp_name'],
        $folderFoto . $namaFile
    )) {
        return [
            'status' => false,
            'pesan' => 'Foto gagal disimpan.'
        ];
    }

    return [
        'status' => true,
        'nama' => $namaFile
    ];
}

// Fungsi menghapus foto lama
function hapusFotoObat($namaFoto, $folderFoto)
{
    if (empty($namaFoto)) {
        return;
    }

    // Hanya izinkan nama file, bukan path sembarang
    $namaFoto = basename($namaFoto);

    if (
        $namaFoto !== 'default.png' &&
        file_exists($folderFoto . $namaFoto)
    ) {
        unlink($folderFoto . $namaFoto);
    }
}

// ============================================
// AKSI TAMBAH OBAT
// ============================================

if ($aksi === 'tambah') {

    $namaobat = trim($_POST['namaobat'] ?? '');
    $idmerk = (int) ($_POST['idmerk'] ?? 0);
    $idkategori = (int) ($_POST['idkategori'] ?? 0);
    $harga = (int) ($_POST['harga'] ?? 0);
    $stok = (int) ($_POST['stok'] ?? 0);
    $tanggalstok = $_POST['tanggalstok'] ?? '';

    // Validasi input
    if (
        $namaobat === '' ||
        $idmerk <= 0 ||
        $idkategori <= 0 ||
        $harga < 0 ||
        $stok < 0
    ) {
        header("Location: index.php?page=createobat&pesan=invalid");
        exit;
    }

    if (
        $tanggalstok !== '' &&
        !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalstok)
    ) {
        header("Location: index.php?page=createobat&pesan=tanggal");
        exit;
    }

    // Pastikan merk tersedia
    $cekMerk = mysqli_prepare(
        $koneksi,
        "SELECT idmerk FROM merk WHERE idmerk = ?"
    );
    mysqli_stmt_bind_param($cekMerk, "i", $idmerk);
    mysqli_stmt_execute($cekMerk);
    $hasilMerk = mysqli_stmt_get_result($cekMerk);
    $merkAda = mysqli_num_rows($hasilMerk) > 0;
    mysqli_stmt_close($cekMerk);

    // Pastikan kategori tersedia
    $cekKategori = mysqli_prepare(
        $koneksi,
        "SELECT idkategori FROM kategori WHERE idkategori = ?"
    );
    mysqli_stmt_bind_param($cekKategori, "i", $idkategori);
    mysqli_stmt_execute($cekKategori);
    $hasilKategori = mysqli_stmt_get_result($cekKategori);
    $kategoriAda = mysqli_num_rows($hasilKategori) > 0;
    mysqli_stmt_close($cekKategori);

    if (!$merkAda || !$kategoriAda) {
        header("Location: index.php?page=createobat&pesan=referensi");
        exit;
    }

    // Upload foto
    $upload = uploadFotoObat($_FILES['foto'] ?? null, $folderFoto);

    if (!$upload['status']) {
        header("Location: index.php?page=createobat&pesan=foto");
        exit;
    }

    $foto = $upload['nama'];

    // Mulai transaksi database
    mysqli_begin_transaction($koneksi);

    try {

        // Simpan data obat
        $query = mysqli_prepare(
            $koneksi,
            "INSERT INTO obat
            (idmerk, idkategori, namaobat, harga, tanggalstok, stok, foto)
            VALUES (?, ?, ?, ?, NULLIF(?, ''), ?, ?)"
        );

        mysqli_stmt_bind_param(
            $query,
            "iisssis",
            $idmerk,
            $idkategori,
            $namaobat,
            $harga,
            $tanggalstok,
            $stok,
            $foto
        );

        mysqli_stmt_execute($query);

        $idobat = mysqli_insert_id($koneksi);
        mysqli_stmt_close($query);

        // Catat stok awal sebagai mutasi masuk
        if ($stok > 0) {

            $keterangan = 'Stok awal obat';

            $mutasi = mysqli_prepare(
                $koneksi,
                "INSERT INTO mutasistok
                (idobat, iduser, jenismutasi, jumlah, keterangan)
                VALUES (?, ?, 'Masuk', ?, ?)"
            );

            mysqli_stmt_bind_param(
                $mutasi,
                "iiis",
                $idobat,
                $iduser,
                $stok,
                $keterangan
            );

            mysqli_stmt_execute($mutasi);
            mysqli_stmt_close($mutasi);
        }

        mysqli_commit($koneksi);

        header("Location: index.php?page=obat&pesan=tambah");
        exit;

    } catch (Throwable $e) {

        mysqli_rollback($koneksi);

        if (!empty($foto)) {
            hapusFotoObat($foto, $folderFoto);
        }

        error_log($e->getMessage());

        header("Location: index.php?page=createobat&pesan=gagal");
        exit;
    }
}

// ============================================
// AKSI UBAH OBAT
// STOK TIDAK BOLEH DIUBAH DI SINI
// ============================================

elseif ($aksi === 'ubah') {

    $idobat = (int) ($_POST['idobat'] ?? 0);
    $namaobat = trim($_POST['namaobat'] ?? '');
    $idmerk = (int) ($_POST['idmerk'] ?? 0);
    $idkategori = (int) ($_POST['idkategori'] ?? 0);
    $harga = (int) ($_POST['harga'] ?? 0);
    $tanggalstok = $_POST['tanggalstok'] ?? '';

    if (
        $idobat <= 0 ||
        $namaobat === '' ||
        $idmerk <= 0 ||
        $idkategori <= 0 ||
        $harga < 0
    ) {
        header("Location: index.php?page=obat&pesan=invalid");
        exit;
    }

    if (
        $tanggalstok !== '' &&
        !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalstok)
    ) {
        header("Location: index.php?page=editobat&idobat=$idobat&pesan=tanggal");
        exit;
    }

    // Ambil data obat lama
    $cekObat = mysqli_prepare(
        $koneksi,
        "SELECT foto FROM obat WHERE idobat = ?"
    );

    mysqli_stmt_bind_param($cekObat, "i", $idobat);
    mysqli_stmt_execute($cekObat);
    $hasilObat = mysqli_stmt_get_result($cekObat);
    $dataObat = mysqli_fetch_assoc($hasilObat);
    mysqli_stmt_close($cekObat);

    if (!$dataObat) {
        header("Location: index.php?page=obat&pesan=tidakada");
        exit;
    }

    // Pastikan merk tersedia
    $cekMerk = mysqli_prepare(
        $koneksi,
        "SELECT idmerk FROM merk WHERE idmerk = ?"
    );
    mysqli_stmt_bind_param($cekMerk, "i", $idmerk);
    mysqli_stmt_execute($cekMerk);
    $hasilMerk = mysqli_stmt_get_result($cekMerk);
    $merkAda = mysqli_num_rows($hasilMerk) > 0;
    mysqli_stmt_close($cekMerk);

    // Pastikan kategori tersedia
    $cekKategori = mysqli_prepare(
        $koneksi,
        "SELECT idkategori FROM kategori WHERE idkategori = ?"
    );
    mysqli_stmt_bind_param($cekKategori, "i", $idkategori);
    mysqli_stmt_execute($cekKategori);
    $hasilKategori = mysqli_stmt_get_result($cekKategori);
    $kategoriAda = mysqli_num_rows($hasilKategori) > 0;
    mysqli_stmt_close($cekKategori);

    if (!$merkAda || !$kategoriAda) {
        header("Location: index.php?page=editobat&idobat=$idobat&pesan=referensi");
        exit;
    }

    $fotoLama = $dataObat['foto'];
    $fotoBaru = null;

    // Upload foto baru jika dipilih
    $upload = uploadFotoObat($_FILES['foto'] ?? null, $folderFoto);

    if (!$upload['status']) {
        header("Location: index.php?page=editobat&idobat=$idobat&pesan=foto");
        exit;
    }

    $fotoBaru = $upload['nama'];

    // Jika tidak ada foto baru, pertahankan foto lama
    $foto = $fotoBaru ?? $fotoLama;

    mysqli_begin_transaction($koneksi);

    try {

        // Update data obat tanpa mengubah stok
        $query = mysqli_prepare(
            $koneksi,
            "UPDATE obat SET
                idmerk = ?,
                idkategori = ?,
                namaobat = ?,
                harga = ?,
                tanggalstok = NULLIF(?, ''),
                foto = ?
            WHERE idobat = ?"
        );

        mysqli_stmt_bind_param(
            $query,
            "iissssi",
            $idmerk,
            $idkategori,
            $namaobat,
            $harga,
            $tanggalstok,
            $foto,
            $idobat
        );

        mysqli_stmt_execute($query);
        mysqli_stmt_close($query);

        mysqli_commit($koneksi);

        // Hapus foto lama setelah update berhasil
        if (!empty($fotoBaru) && !empty($fotoLama)) {
            hapusFotoObat($fotoLama, $folderFoto);
        }

        header("Location: index.php?page=obat&pesan=ubah");
        exit;

    } catch (Throwable $e) {

        mysqli_rollback($koneksi);

        if (!empty($fotoBaru)) {
            hapusFotoObat($fotoBaru, $folderFoto);
        }

        error_log($e->getMessage());

        header("Location: index.php?page=editobat&idobat=$idobat&pesan=gagal");
        exit;
    }
}

// ============================================
// AKSI HAPUS OBAT
// ============================================

elseif ($aksi === 'hapus') {

    $idobat = (int) ($_POST['idobat'] ?? 0);

    if ($idobat <= 0) {
        header("Location: index.php?page=obat&pesan=invalid");
        exit;
    }

    // Ambil data obat
    $cekObat = mysqli_prepare(
        $koneksi,
        "SELECT foto FROM obat WHERE idobat = ?"
    );

    mysqli_stmt_bind_param($cekObat, "i", $idobat);
    mysqli_stmt_execute($cekObat);
    $hasilObat = mysqli_stmt_get_result($cekObat);
    $dataObat = mysqli_fetch_assoc($hasilObat);
    mysqli_stmt_close($cekObat);

    if (!$dataObat) {
        header("Location: index.php?page=obat&pesan=tidakada");
        exit;
    }

    // Periksa riwayat kunjungan
    $cekKunjungan = mysqli_prepare(
        $koneksi,
        "SELECT COUNT(*) AS total
         FROM detilkunjungan
         WHERE idobat = ?"
    );

    mysqli_stmt_bind_param($cekKunjungan, "i", $idobat);
    mysqli_stmt_execute($cekKunjungan);
    $kunjungan = mysqli_fetch_assoc(
        mysqli_stmt_get_result($cekKunjungan)
    );
    mysqli_stmt_close($cekKunjungan);

    // Periksa riwayat transaksi
    $cekTransaksi = mysqli_prepare(
        $koneksi,
        "SELECT COUNT(*) AS total
         FROM detiltransaksi
         WHERE idobat = ?"
    );

    mysqli_stmt_bind_param($cekTransaksi, "i", $idobat);
    mysqli_stmt_execute($cekTransaksi);
    $transaksi = mysqli_fetch_assoc(
        mysqli_stmt_get_result($cekTransaksi)
    );
    mysqli_stmt_close($cekTransaksi);

    // Periksa riwayat mutasi stok
    $cekMutasi = mysqli_prepare(
        $koneksi,
        "SELECT COUNT(*) AS total
         FROM mutasistok
         WHERE idobat = ?"
    );

    mysqli_stmt_bind_param($cekMutasi, "i", $idobat);
    mysqli_stmt_execute($cekMutasi);
    $mutasi = mysqli_fetch_assoc(
        mysqli_stmt_get_result($cekMutasi)
    );
    mysqli_stmt_close($cekMutasi);

    // Tolak penghapusan jika sudah memiliki riwayat
    if (
        $kunjungan['total'] > 0 ||
        $transaksi['total'] > 0 ||
        $mutasi['total'] > 0
    ) {
        header("Location: index.php?page=obat&pesan=terpakai");
        exit;
    }

    // Hapus data obat
    $hapus = mysqli_prepare(
        $koneksi,
        "DELETE FROM obat WHERE idobat = ?"
    );

    mysqli_stmt_bind_param($hapus, "i", $idobat);
    mysqli_stmt_execute($hapus);
    mysqli_stmt_close($hapus);

    // Hapus foto jika data berhasil dihapus
    hapusFotoObat($dataObat['foto'], $folderFoto);

    header("Location: index.php?page=obat&pesan=hapus");
    exit;
}

// ============================================
// AKSI TIDAK DIKENAL
// ============================================

else {
    header("Location: index.php?page=obat&pesan=aksi");
    exit;
}
