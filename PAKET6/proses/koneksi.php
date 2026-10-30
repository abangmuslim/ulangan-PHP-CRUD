<?php
$koneksi = mysqli_connect("localhost", "root", "", "rumahsakitahmadi6");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
