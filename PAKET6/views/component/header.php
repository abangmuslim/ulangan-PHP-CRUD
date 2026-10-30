
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Rumah Sakit Ahmadi</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

/* =========================================
   PENGATURAN DASAR
========================================= */

body {
    background-color: #f4f6f9;
    font-family: Arial, sans-serif;
    margin: 0;
}

.wrapper {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.content-wrapper {
    flex: 1;
    width: 100%;
}


/* =========================================
   NAVBAR TAMU
========================================= */

.navbar-tamu {
    background-color: #12613f;
}

.navbar-tamu .navbar-brand {
    color: #ffffff;
    font-weight: bold;
}

.navbar-tamu .navbar-brand:hover {
    color: #e2f3e9;
}

.navbar-tamu .nav-link {
    color: #d5eadd;
    padding: 8px 12px;
    margin: 0 2px;
    border-radius: 6px;
    transition: 0.2s;
}

.navbar-tamu .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.12);
    color: #ffffff;
}

.navbar-tamu .nav-link.active {
    background-color: #ffffff;
    color: #12613f !important;
    font-weight: bold;
}


/* =========================================
   NAVBAR ADMIN
========================================= */

.navbar-admin {
    background: linear-gradient(90deg, #146c43, #198754);
    min-height: 58px;
    padding: 6px 12px;
    position: relative;
    z-index: 1030;
}


/* BRAND ADMIN */

.navbar-admin .navbar-brand {
    color: #ffffff;
    font-size: 17px;
    font-weight: bold;
    white-space: nowrap;
}

.navbar-admin .navbar-brand:hover {
    color: #e2f3e9;
}


/* TOMBOL PUSH MENU */

.navbar-admin .btn-pushmenu {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 27px;
    line-height: 1;
    padding: 5px 9px;
    margin-right: 8px;
    border: none;
    background: transparent;
    box-shadow: none;
    border-radius: 6px;
}

.navbar-admin .btn-pushmenu:hover,
.navbar-admin .btn-pushmenu:focus {
    color: #ffffff;
    background-color: rgba(255, 255, 255, 0.12);
    box-shadow: none;
}


/* MENU NAVBAR */

.navbar-admin .navbar-nav {
    align-items: center;
}

.navbar-admin .navbar-nav {
    align-items: center;
}

.navbar-admin .nav-item {
    position: relative;
}

.navbar-admin .dropdown-menu {
    position: absolute;
    z-index: 1050;
}

.navbar-admin .nav-link {
    display: flex;
    align-items: center;
    min-height: 42px;
    color: rgba(255, 255, 255, 0.9);
    padding: 10px 12px;
    border-radius: 6px;
    transition: 0.2s;
    white-space: nowrap;
}

.navbar-admin .nav-link:hover,
.navbar-admin .nav-link:focus {
    color: #ffffff;
    background-color: rgba(255, 255, 255, 0.12);
}


/* DROPDOWN LAPORAN */

.navbar-admin .dropdown-menu {
    min-width: 220px;
    margin-top: 5px;
    padding: 7px;
    border: none;
    border-radius: 8px;
    box-shadow: 0 5px 18px rgba(0, 0, 0, 0.15);
    z-index: 1050;
}

.navbar-admin .dropdown-item {
    padding: 9px 12px;
    border-radius: 5px;
    font-size: 14px;
    color: #495057;
}

.navbar-admin .dropdown-item:hover,
.navbar-admin .dropdown-item:focus {
    background-color: #e8f5ed;
    color: #198754;
}


/* SAPAAN PENGGUNA */

.navbar-admin .user-greeting {
    color: #ffffff;
    white-space: nowrap;
}


/* TOMBOL LOGOUT */

.navbar-admin .btn-logout {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 7px 13px;
    min-height: 38px;
    border-radius: 6px;
    font-size: 14px;
    line-height: 1.2;
    white-space: nowrap;
}


/* =========================================
   LAYOUT ADMIN
========================================= */

#adminLayout {
    display: flex;
    flex-wrap: nowrap;
    width: 100%;
    margin: 0;
    transition: all 0.25s ease;
}


/* =========================================
   SIDEBAR ADMIN
========================================= */

#sidebarAdmin {
    min-height: calc(100vh - 58px);
    background-color: #ffffff;
    border-right: 1px solid #dee2e6;
    transition: all 0.25s ease;
}

.sidebar-admin {
    background-color: #ffffff;
}


/* JUDUL KELOMPOK MENU */

.sidebar-admin .menu-title {
    font-size: 11px;
    font-weight: bold;
    color: #89939d;
    padding: 18px 16px 8px;
    text-transform: uppercase;
    letter-spacing: 1px;
}


/* LINK SIDEBAR */

.sidebar-admin .nav-link {
    display: block;
    color: #495057;
    padding: 11px 16px;
    margin: 2px 10px;
    border-radius: 6px;
    font-size: 14px;
    text-decoration: none;
    transition: 0.2s;
}

.sidebar-admin .nav-link:hover {
    background-color: #e8f5ed;
    color: #198754;
}

.sidebar-admin .nav-link.active {
    background-color: #198754;
    color: #ffffff;
}

.sidebar-admin .nav-link i {
    width: 25px;
    display: inline-block;
}


/* =========================================
   KONTEN UTAMA ADMIN
========================================= */

#mainContent {
    flex: 1 1 auto;
    min-width: 0;
    width: auto;
    max-width: none;
    padding: 25px;
    transition: all 0.25s ease;
}


/* =========================================
   PUSH MENU
========================================= */

/* SIDEBAR DISEMBUNYIKAN */

#adminLayout.sidebar-collapsed #sidebarAdmin {
    display: none !important;
}

/* KONTEN UTAMA MELEBAR */

#adminLayout.sidebar-collapsed #mainContent {
    flex: 0 0 100%;
    width: 100%;
    max-width: 100%;
}


/* =========================================
   RESPONSIVE TABLET DAN HP
========================================= */

@media (max-width: 991.98px) {

    /* NAVBAR ADMIN */

    .navbar-admin .navbar-collapse {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .navbar-admin .navbar-nav {
        align-items: stretch;
    }

    .navbar-admin .navbar-nav .nav-link {
        padding: 10px 12px;
    }

    .navbar-admin .user-greeting {
        padding-left: 12px;
    }

    .navbar-admin .btn-logout {
        margin: 5px 12px;
    }


    /* DROPDOWN */

    .navbar-admin .dropdown-menu {
        margin-top: 2px;
        margin-bottom: 5px;
    }


    /* SIDEBAR */

    #sidebarAdmin {
        min-height: auto;
        border-right: none;
        border-bottom: 1px solid #dee2e6;
    }


    /* KONTEN */

    #mainContent {
        padding: 15px;
    }

}


@media (max-width: 575.98px) {

    .navbar-admin .navbar-brand {
        font-size: 15px;
    }

    .navbar-admin .btn-pushmenu {
        margin-right: 3px;
        padding: 5px;
    }

    .navbar-tamu .navbar-brand {
        font-size: 16px;
    }

    #mainContent {
        padding: 12px;
    }

}

</style>

</head>

<body>

<div class="wrapper">
