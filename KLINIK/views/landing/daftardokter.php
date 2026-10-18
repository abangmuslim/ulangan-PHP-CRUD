
<?php
/*
|--------------------------------------------------------------------------
| KATALOG DOKTER - RUMAH SAKIT AHMADI
| PHP Native + AdminLTE 3.2
|--------------------------------------------------------------------------
| Fitur:
| - Hero modern seragam dengan katalog obat
| - Statistik dokter, spesialisasi, dan jadwal praktik
| - Grid kartu dokter responsif
| - Foto dokter dengan fallback ikon
| - Informasi spesialisasi, nomor HP, dan jadwal
| - Tautan detail dokter
|--------------------------------------------------------------------------
*/

// =====================================================
// 1. RINGKASAN KATALOG DOKTER
// =====================================================
$queryRingkasan = "SELECT
    (SELECT COUNT(*) FROM dokter) AS totalDokter,
    (SELECT COUNT(DISTINCT spesialis)
        FROM dokter
        WHERE spesialis IS NOT NULL AND TRIM(spesialis) <> '') AS totalSpesialisasi,
    (SELECT COUNT(*) FROM jadwaldokter) AS totalJadwal";

$resultRingkasan = mysqli_query($koneksi, $queryRingkasan);

$ringkasanDokter = [
    'totalDokter' => 0,
    'totalSpesialisasi' => 0,
    'totalJadwal' => 0
];

if ($resultRingkasan && $dataRingkasan = mysqli_fetch_assoc($resultRingkasan)) {
    $ringkasanDokter = $dataRingkasan;
}

// =====================================================
// 2. DATA DOKTER DAN JUMLAH JADWAL
// =====================================================
$queryDokter = "SELECT
                    d.*,
                    COALESCE(j.totaljadwal, 0) AS totaljadwal
                FROM dokter d
                LEFT JOIN (
                    SELECT iddokter, COUNT(*) AS totaljadwal
                    FROM jadwaldokter
                    GROUP BY iddokter
                ) j ON d.iddokter = j.iddokter
                ORDER BY d.iddokter DESC";

$resultDokter = mysqli_query($koneksi, $queryDokter);
?>


<style>
.dokter-section{min-height:60vh;padding:22px 0 35px;background:radial-gradient(ellipse at top right,rgba(16,185,129,.08),transparent 40%),#f5f7fb}
.dokter-container{width:100%;max-width:1800px;margin:0 auto;padding:0 18px}
.dokter-hero{position:relative;overflow:hidden;padding:24px 28px;margin-bottom:18px;color:#fff;border-radius:17px;background:linear-gradient(120deg,#102f35 0%,#087f70 58%,#10b981 100%);box-shadow:0 12px 30px rgba(8,127,112,.15)}
.dokter-hero::after{content:"";position:absolute;width:230px;height:230px;top:-125px;right:8%;border:35px solid rgba(255,255,255,.07);border-radius:50%;pointer-events:none}
.dokter-hero-content{position:relative;z-index:1}
.dokter-eyebrow{display:inline-flex;align-items:center;gap:7px;margin-bottom:7px;color:#c9fff0;font-size:.7rem;font-weight:700;letter-spacing:1.4px;text-transform:uppercase}
.dokter-hero h1{margin:0 0 7px;font-size:clamp(1.4rem,2.5vw,2rem);font-weight:800;letter-spacing:-.7px;line-height:1.25}
.dokter-hero h1 span{color:#ead470}
.dokter-hero p{max-width:560px;margin:0;color:rgba(255,255,255,.82);font-size:.86rem;line-height:1.6}
.dokter-hero-actions{display:flex;flex-wrap:wrap;gap:8px;margin-top:16px}
.dokter-hero-btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:8px 13px;border:1px solid rgba(255,255,255,.3);border-radius:8px;color:#fff!important;background:rgba(255,255,255,.12);font-size:.76rem;font-weight:600;text-decoration:none!important;transition:.2s ease}
.dokter-hero-btn:hover,.dokter-hero-btn-light{color:#075e54!important;background:#fff;border-color:#fff}
.dokter-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin-bottom:24px}
.dokter-stat{display:flex;align-items:center;gap:12px;min-width:0;padding:13px 15px;border:1px solid #e9eef4;border-radius:12px;background:#fff;box-shadow:0 4px 14px rgba(24,39,75,.035);transition:transform .2s ease,box-shadow .2s ease}
.dokter-stat:hover{transform:translateY(-2px);box-shadow:0 8px 18px rgba(24,39,75,.07)}
.dokter-stat-icon{display:flex;flex:0 0 39px;align-items:center;justify-content:center;width:39px;height:39px;border-radius:10px;font-size:1rem}
.dokter-stat-icon.green{color:#059669;background:#dff9ed}
.dokter-stat-icon.blue{color:#2563eb;background:#e5efff}
.dokter-stat-icon.orange{color:#d97706;background:#fff1d6}
.dokter-stat-label{margin-bottom:2px;color:#7b8798;font-size:.7rem}
.dokter-stat-number{color:#1e293b;font-size:1.25rem;font-weight:800;line-height:1.2}
.dokter-toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:13px;scroll-margin-top:20px}
.dokter-toolbar h2{margin:0;color:#243047;font-size:1.05rem;font-weight:800}
.dokter-toolbar p{margin:3px 0 0;color:#8993a3;font-size:.75rem}
.dokter-total{flex-shrink:0;padding:6px 10px;border-radius:20px;color:#087f70;background:#dff9ed;font-size:.72rem;font-weight:700}
.dokter-grid{display:grid;grid-template-columns:repeat(10,minmax(0,1fr));gap:12px}
.dokter-card{display:flex;flex-direction:column;min-width:0;overflow:hidden;border:1px solid #e9eef4;border-radius:10px;background:#fff;transition:transform .2s ease,box-shadow .2s ease}
.dokter-card:hover{transform:translateY(-3px);box-shadow:0 9px 20px rgba(30,45,70,.10)}
.dokter-photo-wrapper{position:relative;width:100%;aspect-ratio:1/1;overflow:hidden;background:linear-gradient(135deg,#e5f8f1,#eaf1ff)}
.dokter-photo{display:block;width:100%;height:100%;object-fit:cover;object-position:center;transition:transform .3s ease}
.dokter-card:hover .dokter-photo{transform:scale(1.06)}
.dokter-photo-placeholder{display:flex;align-items:center;justify-content:center;width:100%;height:100%;color:#0d9488;font-size:2.5rem}
.dokter-photo-badge{position:absolute;top:6px;left:6px;max-width:calc(100% - 12px);padding:4px 6px;overflow:hidden;border:1px solid rgba(255,255,255,.75);border-radius:6px;color:#087f70;background:rgba(255,255,255,.95);font-size:.59rem;font-weight:700;text-overflow:ellipsis;white-space:nowrap}
.dokter-card-body{display:flex;flex:1;flex-direction:column;min-width:0;padding:9px 8px 10px}
.dokter-name{display:-webkit-box;min-height:2.5em;margin:0 0 7px;overflow:hidden;color:#293548;font-size:.76rem;font-weight:800;line-height:1.25;overflow-wrap:anywhere;-webkit-box-orient:vertical;-webkit-line-clamp:2}
.dokter-specialist{display:flex;align-items:flex-start;gap:5px;min-height:2.5em;margin-bottom:8px;color:#087f70;font-size:.66rem;font-weight:700;line-height:1.35}
.dokter-specialist i{flex-shrink:0;margin-top:2px}
.dokter-meta{display:flex;flex-direction:column;gap:5px;margin-bottom:9px;color:#7b8798;font-size:.64rem}
.dokter-meta-item{display:flex;align-items:flex-start;gap:5px;min-width:0}
.dokter-meta-item i{flex-shrink:0;width:11px;margin-top:2px;color:#98a5b5;text-align:center}
.dokter-meta-item span{min-width:0;overflow-wrap:anywhere}
.dokter-schedule{display:flex;align-items:center;gap:5px;margin-top:auto;margin-bottom:9px;padding:6px 7px;border:1px solid #e0f2eb;border-radius:6px;color:#087f70;background:#effcf6;font-size:.63rem;font-weight:700}
.dokter-detail-button{display:flex;align-items:center;justify-content:center;gap:4px;width:100%;padding:6px 3px;border:0;border-radius:6px;color:#fff!important;background:#087f70;font-size:.67rem;font-weight:600;text-align:center;text-decoration:none!important;transition:background .2s ease}
.dokter-detail-button:hover{color:#fff!important;background:#06665b}
.dokter-empty{grid-column:1/-1;padding:38px 15px;border:1px dashed #d8e1eb;border-radius:13px;background:#fff;color:#7b8798;text-align:center}
.dokter-empty i{margin-bottom:10px;color:#0d9488;font-size:2rem}
.dokter-empty h5{color:#293548;font-weight:700}
@media(max-width:1599.98px){.dokter-grid{grid-template-columns:repeat(8,minmax(0,1fr))}}
@media(max-width:1199.98px){.dokter-grid{grid-template-columns:repeat(6,minmax(0,1fr));gap:10px}}
@media(max-width:991.98px){.dokter-container{padding:0 14px}.dokter-grid{grid-template-columns:repeat(5,minmax(0,1fr))}}
@media(max-width:767.98px){.dokter-section{padding-top:16px}.dokter-hero{padding:21px 19px;border-radius:13px}.dokter-stats{gap:8px;margin-bottom:20px}.dokter-stat{display:block;padding:10px}.dokter-stat-icon{width:32px;height:32px;margin-bottom:7px;font-size:.85rem}.dokter-stat-label{font-size:.65rem}.dokter-stat-number{font-size:1.05rem}.dokter-grid{grid-template-columns:repeat(4,minmax(0,1fr));gap:9px}.dokter-card-body{padding:8px 6px}}
@media(max-width:575.98px){.dokter-container{padding:0 10px}.dokter-hero{padding:19px 16px}.dokter-hero p{font-size:.8rem}.dokter-hero-btn{padding:7px 10px;font-size:.7rem}.dokter-stats{gap:6px}.dokter-stat{padding:8px 6px}.dokter-stat-icon{width:29px;height:29px}.dokter-stat-label{min-height:2em;font-size:.61rem}.dokter-stat-number{font-size:.95rem}.dokter-grid{grid-template-columns:repeat(3,minmax(0,1fr));gap:8px}.dokter-name{font-size:.72rem}.dokter-specialist{font-size:.63rem}.dokter-meta{font-size:.61rem}.dokter-schedule{font-size:.6rem}.dokter-detail-button{font-size:.63rem}.dokter-toolbar h2{font-size:.95rem}}
@media(max-width:359.98px){.dokter-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(prefers-reduced-motion:reduce){.dokter-section *,.dokter-section *::before,.dokter-section *::after{transition-duration:.01ms!important}}
</style>


<!-- =====================================================
     HALAMAN KATALOG DOKTER
===================================================== -->
<section class="dokter-section">
    <div class="dokter-container">

        <!-- HERO -->
        <div class="dokter-hero">
            <div class="dokter-hero-content">
                <div class="dokter-eyebrow">
                    <i class="fas fa-hospital-user"></i>
                    Direktori Medis Rumah Sakit
                </div>
                <h1>
                    Kenali <span>Dokter Rumah Sakit Ahmadi</span>
                </h1>
                <p>
                    Temukan informasi dokter berdasarkan spesialisasi,
                    nomor kontak, dan ketersediaan jadwal praktik yang
                    tercatat di sistem Rumah Sakit Ahmadi.
                </p>
                <div class="dokter-hero-actions">
                    <a href="#daftarDokter" class="dokter-hero-btn dokter-hero-btn-light">
                        <i class="fas fa-user-md"></i>
                        Jelajahi Dokter
                    </a>
                    <a href="index.php?page=layanan" class="dokter-hero-btn">
                        <i class="fas fa-heartbeat"></i>
                        Lihat Layanan
                    </a>
                </div>
            </div>
        </div>

        <!-- STATISTIK -->
        <div class="dokter-stats">
            <div class="dokter-stat">
                <div class="dokter-stat-icon green">
                    <i class="fas fa-user-md"></i>
                </div>
                <div>
                    <div class="dokter-stat-label">Total Dokter</div>
                    <div class="dokter-stat-number">
                        <?= number_format((int)$ringkasanDokter['totalDokter'], 0, ',', '.'); ?>
                    </div>
                </div>
            </div>
            <div class="dokter-stat">
                <div class="dokter-stat-icon blue">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <div>
                    <div class="dokter-stat-label">Spesialisasi</div>
                    <div class="dokter-stat-number">
                        <?= number_format((int)$ringkasanDokter['totalSpesialisasi'], 0, ',', '.'); ?>
                    </div>
                </div>
            </div>
            <div class="dokter-stat">
                <div class="dokter-stat-icon orange">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <div class="dokter-stat-label">Jadwal Praktik</div>
                    <div class="dokter-stat-number">
                        <?= number_format((int)$ringkasanDokter['totalJadwal'], 0, ',', '.'); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- JUDUL DAFTAR DOKTER -->
        <div class="dokter-toolbar" id="daftarDokter">
            <div>
                <h2>
                    <i class="fas fa-th-large mr-1" style="color:#087f70;"></i>
                    Semua Dokter
                </h2>
                <p>Pilih dokter untuk melihat informasi selengkapnya.</p>
            </div>
            <?php if ($resultDokter): ?>
                <span class="dokter-total">
                    <?= number_format(mysqli_num_rows($resultDokter), 0, ',', '.'); ?>
                    Dokter
                </span>
            <?php endif; ?>
        </div>

        <!-- GRID DOKTER -->
        <div class="dokter-grid">
            <?php if ($resultDokter && mysqli_num_rows($resultDokter) > 0): ?>
                <?php while ($dokter = mysqli_fetch_assoc($resultDokter)): ?>
                    <?php
                    $idDokter = (int)($dokter['iddokter'] ?? 0);
                    $namaDokter = trim($dokter['namadokter'] ?? 'Dokter');
                    $spesialis = trim($dokter['spesialis'] ?? '');
                    $nohp = trim($dokter['nohp'] ?? '');
                    $alamat = trim($dokter['alamat'] ?? '');
                    $totalJadwal = (int)($dokter['totaljadwal'] ?? 0);

                    $spesialisTampil = $spesialis !== ''
                        ? $spesialis
                        : 'Spesialisasi belum dicantumkan';

                    $nohpTampil = $nohp !== ''
                        ? $nohp
                        : 'Belum dicantumkan';

                    $alamatTampil = $alamat !== ''
                        ? $alamat
                        : 'Belum dicantumkan';

                    $foto = trim($dokter['foto'] ?? '');
                    $fileFoto = basename($foto);
                    $pathFoto = 'assets/images/dokter/' . $fileFoto;
                    $pathFotoFisik = __DIR__ . '/../../assets/images/dokter/' . $fileFoto;
                    $fotoTersedia = $fileFoto !== '' && is_file($pathFotoFisik);
                    ?>

                    <article class="dokter-card">

                        <!-- FOTO DOKTER -->
                        <div class="dokter-photo-wrapper">
                            <?php if ($fotoTersedia): ?>
                                <img
                                    src="<?= htmlspecialchars($pathFoto, ENT_QUOTES, 'UTF-8'); ?>"
                                    alt="<?= htmlspecialchars($namaDokter, ENT_QUOTES, 'UTF-8'); ?>"
                                    class="dokter-photo"
                                    loading="lazy">
                            <?php else: ?>
                                <div class="dokter-photo-placeholder">
                                    <i class="fas fa-user-md"></i>
                                </div>
                            <?php endif; ?>

                            <span class="dokter-photo-badge">
                                <i class="fas fa-stethoscope"></i>
                                Tenaga Medis
                            </span>
                        </div>

                        <!-- INFORMASI DOKTER -->
                        <div class="dokter-card-body">
                            <h3 class="dokter-name"
                                title="<?= htmlspecialchars($namaDokter, ENT_QUOTES, 'UTF-8'); ?>">
                                <?= htmlspecialchars($namaDokter, ENT_QUOTES, 'UTF-8'); ?>
                            </h3>

                            <div class="dokter-specialist"
                                 title="<?= htmlspecialchars($spesialisTampil, ENT_QUOTES, 'UTF-8'); ?>">
                                <i class="fas fa-certificate"></i>
                                <span><?= htmlspecialchars($spesialisTampil, ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>

                            <div class="dokter-meta">
                                <div class="dokter-meta-item"
                                     title="Nomor HP: <?= htmlspecialchars($nohpTampil, ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="fas fa-phone-alt"></i>
                                    <span><?= htmlspecialchars($nohpTampil, ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                                <div class="dokter-meta-item"
                                     title="Alamat: <?= htmlspecialchars($alamatTampil, ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?= htmlspecialchars($alamatTampil, ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                            </div>

                            <div class="dokter-schedule">
                                <i class="fas fa-calendar-alt"></i>
                                <?= number_format($totalJadwal, 0, ',', '.'); ?> jadwal praktik
                            </div>

                            <a
                                href="index.php?page=detildokter&amp;id=<?= $idDokter; ?>"
                                class="dokter-detail-button">
                                <i class="fas fa-eye"></i>
                                Lihat Detail
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="dokter-empty">
                    <i class="fas fa-user-md"></i>
                    <h5>Belum Ada Data Dokter</h5>
                    <p>
                        Saat ini belum ada dokter yang tercatat dalam direktori.
                        Silakan kunjungi kembali nanti.
                    </p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>
