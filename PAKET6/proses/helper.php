<?php
function redirect($url) {
    header("Location: " . $url);
    exit;
}

function aktif($page, $halaman) {
    if ($page == $halaman) {
        return "active";
    }
    return "";
}
?>
