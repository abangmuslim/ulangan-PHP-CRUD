
<?php
$menuAktif = $page ?? '';
?>

<div class="sidebar-admin py-2">


    <!-- =================================================
         MENU UTAMA
    ================================================== -->

    <div class="menu-title">
        Menu Utama
    </div>


    <!-- DASHBOARD -->
    <a href="index.php?page=dashboardpasien"
       class="nav-link <?= $menuAktif == 'dashboardpasien' ? 'active' : '' ?>">

        <i class="bi bi-speedometer2"></i>
        Dashboard

    </a>


    <!-- =================================================
         LAYANAN PASIEN
    ================================================== -->

    <div class="menu-title">
        Layanan Pasien
    </div>


    <!-- PENDAFTARAN CHECKUP -->
    <a href="index.php?page=pendaftarancheckup"
       class="nav-link <?= in_array($menuAktif, ['pendaftarancheckup']) ? 'active' : '' ?>">

        <i class="bi bi-calendar2-plus"></i>
        Pendaftaran Checkup

    </a>


    <!-- RIWAYAT KUNJUNGAN -->
    <a href="index.php?page=riwayatkunjungan"
       class="nav-link <?= in_array($menuAktif, ['riwayatkunjungan', 'detilkunjungan']) ? 'active' : '' ?>">

        <i class="bi bi-clock-history"></i>
        Riwayat Kunjungan

    </a>


    <!-- =================================================
         PROFIL SAYA
    ================================================== -->

    <div class="menu-title">
        Akun Saya
    </div>


    <!-- PROFIL -->
    <a href="index.php?page=profil"
       class="nav-link <?= in_array($menuAktif, ['profil', 'editprofil']) ? 'active' : '' ?>">

        <i class="bi bi-person-vcard"></i>
        Profil Saya

    </a>


    <!-- EDIT PROFIL -->
    <a href="index.php?page=editprofil"
       class="nav-link <?= $menuAktif == 'editprofil' ? 'active' : '' ?>">

        <i class="bi bi-pencil-square"></i>
        Edit Profil

    </a>



<!-- =================================================
     INFORMASI KLINIK
================================================== -->

<div class="menu-title">
    Informasi Klinik
</div>

<!-- DAFTAR DOKTER -->
<a href="index.php?page=daftardokter"
   class="nav-link <?= in_array($menuAktif, ['daftardokter']) ? 'active' : '' ?>">

    <i class="bi bi-person-badge"></i>
    Daftar Dokter

</a>

<!-- INFORMASI OBAT -->
<a href="index.php?page=informasiobat"
   class="nav-link <?= in_array($menuAktif, ['informasiobat']) ? 'active' : '' ?>">

    <i class="bi bi-capsule"></i>
    Informasi Obat

</a>



</div>
