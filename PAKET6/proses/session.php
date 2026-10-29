<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$batasWaktu = 3600;

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $batasWaktu) {
    $_SESSION = [];
    session_destroy();
    header("Location: index.php?page=loginuser&pesan=timeout");
    exit;
}

$_SESSION['last_activity'] = time();
?>