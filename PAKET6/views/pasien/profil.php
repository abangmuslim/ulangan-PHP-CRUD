<?php
$idpasien = $_SESSION['idpasien'];

$query = mysqli_query($koneksi, "
    SELECT * FROM pasien
    WHERE idpasien = '$idpasien'
");

$data = mysqli_fetch_assoc($query);
?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Profil Saya</h4>
            <p class="text-muted mb-0">Informasi akun pasien.</p>
        </div>

        <a href="?page=editprofil" class="btn btn-primary">
            <i class="bi bi-pencil-square"></i> Edit Profil
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <div class="row align-items-center">

                <div class="col-md-3 text-center mb-4 mb-md-0">

                    <?php
                    $foto = !empty($data['foto'])
                        ? "assets/images/pasien/" . $data['foto']
                        : "assets/images/pasien/default.png";
                    ?>

                    <img src="<?= htmlspecialchars($foto); ?>"
                         class="rounded-circle img-thumbnail"
                         width="160"
                         height="160"
                         style="object-fit: cover;"
                         alt="Foto Pasien">

                    <h5 class="fw-bold mt-3 mb-1">
                        <?= htmlspecialchars($data['namapasien']); ?>
                    </h5>

                    <span class="badge bg-success">Pasien</span>

                </div>

                <div class="col-md-9">

                    <h5 class="fw-bold mb-3">Informasi Pribadi</h5>

                    <div class="table-responsive">
                        <table class="table table-borderless">

                            <tr>
                                <th width="180">Nama Lengkap</th>
                                <td>
                                    : <?= htmlspecialchars($data['namapasien']); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Username</th>
                                <td>
                                    : <?= htmlspecialchars($data['username']); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Nomor HP</th>
                                <td>
                                    : <?= htmlspecialchars($data['nohp']); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Alamat</th>
                                <td>
                                    : <?= htmlspecialchars($data['alamat']); ?>
                                </td>
                            </tr>

                        </table>
                    </div>

                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i>
                        Pastikan informasi profil selalu benar dan terbaru.
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>