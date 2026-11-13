
<?php
$namaPasien = $_SESSION['namapasien'] ?? 'Pasien';
$usernamePasien = $_SESSION['username'] ?? '';
$fotoPasien = $_SESSION['foto'] ?? 'default.png';
?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- MENU KIRI -->
    <ul class="navbar-nav">

        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
            <a href="index.php?page=dashboardpasien" class="nav-link">
                Dashboard Pasien
            </a>
        </li>

    </ul>

    <!-- MENU KANAN -->
    <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown">

            <a class="nav-link" data-toggle="dropdown" href="#">

                <img src="assets/images/pasien/<?= e($fotoPasien) ?>"
                     class="img-circle elevation-2"
                     alt="Foto Pasien"
                     style="width:30px;height:30px;object-fit:cover;">

                <span class="ml-1"><?= e($namaPasien) ?></span>

            </a>

            <div class="dropdown-menu dropdown-menu-right">

                <!-- INFORMASI PASIEN -->
                <div class="dropdown-item text-center">

                    <img src="assets/images/pasien/<?= e($fotoPasien) ?>"
                         class="img-circle elevation-2 mb-2"
                         alt="Foto Pasien"
                         style="width:65px;height:65px;object-fit:cover;">

                    <p class="mb-0 font-weight-bold">
                        <?= e($namaPasien) ?>
                    </p>

                    <small class="text-muted">Pasien</small>
                    <br>

                    <small class="text-muted">
                        <?= e($usernamePasien) ?>
                    </small>

                </div>

                <div class="dropdown-divider"></div>

                <!-- PROFIL -->
                <a href="index.php?page=profilpasien" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i>
                    Profil Saya
                </a>

                <!-- EDIT PROFIL -->
                <a href="index.php?page=editprofilpasien" class="dropdown-item">
                    <i class="fas fa-user-edit mr-2"></i>
                    Edit Profil
                </a>

                <!-- PENDAFTARAN -->
                <a href="index.php?page=pendaftaranpasien" class="dropdown-item">
                    <i class="fas fa-calendar-plus mr-2"></i>
                    Pendaftaran Checkup
                </a>

                <!-- ANTRIAN -->
                <a href="index.php?page=antrianpasien" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i>
                    Antrian Saya
                </a>

                <div class="dropdown-divider"></div>

                <!-- LOGOUT -->
                <form action="index.php?page=logout" method="POST">

                    <?= csrfField() ?>
                    <input type="hidden" name="page" value="logout">

                    <button type="submit" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Logout
                    </button>

                </form>

            </div>

        </li>

    </ul>

</nav>
