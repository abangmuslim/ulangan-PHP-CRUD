<?php
$koneksi = mysqli_connect("localhost", "root", "", "klinikahmadi7");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>