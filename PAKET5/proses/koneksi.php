<?php
$koneksi = mysqli_connect("localhost", "root", "", "rumahsakitahmadi4");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
