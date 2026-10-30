
<?php

$id = $_GET['id'] ?? 0;
$id = (int) $id;

$query = mysqli_query($koneksi, "
    SELECT jadwaldokter.*, dokter.namadokter, dokter.spesialis,
           dokter.nohp, dokter.alamat, dokter.foto
    FROM jadwaldokter
    INNER JOIN dokter ON jadwaldokter.iddokter = dokter.iddokter
    WHERE jadwaldokter.idjadwal='$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>window.location='index.php?page=jadwaldokter';</script>";
    exit;
}

$foto = empty($data['foto'])
    ? 'default.png'
    : $data['foto'];
?>

<style>
    .foto-dokter {
        width: 190px;
        height: 250px;
        object-fit: cover;
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 4px;
        background: #fff;
    }
</style>

<section class="content-header">
    <div class="container-fluid">

        <div class="row mb-2">

            <div class="col-sm-6">
                <h1>
                    <i class="bi bi-calendar-week"></i>
                    Detail Jadwal Dokter
                </h1>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">

                    <li class="breadcrumb-item">
                        <a href="index.php?page=dashboarduser">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="index.php?page=jadwaldokter">
                            Jadwal Dokter
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        Detail
                    </li>

                </ol>
            </div>

        </div>

    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="row">

            <!-- FOTO DOKTER -->
            <div class="col-md-4">

                <div class="card card-primary card-outline">

                    <div class="card-header text-center">
                        <h3 class="card-title float-none">
                            <i class="bi bi-image"></i>
                            Foto Dokter
                        </h3>
                    </div>

                    <div class="card-body box-profile">

                        <div class="text-center">

                            <img
                                src="assets/images/dokter/<?= htmlspecialchars($foto); ?>"
                                class="foto-dokter"
                                alt="Foto Dokter">

                        </div>

                        <h3 class="profile-username text-center mt-3">
                            <?= htmlspecialchars($data['namadokter']); ?>
                        </h3>

                        <p class="text-muted text-center">
                            <?= htmlspecialchars($data['spesialis'] ?? '-'); ?>
                        </p>

                        <div class="text-center">

                            <span class="badge bg-primary p-2">
                                <i class="bi bi-calendar-check"></i>
                                Jadwal Praktik Dokter
                            </span>

                        </div>

                    </div>

                </div>

            </div>

            <!-- INFORMASI JADWAL -->
            <div class="col-md-8">

                <div class="card card-secondary card-outline">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-info-circle"></i>
                            Informasi Jadwal Praktik
                        </h3>
                    </div>

                    <div class="card-body">

                        <table class="table table-bordered">

                            <tr>
                                <th width="30%">ID Jadwal</th>
                                <td>
                                    <?= $data['idjadwal']; ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Nama Dokter</th>
                                <td>
                                    <?= htmlspecialchars($data['namadokter']); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Spesialis</th>
                                <td>
                                    <?= htmlspecialchars($data['spesialis'] ?? '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Hari Praktik</th>
                                <td>
                                    <span class="badge bg-info p-2">
                                        <?= htmlspecialchars($data['haripraktik']); ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th>Jam Mulai</th>
                                <td>
                                    <?= htmlspecialchars(substr($data['jam_mulai'], 0, 5)); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Jam Selesai</th>
                                <td>
                                    <?= htmlspecialchars(substr($data['jam_selesai'], 0, 5)); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>No HP Dokter</th>
                                <td>
                                    <?= htmlspecialchars($data['nohp'] ?? '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Alamat Dokter</th>
                                <td>
                                    <?= nl2br(htmlspecialchars($data['alamat'] ?? '-')); ?>
                                </td>
                            </tr>

                        </table>

                    </div>

                    <div class="card-footer text-end">

                        <a href="index.php?page=jadwaldokter"
                            class="btn btn-secondary">

                            <i class="bi bi-arrow-left"></i>
                            Kembali

                        </a>

                        <a href="index.php?page=editjadwaldokter&id=<?= $data['idjadwal']; ?>"
                            class="btn btn-warning">

                            <i class="bi bi-pencil-square"></i>
                            Edit Jadwal

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>
