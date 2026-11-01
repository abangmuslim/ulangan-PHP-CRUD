
<?php
require_once __DIR__ . "/koneksi.php";
session_start();

$aksi = $_GET['aksi'];


// ========================================
// TAMBAH JADWAL DOKTER
// ========================================
if ($aksi == "tambah") {

    $iddokter = $_POST['iddokter'];
    $haripraktik = $_POST['haripraktik'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    // Periksa jam praktik
    if ($jam_mulai >= $jam_selesai) {
        header("location:../index.php?page=createjadwaldokter&status=jamtidakvalid");
        exit;
    }

    // Periksa bentrok jadwal dokter
    $cek = mysqli_query($koneksi, "
        SELECT * FROM jadwaldokter
        WHERE iddokter = '$iddokter'
        AND haripraktik = '$haripraktik'
        AND jam_mulai < '$jam_selesai'
        AND jam_selesai > '$jam_mulai'
    ");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=createjadwaldokter&status=jadwalbentrok");
        exit;
    }

    mysqli_query($koneksi, "
        INSERT INTO jadwaldokter
        (iddokter, haripraktik, jam_mulai, jam_selesai)
        VALUES
        ('$iddokter', '$haripraktik', '$jam_mulai', '$jam_selesai')
    ");

    header("location:../index.php?page=jadwaldokter&status=tambah");
    exit;
}


// ========================================
// UBAH JADWAL DOKTER
// ========================================
elseif ($aksi == "ubah") {

    $idjadwal = $_POST['idjadwal'];
    $iddokter = $_POST['iddokter'];
    $haripraktik = $_POST['haripraktik'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    // Periksa jam praktik
    if ($jam_mulai >= $jam_selesai) {
        header("location:../index.php?page=editjadwaldokter&id=$idjadwal&status=jamtidakvalid");
        exit;
    }

    // Periksa bentrok jadwal dokter selain jadwal yang sedang diubah
    $cek = mysqli_query($koneksi, "
        SELECT * FROM jadwaldokter
        WHERE iddokter = '$iddokter'
        AND haripraktik = '$haripraktik'
        AND jam_mulai < '$jam_selesai'
        AND jam_selesai > '$jam_mulai'
        AND idjadwal != '$idjadwal'
    ");

    if (mysqli_num_rows($cek) > 0) {
        header("location:../index.php?page=editjadwaldokter&id=$idjadwal&status=jadwalbentrok");
        exit;
    }

    mysqli_query($koneksi, "
        UPDATE jadwaldokter SET
            iddokter = '$iddokter',
            haripraktik = '$haripraktik',
            jam_mulai = '$jam_mulai',
            jam_selesai = '$jam_selesai'
        WHERE idjadwal = '$idjadwal'
    ");

    header("location:../index.php?page=jadwaldokter&status=ubah");
    exit;
}



// ========================================
// HAPUS JADWAL DOKTER
// ========================================
elseif ($aksi == "hapus") {

    $idjadwal = $_POST['idjadwal'];

    // Periksa apakah jadwal sudah digunakan pada kunjungan
    $cekkunjungan = mysqli_query($koneksi, "
        SELECT * FROM kunjungan
        WHERE idjadwal = '$idjadwal'
    ");

    if (mysqli_num_rows($cekkunjungan) > 0) {
        header("location:../index.php?page=jadwaldokter&status=masihdigunakan");
        exit;
    }

    // Periksa apakah jadwal sudah digunakan pada pendaftaran checkup
    $cekpendaftaran = mysqli_query($koneksi, "
        SELECT * FROM pendaftarancheckup
        WHERE idjadwal = '$idjadwal'
    ");

    if (mysqli_num_rows($cekpendaftaran) > 0) {
        header("location:../index.php?page=jadwaldokter&status=masihdigunakan");
        exit;
    }

    // Hapus jadwal dokter
    mysqli_query($koneksi, "
        DELETE FROM jadwaldokter
        WHERE idjadwal = '$idjadwal'
    ");

    header("location:../index.php?page=jadwaldokter&status=hapus");
    exit;
}



// ========================================
// AKSI TIDAK DIKENAL
// ========================================
else {
    header("location:../index.php?page=jadwaldokter");
    exit;
}

?>
