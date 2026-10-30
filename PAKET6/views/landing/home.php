
<?php
// Memanggil koneksi database
require_once __DIR__ . '/../../proses/koneksi.php';

// Menghitung jumlah data dari database
function hitungDataHome($koneksi, $tabel)
{
    $tabelDiizinkan = array(
        'dokter',
        'obat',
        'kategori',
        'pasien'
    );

    if (!in_array($tabel, $tabelDiizinkan)) {
        return 0;
    }

    $query = mysqli_query(
        $koneksi,
        "SELECT COUNT(*) AS total FROM " . $tabel
    );

    if ($query) {
        $data = mysqli_fetch_assoc($query);
        return (int) $data['total'];
    }

    return 0;
}

// Mengambil jumlah data
$totalDokter = hitungDataHome($koneksi, 'dokter');
$totalObat = hitungDataHome($koneksi, 'obat');
$totalKategori = hitungDataHome($koneksi, 'kategori');
$totalPasien = hitungDataHome($koneksi, 'pasien');
?>

<!-- Bagian Sambutan -->
<section class="bg-success text-white py-5">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold">
                    Selamat Datang di Rumah Sakit Ahmadi
                </h1>

                <p class="lead mt-3">
                    Pelayanan kesehatan yang informatif, mudah diakses,
                    dan berorientasi pada kebutuhan pasien.
                </p>

                <p>
                    Temukan informasi dokter, obat, kategori obat,
                    dan layanan yang tersedia di Rumah Sakit Ahmadi.
                </p>

                <a href="index.php?page=daftardokter"
                   class="btn btn-light btn-lg mt-2">
                    <i class="bi bi-person-badge"></i>
                    Lihat Daftar Dokter
                </a>

                <a href="index.php?page=loginpasien"
                   class="btn btn-outline-light btn-lg mt-2">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Login Pasien
                </a>
            </div>

            <div class="col-lg-4 text-center mt-4 mt-lg-0">
                <i class="bi bi-hospital"
                   style="font-size: 140px;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Bagian Ringkasan Data -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Informasi Rumah Sakit</h2>
            <p class="text-muted">
                Ringkasan data yang tersedia dalam sistem.
            </p>
        </div>

        <div class="row g-4">

            <!-- Jumlah Dokter -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-person-badge text-success"
                           style="font-size: 45px;"></i>

                        <h3 class="fw-bold mt-3">
                            <?= $totalDokter; ?>
                        </h3>

                        <p class="text-muted mb-0">
                            Dokter
                        </p>

                        <a href="index.php?page=daftardokter"
                           class="btn btn-outline-success btn-sm mt-3">
                            Lihat Dokter
                        </a>
                    </div>
                </div>
            </div>

            <!-- Jumlah Obat -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-capsule text-primary"
                           style="font-size: 45px;"></i>

                        <h3 class="fw-bold mt-3">
                            <?= $totalObat; ?>
                        </h3>

                        <p class="text-muted mb-0">
                            Jenis Obat
                        </p>

                        <a href="index.php?page=daftarobat"
                           class="btn btn-outline-primary btn-sm mt-3">
                            Lihat Obat
                        </a>
                    </div>
                </div>
            </div>

            <!-- Jumlah Kategori -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-grid text-warning"
                           style="font-size: 45px;"></i>

                        <h3 class="fw-bold mt-3">
                            <?= $totalKategori; ?>
                        </h3>

                        <p class="text-muted mb-0">
                            Kategori Obat
                        </p>

                        <a href="index.php?page=daftarkategori"
                           class="btn btn-outline-warning btn-sm mt-3">
                            Lihat Kategori
                        </a>
                    </div>
                </div>
            </div>

            <!-- Jumlah Pasien -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-people text-info"
                           style="font-size: 45px;"></i>

                        <h3 class="fw-bold mt-3">
                            <?= $totalPasien; ?>
                        </h3>

                        <p class="text-muted mb-0">
                            Pasien Terdaftar
                        </p>

                        <a href="index.php?page=registerpasien"
                           class="btn btn-outline-info btn-sm mt-3">
                            Daftar Pasien
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Bagian Layanan -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Informasi dan Layanan</h2>
            <p class="text-muted">
                Akses informasi rumah sakit melalui menu berikut.
            </p>
        </div>

        <div class="row g-4">

            <!-- Informasi Dokter -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-person-vcard text-success"
                           style="font-size: 50px;"></i>

                        <h5 class="fw-bold mt-3">
                            Informasi Dokter
                        </h5>

                        <p class="text-muted">
                            Lihat daftar dokter dan informasi
                            spesialisasi yang tersedia.
                        </p>

                        <a href="index.php?page=daftardokter"
                           class="btn btn-success">
                            Lihat Dokter
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informasi Obat -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-capsule-pill text-primary"
                           style="font-size: 50px;"></i>

                        <h5 class="fw-bold mt-3">
                            Informasi Obat
                        </h5>

                        <p class="text-muted">
                            Lihat informasi obat dan kategori
                            obat yang tercatat dalam sistem.
                        </p>

                        <a href="index.php?page=daftarobat"
                           class="btn btn-primary">
                            Lihat Obat
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pendaftaran Pasien -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-calendar-check text-danger"
                           style="font-size: 50px;"></i>

                        <h5 class="fw-bold mt-3">
                            Pendaftaran Pasien
                        </h5>

                        <p class="text-muted">
                            Buat akun pasien untuk mengakses
                            fitur yang tersedia bagi pasien.
                        </p>

                        <a href="index.php?page=registerpasien"
                           class="btn btn-danger">
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Bagian Tentang Rumah Sakit -->
<section class="bg-light py-5">
    <div class="container text-center">
        <h2 class="fw-bold">Tentang Rumah Sakit Ahmadi</h2>

        <p class="text-muted mx-auto mt-3"
           style="max-width: 800px;">
            Rumah Sakit Ahmadi menyediakan informasi mengenai
            dokter, obat, dan layanan yang dapat diakses melalui
            aplikasi ini. Sistem dirancang untuk membantu pengguna
            memperoleh informasi serta memudahkan pasien mengakses
            fitur yang tersedia.
        </p>

        <a href="index.php?page=tentang"
           class="btn btn-success mt-2">
            Selengkapnya
        </a>

        <a href="index.php?page=kontak"
           class="btn btn-outline-success mt-2">
            Hubungi Kami
        </a>
    </div>
</section>
