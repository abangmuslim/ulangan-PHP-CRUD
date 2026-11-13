
<?php
// ============================================
// PROSES CRUD JADWAL PRAKTIK DOKTER
// ZONA ADMIN / PETUGAS
// ============================================

// Hanya menerima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metode tidak diizinkan.');
}

// ============================================
// 1. VALIDASI HAK AKSES
// ============================================

$level = $_SESSION['level'] ?? '';

if (!in_array($level, ['admin', 'petugas'], true)) {
    http_response_code(403);
    exit('Anda tidak memiliki hak akses.');
}

// ============================================
// 2. VALIDASI CSRF
// ============================================

if (!csrfCheck()) {
    http_response_code(403);
    exit('Token keamanan tidak valid. Silakan ulangi proses.');
}

// ============================================
// 3. PENGATURAN PROSES
// ============================================

$aksi = $_POST['aksi'] ?? '';

// Fungsi pemberitahuan dan kembali ke daftar
$kembali = function ($pesan) {
    echo "<script>
        alert(" . json_encode($pesan) . ");
        window.location.href = 'index.php?page=jadwaldokter';
    </script>";
    exit;
};

// Daftar hari yang diperbolehkan
$daftarHari = [
    'Senin',
    'Selasa',
    'Rabu',
    'Kamis',
    'Jumat',
    'Sabtu',
    'Minggu'
];

// ============================================
// 4. PROSES TAMBAH BANYAK JADWAL
// ============================================

if ($aksi == 'tambah') {

    // Ambil ID dokter dari form
    $iddokter = (int) ($_POST['iddokter'] ?? 0);

    // Ambil data array dari form
    $hariArray = $_POST['haripraktik'] ?? [];
    $mulaiArray = $_POST['jam_mulai'] ?? [];
    $selesaiArray = $_POST['jam_selesai'] ?? [];

    // Validasi ID dokter
    if ($iddokter <= 0) {
        $kembali('Dokter belum dipilih.');
    }

    // Pastikan dokter tersedia
    $cekDokter = mysqli_prepare($koneksi, "
        SELECT iddokter
        FROM dokter
        WHERE iddokter = ?
    ");

    if (!$cekDokter) {
        $kembali('Gagal memeriksa data dokter.');
    }

    mysqli_stmt_bind_param($cekDokter, "i", $iddokter);
    mysqli_stmt_execute($cekDokter);
    mysqli_stmt_store_result($cekDokter);

    $adaDokter = mysqli_stmt_num_rows($cekDokter) > 0;

    mysqli_stmt_close($cekDokter);

    if (!$adaDokter) {
        $kembali('Dokter tidak ditemukan.');
    }

    // Pastikan semua input berbentuk array
    if (
        !is_array($hariArray) ||
        !is_array($mulaiArray) ||
        !is_array($selesaiArray)
    ) {
        $kembali('Format data jadwal tidak valid.');
    }

    // Pastikan jumlah data sama dan tidak kosong
    $jumlahJadwal = count($hariArray);

    if (
        $jumlahJadwal == 0 ||
        count($mulaiArray) != $jumlahJadwal ||
        count($selesaiArray) != $jumlahJadwal
    ) {
        $kembali('Data jadwal praktik belum lengkap.');
    }

    // ========================================
    // 4A. VALIDASI SELURUH DATA JADWAL
    // ========================================

    $daftarJadwal = [];

    for ($i = 0; $i < $jumlahJadwal; $i++) {

        $hari = trim($hariArray[$i] ?? '');
        $mulai = trim($mulaiArray[$i] ?? '');
        $selesai = trim($selesaiArray[$i] ?? '');

        // Validasi hari
        if (!in_array($hari, $daftarHari, true)) {
            $kembali('Hari praktik pada jadwal nomor ' . ($i + 1) . ' tidak valid.');
        }

        // Validasi format jam
        if (
            !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $mulai) ||
            !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $selesai)
        ) {
            $kembali('Format jam pada jadwal nomor ' . ($i + 1) . ' tidak valid.');
        }

        // Jam mulai harus lebih awal dari jam selesai
        if ($mulai >= $selesai) {
            $kembali('Jam selesai pada jadwal nomor ' . ($i + 1) . ' harus lebih besar dari jam mulai.');
        }

        // Masukkan data ke array sementara
        $daftarJadwal[] = [
            'hari' => $hari,
            'mulai' => $mulai,
            'selesai' => $selesai
        ];
    }

    // ========================================
    // 4B. MULAI TRANSAKSI DATABASE
    // ========================================

    mysqli_begin_transaction($koneksi);

    $berhasil = true;
    $pesanGagal = '';

    foreach ($daftarJadwal as $index => $jadwal) {

        $hari = $jadwal['hari'];
        $mulai = $jadwal['mulai'];
        $selesai = $jadwal['selesai'];

        // ====================================
        // CEK JADWAL BENTROK
        // ====================================

        $cekBentrok = mysqli_prepare($koneksi, "
            SELECT idjadwal
            FROM jadwaldokter
            WHERE iddokter = ?
            AND haripraktik = ?
            AND jam_mulai < ?
            AND jam_selesai > ?
            LIMIT 1
        ");

        if (!$cekBentrok) {
            $berhasil = false;
            $pesanGagal = 'Gagal memeriksa jadwal praktik.';
            break;
        }

        mysqli_stmt_bind_param(
            $cekBentrok,
            "isss",
            $iddokter,
            $hari,
            $selesai,
            $mulai
        );

        mysqli_stmt_execute($cekBentrok);
        mysqli_stmt_store_result($cekBentrok);

        $adaBentrok = mysqli_stmt_num_rows($cekBentrok) > 0;

        mysqli_stmt_close($cekBentrok);

        if ($adaBentrok) {
            $berhasil = false;
            $pesanGagal = 'Jadwal nomor ' . ($index + 1) . ' bentrok dengan jadwal praktik dokter tersebut.';
            break;
        }

        // ====================================
        // SIMPAN JADWAL
        // ====================================

        $query = mysqli_prepare($koneksi, "
            INSERT INTO jadwaldokter
            SET iddokter = ?,
                haripraktik = ?,
                jam_mulai = ?,
                jam_selesai = ?
        ");

        if (!$query) {
            $berhasil = false;
            $pesanGagal = 'Gagal menyiapkan penyimpanan jadwal.';
            break;
        }

        mysqli_stmt_bind_param(
            $query,
            "isss",
            $iddokter,
            $hari,
            $mulai,
            $selesai
        );

        if (!mysqli_stmt_execute($query)) {
            $berhasil = false;
            $pesanGagal = 'Jadwal nomor ' . ($index + 1) . ' gagal disimpan.';
            mysqli_stmt_close($query);
            break;
        }

        mysqli_stmt_close($query);
    }

    // ========================================
    // 4C. COMMIT ATAU ROLLBACK
    // ========================================

    if ($berhasil) {
        mysqli_commit($koneksi);
        $kembali($jumlahJadwal . ' jadwal praktik berhasil ditambahkan.');
    } else {
        mysqli_rollback($koneksi);
        $kembali($pesanGagal . ' Seluruh jadwal dibatalkan.');
    }
}

// ============================================
// 5. PROSES UBAH SATU JADWAL
// ============================================

elseif ($aksi == 'ubah') {

    // Ambil data form
    $idjadwal = (int) ($_POST['idjadwal'] ?? 0);
    $iddokter = (int) ($_POST['iddokter'] ?? 0);
    $haripraktik = trim($_POST['haripraktik'] ?? '');
    $jam_mulai = trim($_POST['jam_mulai'] ?? '');
    $jam_selesai = trim($_POST['jam_selesai'] ?? '');

    // Validasi ID
    if ($idjadwal <= 0 || $iddokter <= 0) {
        $kembali('ID jadwal atau dokter tidak valid.');
    }

    // Validasi dokter
    $cekDokter = mysqli_prepare($koneksi, "
        SELECT iddokter
        FROM dokter
        WHERE iddokter = ?
    ");

    if (!$cekDokter) {
        $kembali('Gagal memeriksa data dokter.');
    }

    mysqli_stmt_bind_param($cekDokter, "i", $iddokter);
    mysqli_stmt_execute($cekDokter);
    mysqli_stmt_store_result($cekDokter);

    $adaDokter = mysqli_stmt_num_rows($cekDokter) > 0;

    mysqli_stmt_close($cekDokter);

    if (!$adaDokter) {
        $kembali('Dokter tidak ditemukan.');
    }

    // Validasi hari
    if (!in_array($haripraktik, $daftarHari, true)) {
        $kembali('Hari praktik tidak valid.');
    }

    // Validasi format jam
    if (
        !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $jam_mulai) ||
        !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $jam_selesai)
    ) {
        $kembali('Format jam praktik tidak valid.');
    }

    // Jam mulai harus lebih awal dari jam selesai
    if ($jam_mulai >= $jam_selesai) {
        $kembali('Jam selesai harus lebih besar dari jam mulai.');
    }

    // ========================================
    // 5A. VALIDASI KEBERADAAN JADWAL
    // ========================================

    $cek = mysqli_prepare($koneksi, "
        SELECT idjadwal
        FROM jadwaldokter
        WHERE idjadwal = ?
    ");

    if (!$cek) {
        $kembali('Gagal memeriksa data jadwal.');
    }

    mysqli_stmt_bind_param($cek, "i", $idjadwal);
    mysqli_stmt_execute($cek);
    mysqli_stmt_store_result($cek);

    $adaJadwal = mysqli_stmt_num_rows($cek) > 0;

    mysqli_stmt_close($cek);

    if (!$adaJadwal) {
        $kembali('Jadwal tidak ditemukan.');
    }

    // ========================================
    // 5B. CEK JADWAL BENTROK
    // ========================================

    $cekBentrok = mysqli_prepare($koneksi, "
        SELECT idjadwal
        FROM jadwaldokter
        WHERE iddokter = ?
        AND haripraktik = ?
        AND idjadwal != ?
        AND jam_mulai < ?
        AND jam_selesai > ?
        LIMIT 1
    ");

    if (!$cekBentrok) {
        $kembali('Gagal memeriksa bentrok jadwal.');
    }

    mysqli_stmt_bind_param(
        $cekBentrok,
        "isiss",
        $iddokter,
        $haripraktik,
        $idjadwal,
        $jam_selesai,
        $jam_mulai
    );

    mysqli_stmt_execute($cekBentrok);
    mysqli_stmt_store_result($cekBentrok);

    $adaBentrok = mysqli_stmt_num_rows($cekBentrok) > 0;

    mysqli_stmt_close($cekBentrok);

    if ($adaBentrok) {
        $kembali('Jadwal bentrok dengan jadwal praktik dokter tersebut.');
    }

    // ========================================
    // 5C. SIMPAN PERUBAHAN JADWAL
    // ========================================

    $query = mysqli_prepare($koneksi, "
        UPDATE jadwaldokter
        SET iddokter = ?,
            haripraktik = ?,
            jam_mulai = ?,
            jam_selesai = ?
        WHERE idjadwal = ?
    ");

    if (!$query) {
        $kembali('Gagal menyiapkan perubahan jadwal.');
    }

    mysqli_stmt_bind_param(
        $query,
        "isssi",
        $iddokter,
        $haripraktik,
        $jam_mulai,
        $jam_selesai,
        $idjadwal
    );

    if (mysqli_stmt_execute($query)) {
        mysqli_stmt_close($query);
        $kembali('Jadwal praktik berhasil diperbarui.');
    }

    mysqli_stmt_close($query);
    $kembali('Jadwal praktik gagal diperbarui.');
}

// ============================================
// 6. PROSES HAPUS JADWAL
// ============================================

elseif ($aksi == 'hapus') {

    $idjadwal = (int) ($_POST['idjadwal'] ?? 0);

    if ($idjadwal <= 0) {
        $kembali('ID jadwal tidak valid.');
    }

    // ========================================
    // 6A. VALIDASI KEBERADAAN JADWAL
    // ========================================

    $cek = mysqli_prepare($koneksi, "
        SELECT idjadwal
        FROM jadwaldokter
        WHERE idjadwal = ?
    ");

    if (!$cek) {
        $kembali('Gagal memeriksa data jadwal.');
    }

    mysqli_stmt_bind_param($cek, "i", $idjadwal);
    mysqli_stmt_execute($cek);
    mysqli_stmt_store_result($cek);

    $adaJadwal = mysqli_stmt_num_rows($cek) > 0;

    mysqli_stmt_close($cek);

    if (!$adaJadwal) {
        $kembali('Jadwal tidak ditemukan.');
    }

    // ========================================
    // 6B. CEK PENDAFTARAN CHECKUP
    // ========================================

    $cekPendaftaran = mysqli_prepare($koneksi, "
        SELECT idjadwal
        FROM pendaftarancheckup
        WHERE idjadwal = ?
        LIMIT 1
    ");

    if (!$cekPendaftaran) {
        $kembali('Gagal memeriksa penggunaan jadwal pada pendaftaran.');
    }

    mysqli_stmt_bind_param($cekPendaftaran, "i", $idjadwal);
    mysqli_stmt_execute($cekPendaftaran);
    mysqli_stmt_store_result($cekPendaftaran);

    $adaPendaftaran = mysqli_stmt_num_rows($cekPendaftaran) > 0;

    mysqli_stmt_close($cekPendaftaran);

    if ($adaPendaftaran) {
        $kembali('Jadwal tidak dapat dihapus karena sudah digunakan dalam pendaftaran checkup pasien.');
    }

    // ========================================
    // 6C. CEK RIWAYAT KUNJUNGAN
    // ========================================

    $cekKunjungan = mysqli_prepare($koneksi, "
        SELECT idjadwal
        FROM kunjungan
        WHERE idjadwal = ?
        LIMIT 1
    ");

    if (!$cekKunjungan) {
        $kembali('Gagal memeriksa riwayat kunjungan.');
    }

    mysqli_stmt_bind_param($cekKunjungan, "i", $idjadwal);
    mysqli_stmt_execute($cekKunjungan);
    mysqli_stmt_store_result($cekKunjungan);

    $adaKunjungan = mysqli_stmt_num_rows($cekKunjungan) > 0;

    mysqli_stmt_close($cekKunjungan);

    if ($adaKunjungan) {
        $kembali('Jadwal tidak dapat dihapus karena sudah memiliki riwayat kunjungan pasien.');
    }

    // ========================================
    // 6D. HAPUS JADWAL
    // ========================================

    $query = mysqli_prepare($koneksi, "
        DELETE FROM jadwaldokter
        WHERE idjadwal = ?
    ");

    if (!$query) {
        $kembali('Gagal menyiapkan penghapusan jadwal.');
    }

    mysqli_stmt_bind_param($query, "i", $idjadwal);

    if (mysqli_stmt_execute($query)) {
        mysqli_stmt_close($query);
        $kembali('Jadwal praktik berhasil dihapus.');
    }

    mysqli_stmt_close($query);
    $kembali('Jadwal praktik gagal dihapus.');
}

// ============================================
// 7. AKSI TIDAK DIKENAL
// ============================================

else {
    http_response_code(400);
    exit('Aksi jadwal dokter tidak dikenal.');
}
