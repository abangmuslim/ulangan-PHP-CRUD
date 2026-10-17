
<style>
.wrapper{min-height:100vh;display:flex;flex-direction:column}
.wrapper>.content-wrapper{flex:1 0 auto}
.wrapper>.main-footer{flex-shrink:0}
.main-footer.tamu-footer{margin:0!important;padding:16px 0;background:#fff;border-top:1px solid #dceee9;color:#64748b;font-size:12px;line-height:1.6}
.tamu-footer .footer-inner{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
.tamu-footer .footer-brand{color:#075c45;font-size:13px;font-weight:700}
.tamu-footer .footer-copy{margin:0}
.tamu-footer .footer-note{display:flex;align-items:center;gap:7px;color:#64748b}
.tamu-footer .footer-note i{color:#087f5b}
@media(max-width:575.98px){.main-footer.tamu-footer{padding:13px 0}.tamu-footer .footer-inner{justify-content:center;text-align:center;gap:5px}.tamu-footer .footer-note{justify-content:center;width:100%}}
</style>

<footer class="main-footer tamu-footer">
    <div class="container">
        <div class="footer-inner">
            <p class="footer-copy">
                &copy; <?= date('Y'); ?>
                <span class="footer-brand">RumahSakit Ahmadi</span>.
                Semua hak dilindungi.
            </p>
            <div class="footer-note">
                <i class="fas fa-heartbeat"></i>
                <span>Sistem Informasi Pelayanan Kesehatan</span>
            </div>
        </div>
    </div>
</footer>
