
<?php
$namaDokter = $_SESSION['namadokter'] ?? 'Dokter';
$usernameDokter = $_SESSION['username'] ?? '';
$fotoDokter = $_SESSION['foto'] ?? 'default.png';
?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- MENU KIRI -->
    <ul class="navbar-nav">

        <li class="nav-item">
            <a class="nav-link"
               data-widget="pushmenu"
               href="#"
               role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
            <a href="index.php?page=dashboarddokter" class="nav-link">
                Dashboard Dokter
            </a>
        </li>

    </ul>

    <!-- MENU KANAN -->
    <ul class="navbar-nav ml-auto">

        <!-- IDENTITAS DOKTER -->
        <li class="nav-item dropdown">

            <a class="nav-link" data-toggle="dropdown" href="#">
                <img src="assets/images/dokter/<?= e($fotoDokter) ?>"
                     class="img-circle elevation-2"
                     alt="Foto Dokter"
                     style="width:30px;height:30px;object-fit:cover;">

                <span class="ml-1">
                    <?= e($namaDokter) ?>
                </span>
            </a>

            <div class="dropdown-menu dropdown-menu-right">

                <!-- INFORMASI DOKTER -->
                <div class="dropdown-item text-center">

                    <img src="assets/images/dokter/<?= e($fotoDokter) ?>"
                         class="img-circle elevation-2 mb-2"
                         alt="Foto Dokter"
                         style="width:65px;height:65px;object-fit:cover;">

                    <p class="mb-0 font-weight-bold">
                        <?= e($namaDokter) ?>
                    </p>

                    <small class="text-muted">Dokter</small>
                    <br>

                    <small class="text-muted">
                        <?= e($usernameDokter) ?>
                    </small>

                </div>

                <div class="dropdown-divider"></div>

                <!-- PROFIL -->
                <a href="index.php?page=profil" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i>
                    Profil Saya
                </a>

                <!-- EDIT PROFIL -->
                <a href="index.php?page=editprofil" class="dropdown-item">
                    <i class="fas fa-user-edit mr-2"></i>
                    Edit Profil
                </a>

                <!-- JADWAL KUNJUNGAN -->
                <a href="index.php?page=jadwalkunjungan" class="dropdown-item">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Jadwal Kunjungan
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
