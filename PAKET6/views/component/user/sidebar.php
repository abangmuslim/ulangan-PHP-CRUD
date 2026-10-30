
<?php
$menuAktif = $page ?? '';
?>

<div class="sidebar-admin py-2">

    <!-- DASHBOARD -->
    <div class="menu-title">Menu Utama</div>

    <a href="index.php?page=dashboarduser"
       class="nav-link <?= $menuAktif == 'dashboarduser' ? 'active' : '' ?>">
        <i class="bi bi-speedometer2"></i>
        Dashboard
    </a>


    <!-- MASTER DATA -->
    <div class="menu-title">Master Data</div>

    <a href="index.php?page=user"
       class="nav-link <?= in_array($menuAktif, ['user', 'createuser', 'edituser', 'showuser']) ? 'active' : '' ?>">
        <i class="bi bi-person-gear"></i>
        Data Admin
    </a>

    <a href="index.php?page=pasien"
       class="nav-link <?= in_array($menuAktif, ['pasien', 'createpasien', 'editpasien', 'showpasien']) ? 'active' : '' ?>">
        <i class="bi bi-people"></i>
        Data Pasien
    </a>

    <a href="index.php?page=dokter"
       class="nav-link <?= in_array($menuAktif, ['dokter', 'createdokter', 'editdokter', 'showdokter']) ? 'active' : '' ?>">
        <i class="bi bi-person-badge"></i>
        Data Dokter
    </a>

    <a href="index.php?page=jadwaldokter"
       class="nav-link <?= in_array($menuAktif, ['jadwaldokter', 'createjadwal', 'editjadwal', 'showjadwal']) ? 'active' : '' ?>">
        <i class="bi bi-calendar-week"></i>
        Jadwal Dokter
    </a>

    <a href="index.php?page=kategori"
       class="nav-link <?= in_array($menuAktif, ['kategori', 'createkategori', 'editkategori', 'showkategori']) ? 'active' : '' ?>">
        <i class="bi bi-tags"></i>
        Kategori Obat
    </a>

    <a href="index.php?page=merk"
       class="nav-link <?= in_array($menuAktif, ['merk', 'createmerk', 'editmerk', 'showmerk']) ? 'active' : '' ?>">
        <i class="bi bi-bookmark"></i>
        Merek Obat
    </a>

    <a href="index.php?page=obat"
       class="nav-link <?= in_array($menuAktif, ['obat', 'createobat', 'editobat', 'showobat']) ? 'active' : '' ?>">
        <i class="bi bi-capsule"></i>
        Data Obat
    </a>


</div>
