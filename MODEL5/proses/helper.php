<?php
// ========================================
// 1. HELPER OUTPUT DAN URL
// ========================================

function e($teks) {
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

function urlPage($page, $parameter = []) {
    $parameter['page'] = $page;
    return 'index.php?' . http_build_query($parameter);
}

function redirect($url) {
    header("Location: " . $url);
    exit;
}

// ========================================
// 2. HELPER SESSION DAN NOTIFIKASI
// ========================================

function setFlash($tipe, $pesan) {
    $_SESSION['flash'][$tipe] = $pesan;
}

function getFlash($tipe) {
    if (!isset($_SESSION['flash'][$tipe])) {
        return null;
    }

    $pesan = $_SESSION['flash'][$tipe];
    unset($_SESSION['flash'][$tipe]);

    return $pesan;
}

// ========================================
// 3. HELPER REQUEST DAN INPUT
// ========================================

function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function ambilPost($nama, $default = '') {
    if (!isset($_POST[$nama]) || !is_string($_POST[$nama])) {
        return $default;
    }

    return trim($_POST[$nama]);
}

function ambilGet($nama, $default = '') {
    if (!isset($_GET[$nama]) || !is_string($_GET[$nama])) {
        return $default;
    }

    return trim($_GET[$nama]);
}

// ========================================
// 4. HELPER FORMAT ANGKA, UANG, DAN TANGGAL
// ========================================

function formatRupiah($angka) {
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
}

function formatAngka($angka) {
    return number_format((float) $angka, 0, ',', '.');
}

function formatTanggal($tanggal) {
    if (empty($tanggal)) {
        return '-';
    }

    $waktu = strtotime($tanggal);

    if ($waktu === false) {
        return '-';
    }

    return date('d-m-Y', $waktu);
}

function formatTanggalWaktu($tanggal) {
    if (empty($tanggal)) {
        return '-';
    }

    $waktu = strtotime($tanggal);

    if ($waktu === false) {
        return '-';
    }

    return date('d-m-Y H:i', $waktu);
}

// ========================================
// 5. HELPER UPLOAD FOTO
// ========================================

function uploadFoto($file, $folder, $namaDasar = 'foto') {
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return [
            'success' => true,
            'filename' => null,
            'message' => 'Tidak ada foto yang diunggah.'
        ];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [
            'success' => false,
            'filename' => null,
            'message' => 'Upload foto gagal.'
        ];
    }

    if (!isset($file['tmp_name'], $file['size']) ||
        !is_uploaded_file($file['tmp_name'])) {
        return [
            'success' => false,
            'filename' => null,
            'message' => 'File upload tidak valid.'
        ];
    }

    // Maksimal ukuran foto 2 MB
    if ($file['size'] > 2 * 1024 * 1024) {
        return [
            'success' => false,
            'filename' => null,
            'message' => 'Ukuran foto maksimal 2 MB.'
        ];
    }

    $ekstensiDiizinkan = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'webp' => 'image/webp'
    ];

    $ekstensi = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));

    if (!isset($ekstensiDiizinkan[$ekstensi])) {
        return [
            'success' => false,
            'filename' => null,
            'message' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.'
        ];
    }

    $info = finfo_open(FILEINFO_MIME_TYPE);
    $mime = $info ? finfo_file($info, $file['tmp_name']) : false;

    if ($info) {
        finfo_close($info);
    }

    if ($mime !== $ekstensiDiizinkan[$ekstensi]) {
        return [
            'success' => false,
            'filename' => null,
            'message' => 'Isi file tidak sesuai dengan format foto.'
        ];
    }

    // Folder dihitung dari direktori utama proyek
    $folderTujuan = dirname(__DIR__) . '/' . trim($folder, '/\\');

    if (!is_dir($folderTujuan)) {
        return [
            'success' => false,
            'filename' => null,
            'message' => 'Folder penyimpanan foto tidak ditemukan.'
        ];
    }

    // Bersihkan nama dasar file
    $namaDasar = preg_replace('/[^a-zA-Z0-9_-]/', '_', $namaDasar);

    if ($namaDasar === '') {
        $namaDasar = 'foto';
    }

    $namaFile = $namaDasar . '_' . date('YmdHis') . '_' .
        bin2hex(random_bytes(4)) . '.' . $ekstensi;

    if (!move_uploaded_file($file['tmp_name'], $folderTujuan . '/' . $namaFile)) {
        return [
            'success' => false,
            'filename' => null,
            'message' => 'Foto gagal disimpan.'
        ];
    }

    return [
        'success' => true,
        'filename' => $namaFile,
        'message' => 'Foto berhasil diunggah.'
    ];
}

// ========================================
// 6. HELPER HAPUS FOTO
// ========================================

function hapusFoto($namaFile, $folder, $fotoDefault = 'default.png') {
    if (empty($namaFile) || basename($namaFile) !== $namaFile) {
        return false;
    }

    if ($namaFile === $fotoDefault) {
        return false;
    }

    $folderTujuan = dirname(__DIR__) . '/' . trim($folder, '/\\');
    $lokasiFoto = $folderTujuan . '/' . $namaFile;

    if (is_file($lokasiFoto)) {
        return unlink($lokasiFoto);
    }

    return false;
}
?>