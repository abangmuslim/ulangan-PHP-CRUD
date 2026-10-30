<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    console.log("Bootstrap:", typeof bootstrap);

    const menuLaporan = document.getElementById("menuLaporan");

    console.log("Menu Laporan:", menuLaporan);

    if (menuLaporan) {

        menuLaporan.addEventListener("shown.bs.dropdown", function () {
            console.log("Dropdown berhasil dibuka!");
        });

    }

    // PUSH MENU ADMIN
    const toggleSidebar = document.getElementById("toggleSidebar");
    const adminLayout = document.getElementById("adminLayout");

    if (toggleSidebar && adminLayout) {

        toggleSidebar.addEventListener("click", function (event) {

            event.preventDefault();

            adminLayout.classList.toggle("sidebar-collapsed");

        });

    }

});
</script>

</body>
</html>