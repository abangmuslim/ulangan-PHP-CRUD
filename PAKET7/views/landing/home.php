
<?php
/*
|--------------------------------------------------------------------------
| HOMEPAGE - RUMAH SAKIT AHMADI
| PHP Native + MySQLi Prosedural + AdminLTE 3.2
|--------------------------------------------------------------------------
*/

$folderSlider = __DIR__ . '/../../assets/images/slider/';
$urlSlider = 'assets/images/slider/';
$daftarSlider = [];

if (is_dir($folderSlider)) {
    $fileSlider = glob($folderSlider . '*');
    if ($fileSlider !== false) {
        foreach ($fileSlider as $file) {
            if (is_file($file) && preg_match('/\.(jpg|jpeg|png|webp|avif)$/i', $file)) {
                $daftarSlider[] = $urlSlider . basename($file);
            }
        }
    }
}
sort($daftarSlider);

$hitungStatistikHome = function ($tabel) use ($koneksi) {
    $tabelDiizinkan = ['dokter', 'pasien', 'obat', 'jadwaldokter', 'antrian'];
    if (!in_array($tabel, $tabelDiizinkan, true)) {
        return 0;
    }

    $hasil = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM `$tabel`");
    if (!$hasil) {
        return 0;
    }

    $data = mysqli_fetch_assoc($hasil);
    return (int) ($data['total'] ?? 0);
};

$totalDokter = $hitungStatistikHome('dokter');
$totalPasien = $hitungStatistikHome('pasien');
$totalObat = $hitungStatistikHome('obat');
$totalJadwal = $hitungStatistikHome('jadwaldokter');
$totalAntrian = $hitungStatistikHome('antrian');

$queryDokterHome = "SELECT d.iddokter, d.namadokter, d.spesialis,
    COUNT(j.idjadwal) AS jumlahjadwal
    FROM dokter d
    LEFT JOIN jadwaldokter j ON j.iddokter = d.iddokter
    GROUP BY d.iddokter, d.namadokter, d.spesialis
    ORDER BY d.iddokter DESC
    LIMIT 8";

$resultDokterHome = mysqli_query($koneksi, $queryDokterHome);

function angkaHome($nilai) {
    return number_format((int) $nilai, 0, ',', '.');
}
?>

<style>
.home-modern{--hp:#087f70;--hpd:#075c52;--ha:#b9f3d4;--ht:#17352f;--hm:#687e77;--hb:#e1eee8;--hs:#f4faf7;color:var(--ht);overflow:hidden}
.home-modern,.home-modern *{box-sizing:border-box}
.home-modern a{text-decoration:none}
.home-modern a:focus-visible{outline:2px solid var(--hp);outline-offset:3px}
.home-modern .section-container{width:92%;max-width:1320px;margin:auto}
.home-modern .section-heading{display:flex;align-items:end;justify-content:space-between;gap:15px;margin-bottom:22px}
.home-modern .section-heading h2{margin:0;color:var(--ht);font-size:clamp(22px,2.5vw,30px);font-weight:800;letter-spacing:-.5px}
.home-modern .section-heading p{margin:7px 0 0;color:var(--hm);font-size:13px;line-height:1.7}
.home-modern .section-heading h2 i{margin-right:8px;color:var(--hp)}
.home-modern .section-link{flex-shrink:0;color:var(--hp);font-size:12px;font-weight:700}
.home-modern .section-link:hover{color:var(--hpd)}

/* HERO */
.home-modern .hospital-hero{position:relative;overflow:hidden;background:#123b35}
.home-modern .hospital-hero .carousel,
.home-modern .hospital-hero .carousel-inner,
.home-modern .hospital-hero .carousel-item{height:min(75vh,710px);min-height:490px}
.home-modern .hospital-hero .carousel-item{position:relative;background:radial-gradient(circle at 80% 20%,#287e71,transparent 42%),linear-gradient(120deg,#102f2b,#087f70)}
.home-modern .hero-image{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;opacity:.72}
.home-modern .hero-overlay{position:absolute;inset:0;background:linear-gradient(90deg,rgba(6,35,31,.94),rgba(6,35,31,.72) 45%,rgba(6,35,31,.15)),linear-gradient(0deg,rgba(6,35,31,.35),transparent 55%)}
.home-modern .hero-content{position:relative;z-index:2;display:flex;align-items:center;height:100%;padding-top:55px;padding-bottom:85px}
.home-modern .hero-copy{max-width:720px;color:#fff}
.home-modern .hero-eyebrow{display:inline-flex;align-items:center;gap:9px;margin-bottom:22px;padding:9px 15px;border:1px solid rgba(255,255,255,.25);border-radius:50px;background:rgba(255,255,255,.09);color:#e0fff1;font-size:11px;font-weight:700;letter-spacing:1.3px;text-transform:uppercase;backdrop-filter:blur(10px)}
.home-modern .hero-eyebrow i{color:var(--ha)}
.home-modern .hero-title{margin:0 0 22px;color:#fff;font-size:clamp(38px,5.2vw,68px);font-weight:800;line-height:1.09;letter-spacing:-1.7px}
.home-modern .hero-title span{color:var(--ha)}
.home-modern .hero-description{max-width:590px;margin-bottom:30px;color:rgba(255,255,255,.84);font-size:clamp(14px,1.5vw,18px);line-height:1.9}
.home-modern .hero-actions{display:flex;flex-wrap:wrap;gap:12px}
.home-modern .hero-btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;min-height:48px;padding:12px 21px;border:1px solid transparent;border-radius:9px;font-size:13px;font-weight:700;transition:all .2s}
.home-modern .hero-btn-primary{background:var(--ha);color:#123e32}
.home-modern .hero-btn-primary:hover{transform:translateY(-2px);background:#fff;color:#123e32}
.home-modern .hero-btn-outline{border-color:rgba(255,255,255,.45);background:rgba(255,255,255,.07);color:#fff}
.home-modern .hero-btn-outline:hover{background:rgba(255,255,255,.17);color:#fff}
.home-modern .carousel-indicators{z-index:4;bottom:22px;align-items:center;margin-bottom:0}
.home-modern .carousel-indicators li{width:23px;height:4px;margin:0 4px;border:0;border-radius:10px;background:rgba(255,255,255,.55)}
.home-modern .carousel-indicators li.active{width:42px;background:var(--ha)}
.home-modern .carousel-control-prev,.home-modern .carousel-control-next{z-index:3;width:7%;opacity:.8}
.home-modern .carousel-control-prev-icon,.home-modern .carousel-control-next-icon{width:40px;height:40px;border:1px solid rgba(255,255,255,.3);border-radius:50%;background-color:rgba(0,0,0,.2);background-size:45%}

/* KEUNGGULAN */
.home-modern .hospital-highlights{padding:18px 0;border-bottom:1px solid var(--hb);background:#f7fbf9}
.home-modern .hospital-highlights .row{row-gap:12px}
.home-modern .highlight-card{display:flex;align-items:center;gap:12px;height:100%;min-height:84px;padding:15px;border:1px solid #dcebe3;border-radius:12px;background:#fff;box-shadow:0 4px 14px rgba(20,70,50,.05);transition:all .2s}
.home-modern .highlight-card:hover{transform:translateY(-2px);border-color:#a9d9c3;box-shadow:0 8px 20px rgba(20,70,50,.09)}
.home-modern .highlight-icon{display:flex;flex:0 0 43px;align-items:center;justify-content:center;width:43px;height:43px;border:1px solid #d2eee0;border-radius:12px;background:#e8f8ef;color:var(--hp);font-size:18px}
.home-modern .highlight-card strong{display:block;margin-bottom:4px;color:var(--ht);font-size:12px;font-weight:800}
.home-modern .highlight-card small{display:block;color:var(--hm);font-size:10px;line-height:1.6}

/* JADWAL & DOKTER */
.home-modern .hospital-doctors{padding:60px 0;background:#fff;border-bottom:1px solid #edf3ef}
.home-modern .doctor-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}
.home-modern .doctor-card{min-width:0;overflow:hidden;border:1px solid #e1eee7;border-radius:14px;background:#fff;transition:all .2s}
.home-modern .doctor-card:hover{transform:translateY(-3px);border-color:#b5ddc6;box-shadow:0 10px 25px rgba(16,80,55,.09)}
.home-modern .doctor-visual{display:flex;align-items:center;justify-content:center;min-height:130px;background:radial-gradient(circle at 50% 30%,#fff,#e9f8ef);color:#16836c;font-size:48px}
.home-modern .doctor-body{padding:15px}
.home-modern .doctor-name{display:-webkit-box;min-height:2.6em;margin:0 0 8px;overflow:hidden;color:var(--ht);font-size:14px;font-weight:800;line-height:1.35;-webkit-box-orient:vertical;-webkit-line-clamp:2}
.home-modern .doctor-specialty{display:inline-block;margin-bottom:10px;padding:5px 9px;border-radius:20px;background:#e8f7ee;color:#17664f;font-size:10px;font-weight:700}
.home-modern .doctor-schedule{display:flex;align-items:center;gap:6px;margin:0 0 12px;color:var(--hm);font-size:11px}
.home-modern .doctor-schedule i{color:var(--hp)}
.home-modern .doctor-button{display:flex;align-items:center;justify-content:center;gap:7px;width:100%;padding:9px;border-radius:7px;background:var(--hp);color:#fff;font-size:11px;font-weight:700}
.home-modern .doctor-button:hover{background:var(--hpd);color:#fff}
.home-modern .doctor-empty{grid-column:1/-1;padding:30px 15px;border:1px dashed #d6e7de;border-radius:12px;background:var(--hs);color:var(--hm);font-size:13px;text-align:center}

/* TENTANG */
.home-modern .hospital-about{padding:75px 0;background:radial-gradient(circle at 5% 20%,#eaf8ef,transparent 34%),#fff}
.home-modern .about-label{display:inline-block;margin-bottom:15px;color:var(--hp);font-size:11px;font-weight:800;letter-spacing:1.6px;text-transform:uppercase}
.home-modern .about-title{max-width:600px;margin:0 0 20px;color:var(--ht);font-size:clamp(29px,3.6vw,43px);font-weight:800;line-height:1.2;letter-spacing:-1px}
.home-modern .about-title span{color:var(--hp)}
.home-modern .about-description{max-width:590px;margin-bottom:23px;color:var(--hm);font-size:14px;line-height:1.95}
.home-modern .about-link{display:inline-flex;align-items:center;gap:9px;color:var(--hp);font-size:13px;font-weight:800}
.home-modern .about-link:hover{gap:14px;color:var(--hpd)}
.home-modern .about-visual{position:relative;padding:20px 0 20px 20px}
.home-modern .about-visual:before{position:absolute;inset:0;border-radius:24px;background:linear-gradient(135deg,#d8f3e3,#f4fbf6);content:"";transform:rotate(-3deg)}
.home-modern .about-card{position:relative;padding:30px;border:1px solid rgba(255,255,255,.9);border-radius:20px;background:rgba(255,255,255,.96);box-shadow:0 18px 55px rgba(18,67,45,.08)}
.home-modern .about-icon{display:flex;align-items:center;justify-content:center;width:58px;height:58px;margin-bottom:22px;border-radius:16px;background:var(--hp);color:#fff;font-size:24px}
.home-modern .about-card h3{margin-bottom:12px;color:var(--ht);font-size:22px;font-weight:800}
.home-modern .about-card p{margin:0;color:var(--hm);font-size:13px;line-height:1.9}
.home-modern .about-pill{display:inline-flex;align-items:center;gap:8px;margin-top:20px;padding:9px 12px;border-radius:8px;background:#eaf8ef;color:var(--hpd);font-size:11px;font-weight:700}

/* STATISTIK */
.home-modern .hospital-stats{padding:55px 0;background:#f6faf8;border-top:1px solid #edf3ef}
.home-modern .stats-heading{max-width:650px;margin:0 auto 28px}
.home-modern .stats-eyebrow{display:inline-block;margin-bottom:9px;color:var(--hp);font-size:10px;font-weight:800;letter-spacing:1.6px;text-transform:uppercase}
.home-modern .stats-title{margin:0 0 10px;color:var(--ht);font-size:clamp(25px,3vw,34px);font-weight:800}
.home-modern .stats-subtitle{margin:0;color:var(--hm);font-size:13px;line-height:1.8}
.home-modern .stat-grid{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:12px}
.home-modern .stat-card{display:flex;align-items:center;gap:11px;min-width:0;height:100%;padding:14px;border:1px solid #e3eee7;border-radius:12px;background:#fff;box-shadow:0 4px 13px rgba(24,70,45,.035);transition:all .2s}
.home-modern .stat-card:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(24,70,45,.08)}
.home-modern .stat-icon{display:flex;flex:0 0 38px;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;background:#e3f7eb;color:#087f70;font-size:16px}
.home-modern .stat-icon.patients{background:#e5efff;color:#2563eb}
.home-modern .stat-icon.medicines{background:#fff1d6;color:#c77708}
.home-modern .stat-icon.schedules{background:#f0e8ff;color:#7c3aed}
.home-modern .stat-icon.queue{background:#e0f7fc;color:#0891b2}
.home-modern .stat-content{display:flex;flex:1;flex-direction:column;min-width:0}
.home-modern .stat-number{color:#203d34;font-size:22px;font-weight:800;line-height:1.3;overflow-wrap:anywhere}
.home-modern .stat-label{margin-top:3px;color:#778a81;font-size:10px;line-height:1.5}

/* RESPONSIVE */
@media(max-width:1199.98px){.home-modern .stat-grid{grid-template-columns:repeat(3,minmax(0,1fr))}.home-modern .doctor-grid{grid-template-columns:repeat(4,minmax(0,1fr))}}
@media(max-width:991.98px){.home-modern .hospital-hero .carousel,.home-modern .hospital-hero .carousel-inner,.home-modern .hospital-hero .carousel-item{height:65vh;min-height:470px}.home-modern .hero-overlay{background:linear-gradient(90deg,rgba(6,35,31,.92),rgba(6,35,31,.43))}.home-modern .doctor-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.home-modern .hospital-about{padding:60px 0}.home-modern .about-visual{margin-top:25px}}
@media(max-width:767.98px){.home-modern .section-container{width:94%}.home-modern .hospital-doctors{padding:42px 0}.home-modern .section-heading{align-items:flex-start}.home-modern .section-heading h2{font-size:23px}.home-modern .section-heading p{font-size:12px}.home-modern .section-link{font-size:11px}.home-modern .stat-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:8px}.home-modern .stat-card{gap:8px;padding:10px}.home-modern .stat-icon{flex-basis:32px;width:32px;height:32px;font-size:14px}.home-modern .stat-number{font-size:19px}.home-modern .stat-label{font-size:10px}.home-modern .hospital-stats{padding:42px 0}}
@media(max-width:575.98px){.home-modern .hospital-hero .carousel,.home-modern .hospital-hero .carousel-inner,.home-modern .hospital-hero .carousel-item{height:75vh;min-height:520px;max-height:660px}.home-modern .hero-content{padding-top:40px;padding-bottom:70px}.home-modern .hero-title{font-size:38px;letter-spacing:-1px}.home-modern .hero-description{font-size:13px;line-height:1.85}.home-modern .hero-btn{min-height:44px;padding:10px 13px;font-size:11px}.home-modern .carousel-control-prev,.home-modern .carousel-control-next{display:none}.home-modern .hospital-highlights{padding:12px 0}.home-modern .highlight-card{gap:9px;min-height:70px;padding:10px}.home-modern .highlight-icon{flex-basis:35px;width:35px;height:35px;font-size:15px}.home-modern .highlight-card strong{font-size:11px}.home-modern .highlight-card small{font-size:9px}.home-modern .doctor-grid{gap:9px}.home-modern .doctor-visual{min-height:100px;font-size:38px}.home-modern .doctor-body{padding:11px}.home-modern .doctor-name{font-size:12px}.home-modern .doctor-specialty{font-size:9px}.home-modern .doctor-schedule{font-size:10px}.home-modern .doctor-button{font-size:10px}.home-modern .about-title{font-size:30px}.home-modern .about-description{font-size:13px}.home-modern .about-visual{padding-left:12px}.home-modern .about-card{padding:23px}.home-modern .section-heading h2{font-size:20px}.home-modern .section-heading p{font-size:11px}.home-modern .section-link{max-width:100px;text-align:right}.home-modern .stats-title{font-size:25px}}
@media(max-width:359.98px){.home-modern .hero-title{font-size:33px}.home-modern .hero-actions{flex-direction:column}.home-modern .hero-btn{width:100%}.home-modern .stat-grid{grid-template-columns:1fr}}
@media(prefers-reduced-motion:reduce){.home-modern *,.home-modern *:before,.home-modern *:after{scroll-behavior:auto!important;animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important}}
</style>

<div class="home-modern">

    <!-- HERO SLIDER -->
    <section class="hospital-hero" aria-label="Selamat datang di Rumah Sakit Ahmadi">
        <div id="hospitalHeroCarousel" class="carousel slide" data-ride="carousel" data-interval="5500" data-pause="hover" data-touch="true">
            <?php if (count($daftarSlider) > 1): ?>
                <ol class="carousel-indicators">
                    <?php foreach ($daftarSlider as $i => $gambar): ?>
                        <li data-target="#hospitalHeroCarousel" data-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>" aria-label="Slide <?= $i + 1 ?>"></li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>

            <div class="carousel-inner">
                <?php if ($daftarSlider): ?>
                    <?php foreach ($daftarSlider as $i => $gambar): ?>
                        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                            <img class="hero-image" src="<?= htmlspecialchars($gambar, ENT_QUOTES, 'UTF-8') ?>" alt="Fasilitas Rumah Sakit Ahmadi" <?= $i === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?>>
                            <div class="hero-overlay"></div>
                            <div class="container hero-content">
                                <div class="hero-copy">
                                    <div class="hero-eyebrow"><i class="fas fa-heartbeat"></i> Rumah Sakit Ahmadi</div>
                                    <h1 class="hero-title">Kesehatan Anda,<br><span>Prioritas Kami.</span></h1>
                                    <p class="hero-description">Kami hadir untuk membantu Anda mendapatkan informasi dokter, jadwal praktik, dan layanan kesehatan dengan mudah melalui Sistem Informasi Rumah Sakit Ahmadi.</p>
                                    <div class="hero-actions">
                                        <a href="index.php?page=daftardokter" class="hero-btn hero-btn-primary">Temukan Dokter <i class="fas fa-arrow-right"></i></a>
                                        <a href="index.php?page=layanan" class="hero-btn hero-btn-outline"><i class="fas fa-hospital"></i> Lihat Layanan</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="carousel-item active">
                        <div class="hero-overlay"></div>
                        <div class="container hero-content">
                            <div class="hero-copy">
                                <div class="hero-eyebrow"><i class="fas fa-heartbeat"></i> Rumah Sakit Ahmadi</div>
                                <h1 class="hero-title">Kesehatan Anda,<br><span>Prioritas Kami.</span></h1>
                                <p class="hero-description">Temukan informasi dokter, jadwal praktik, dan layanan kesehatan melalui Sistem Informasi Rumah Sakit Ahmadi.</p>
                                <div class="hero-actions">
                                    <a href="index.php?page=daftardokter" class="hero-btn hero-btn-primary">Temukan Dokter <i class="fas fa-arrow-right"></i></a>
                                    <a href="index.php?page=layanan" class="hero-btn hero-btn-outline"><i class="fas fa-hospital"></i> Lihat Layanan</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (count($daftarSlider) > 1): ?>
                <a class="carousel-control-prev" href="#hospitalHeroCarousel" role="button" data-slide="prev" aria-label="Slide sebelumnya"><span class="carousel-control-prev-icon" aria-hidden="true"></span></a>
                <a class="carousel-control-next" href="#hospitalHeroCarousel" role="button" data-slide="next" aria-label="Slide berikutnya"><span class="carousel-control-next-icon" aria-hidden="true"></span></a>
            <?php endif; ?>
        </div>
    </section>

    <!-- KEUNGGULAN -->
    <section class="hospital-highlights" aria-label="Keunggulan layanan">
        <div class="container">
            <div class="row">
                <div class="col-6 col-lg-3">
                    <div class="highlight-card"><div class="highlight-icon"><i class="fas fa-user-md"></i></div><div><strong>Informasi Dokter</strong><small>Kenali dokter dan spesialisasi</small></div></div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="highlight-card"><div class="highlight-icon"><i class="fas fa-calendar-alt"></i></div><div><strong>Jadwal Praktik</strong><small>Informasi hari dan jam praktik</small></div></div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="highlight-card"><div class="highlight-icon"><i class="fas fa-pills"></i></div><div><strong>Informasi Obat</strong><small>Telusuri katalog obat</small></div></div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="highlight-card"><div class="highlight-icon"><i class="fas fa-laptop-medical"></i></div><div><strong>Layanan Digital</strong><small>Akses informasi lebih praktis</small></div></div>
                </div>
            </div>
        </div>
    </section>

    <!-- DOKTER -->
    <section class="hospital-doctors" aria-label="Dokter Rumah Sakit Ahmadi">
        <div class="section-container">
            <div class="section-heading">
                <div>
                    <h2><i class="fas fa-user-md"></i> Kenali Dokter Kami</h2>
                    <p>Informasi dokter dan jadwal praktik yang tersedia.</p>
                </div>
                <a href="index.php?page=daftardokter" class="section-link">Semua Dokter <i class="fas fa-arrow-right ml-1"></i></a>
            </div>

            <div class="doctor-grid">
                <?php if ($resultDokterHome && mysqli_num_rows($resultDokterHome) > 0): ?>
                    <?php while ($dokterHome = mysqli_fetch_assoc($resultDokterHome)): ?>
                        <?php
                        $idDokterHome = (int) $dokterHome['iddokter'];
                        $namaDokterHome = $dokterHome['namadokter'] ?? 'Dokter';
                        $spesialisHome = trim($dokterHome['spesialis'] ?? '');
                        ?>
                        <article class="doctor-card">
                            <div class="doctor-visual"><i class="fas fa-user-md"></i></div>
                            <div class="doctor-body">
                                <h3 class="doctor-name"><?= htmlspecialchars($namaDokterHome, ENT_QUOTES, 'UTF-8') ?></h3>
                                <span class="doctor-specialty"><?= htmlspecialchars($spesialisHome !== '' ? $spesialisHome : 'Dokter Umum', ENT_QUOTES, 'UTF-8') ?></span>
                                <p class="doctor-schedule"><i class="far fa-calendar-check"></i> <?= angkaHome($dokterHome['jumlahjadwal']) ?> jadwal praktik</p>
                                <a href="index.php?page=detildokter&id=<?= $idDokterHome ?>" class="doctor-button">Lihat Detail <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="doctor-empty"><i class="fas fa-user-md mr-1"></i> Informasi dokter belum tersedia.</div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- TENTANG -->
    <section class="hospital-about">
        <div class="section-container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="about-label">Mengenal Rumah Sakit Kami</span>
                    <h2 class="about-title">Pelayanan kesehatan dengan <span>informasi yang mudah diakses.</span></h2>
                    <p class="about-description">Rumah Sakit Ahmadi menghadirkan sistem informasi untuk membantu masyarakat mengenal dokter, melihat jadwal praktik, serta menemukan informasi layanan dan obat yang tersedia. Kami berupaya menyajikan informasi secara jelas, praktis, dan mudah dijangkau.</p>
                    <a href="index.php?page=tentang" class="about-link">Tentang Rumah Sakit Ahmadi <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="col-lg-5">
                    <div class="about-visual">
                        <div class="about-card">
                            <div class="about-icon"><i class="fas fa-heartbeat"></i></div>
                            <h3>Informasi dalam Satu Sistem</h3>
                            <p>Jelajahi informasi dokter, jadwal praktik, obat, dan layanan melalui satu halaman yang mudah digunakan.</p>
                            <div class="about-pill"><i class="fas fa-check-circle"></i> Praktis dan informatif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- STATISTIK -->
    <section class="hospital-stats">
        <div class="section-container">
            <div class="stats-heading text-center">
                <span class="stats-eyebrow">Rumah Sakit dalam Angka</span>
                <h2 class="stats-title">Informasi Layanan Kami</h2>
                <p class="stats-subtitle">Ringkasan data yang tersedia pada Sistem Informasi Rumah Sakit Ahmadi.</p>
            </div>

            <div class="stat-grid">
                <div class="stat-card"><div class="stat-icon"><i class="fas fa-user-md"></i></div><div class="stat-content"><span class="stat-number"><?= angkaHome($totalDokter) ?></span><span class="stat-label">Dokter terdaftar</span></div></div>
                <div class="stat-card"><div class="stat-icon patients"><i class="fas fa-users"></i></div><div class="stat-content"><span class="stat-number"><?= angkaHome($totalPasien) ?></span><span class="stat-label">Pasien terdaftar</span></div></div>
                <div class="stat-card"><div class="stat-icon medicines"><i class="fas fa-pills"></i></div><div class="stat-content"><span class="stat-number"><?= angkaHome($totalObat) ?></span><span class="stat-label">Jenis obat terdata</span></div></div>
                <div class="stat-card"><div class="stat-icon schedules"><i class="fas fa-calendar-alt"></i></div><div class="stat-content"><span class="stat-number"><?= angkaHome($totalJadwal) ?></span><span class="stat-label">Jadwal praktik</span></div></div>
                <div class="stat-card"><div class="stat-icon queue"><i class="fas fa-clipboard-list"></i></div><div class="stat-content"><span class="stat-number"><?= angkaHome($totalAntrian) ?></span><span class="stat-label">Data antrean</span></div></div>
            </div>
        </div>
    </section>

</div>
