<?php
$tentangFitur = [
    [
        'icon' => 'fa-user-md',
        'nomor' => '01',
        'judul' => 'Informasi Dokter',
        'deskripsi' => 'Membantu pengunjung menemukan profil dokter, bidang spesialisasi, dan jadwal praktik yang tercatat.',
        'warna' => 'dokter',
        'link' => 'index.php?page=daftardokter',
        'tombol' => 'Jelajahi Dokter'
    ],
    [
        'icon' => 'fa-capsules',
        'nomor' => '02',
        'judul' => 'Informasi Obat',
        'deskripsi' => 'Menyediakan akses ke katalog obat, informasi harga, kategori, dan data stok yang tersedia di aplikasi.',
        'warna' => 'obat',
        'link' => 'index.php?page=daftarobat',
        'tombol' => 'Lihat Katalog'
    ],
    [
        'icon' => 'fa-layer-group',
        'nomor' => '03',
        'judul' => 'Kategori Obat',
        'deskripsi' => 'Memudahkan pengunjung menelusuri pengelompokan obat dan menemukan informasi produk yang dicari.',
        'warna' => 'kategori',
        'link' => 'index.php?page=daftarkategori',
        'tombol' => 'Lihat Kategori'
    ]
];

$tentangNilai = [
    [
        'icon' => 'fa-bolt',
        'judul' => 'Praktis dan Efisien',
        'deskripsi' => 'Informasi disusun dalam halaman yang terstruktur agar lebih mudah ditemukan.'
    ],
    [
        'icon' => 'fa-laptop-medical',
        'judul' => 'Berbasis Digital',
        'deskripsi' => 'Memanfaatkan aplikasi web untuk menyajikan informasi dalam satu tempat.'
    ],
    [
        'icon' => 'fa-shield-alt',
        'judul' => 'Informasi yang Jelas',
        'deskripsi' => 'Mendorong pengunjung memeriksa informasi yang tersedia dan mengonfirmasi hal penting kepada pihak rumah sakit.'
    ]
];
?>

<style>
.tentang-section{position:relative;min-height:75vh;overflow:hidden;padding:32px 0 60px;background:radial-gradient(ellipse at 8% 8%,rgba(16,185,129,.1),transparent 32%),radial-gradient(ellipse at 95% 35%,rgba(59,130,246,.07),transparent 30%),#f4f7fb}
.tentang-container{position:relative;z-index:1;width:95%;max-width:1450px;margin:0 auto}
.tentang-hero{position:relative;overflow:hidden;padding:34px 38px;margin-bottom:28px;border-radius:20px;color:#fff;background:linear-gradient(115deg,#102a36 0%,#075e54 52%,#0d9488 100%);box-shadow:0 15px 35px rgba(8,127,112,.15)}
.tentang-hero::before,.tentang-hero::after{content:"";position:absolute;border:1px solid rgba(255,255,255,.13);border-radius:50%;pointer-events:none}
.tentang-hero::before{width:300px;height:300px;right:-65px;top:-175px;border-width:35px}
.tentang-hero::after{width:190px;height:190px;right:185px;bottom:-145px}
.tentang-hero-content{position:relative;z-index:1;max-width:760px}
.tentang-eyebrow{display:inline-flex;align-items:center;gap:8px;padding:7px 12px;margin-bottom:13px;border:1px solid rgba(255,255,255,.22);border-radius:30px;color:#a7f3d0;background:rgba(255,255,255,.08);font-size:11px;font-weight:800;letter-spacing:1.4px;text-transform:uppercase}
.tentang-hero h1{margin:0 0 12px;color:#fff;font-size:clamp(27px,3.2vw,40px);font-weight:800;line-height:1.25}
.tentang-hero p{max-width:650px;margin:0;color:rgba(255,255,255,.82);font-size:14px;line-height:1.85}
.tentang-hero-actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:23px}
.tentang-hero-btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;min-height:43px;padding:11px 17px;border:1px solid rgba(255,255,255,.25);border-radius:10px;color:#fff!important;background:rgba(255,255,255,.1);font-size:12px;font-weight:800;text-decoration:none!important;transition:.25s}
.tentang-hero-btn-primary{border-color:#d9f99d;color:#173b2e!important;background:#d9f99d}
.tentang-hero-btn-primary:hover{color:#173b2e!important;background:#ecfccb;transform:translateY(-2px)}
.tentang-hero-btn-outline:hover{color:#fff!important;background:rgba(255,255,255,.2);transform:translateY(-2px)}
.tentang-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:15px;margin-bottom:38px}
.tentang-stat{display:flex;align-items:center;gap:13px;min-width:0;padding:17px 19px;border:1px solid #e4eaf1;border-radius:15px;background:rgba(255,255,255,.88);box-shadow:0 5px 20px rgba(30,45,70,.035)}
.tentang-stat-icon{display:flex;flex:0 0 43px;align-items:center;justify-content:center;width:43px;height:43px;border-radius:12px;color:#087f70;background:#e1f8ed;font-size:18px}
.tentang-stat strong{display:block;margin-bottom:4px;color:#20372f;font-size:14px;font-weight:800}
.tentang-stat span{display:block;color:#829087;font-size:11px;line-height:1.5}
.tentang-heading{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:21px}
.tentang-heading-label{display:block;margin-bottom:7px;color:#0d9488;font-size:10px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase}
.tentang-heading h2{margin:0 0 7px;color:#172b25;font-size:clamp(22px,2.5vw,29px);font-weight:800}
.tentang-heading p{max-width:720px;margin:0;color:#77867e;font-size:13px;line-height:1.8}
.tentang-heading-mark{display:flex;flex:0 0 48px;align-items:center;justify-content:center;width:48px;height:48px;border:1px solid #d6eee2;border-radius:14px;color:#087f70;background:#e9faf1;font-size:20px}
.tentang-profile{display:grid;grid-template-columns:minmax(0,1.25fr) minmax(260px,.75fr);gap:20px;margin-bottom:38px}
.tentang-profile-panel{min-width:0;padding:27px;border:1px solid #e4eaf1;border-radius:17px;background:#fff;box-shadow:0 7px 25px rgba(30,45,70,.045)}
.tentang-profile-label{display:inline-flex;align-items:center;gap:8px;margin-bottom:15px;color:#0d9488;font-size:10px;font-weight:800;letter-spacing:1.3px;text-transform:uppercase}
.tentang-profile-panel h3{margin:0 0 13px;color:#20372f;font-size:22px;font-weight:800;line-height:1.45}
.tentang-profile-panel p{margin:0 0 13px;color:#718078;font-size:13px;line-height:1.95}
.tentang-profile-panel p:last-child{margin-bottom:0}
.tentang-profile-visual{position:relative;display:flex;flex-direction:column;justify-content:space-between;gap:25px;overflow:hidden;padding:27px;border-radius:17px;color:#fff;background:linear-gradient(145deg,#075e54,#087f70 60%,#0d9488);box-shadow:0 10px 28px rgba(8,127,112,.12)}
.tentang-profile-visual::before{content:"";position:absolute;width:220px;height:220px;right:-90px;top:-95px;border:30px solid rgba(255,255,255,.08);border-radius:50%}
.tentang-visual-icon{position:relative;display:flex;align-items:center;justify-content:center;width:62px;height:62px;border:1px solid rgba(255,255,255,.2);border-radius:17px;color:#d9f99d;background:rgba(255,255,255,.1);font-size:26px}
.tentang-profile-visual h3{position:relative;margin:0 0 9px;color:#fff;font-size:22px;font-weight:800;line-height:1.4}
.tentang-profile-visual p{position:relative;margin:0;color:rgba(255,255,255,.8);font-size:12px;line-height:1.9}
.tentang-visual-tag{position:relative;display:inline-flex;align-items:center;gap:8px;align-self:flex-start;padding:8px 12px;border:1px solid rgba(255,255,255,.2);border-radius:30px;color:#d9f99d;background:rgba(255,255,255,.07);font-size:10px;font-weight:800}
.tentang-feature-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px}
.tentang-feature-card{position:relative;display:flex;flex-direction:column;min-width:0;overflow:hidden;padding:23px 21px 20px;border:1px solid #e4eaf1;border-radius:17px;background:#fff;box-shadow:0 7px 25px rgba(30,45,70,.045);transition:transform .25s ease,box-shadow .25s ease,border-color .25s ease}
.tentang-feature-card:hover{transform:translateY(-5px);border-color:#b8e5d1;box-shadow:0 15px 32px rgba(8,127,112,.1)}
.tentang-feature-top{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:20px}
.tentang-feature-icon{display:flex;align-items:center;justify-content:center;width:51px;height:51px;border-radius:14px;color:#087f70;background:linear-gradient(145deg,#e8faf1,#d5f5e5);font-size:21px}
.tentang-feature-number{color:#c0ccc6;font-size:12px;font-weight:900;letter-spacing:1px}
.tentang-feature-card h3{margin:0 0 10px;color:#20372f;font-size:17px;font-weight:800;line-height:1.45}
.tentang-feature-card p{flex:1;margin:0 0 19px;color:#78867f;font-size:12px;line-height:1.9}
.tentang-feature-link{display:flex;align-items:center;justify-content:space-between;gap:10px;padding-top:14px;border-top:1px solid #edf2ef;color:#087f70!important;font-size:12px;font-weight:800;text-decoration:none!important}
.tentang-feature-link i{transition:transform .2s ease}
.tentang-feature-link:hover{color:#075c45!important}
.tentang-feature-link:hover i{transform:translateX(4px)}
.tentang-feature-card.obat .tentang-feature-icon{color:#2563eb;background:linear-gradient(145deg,#eff6ff,#dbeafe)}
.tentang-feature-card.kategori .tentang-feature-icon{color:#7c3aed;background:linear-gradient(145deg,#f5f0ff,#e9ddff)}
.tentang-values{padding:26px 27px;margin-top:36px;border:1px solid #e2eee7;border-radius:18px;background:linear-gradient(120deg,#f0fdf4,#f8fbff)}
.tentang-values-heading{margin-bottom:22px}
.tentang-values-heading span{display:block;margin-bottom:7px;color:#0d9488;font-size:10px;font-weight:800;letter-spacing:1.4px;text-transform:uppercase}
.tentang-values-heading h2{margin:0 0 7px;color:#172b25;font-size:21px;font-weight:800}
.tentang-values-heading p{margin:0;color:#77867e;font-size:12px;line-height:1.8}
.tentang-values-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:20px}
.tentang-value-item{display:flex;align-items:flex-start;gap:13px;min-width:0}
.tentang-value-icon{display:flex;flex:0 0 39px;align-items:center;justify-content:center;width:39px;height:39px;border:1px solid #cdebd9;border-radius:11px;color:#087f70;background:#fff;font-size:16px}
.tentang-value-item h3{margin:2px 0 6px;color:#20372f;font-size:13px;font-weight:800}
.tentang-value-item p{margin:0;color:#77867e;font-size:11px;line-height:1.85}
.tentang-cta{position:relative;display:flex;align-items:center;justify-content:space-between;gap:25px;overflow:hidden;padding:27px 30px;margin-top:25px;border-radius:17px;color:#fff;background:linear-gradient(115deg,#102a36,#075e54 60%,#0d9488)}
.tentang-cta-content{position:relative;z-index:1}
.tentang-cta h2{margin:0 0 8px;color:#fff;font-size:21px;font-weight:800}
.tentang-cta p{max-width:650px;margin:0;color:rgba(255,255,255,.78);font-size:12px;line-height:1.8}
.tentang-cta-btn{position:relative;z-index:1;display:inline-flex;flex-shrink:0;align-items:center;justify-content:center;gap:9px;padding:12px 17px;border:1px solid #d9f99d;border-radius:10px;color:#173b2e!important;background:#d9f99d;font-size:12px;font-weight:800;text-decoration:none!important;transition:.25s}
.tentang-cta-btn:hover{color:#173b2e!important;background:#ecfccb;transform:translateY(-2px)}
.tentang-disclaimer{display:flex;align-items:flex-start;gap:10px;padding:13px 15px;margin-top:18px;border:1px solid #e5eaf1;border-radius:11px;color:#738177;background:#fff;font-size:11px;line-height:1.8}
.tentang-disclaimer i{margin-top:3px;color:#0d9488;font-size:14px}
@media(max-width:1199.98px){.tentang-profile{grid-template-columns:minmax(0,1fr) minmax(230px,.8fr)}.tentang-feature-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
@media(max-width:991.98px){.tentang-profile{grid-template-columns:1fr}.tentang-profile-visual{min-height:250px}.tentang-feature-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.tentang-values-grid{grid-template-columns:1fr;gap:18px}}
@media(max-width:767.98px){.tentang-section{padding:23px 0 40px}.tentang-container{width:92%}.tentang-hero{padding:27px 23px;border-radius:17px}.tentang-stats{gap:10px;margin-bottom:30px}.tentang-stat{align-items:flex-start;flex-direction:column;padding:13px}.tentang-stat-icon{width:38px;height:38px;flex-basis:38px;font-size:16px}.tentang-stat strong{font-size:12px}.tentang-stat span{font-size:10px}.tentang-heading{align-items:flex-start}.tentang-heading-mark{flex-basis:40px;width:40px;height:40px}.tentang-profile-panel{padding:22px 19px}.tentang-profile-visual{padding:23px}.tentang-feature-grid{gap:13px}.tentang-feature-card{padding:19px 16px}.tentang-feature-card h3{font-size:15px}.tentang-feature-card p{font-size:11px}.tentang-values{padding:21px 18px;margin-top:28px}.tentang-cta{align-items:flex-start;flex-direction:column;padding:24px 21px}.tentang-cta h2{font-size:19px}}
@media(max-width:419.98px){.tentang-container{width:94%}.tentang-hero{padding:23px 18px}.tentang-hero-actions{flex-direction:column}.tentang-hero-btn{width:100%}.tentang-stats{grid-template-columns:1fr}.tentang-stat{align-items:center;flex-direction:row}.tentang-feature-grid{grid-template-columns:1fr}.tentang-feature-card{padding:21px}.tentang-feature-card p{font-size:12px}.tentang-heading h2{font-size:22px}.tentang-cta-btn{width:100%}}
@media(prefers-reduced-motion:reduce){.tentang-section *,.tentang-section *::before,.tentang-section *::after{transition-duration:.01ms!important;scroll-behavior:auto!important}}
</style>

<section class="tentang-section">
    <div class="tentang-container">
        <div class="tentang-hero">
            <div class="tentang-hero-content">
                <div class="tentang-eyebrow">
                    <i class="fas fa-hospital-alt"></i>
                    Tentang RumahSakitAhmadi
                </div>
                <h1>Mengenal RumahSakitAhmadi Lebih Dekat</h1>
                <p>RumahSakitAhmadi merupakan aplikasi web yang dirancang untuk membantu pengunjung mengakses informasi dokter, katalog obat, kategori obat, dan informasi terkait melalui tampilan yang modern, terstruktur, dan mudah digunakan.</p>
                <div class="tentang-hero-actions">
                    <a href="index.php?page=layanan" class="tentang-hero-btn tentang-hero-btn-primary">
                        <i class="fas fa-th-large"></i> Jelajahi Layanan
                    </a>
                    <a href="index.php?page=kontak" class="tentang-hero-btn tentang-hero-btn-outline">
                        <i class="fas fa-envelope"></i> Hubungi Kami
                    </a>
                </div>
            </div>
        </div>

        <div class="tentang-stats">
            <div class="tentang-stat">
                <div class="tentang-stat-icon"><i class="fas fa-user-md"></i></div>
                <div>
                    <strong>Informasi Dokter</strong>
                    <span>Profil dan jadwal praktik</span>
                </div>
            </div>
            <div class="tentang-stat">
                <div class="tentang-stat-icon"><i class="fas fa-pills"></i></div>
                <div>
                    <strong>Informasi Obat</strong>
                    <span>Katalog dan data produk</span>
                </div>
            </div>
            <div class="tentang-stat">
                <div class="tentang-stat-icon"><i class="fas fa-globe"></i></div>
                <div>
                    <strong>Akses Digital</strong>
                    <span>Informasi dalam satu aplikasi</span>
                </div>
            </div>
        </div>

        <div class="tentang-heading">
            <div>
                <span class="tentang-heading-label">Profil Aplikasi</span>
                <h2>Solusi Informasi Kesehatan Digital</h2>
                <p>Menghadirkan informasi yang tersusun rapi agar pengunjung dapat menjelajahi halaman yang tersedia dengan lebih praktis.</p>
            </div>
            <div class="tentang-heading-mark"><i class="fas fa-hospital-user"></i></div>
        </div>

        <div class="tentang-profile">
            <div class="tentang-profile-panel">
                <div class="tentang-profile-label">
                    <i class="fas fa-info-circle"></i>
                    Siapa Kami?
                </div>
                <h3>Informasi yang Lebih Mudah Diakses</h3>
                <p>RumahSakitAhmadi dikembangkan sebagai aplikasi berbasis web untuk menyajikan informasi secara terstruktur. Pengunjung dapat menelusuri halaman dokter, melihat informasi obat, serta menjelajahi kategori obat melalui navigasi yang tersedia.</p>
                <p>Dengan tampilan yang responsif, halaman aplikasi dirancang agar tetap nyaman digunakan melalui komputer, tablet, maupun telepon seluler.</p>
                <p>Aplikasi ini juga menyediakan halaman informasi dan kontak untuk membantu pengunjung mengetahui ke mana harus mencari informasi lebih lanjut.</p>
            </div>
            <div class="tentang-profile-visual">
                <div class="tentang-visual-icon"><i class="fas fa-laptop-medical"></i></div>
                <div>
                    <h3>Teknologi untuk Akses Informasi</h3>
                    <p>Antarmuka digital yang menghubungkan pengunjung dengan informasi yang tersedia dalam aplikasi secara lebih praktis dan terorganisasi.</p>
                </div>
                <div class="tentang-visual-tag">
                    <i class="fas fa-check-circle"></i>
                    Informasi Terstruktur
                </div>
            </div>
        </div>

        <div class="tentang-heading">
            <div>
                <span class="tentang-heading-label">Fitur Utama</span>
                <h2>Apa yang Bisa Anda Temukan?</h2>
                <p>Jelajahi fitur informasi yang telah disediakan di dalam aplikasi RumahSakitAhmadi.</p>
            </div>
            <div class="tentang-heading-mark"><i class="fas fa-cubes"></i></div>
        </div>

        <div class="tentang-feature-grid">
            <?php foreach ($tentangFitur as $fitur): ?>
                <article class="tentang-feature-card <?= htmlspecialchars($fitur['warna']) ?>">
                    <div class="tentang-feature-top">
                        <div class="tentang-feature-icon">
                            <i class="fas <?= htmlspecialchars($fitur['icon']) ?>"></i>
                        </div>
                        <span class="tentang-feature-number"><?= htmlspecialchars($fitur['nomor']) ?></span>
                    </div>
                    <h3><?= htmlspecialchars($fitur['judul']) ?></h3>
                    <p><?= htmlspecialchars($fitur['deskripsi']) ?></p>
                    <a href="<?= htmlspecialchars($fitur['link']) ?>" class="tentang-feature-link">
                        <?= htmlspecialchars($fitur['tombol']) ?>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="tentang-values">
            <div class="tentang-values-heading">
                <span>Prinsip Pengembangan</span>
                <h2>Dirancang untuk Pengalaman yang Lebih Baik</h2>
                <p>Beberapa prinsip yang menjadi dasar penyusunan tampilan dan navigasi aplikasi.</p>
            </div>
            <div class="tentang-values-grid">
                <?php foreach ($tentangNilai as $nilai): ?>
                    <div class="tentang-value-item">
                        <div class="tentang-value-icon">
                            <i class="fas <?= htmlspecialchars($nilai['icon']) ?>"></i>
                        </div>
                        <div>
                            <h3><?= htmlspecialchars($nilai['judul']) ?></h3>
                            <p><?= htmlspecialchars($nilai['deskripsi']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="tentang-cta">
            <div class="tentang-cta-content">
                <h2>Temukan Informasi yang Anda Butuhkan</h2>
                <p>Mulai jelajahi informasi dokter, katalog obat, dan halaman layanan yang tersedia di RumahSakitAhmadi.</p>
            </div>
            <a href="index.php?page=layanan" class="tentang-cta-btn">
                <i class="fas fa-compass"></i> Jelajahi Layanan
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="tentang-disclaimer">
            <i class="fas fa-info-circle"></i>
            <div>RumahSakitAhmadi pada halaman ini merujuk pada aplikasi informasi berbasis web. Informasi mengenai sejarah, alamat, legalitas, akreditasi, fasilitas, dan layanan resmi rumah sakit perlu dilengkapi berdasarkan data yang telah dikonfirmasi oleh pihak terkait.</div>
        </div>
    </div>
</section>