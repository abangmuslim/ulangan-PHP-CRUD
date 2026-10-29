
<?php
include "proses/koneksi.php";

// Data 4 user yang akan dibuat
$users = [
    [
        "nama" => "Administrator",
        "username" => "admin",
        "password" => "admin123"
    ],
    [
        "nama" => "ahmadiuser",
        "username" => "ahmadiuser",
        "password" => "ahmadiuser"
    ],
    [
        "nama" => "Petugas Pendaftaran",
        "username" => "pendaftaran",
        "password" => "daftar123"
    ],
    [
        "nama" => "Petugas Apotek",
        "username" => "apotek",
        "password" => "apotek123"
    ],
    [
        "nama" => "Petugas Kasir",
        "username" => "kasir",
        "password" => "kasir123"
    ]
];

// Siapkan query INSERT
$stmt = mysqli_prepare(
    $koneksi,
    "INSERT INTO user (namauser, username, password)
     VALUES (?, ?, ?)"
);

if (!$stmt) {
    die("Gagal menyiapkan query: " . mysqli_error($koneksi));
}

// Simpan setiap user
$berhasil = 0;
$gagal = 0;

foreach ($users as $user) {
    $nama = $user["nama"];
    $username = $user["username"];
    $password = password_hash(
        $user["password"],
        PASSWORD_DEFAULT
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $nama,
        $username,
        $password
    );

    if (mysqli_stmt_execute($stmt)) {
        echo "User $username berhasil dibuat.<br>";
        $berhasil++;
    } else {
        echo "User $username gagal dibuat: "
            . mysqli_stmt_error($stmt) . "<br>";
        $gagal++;
    }
}

mysqli_stmt_close($stmt);

echo "<hr>";
echo "Total berhasil: $berhasil<br>";
echo "Total gagal: $gagal";
?>
