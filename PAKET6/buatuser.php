
<?php
include "proses/koneksi.php";

// ========================================
// DATA USER: 5 ADMIN DAN PETUGAS
// ========================================
$users = [
    [
        "namauser" => "Ahmadi Muslim",
        "username" => "ahmadiuser",
        "password" => "ahmadiuser",
        "nohp" => "081234567801",
        "alamat" => "Rumah Sakit Ahmadi"
    ],
    [
        "namauser" => "Administrator Utama",
        "username" => "admin",
        "password" => "admin123",
        "nohp" => "081234567801",
        "alamat" => "Rumah Sakit Ahmadi"
    ],
    [
        "namauser" => "Administrator Kedua",
        "username" => "admin2",
        "password" => "admin2123",
        "nohp" => "081234567802",
        "alamat" => "Rumah Sakit Ahmadi"
    ],
    [
        "namauser" => "Petugas Pendaftaran",
        "username" => "pendaftaran",
        "password" => "daftar123",
        "nohp" => "081234567803",
        "alamat" => "Rumah Sakit Ahmadi"
    ],
    [
        "namauser" => "Petugas Apotek",
        "username" => "apotek",
        "password" => "apotek123",
        "nohp" => "081234567804",
        "alamat" => "Rumah Sakit Ahmadi"
    ]
];

// ========================================
// DATA PASIEN: 2 PASIEN
// ========================================
$pasiens = [
    [
        "namapasien" => "Ahmad Fauzi",
        "username" => "pasien1",
        "password" => "pasien123",
        "nohp" => "081234567805",
        "alamat" => "Karang Baru, Aceh Tamiang"
    ],
    [
        "namapasien" => "Siti Aminah",
        "username" => "pasien2",
        "password" => "pasien2123",
        "nohp" => "081234567806",
        "alamat" => "Kuala Simpang, Aceh Tamiang"
    ]
];

// ========================================
// DATA DOKTER: 2 DOKTER
// ========================================
$dokters = [
    [
        "namadokter" => "Dr. Andi Saputra",
        "username" => "dokter1",
        "password" => "dokter123",
        "spesialis" => "Dokter Umum",
        "nohp" => "081234567807",
        "alamat" => "Karang Baru, Aceh Tamiang"
    ],
    [
        "namadokter" => "Dr. Rina Amelia",
        "username" => "dokter2",
        "password" => "dokter2123",
        "spesialis" => "Dokter Umum",
        "nohp" => "081234567808",
        "alamat" => "Kuala Simpang, Aceh Tamiang"
    ]
];

// ========================================
// FUNGSI MENAMPILKAN HASIL
// ========================================
function tampilHasil($jenis, $username, $berhasil, $pesan = '') {

    $warna = $berhasil ? 'green' : 'red';
    $status = $berhasil ? 'BERHASIL' : 'GAGAL';

    echo '<div style="padding:8px;margin:5px 0;border:1px solid #ddd;border-radius:5px;">';

    echo '<strong style="color:' . $warna . ';">' . $status . '</strong> - ';

    echo htmlspecialchars($jenis) . ': <strong>' .
        htmlspecialchars($username) . '</strong>';

    if ($pesan !== '') {
        echo '<br><small>' . htmlspecialchars($pesan) . '</small>';
    }

    echo '</div>';
}


// ========================================
// PROSES MEMBUAT USER
// ========================================
echo "<h3>Proses Pembuatan Akun User</h3>";

$sqlUser = "INSERT INTO `user`
    (namauser, username, password, nohp, alamat)
    VALUES (?, ?, ?, ?, ?)";

$stmtUser = mysqli_prepare($koneksi, $sqlUser);

if (!$stmtUser) {
    die("Gagal menyiapkan query user: " .
        htmlspecialchars(mysqli_error($koneksi)));
}

foreach ($users as $data) {

    $nama = $data['namauser'];
    $username = $data['username'];
    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
    $nohp = $data['nohp'];
    $alamat = $data['alamat'];

    mysqli_stmt_bind_param(
        $stmtUser,
        "sssss",
        $nama,
        $username,
        $passwordHash,
        $nohp,
        $alamat
    );

    if (mysqli_stmt_execute($stmtUser)) {

        tampilHasil('User', $username, true);

    } else {

        tampilHasil(
            'User',
            $username,
            false,
            mysqli_stmt_error($stmtUser)
        );
    }
}

mysqli_stmt_close($stmtUser);


// ========================================
// PROSES MEMBUAT PASIEN
// ========================================
echo "<h3>Proses Pembuatan Akun Pasien</h3>";

$sqlPasien = "INSERT INTO pasien
    (namapasien, username, password, nohp, alamat, foto)
    VALUES (?, ?, ?, ?, ?, ?)";

$stmtPasien = mysqli_prepare($koneksi, $sqlPasien);

if (!$stmtPasien) {
    die("Gagal menyiapkan query pasien: " .
        htmlspecialchars(mysqli_error($koneksi)));
}

foreach ($pasiens as $data) {

    $nama = $data['namapasien'];
    $username = $data['username'];
    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
    $nohp = $data['nohp'];
    $alamat = $data['alamat'];
    $foto = 'default.png';

    mysqli_stmt_bind_param(
        $stmtPasien,
        "ssssss",
        $nama,
        $username,
        $passwordHash,
        $nohp,
        $alamat,
        $foto
    );

    if (mysqli_stmt_execute($stmtPasien)) {

        tampilHasil('Pasien', $username, true);

    } else {

        tampilHasil(
            'Pasien',
            $username,
            false,
            mysqli_stmt_error($stmtPasien)
        );
    }
}

mysqli_stmt_close($stmtPasien);


// ========================================
// PROSES MEMBUAT DOKTER
// ========================================
echo "<h3>Proses Pembuatan Akun Dokter</h3>";

$sqlDokter = "INSERT INTO dokter
    (namadokter, username, password, spesialis, nohp, alamat, foto)
    VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmtDokter = mysqli_prepare($koneksi, $sqlDokter);

if (!$stmtDokter) {
    die("Gagal menyiapkan query dokter: " .
        htmlspecialchars(mysqli_error($koneksi)));
}

foreach ($dokters as $data) {

    $nama = $data['namadokter'];
    $username = $data['username'];
    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
    $spesialis = $data['spesialis'];
    $nohp = $data['nohp'];
    $alamat = $data['alamat'];
    $foto = 'default.png';

    mysqli_stmt_bind_param(
        $stmtDokter,
        "sssssss",
        $nama,
        $username,
        $passwordHash,
        $spesialis,
        $nohp,
        $alamat,
        $foto
    );

    if (mysqli_stmt_execute($stmtDokter)) {

        tampilHasil('Dokter', $username, true);

    } else {

        tampilHasil(
            'Dokter',
            $username,
            false,
            mysqli_stmt_error($stmtDokter)
        );
    }
}

mysqli_stmt_close($stmtDokter);


// ========================================
// HASIL AKHIR
// ========================================
echo "<hr>";

echo "<h4>Proses pembuatan akun selesai.</h4>";

echo "<p>Total akun yang dicoba: 9 akun.</p>";

echo "<ul>";
echo "<li>User: 5 akun</li>";
echo "<li>Pasien: 2 akun</li>";
echo "<li>Dokter: 2 akun</li>";
echo "</ul>";

echo "<p><strong>Penting:</strong> Hapus file buatuser.php setelah selesai digunakan.</p>";

mysqli_close($koneksi);
?>
