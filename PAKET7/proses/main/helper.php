
<?php
// ============================================
// 1. FUNGSI REDIRECT
// ============================================
function redirect($url)
{
    header("Location: " . $url);
    exit;
}

// ============================================
// 2. FUNGSI MENU AKTIF
// Mendukung satu halaman atau beberapa halaman
// ============================================
function menuAktif($halaman)
{
    global $page;

    if (is_array($halaman)) {
        return in_array($page, $halaman, true) ? 'active' : '';
    }

    return ($page === $halaman) ? 'active' : '';
}

// ============================================
// 3. FUNGSI AKTIF LAMA
// Tetap dipertahankan agar kompatibel
// ============================================
function aktif($page, $halaman)
{
    if ($page == $halaman) {
        return "active";
    }

    return "";
}

// ============================================
// 4. FUNGSI ESCAPE HTML
// ============================================
function e($nilai)
{
    return htmlspecialchars(
        (string) $nilai,
        ENT_QUOTES,
        'UTF-8'
    );
}

// ============================================
// 5. TOKEN CSRF
// ============================================
function csrfToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// ============================================
// 6. FIELD CSRF
// ============================================
function csrfField()
{
    return '<input type="hidden" name="csrf_token" value="' .
        e(csrfToken()) .
        '">';
}

// ============================================
// 7. VALIDASI CSRF
// ============================================
function csrfCheck()
{
    if (
        !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        die('Token CSRF tidak valid.');
    }
}
