<?php
/*
|--------------------------------------------------------------------------
| HOMEPAGE - KLINIK AHMADI
| PHP Native + MySQLi + Bootstrap 5
|--------------------------------------------------------------------------
| Database : klinik7
| Tabel    : dokter, pasien, merk, kategori, obat, jadwaldokter
|--------------------------------------------------------------------------
*/
// =====================================================
// 1. GAMBAR SLIDER HOMEPAGE
// =====================================================
$folderSlider = __DIR__ . '/../../assets/images/slider/';
$urlSlider = 'assets/images/slider/';
$daftarSlider = [];
if (is_dir($folderSlider)) {
    $fileSlider = glob($folderSlider . '*');
    if ($fileSlider !== false) {
        foreach ($fileSlider as $file) {
            if (
                is_file($file) &&
                preg_match('/\.(jpg|jpeg|png|webp|avif)$/i', $file)
            ) {
                $daftarSlider[] = $urlSlider . basename($file);
            }
        }
    }
}
sort($daftarSlider);
// =====================================================
// 2. FUNGSI PENDUKUNG HOMEPAGE
// =====================================================
// Mengamankan output HTML
$homeEscape = function ($nilai) {
    return htmlspecialchars(
        (string) $nilai,
        ENT_QUOTES,
        'UTF-8'
    );
};
// Menghitung jumlah data tabel
$hitungStatistikHome = function ($tabel) use ($koneksi) {
    $tabelDiizinkan = [
        'dokter',
        'pasien',
        'obat',
        'kategori',
        'merk',
        'jadwaldokter'
    ];
    if (!in_array($tabel, $tabelDiizinkan, true)) {
        return 0;
    }
    $query = mysqli_query(
        $koneksi,
        "SELECT COUNT(*) AS total FROM `$tabel`"
    );
    if (!$query) {
        return 0;
    }
    $data = mysqli_fetch_assoc($query);
    return (int) ($data['total'] ?? 0);
};
// =====================================================
// 3. STATISTIK KLINIK
// =====================================================
$totalDokter = $hitungStatistikHome('dokter');
$totalPasien = $hitungStatistikHome('pasien');
$totalObat = $hitungStatistikHome('obat');
$totalKategori = $hitungStatistikHome('kategori');
$totalMerk = $hitungStatistikHome('merk');
$totalJadwal = $hitungStatistikHome('jadwaldokter');
// =====================================================
// 4. DOKTER DAN JADWAL PRAKTIK
// =====================================================
$queryDokterHome = "
    SELECT
        d.iddokter,
        d.namadokter,
        d.spesialis,
        d.foto,
        GROUP_CONCAT(
            CONCAT(
                j.haripraktik,
                ' | ',
                TIME_FORMAT(j.jam_mulai, '%H:%i'),
                ' - ',
                TIME_FORMAT(j.jam_selesai, '%H:%i')
            )
            ORDER BY j.idjadwal ASC
            SEPARATOR ';;'
        ) AS daftarjadwal
    FROM dokter d
    LEFT JOIN jadwaldokter j
        ON d.iddokter = j.iddokter
    GROUP BY
        d.iddokter,
        d.namadokter,
        d.spesialis,
        d.foto
    ORDER BY d.iddokter DESC
    LIMIT 8
";
$resultDokterHome = mysqli_query($koneksi, $queryDokterHome);
// =====================================================
// 5. KATEGORI OBAT DAN JUMLAH OBAT
// =====================================================
$queryKategoriHome = "
    SELECT
        k.idkategori,
        k.namakategori,
        COUNT(o.idobat) AS jumlahobat
    FROM kategori k
    LEFT JOIN obat o
        ON k.idkategori = o.idkategori
    GROUP BY
        k.idkategori,
        k.namakategori
    ORDER BY k.idkategori DESC
    LIMIT 10
";
$resultKategoriHome = mysqli_query($koneksi, $queryKategoriHome);
// =====================================================
// 6. DAFTAR OBAT TERBARU
// =====================================================
$queryObatHome = "
    SELECT
        o.idobat,
        o.namaobat,
        o.harga,
        o.stok,
        o.foto,
        m.namamerk,
        k.namakategori
    FROM obat o
    LEFT JOIN merk m
        ON o.idmerk = m.idmerk
    LEFT JOIN kategori k
        ON o.idkategori = k.idkategori
    ORDER BY o.idobat DESC
    LIMIT 8
";
$resultObatHome = mysqli_query($koneksi, $queryObatHome);
?>
<style>
/* ========================================================= HOMEPAGE KLINIK AHMADI ========================================================= */
.home-modern{--home-primary: #087f70;--home-primary-dark: #075c52;--home-accent: #b9f3d4;--home-text: #18342e;--home-muted: #71817c;--home-border: #e3eee9;--home-soft: #f4faf7;color: var(--home-text);overflow: hidden}
.home-modern, .home-modern *{box-sizing: border-box}
.home-modern a{text-decoration: none}
.home-modern img{max-width: 100%}
.home-modern a:focus-visible{outline: 2px solid var(--home-primary);outline-offset: 3px}
/* ========================================================= 1. HERO SLIDER ========================================================= */
.home-hero{position: relative;overflow: hidden;background: #103d35}
.home-hero .carousel, .home-hero .carousel-inner, .home-hero .carousel-item{height: min(76vh, 720px);min-height: 510px}
.home-hero .carousel-item{position: relative;background: radial-gradient(circle at 75% 30%, #247b68, transparent 40%), linear-gradient(120deg, #103d35, #087f70)}
.home-hero .hero-image{position: absolute;inset: 0;width: 100%;height: 100%;object-fit: cover;object-position: center;opacity: .72}
.home-hero .hero-overlay{position: absolute;inset: 0;background: linear-gradient( 90deg, rgba(5, 35, 30, .94) 0%, rgba(5, 35, 30, .78) 43%, rgba(5, 35, 30, .18) 100% ), linear-gradient( 0deg, rgba(5, 35, 30, .42), transparent 55% )}
.home-hero .hero-content{position: relative;z-index: 2;display: flex;align-items: center;height: 100%;padding-top: 60px;padding-bottom: 90px}
.home-hero .hero-copy{max-width: 690px;color: #fff}
.home-hero .hero-eyebrow{display: inline-flex;align-items: center;gap: 9px;margin-bottom: 22px;padding: 9px 15px;border: 1px solid rgba(255,255,255,.24);border-radius: 50px;background: rgba(255,255,255,.09);color: #e4f9ec;font-size: 12px;font-weight: 700;letter-spacing: 1.4px;text-transform: uppercase;backdrop-filter: blur(10px)}
.home-hero .hero-eyebrow i{color: var(--home-accent)}
.home-hero .hero-title{margin: 0 0 22px;color: #fff;font-size: clamp(36px, 5.3vw, 67px);font-weight: 800;line-height: 1.08;letter-spacing: -1.8px}
.home-hero .hero-title span{color: var(--home-accent)}
.home-hero .hero-description{max-width: 560px;margin-bottom: 30px;color: rgba(255,255,255,.84);font-size: clamp(15px, 1.5vw, 18px);line-height: 1.85}
.home-hero .hero-actions{display: flex;flex-wrap: wrap;align-items: center;gap: 12px}
.home-hero .hero-btn{display: inline-flex;align-items: center;justify-content: center;gap: 10px;min-height: 49px;padding: 13px 22px;border: 1px solid transparent;border-radius: 9px;font-size: 14px;font-weight: 700;transition: .2s ease}
.home-hero .hero-btn-primary{background: var(--home-accent);color: #173d35}
.home-hero .hero-btn-primary:hover{transform: translateY(-2px);background: #fff;color: #173d35}
.home-hero .hero-btn-outline{border-color: rgba(255,255,255,.45);background: rgba(255,255,255,.06);color: #fff}
.home-hero .hero-btn-outline:hover{border-color: #fff;background: rgba(255,255,255,.14);color: #fff}
.home-hero .carousel-indicators{z-index: 4;bottom: 24px;align-items: center;margin-bottom: 0}
.home-hero .carousel-indicators [data-bs-target]{width: 24px;height: 4px;margin: 0 4px;border: 0;border-radius: 10px;background: rgba(255,255,255,.55);transition: width .25s ease}
.home-hero .carousel-indicators .active{width: 44px;background: var(--home-accent)}
.home-hero .carousel-control-prev, .home-hero .carousel-control-next{z-index: 3;width: 7%;opacity: .8}
.home-hero .carousel-control-prev-icon, .home-hero .carousel-control-next-icon{width: 42px;height: 42px;border: 1px solid rgba(255,255,255,.3);border-radius: 50%;background-color: rgba(0,0,0,.2);background-size: 45%;backdrop-filter: blur(8px)}
/* ========================================================= 2. HIGHLIGHT LAYANAN ========================================================= */
.home-highlights{position: relative;z-index: 5;padding: 18px 0;border-bottom: 1px solid var(--home-border);background: #f8fbf9}
.home-highlight{display: flex;align-items: center;gap: 13px;height: 100%;min-height: 88px;padding: 17px 15px;border: 1px solid #dce9e0;border-radius: 12px;background: #fff;box-shadow: 0 4px 14px rgba(24,70,45,.06);transition: .2s ease}
.home-highlight:hover{transform: translateY(-2px);border-color: #a8d5b8;box-shadow: 0 8px 20px rgba(24,70,45,.10)}
.home-highlight-icon{display: flex;flex: 0 0 45px;align-items: center;justify-content: center;width: 45px;height: 45px;border: 1px solid #ccebd8;border-radius: 12px;background: #e7f7ee;color: var(--home-primary);font-size: 19px}
.home-highlight strong{display: block;margin-bottom: 4px;color: var(--home-text);font-size: 13px;font-weight: 750;line-height: 1.4}
.home-highlight small{display: block;color: var(--home-muted);font-size: 11px;line-height: 1.6}
/* ========================================================= 3. JUDUL SECTION ========================================================= */
.home-section-heading{max-width: 700px;margin: 0 auto 35px;text-align: center}
.home-section-eyebrow{display: inline-block;margin-bottom: 10px;color: var(--home-primary);font-size: 11px;font-weight: 800;letter-spacing: 1.7px;text-transform: uppercase}
.home-section-title{margin: 0 0 12px;color: var(--home-text);font-size: clamp(26px, 3.5vw, 37px);font-weight: 800;line-height: 1.25}
.home-section-description{margin: 0;color: var(--home-muted);font-size: 14px;line-height: 1.8}
/* ========================================================= 4. DOKTER UNGGULAN ========================================================= */
.home-doctor-section{padding: 80px 0;background: #fff}
.home-doctor-card{height: 100%;overflow: hidden;border: 1px solid #e8efeb;border-radius: 13px;background: #fff;box-shadow: 0 5px 18px rgba(20,60,45,.05);transition: .2s ease}
.home-doctor-card:hover{transform: translateY(-4px);border-color: #b9ddca;box-shadow: 0 12px 28px rgba(20,60,45,.10)}
.home-doctor-photo-wrap{display: flex;align-items: center;justify-content: center;height: 205px;overflow: hidden;background: linear-gradient(135deg, #e6f7ed, #f5fbf7)}
.home-doctor-photo{width: 100%;height: 100%;object-fit: cover;object-position: center top}
.home-doctor-placeholder{display: flex;align-items: center;justify-content: center;width: 100%;height: 100%;color: var(--home-primary);font-size: 65px}
.home-doctor-body{padding: 20px}
.home-doctor-name{margin: 0 0 7px;color: var(--home-text);font-size: 16px;font-weight: 800;line-height: 1.4}
.home-doctor-specialist{display: inline-block;margin-bottom: 13px;padding: 5px 10px;border-radius: 30px;background: #e8f7ef;color: var(--home-primary-dark);font-size: 11px;font-weight: 700}
.home-doctor-schedule{min-height: 54px;margin-bottom: 15px;color: var(--home-muted);font-size: 12px;line-height: 1.8}
.home-doctor-schedule i{margin-right: 5px;color: var(--home-primary)}
.home-doctor-button{display: flex;align-items: center;justify-content: center;gap: 8px;padding: 9px 12px;border: 1px solid var(--home-primary);border-radius: 7px;color: var(--home-primary);font-size: 12px;font-weight: 700;transition: .2s ease}
.home-doctor-button:hover{background: var(--home-primary);color: #fff}
.home-empty{padding: 30px 15px;border: 1px dashed #cbded3;border-radius: 10px;background: #f8fbf9;color: var(--home-muted);text-align: center}
/* ========================================================= 5. KATEGORI OBAT ========================================================= */
.home-category-section{padding: 75px 0;border-top: 1px solid #edf2ef;border-bottom: 1px solid #edf2ef;background: #f8fbf9}
.home-category-grid{display: grid;grid-template-columns: repeat(5, minmax(0, 1fr));gap: 13px}
.home-category-card{display: flex;flex-direction: column;align-items: center;justify-content: center;min-width: 0;min-height: 155px;padding: 18px 12px;border: 1px solid #e3eee7;border-radius: 12px;background: #fff;color: var(--home-text);text-align: center;transition: .2s ease}
.home-category-card:hover{transform: translateY(-3px);border-color: #a9d7bb;box-shadow: 0 8px 20px rgba(20,60,45,.08);color: var(--home-primary)}
.home-category-icon{display: flex;align-items: center;justify-content: center;width: 48px;height: 48px;margin-bottom: 12px;border-radius: 13px;background: #e8f7ef;color: var(--home-primary);font-size: 21px}
.home-category-name{display: -webkit-box;min-height: 2.5em;margin: 0 0 5px;overflow: hidden;color: var(--home-text);font-size: 13px;font-weight: 750;line-height: 1.3;overflow-wrap: anywhere;-webkit-box-orient: vertical;-webkit-line-clamp: 2}
.home-category-count{margin: 0;color: var(--home-muted);font-size: 11px}
/* ========================================================= 6. KATALOG OBAT ========================================================= */
.home-medicine-section{padding: 80px 0;background: #fff}
.home-medicine-card{height: 100%;overflow: hidden;border: 1px solid #e7eee9;border-radius: 12px;background: #fff;transition: .2s ease}
.home-medicine-card:hover{transform: translateY(-3px);box-shadow: 0 10px 25px rgba(20,60,45,.09)}
.home-medicine-photo-wrap{position: relative;display: flex;align-items: center;justify-content: center;height: 190px;overflow: hidden;background: #f2f8f4}
.home-medicine-photo{width: 100%;height: 100%;object-fit: contain;padding: 12px}
.home-medicine-placeholder{color: #8ab9a0;font-size: 55px}
.home-medicine-body{padding: 16px}
.home-medicine-category{display: inline-block;margin-bottom: 8px;color: var(--home-primary);font-size: 10px;font-weight: 800;letter-spacing: .5px;text-transform: uppercase}
.home-medicine-name{min-height: 42px;margin: 0 0 6px;color: var(--home-text);font-size: 14px;font-weight: 800;line-height: 1.5}
.home-medicine-brand{margin-bottom: 10px;color: var(--home-muted);font-size: 11px}
.home-medicine-price{margin-bottom: 12px;color: var(--home-primary-dark);font-size: 16px;font-weight: 800}
.home-medicine-stock{display: inline-block;padding: 5px 9px;border-radius: 20px;background: #e8f7ef;color: #087f50;font-size: 10px;font-weight: 700}
.home-medicine-stock.empty{background: #fff0ed;color: #bd4b37}
.home-medicine-button{display: block;margin-top: 13px;padding: 9px;border-radius: 7px;background: var(--home-primary);color: #fff;font-size: 11px;font-weight: 700;text-align: center;transition: .2s ease}
.home-medicine-button:hover{background: var(--home-primary-dark);color: #fff}
/* ========================================================= 7. TENTANG KLINIK ========================================================= */
.home-about-section{padding: 85px 0;background: radial-gradient(circle at 5% 20%, #edf9f1 0, transparent 30%), #f8fbf9}
.home-about-label{display: inline-block;margin-bottom: 18px;color: var(--home-primary);font-size: 12px;font-weight: 800;letter-spacing: 1.8px;text-transform: uppercase}
.home-about-title{max-width: 600px;margin: 0 0 22px;color: var(--home-text);font-size: clamp(30px, 4vw, 45px);font-weight: 800;line-height: 1.2;letter-spacing: -1px}
.home-about-title span{color: var(--home-primary)}
.home-about-description{max-width: 590px;margin-bottom: 25px;color: var(--home-muted);font-size: 15px;line-height: 1.95}
.home-about-link{display: inline-flex;align-items: center;gap: 10px;color: var(--home-primary);font-size: 14px;font-weight: 750;transition: .2s ease}
.home-about-link:hover{gap: 15px;color: var(--home-primary-dark)}
.home-about-visual{position: relative;padding: 20px 0 20px 25px}
.home-about-visual::before{position: absolute;inset: 0;border-radius: 26px;background: linear-gradient(135deg, #dff5e7, #f4fbf6);content: "";transform: rotate(-4deg)}
.home-about-card{position: relative;padding: 34px;border: 1px solid rgba(255,255,255,.9);border-radius: 22px;background: rgba(255,255,255,.94);box-shadow: 0 20px 65px rgba(18,67,45,.09)}
.home-about-card-icon{display: flex;align-items: center;justify-content: center;width: 58px;height: 58px;margin-bottom: 24px;border-radius: 17px;background: var(--home-primary);color: #fff;font-size: 25px}
.home-about-card h3{margin-bottom: 12px;color: var(--home-text);font-size: 23px;font-weight: 800}
.home-about-card p{margin-bottom: 0;color: var(--home-muted);font-size: 14px;line-height: 1.9}
.home-about-pill{display: inline-flex;align-items: center;gap: 8px;margin-top: 23px;padding: 9px 12px;border-radius: 8px;background: #eff9f2;color: var(--home-primary-dark);font-size: 12px;font-weight: 700}
/* ========================================================= 8. STATISTIK KLINIK ========================================================= */
.home-stats-section{padding: 75px 0;background: #fff;border-top: 1px solid #edf2ef}
.home-stat-grid{display: grid;grid-template-columns: repeat(6, minmax(0, 1fr));gap: 13px}
.home-stat-card{display: flex;align-items: center;gap: 12px;min-width: 0;height: 100%;padding: 15px;border: 1px solid #e9eef4;border-radius: 12px;background: #fff;box-shadow: 0 4px 14px rgba(24,39,75,.035);transition: .2s ease}
.home-stat-card:hover{transform: translateY(-3px);box-shadow: 0 9px 20px rgba(30,45,70,.09)}
.home-stat-icon{display: flex;flex: 0 0 40px;align-items: center;justify-content: center;width: 40px;height: 40px;border-radius: 10px;font-size: 1rem}
.home-stat-icon.doctors{background: #dff9ed;color: #059669}
.home-stat-icon.patients{background: #e5efff;color: #2563eb}
.home-stat-icon.medicines{background: #f0e8ff;color: #7c3aed}
.home-stat-icon.categories{background: #fff1d6;color: #d97706}
.home-stat-icon.brands{background: #e0f7fc;color: #0891b2}
.home-stat-icon.schedules{background: #ffe8ed;color: #db4267}
.home-stat-content{display: flex;flex: 1;flex-direction: column;min-width: 0}
.home-stat-number{color: #1e293b;font-size: 1.4rem;font-weight: 800;line-height: 1.3;overflow-wrap: anywhere}
.home-stat-label{margin-top: 2px;color: #7b8798;font-size: .72rem;line-height: 1.4}
/* ========================================================= 9. CALL TO ACTION PENDAFTARAN ========================================================= */
.home-cta-section{padding: 70px 0;background: linear-gradient(120deg, #075c52, #087f70)}
.home-cta-content{max-width: 780px;margin: 0 auto;color: #fff;text-align: center}
.home-cta-icon{display: flex;align-items: center;justify-content: center;width: 65px;height: 65px;margin: 0 auto 20px;border: 1px solid rgba(255,255,255,.25);border-radius: 18px;background: rgba(255,255,255,.12);color: #d5fbe5;font-size: 27px}
.home-cta-content h2{margin-bottom: 15px;color: #fff;font-size: clamp(25px, 3.5vw, 36px);font-weight: 800}
.home-cta-content p{margin-bottom: 25px;color: rgba(255,255,255,.82);font-size: 14px;line-height: 1.8}
.home-cta-button{display: inline-flex;align-items: center;justify-content: center;gap: 9px;min-height: 46px;padding: 12px 22px;border-radius: 8px;background: var(--home-accent);color: #173d35;font-size: 13px;font-weight: 800;transition: .2s ease}
.home-cta-button:hover{transform: translateY(-2px);background: #fff;color: #173d35}
/* ========================================================= 10. RESPONSIVE ========================================================= */
@media (max-width: 1199.98px){.home-stat-grid{grid-template-columns: repeat(3, minmax(0, 1fr))}
.home-category-grid{grid-template-columns: repeat(4, minmax(0, 1fr))}}
@media (max-width: 991.98px){.home-hero .carousel, .home-hero .carousel-inner, .home-hero .carousel-item{height: 65vh;min-height: 490px}
.home-hero .hero-overlay{background: linear-gradient( 90deg, rgba(5,35,30,.91), rgba(5,35,30,.45) )}
.home-about-visual{margin-top: 35px}
.home-highlight{min-height: 78px;padding: 14px}
.home-doctor-section, .home-medicine-section, .home-category-section{padding: 65px 0}}
@media (max-width: 767.98px){.home-category-grid{grid-template-columns: repeat(3, minmax(0, 1fr));gap: 9px}
.home-stat-grid{grid-template-columns: repeat(2, minmax(0, 1fr));gap: 9px}
.home-stat-card{align-items: flex-start;gap: 9px;padding: 12px}
.home-stat-icon{flex-basis: 34px;width: 34px;height: 34px;font-size: .88rem}
.home-stat-number{font-size: 1.2rem}
.home-stat-label{font-size: .67rem}
.home-stats-section{padding: 55px 0}}
@media (max-width: 575.98px){.home-hero .carousel, .home-hero .carousel-inner, .home-hero .carousel-item{height: 76vh;min-height: 540px;max-height: 680px}
.home-hero .hero-content{padding-top: 45px;padding-bottom: 75px}
.home-hero .hero-title{font-size: 39px;letter-spacing: -1.2px}
.home-hero .hero-description{font-size: 14px;line-height: 1.8}
.home-hero .hero-btn{min-height: 45px;padding: 11px 15px;font-size: 12px}
.home-hero .carousel-control-prev, .home-hero .carousel-control-next{display: none}
.home-highlights{padding: 12px 0}
.home-highlight{gap: 10px;min-height: 72px;padding: 12px}
.home-highlight-icon{flex-basis: 38px;width: 38px;height: 38px;font-size: 16px}
.home-highlight strong{font-size: 12px}
.home-highlight small{font-size: 10px}
.home-doctor-section, .home-medicine-section, .home-category-section{padding: 50px 0}
.home-doctor-photo-wrap{height: 175px}
.home-doctor-body{padding: 14px}
.home-doctor-name{font-size: 14px}
.home-doctor-schedule{font-size: 11px}
.home-category-card{min-height: 135px;padding: 13px 7px}
.home-category-name{font-size: 11px}
.home-medicine-photo-wrap{height: 145px}
.home-medicine-body{padding: 12px}
.home-medicine-name{font-size: 12px}
.home-medicine-price{font-size: 14px}
.home-about-section{padding: 60px 0}
.home-about-title{font-size: 32px}
.home-about-description{font-size: 14px}
.home-about-visual{padding-left: 12px}
.home-about-card{padding: 25px}
.home-cta-section{padding: 55px 0}}
@media (max-width: 359.98px){.home-stat-grid{grid-template-columns: 1fr}
.home-hero .hero-title{font-size: 34px}
.home-hero .hero-actions{align-items: stretch;flex-direction: column}
.home-hero .hero-btn{width: 100%}}
@media (prefers-reduced-motion: reduce){.home-modern *, .home-modern *::before, .home-modern *::after{scroll-behavior: auto !important;animation-duration: .01ms !important;animation-iteration-count: 1 !important;transition-duration: .01ms !important}}
</style>
<div class="home-modern">
    <!-- =====================================================
         1. HERO SLIDER KLINIK
    ====================================================== -->
    <section class="home-hero" aria-label="Sambutan Klinik Ahmadi">
        <div
            id="homeHeroCarousel"
            class="carousel slide"
            data-bs-ride="carousel"
            data-bs-interval="5500"
            data-bs-pause="hover">
            <?php if (count($daftarSlider) > 1): ?>
                <div class="carousel-indicators">
                    <?php foreach ($daftarSlider as $index => $gambar): ?>
                        <button
                            type="button"
                            data-bs-target="#homeHeroCarousel"
                            data-bs-slide-to="<?= $index; ?>"
                            class="<?= $index === 0 ? 'active' : ''; ?>"
                            <?= $index === 0 ? 'aria-current="true"' : ''; ?>
                            aria-label="Slide <?= $index + 1; ?>">
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="carousel-inner">
                <?php if (!empty($daftarSlider)): ?>
                    <?php foreach ($daftarSlider as $index => $gambar): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : ''; ?>">
                            <img
                                class="hero-image"
                                src="<?= $homeEscape($gambar); ?>"
                                alt="Pelayanan Klinik Ahmadi"
                                <?= $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
                            <div class="hero-overlay"></div>
                            <div class="container hero-content">
                                <div class="hero-copy">
                                    <div class="hero-eyebrow">
                                        <i class="fas fa-heartbeat"></i>
                                        Selamat Datang di Klinik Ahmadi
                                    </div>
                                    <h1 class="hero-title">
                                        Kesehatan Anda,<br>
                                        <span>Prioritas Kami.</span>
                                    </h1>
                                    <p class="hero-description">
                                        Klinik Ahmadi hadir untuk memberikan
                                        informasi pelayanan kesehatan,
                                        jadwal praktik dokter, dan informasi
                                        obat secara mudah dan informatif.
                                        Mari jaga kesehatan bersama kami.
                                    </p>
                                    <div class="hero-actions">
                                        <a
                                            href="index.php?page=daftardokter"
                                            class="hero-btn hero-btn-primary">
                                            <i class="fas fa-user-md"></i>
                                            Temukan Dokter
                                        </a>
                                        <a
                                            href="index.php?page=registerpasien"
                                            class="hero-btn hero-btn-outline">
                                            <i class="fas fa-user-plus"></i>
                                            Daftar Pasien
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Tampilan jika folder slider belum berisi gambar -->
                    <div class="carousel-item active">
                        <div class="hero-overlay"></div>
                        <div class="container hero-content">
                            <div class="hero-copy">
                                <div class="hero-eyebrow">
                                    <i class="fas fa-heartbeat"></i>
                                    Selamat Datang di Klinik Ahmadi
                                </div>
                                <h1 class="hero-title">
                                    Kesehatan Anda,<br>
                                    <span>Prioritas Kami.</span>
                                </h1>
                                <p class="hero-description">
                                    Temukan informasi dokter, jadwal praktik,
                                    kategori obat, dan layanan kesehatan
                                    melalui aplikasi Klinik Ahmadi.
                                </p>
                                <div class="hero-actions">
                                    <a
                                        href="index.php?page=daftardokter"
                                        class="hero-btn hero-btn-primary">
                                        <i class="fas fa-user-md"></i>
                                        Temukan Dokter
                                    </a>
                                    <a
                                        href="index.php?page=registerpasien"
                                        class="hero-btn hero-btn-outline">
                                        <i class="fas fa-user-plus"></i>
                                        Daftar Pasien
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (count($daftarSlider) > 1): ?>
                <button
                    class="carousel-control-prev"
                    type="button"
                    data-bs-target="#homeHeroCarousel"
                    data-bs-slide="prev"
                    aria-label="Slide sebelumnya">
                    <span class="carousel-control-prev-icon"
                          aria-hidden="true"></span>
                </button>
                <button
                    class="carousel-control-next"
                    type="button"
                    data-bs-target="#homeHeroCarousel"
                    data-bs-slide="next"
                    aria-label="Slide berikutnya">
                    <span class="carousel-control-next-icon"
                          aria-hidden="true"></span>
                </button>
            <?php endif; ?>
        </div>
    </section>
    <!-- =====================================================
         2. HIGHLIGHT LAYANAN KLINIK
    ====================================================== -->
    <section class="home-highlights"
             aria-label="Keunggulan Klinik Ahmadi">
        <div class="container">
            <div class="row g-3">
                <div class="col-12 col-sm-6 col-lg-4 col-xl-2">
                    <div class="home-highlight">
                        <div class="home-highlight-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div>
                            <strong>Dokter Profesional</strong>
                            <small>Informasi tenaga medis</small>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-2">
                    <div class="home-highlight">
                        <div class="home-highlight-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <strong>Jadwal Praktik</strong>
                            <small>Informasi waktu praktik</small>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-2">
                    <div class="home-highlight">
                        <div class="home-highlight-icon">
                            <i class="fas fa-pills"></i>
                        </div>
                        <div>
                            <strong>Informasi Obat</strong>
                            <small>Katalog obat klinik</small>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-2">
                    <div class="home-highlight">
                        <div class="home-highlight-icon">
                            <i class="fas fa-list-alt"></i>
                        </div>
                        <div>
                            <strong>Kategori Obat</strong>
                            <small>Obat lebih mudah ditemukan</small>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-2">
                    <div class="home-highlight">
                        <div class="home-highlight-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div>
                            <strong>Pendaftaran Checkup</strong>
                            <small>Daftar pemeriksaan pasien</small>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-2">
                    <div class="home-highlight">
                        <div class="home-highlight-icon">
                            <i class="fas fa-laptop-medical"></i>
                        </div>
                        <div>
                            <strong>Layanan Digital</strong>
                            <small>Akses informasi lebih praktis</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- =====================================================
         3. DOKTER DAN JADWAL PRAKTIK
    ====================================================== -->
    <section class="home-doctor-section">
        <div class="container">
            <div class="home-section-heading">
                <span class="home-section-eyebrow">
                    Tenaga Medis Kami
                </span>
                <h2 class="home-section-title">
                    Kenali Dokter Klinik Ahmadi
                </h2>
                <p class="home-section-description">
                    Lihat informasi dokter dan jadwal praktik
                    untuk membantu Anda merencanakan kunjungan
                    pemeriksaan kesehatan.
                </p>
            </div>
            <div class="row g-4">
                <?php if ($resultDokterHome && mysqli_num_rows($resultDokterHome) > 0): ?>
                    <?php while ($dokterHome = mysqli_fetch_assoc($resultDokterHome)): ?>
                        <?php
                        $idDokterHome = (int) $dokterHome['iddokter'];
                        $namaDokterHome = $dokterHome['namadokter'] ?? '';
                        $spesialisDokterHome = $dokterHome['spesialis'] ?? '';
                        $fotoDokterHome = basename(
                            trim($dokterHome['foto'] ?? '')
                        );
                        $pathFotoDokterHome =
                            'assets/images/dokter/' . $fotoDokterHome;
                        $fotoDokterTersedia =
                            $fotoDokterHome !== '' &&
                            is_file(
                                __DIR__ . '/../../assets/images/dokter/' .
                                $fotoDokterHome
                            );
                        $daftarJadwalDokter = [];
                        if (!empty($dokterHome['daftarjadwal'])) {
                            $daftarJadwalDokter = explode(
                                ';;',
                                $dokterHome['daftarjadwal']
                            );
                        }
                        ?>
                        <div class="col-6 col-lg-3">
                            <article class="home-doctor-card">
                                <div class="home-doctor-photo-wrap">
                                    <?php if ($fotoDokterTersedia): ?>
                                        <img
                                            src="<?= $homeEscape($pathFotoDokterHome); ?>"
                                            alt="Foto <?= $homeEscape($namaDokterHome); ?>"
                                            class="home-doctor-photo"
                                            loading="lazy">
                                    <?php else: ?>
                                        <div class="home-doctor-placeholder">
                                            <i class="fas fa-user-md"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="home-doctor-body">
                                    <h3 class="home-doctor-name">
                                        <?= $homeEscape($namaDokterHome); ?>
                                    </h3>
                                    <span class="home-doctor-specialist">
                                        <i class="fas fa-stethoscope me-1"></i>
                                        <?= $homeEscape($spesialisDokterHome); ?>
                                    </span>
                                    <div class="home-doctor-schedule">
                                        <strong>
                                            <i class="far fa-calendar-alt"></i>
                                            Jadwal Praktik
                                        </strong>
                                        <?php if (!empty($daftarJadwalDokter)): ?>
                                            <?php foreach ($daftarJadwalDokter as $jadwal): ?>
                                                <div>
                                                    <?= $homeEscape($jadwal); ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div>Jadwal belum tersedia.</div>
                                        <?php endif; ?>
                                    </div>
                                    <a
                                        href="index.php?page=detildokter&id=<?= $idDokterHome; ?>"
                                        class="home-doctor-button">
                                        Lihat Profil
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="home-empty">
                            <i class="fas fa-user-md fa-2x mb-3"></i>
                            <p class="mb-0">
                                Informasi dokter belum tersedia.
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="text-center mt-4">
                <a
                    href="index.php?page=daftardokter"
                    class="btn btn-success px-4">
                    Lihat Semua Dokter
                    <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </section>
    <!-- =====================================================
         4. KATEGORI OBAT
    ====================================================== -->
    <section class="home-category-section">
        <div class="container">
            <div class="home-section-heading">
                <span class="home-section-eyebrow">
                    Katalog Farmasi
                </span>
                <h2 class="home-section-title">
                    Jelajahi Kategori Obat
                </h2>
                <p class="home-section-description">
                    Temukan informasi obat berdasarkan kategori
                    yang tersedia dalam katalog Klinik Ahmadi.
                </p>
            </div>
            <div class="home-category-grid">
                <?php if ($resultKategoriHome && mysqli_num_rows($resultKategoriHome) > 0): ?>
                    <?php while ($kategoriHome = mysqli_fetch_assoc($resultKategoriHome)): ?>
                        <?php
                        $idKategoriHome = (int) $kategoriHome['idkategori'];
                        $namaKategoriHome = $kategoriHome['namakategori'] ?? '';
                        ?>
                        <a
                            href="index.php?page=detilkategori&id=<?= $idKategoriHome; ?>"
                            class="home-category-card">
                            <div class="home-category-icon">
                                <i class="fas fa-capsules"></i>
                            </div>
                            <h3 class="home-category-name">
                                <?= $homeEscape($namaKategoriHome); ?>
                            </h3>
                            <p class="home-category-count">
                                <?= number_format((int) $kategoriHome['jumlahobat'], 0, ',', '.'); ?>
                                jenis obat
                            </p>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="home-empty"
                         style="grid-column: 1 / -1;">
                        <i class="fas fa-folder-open fa-2x mb-3"></i>
                        <p class="mb-0">
                            Belum ada kategori obat yang tersedia.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="text-center mt-4">
                <a
                    href="index.php?page=daftarkategori"
                    class="btn btn-outline-success px-4">
                    Semua Kategori
                    <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </section>
    <!-- =====================================================
         5. INFORMASI OBAT
    ====================================================== -->
    <section class="home-medicine-section">
        <div class="container">
            <div class="home-section-heading">
                <span class="home-section-eyebrow">
                    Informasi Farmasi
                </span>
                <h2 class="home-section-title">
                    Informasi Obat Klinik
                </h2>
                <p class="home-section-description">
                    Lihat informasi obat, merek, kategori, dan
                    ketersediaan yang tercatat dalam katalog klinik.
                </p>
            </div>
            <div class="row g-3">
                <?php if ($resultObatHome && mysqli_num_rows($resultObatHome) > 0): ?>
                    <?php while ($obatHome = mysqli_fetch_assoc($resultObatHome)): ?>
                        <?php
                        $idObatHome = (int) $obatHome['idobat'];
                        $namaObatHome = $obatHome['namaobat'] ?? '';
                        $namaMerkHome = $obatHome['namamerk'] ?? '';
                        $namaKategoriObatHome = $obatHome['namakategori'] ?? '';
                        $hargaObatHome = (float) ($obatHome['harga'] ?? 0);
                        $stokObatHome = (int) ($obatHome['stok'] ?? 0);
                        $fotoObatHome = basename(
                            trim($obatHome['foto'] ?? '')
                        );
                        $pathFotoObatHome =
                            'assets/images/obat/' . $fotoObatHome;
                        $fotoObatTersedia =
                            $fotoObatHome !== '' &&
                            is_file(
                                __DIR__ . '/../../assets/images/obat/' .
                                $fotoObatHome
                            );
                        ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <article class="home-medicine-card">
                                <div class="home-medicine-photo-wrap">
                                    <?php if ($fotoObatTersedia): ?>
                                        <img
                                            src="<?= $homeEscape($pathFotoObatHome); ?>"
                                            alt="<?= $homeEscape($namaObatHome); ?>"
                                            class="home-medicine-photo"
                                            loading="lazy">
                                    <?php else: ?>
                                        <div class="home-medicine-placeholder">
                                            <i class="fas fa-pills"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="home-medicine-body">
                                    <span class="home-medicine-category">
                                        <?= $homeEscape($namaKategoriObatHome); ?>
                                    </span>
                                    <h3 class="home-medicine-name">
                                        <?= $homeEscape($namaObatHome); ?>
                                    </h3>
                                    <p class="home-medicine-brand">
                                        Merek:
                                        <?= $homeEscape($namaMerkHome ?: '-'); ?>
                                    </p>
                                    <div class="home-medicine-price">
                                        Rp<?= number_format($hargaObatHome, 0, ',', '.'); ?>
                                    </div>
                                    <?php if ($stokObatHome > 0): ?>
                                        <span class="home-medicine-stock">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Tersedia
                                        </span>
                                    <?php else: ?>
                                        <span class="home-medicine-stock empty">
                                            <i class="fas fa-times-circle me-1"></i>
                                            Stok Habis
                                        </span>
                                    <?php endif; ?>
                                    <a
                                        href="index.php?page=detilobat&id=<?= $idObatHome; ?>"
                                        class="home-medicine-button">
                                        Lihat Informasi Obat
                                        <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="home-empty">
                            <i class="fas fa-pills fa-2x mb-3"></i>
                            <p class="mb-0">
                                Informasi obat belum tersedia.
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="text-center mt-4">
                <a
                    href="index.php?page=daftarobat"
                    class="btn btn-success px-4">
                    Lihat Semua Obat
                    <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </section>
    <!-- =====================================================
         6. TENTANG KLINIK AHMADI
    ====================================================== -->
    <section class="home-about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="home-about-label">
                        Mengenal Klinik Kami
                    </span>
                    <h2 class="home-about-title">
                        Pelayanan kesehatan yang
                        <span>informatif dan mudah diakses.</span>
                    </h2>
                    <p class="home-about-description">
                        Klinik Ahmadi merupakan aplikasi layanan informasi
                        klinik yang dirancang untuk membantu masyarakat
                        mengenal dokter, melihat jadwal praktik, memperoleh
                        informasi obat, serta mengakses layanan pendaftaran
                        pemeriksaan pasien.
                        Melalui sistem informasi ini, pengunjung dapat
                        memperoleh informasi klinik dengan lebih praktis,
                        sedangkan pasien dapat mengakses fitur yang
                        disediakan sesuai hak aksesnya.
                    </p>
                    <a
                        href="index.php?page=tentang"
                        class="home-about-link">
                        Kenali Klinik Ahmadi
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="col-lg-5">
                    <div class="home-about-visual">
                        <div class="home-about-card">
                            <div class="home-about-card-icon">
                                <i class="fas fa-hospital"></i>
                            </div>
                            <h3>Sehat Bersama Klinik Ahmadi</h3>
                            <p>
                                Informasi dokter, jadwal praktik, katalog
                                obat, dan layanan pendaftaran pasien
                                terintegrasi dalam satu aplikasi klinik.
                            </p>
                            <div class="home-about-pill">
                                <i class="fas fa-check-circle"></i>
                                Informasi layanan lebih praktis
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- =====================================================
         7. STATISTIK KLINIK
    ====================================================== -->
    <section class="home-stats-section">
        <div class="container">
            <div class="home-section-heading">
                <span class="home-section-eyebrow">
                    Klinik dalam Angka
                </span>
                <h2 class="home-section-title">
                    Informasi Klinik Ahmadi
                </h2>
                <p class="home-section-description">
                    Ringkasan data dokter, pasien, obat, dan jadwal
                    yang tercatat dalam sistem informasi Klinik Ahmadi.
                </p>
            </div>
            <div class="home-stat-grid">
                <!-- TOTAL DOKTER -->
                <div class="home-stat-card">
                    <div class="home-stat-icon doctors">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="home-stat-content">
                        <span class="home-stat-number">
                            <?= number_format($totalDokter, 0, ',', '.'); ?>
                        </span>
                        <span class="home-stat-label">
                            Dokter terdaftar
                        </span>
                    </div>
                </div>
                <!-- TOTAL PASIEN -->
                <div class="home-stat-card">
                    <div class="home-stat-icon patients">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="home-stat-content">
                        <span class="home-stat-number">
                            <?= number_format($totalPasien, 0, ',', '.'); ?>
                        </span>
                        <span class="home-stat-label">
                            Pasien terdaftar
                        </span>
                    </div>
                </div>
                <!-- TOTAL OBAT -->
                <div class="home-stat-card">
                    <div class="home-stat-icon medicines">
                        <i class="fas fa-pills"></i>
                    </div>
                    <div class="home-stat-content">
                        <span class="home-stat-number">
                            <?= number_format($totalObat, 0, ',', '.'); ?>
                        </span>
                        <span class="home-stat-label">
                            Jenis obat
                        </span>
                    </div>
                </div>
                <!-- TOTAL KATEGORI -->
                <div class="home-stat-card">
                    <div class="home-stat-icon categories">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="home-stat-content">
                        <span class="home-stat-number">
                            <?= number_format($totalKategori, 0, ',', '.'); ?>
                        </span>
                        <span class="home-stat-label">
                            Kategori obat
                        </span>
                    </div>
                </div>
                <!-- TOTAL MEREK -->
                <div class="home-stat-card">
                    <div class="home-stat-icon brands">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="home-stat-content">
                        <span class="home-stat-number">
                            <?= number_format($totalMerk, 0, ',', '.'); ?>
                        </span>
                        <span class="home-stat-label">
                            Merek obat
                        </span>
                    </div>
                </div>
                <!-- TOTAL JADWAL -->
                <div class="home-stat-card">
                    <div class="home-stat-icon schedules">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="home-stat-content">
                        <span class="home-stat-number">
                            <?= number_format($totalJadwal, 0, ',', '.'); ?>
                        </span>
                        <span class="home-stat-label">
                            Jadwal praktik
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- =====================================================
         8. AJAKAN PENDAFTARAN PASIEN
    ====================================================== -->
    <section class="home-cta-section">
        <div class="container">
            <div class="home-cta-content">
                <div class="home-cta-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2>
                    Mulai Akses Layanan Klinik Ahmadi
                </h2>
                <p>
                    Daftarkan akun pasien untuk mengakses layanan
                    pendaftaran pemeriksaan dan fitur pasien lainnya.
                    Anda juga dapat melihat informasi dokter serta
                    jadwal praktik sebelum melakukan kunjungan.
                </p>
                <a
                    href="index.php?page=registerpasien"
                    class="home-cta-button">
                    <i class="fas fa-clipboard-list"></i>
                    Daftar sebagai Pasien
                </a>
                <a
                    href="index.php?page=loginpasien"
                    class="home-cta-button ms-2">
                    <i class="fas fa-sign-in-alt"></i>
                    Login Pasien
                </a>
            </div>
        </div>
    </section>
</div>
