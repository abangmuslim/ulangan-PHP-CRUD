<?php
function userLogin() {
    return isset($_SESSION['iduser'], $_SESSION['role']);
}

function pasienLogin() {
    return isset($_SESSION['idpasien']);
}

function dokterLogin() {
    return isset($_SESSION['iddokter']);
}

function sudahLogin() {
    return userLogin() || pasienLogin() || dokterLogin();
}

function roleUser() {
    return $_SESSION['role'] ?? null;
}

function userAdmin() {
    return userLogin() && roleUser() === 'admin';
}

function userPetugas() {
    return userLogin() && roleUser() === 'petugas';
}

function cekLogin() {
    if (!sudahLogin()) {
        header("Location: index.php?page=loginuser");
        exit;
    }
}

function cekAdmin() {
    if (!userAdmin()) {
        header("Location: index.php?page=403");
        exit;
    }
}

function cekUser() {
    if (!userLogin()) {
        header("Location: index.php?page=loginuser");
        exit;
    }
}

function cekPasien() {
    if (!pasienLogin()) {
        header("Location: index.php?page=loginpasien");
        exit;
    }
}

function cekDokter() {
    if (!dokterLogin()) {
        header("Location: index.php?page=logindokter");
        exit;
    }
}
?>