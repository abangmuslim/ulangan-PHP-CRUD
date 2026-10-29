<?php
// ==============================
// FUNGSI UMUM APLIKASI KLINIK
// ==============================

// Mencegah file diakses langsung
if (basename($_SERVER['SCRIPT_NAME'] ?? '') === 'helper.php') {
    http_response_code(403);
    exit('Akses langsung tidak diizinkan.');
}

// ==============================
// ESCAPE OUTPUT
// ==============================
function e($teks) {
    return htmlspecialchars(
        (string) $teks,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

// ==============================
// NAVIGASI MENU AKTIF
// ==============================

function menuAktif($halaman) {
    $pageAktif = $_GET['page'] ?? 'home';

    if (is_array($halaman)) {
        $aktif = in_array($pageAktif, $halaman, true);
    } else {
        $aktif = $pageAktif === $halaman;
    }

    return $aktif ? 'active bg-primary text-white' : '';
}


// ==============================
// REDIRECT
// ==============================
function redirect($url) {
    if (headers_sent()) {
        exit('Redirect gagal: header sudah dikirim.');
    }

    header('Location: ' . $url);
    exit;
}

// ==============================
// FORMAT RUPIAH
// ==============================
function rupiah($angka) {
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
}

// ==============================
// FORMAT TANGGAL INDONESIA
// ==============================
function tanggalIndo($tanggal) {
    if (empty($tanggal) || $tanggal === '0000-00-00') {
        return '-';
    }

    $waktu = strtotime($tanggal);

    if ($waktu === false) {
        return '-';
    }

    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April',
        'Mei', 'Juni', 'Juli', 'Agustus',
        'September', 'Oktober', 'November', 'Desember'
    ];

    return date('j', $waktu) . ' ' .
           $bulan[(int) date('n', $waktu)] . ' ' .
           date('Y', $waktu);
}

// ==============================
// FORMAT JAM
// ==============================
function formatJam($jam) {
    if (empty($jam)) {
        return '-';
    }

    $waktu = strtotime($jam);

    return $waktu === false ? '-' : date('H:i', $waktu);
}

// ==============================
// BADGE STATUS
// ==============================
function badgeStatus($status) {
    $warna = [
        'menunggu' => 'warning',
        'dipanggil' => 'info',
        'dilayani' => 'primary',
        'selesai' => 'success',
        'batal' => 'danger',
        'aktif' => 'success',
        'nonaktif' => 'secondary'
    ];

    $status = trim((string) $status);
    $kelas = $warna[strtolower($status)] ?? 'secondary';

    return '<span class="badge badge-' . $kelas . '">' .
           e(ucfirst($status)) .
           '</span>';
}

// ==============================
// FOTO DEFAULT
// ==============================
function fotoDefault($folder, $foto = '') {
    $folder = trim((string) $folder, '/\\');
    $namaFoto = trim((string) $foto);

    // Folder harus berupa satu nama folder
    if (
        $folder === '' ||
        basename($folder) !== $folder ||
        strpos($folder, '..') !== false
    ) {
        $folder = 'default';
    }

    // Nama foto hanya boleh berupa nama file
    if (
        $namaFoto === '' ||
        basename($namaFoto) !== $namaFoto ||
        strpos($namaFoto, '..') !== false
    ) {
        $namaFoto = 'default.png';
    }

    // Lokasi fisik file pada server
    $folderFoto = __DIR__ . '/../assets/images/' . $folder;
    $lokasiFoto = $folderFoto . '/' . $namaFoto;

    // Gunakan default.png jika foto tidak ditemukan
    if (!is_file($lokasiFoto)) {
        $namaFoto = 'default.png';
    }

    // URL relatif terhadap index.php di folder utama aplikasi
    return 'assets/images/' . rawurlencode($folder) . '/' .
           rawurlencode($namaFoto);
}

// ==============================
// TOKEN CSRF
// ==============================
function csrfToken() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        throw new RuntimeException(
            'Session harus aktif sebelum membuat token CSRF.'
        );
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// ==============================
// INPUT TOKEN CSRF
// ==============================
function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' .
           e(csrfToken()) . '">';
}

// ==============================
// VALIDASI TOKEN CSRF
// ==============================
function csrfValid() {
    if (
        session_status() !== PHP_SESSION_ACTIVE ||
        !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
        !is_string($_POST['csrf_token']) ||
        !is_string($_SESSION['csrf_token'])
    ) {
        return false;
    }

    return hash_equals(
        $_SESSION['csrf_token'],
        $_POST['csrf_token']
    );
}

// ==============================
// FLASH MESSAGE
// ==============================
function setFlash($jenis, $pesan) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return;
    }

    $_SESSION['flash'] = [
        'jenis' => $jenis,
        'pesan' => $pesan
    ];
}

function getFlash() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return null;
    }

    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

// ==============================
// TAMPILKAN NOTIFIKASI
// ==============================
function tampilFlash() {
    $flash = getFlash();

    if (!$flash) {
        return;
    }

    $jenisDiizinkan = ['success', 'danger', 'warning', 'info'];
    $jenis = in_array($flash['jenis'] ?? '', $jenisDiizinkan, true)
        ? $flash['jenis']
        : 'info';

    echo '<div class="alert alert-' . $jenis .
         ' alert-dismissible fade show" role="alert">';
    echo e($flash['pesan'] ?? '');
    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Tutup">';
    echo '<span aria-hidden="true">&times;</span>';
    echo '</button></div>';
}

// ==============================
// VALIDASI ID
// ==============================
function validId($id) {
    return filter_var($id, FILTER_VALIDATE_INT) !== false
        && (int) $id > 0;
}

// ==============================
// FORMAT NOMOR TELEPON
// ==============================
function formatTelepon($nomor) {
    $nomor = trim((string) $nomor);

    return $nomor !== '' ? e($nomor) : '-';
}