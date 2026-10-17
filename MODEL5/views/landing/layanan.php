<?php
$layananList = [
    [
        'icon' => 'fa-user-md',
        'nomor' => '01',
        'judul' => 'Konsultasi Dokter',
        'kategori' => 'LAYANAN KESEHATAN',
        'deskripsi' => 'Temukan informasi dokter, bidang spesialisasi, serta jadwal praktik untuk membantu Anda merencanakan konsultasi.',
        'fitur' => ['Informasi dokter', 'Jadwal praktik'],
        'link' => 'index.php?page=daftardokter',
        'tombol' => 'Lihat Dokter',
        'warna' => 'dokter'
    ],
    [
        'icon' => 'fa-capsules',
        'nomor' => '02',
        'judul' => 'Informasi Obat',
        'kategori' => 'LAYANAN FARMASI',
        'deskripsi' => 'Jelajahi katalog obat untuk melihat informasi nama obat, harga, kategori, dan ketersediaan stok yang tercatat.',
        'fitur' => ['Katalog obat', 'Informasi stok'],
        'link' => 'index.php?page=daftarobat',
        'tombol' => 'Jelajahi Obat',
        'warna' => 'obat'
    ],
    [
        'icon' => 'fa-layer-group',
        'nomor' => '03',
        'judul' => 'Kategori Obat',
        'kategori' => 'INFORMASI FARMASI',
        'deskripsi' => 'Telusuri pengelompokan obat berdasarkan kategori untuk membantu menemukan informasi produk dengan lebih mudah.',
        'fitur' => ['Daftar kategori', 'Informasi produk'],
        'link' => 'index.php?page=daftarkategori',
        'tombol' => 'Lihat Kategori',
        'warna' => 'kategori'
    ],
    [
        'icon' => 'fa-heartbeat',
        'nomor' => '04',
        'judul' => 'Informasi Kesehatan',
        'kategori' => 'PUSAT INFORMASI',
        'deskripsi' => 'Kenali informasi rumah sakit dan halaman informasi yang tersedia sebelum mencari layanan yang sesuai dengan kebutuhan Anda.',
        'fitur' => ['Profil rumah sakit', 'Informasi layanan'],
        'link' => 'index.php?page=tentang',
        'tombol' => 'Kenali Rumah Sakit',
        'warna' => 'informasi'
    ]
];
?>

<style>
.layanan-section{position:relative;min-height:75vh;overflow:hidden;padding:32px 0 60px;background:radial-gradient(ellipse at 8% 8%,rgba(16,185,129,.1),transparent 32%),radial-gradient(ellipse at 95% 35%,rgba(59,130,246,.07),transparent 30%),#f4f7fb}
.layanan-container{position:relative;z-index:1;width:95%;max-width:1450px;margin:0 auto}
.layanan-hero{position:relative;overflow:hidden;padding:34px 38px;margin-bottom:28px;border-radius:20px;color:#fff;background:linear-gradient(115deg,#102a36 0%,#075e54 52%,#0d9488 100%);box-shadow:0 15px 35px rgba(8,127,112,.15)}
.layanan-hero::before,.layanan-hero::after{content:"";position:absolute;border:1px solid rgba(255,255,255,.13);border-radius:50%;pointer-events:none}
.layanan-hero::before{width:300px;height:300px;right:-65px;top:-175px;border-width:35px}
.layanan-hero::after{width:190px;height:190px;right:185px;bottom:-145px}
.layanan-hero-content{position:relative;z-index:1;max-width:760px}
.layanan-eyebrow{display:inline-flex;align-items:center;gap:8px;padding:7px 12px;margin-bottom:13px;border:1px solid rgba(255,255,255,.22);border-radius:30px;color:#a7f3d0;background:rgba(255,255,255,.08);font-size:11px;font-weight:800;letter-spacing:1.4px;text-transform:uppercase}
.layanan-hero h1{margin:0 0 12px;color:#fff;font-size:clamp(27px,3.2vw,40px);font-weight:800;line-height:1.25}
.layanan-hero p{max-width:650px;margin:0;color:rgba(255,255,255,.82);font-size:14px;line-height:1.85}
.layanan-hero-actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:23px}
.layanan-hero-btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;min-height:43px;padding:11px 17px;border:1px solid rgba(255,255,255,.25);border-radius:10px;color:#fff!important;background:rgba(255,255,255,.1);font-size:12px;font-weight:800;text-decoration:none!important;transition:.25s}
.layanan-hero-btn-primary{border-color:#d9f99d;color:#173b2e!important;background:#d9f99d}
.layanan-hero-btn-primary:hover{color:#173b2e!important;background:#ecfccb;transform:translateY(-2px)}
.layanan-hero-btn-outline:hover{color:#fff!important;background:rgba(255,255,255,.2);transform:translateY(-2px)}
.layanan-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:15px;margin-bottom:38px}
.layanan-stat{display:flex;align-items:center;gap:13px;min-width:0;padding:17px 19px;border:1px solid #e4eaf1;border-radius:15px;background:rgba(255,255,255,.88);box-shadow:0 5px 20px rgba(30,45,70,.035)}
.layanan-stat-icon{display:flex;flex:0 0 43px;align-items:center;justify-content:center;width:43px;height:43px;border-radius:12px;color:#087f70;background:#e1f8ed;font-size:18px}
.layanan-stat strong{display:block;margin-bottom:4px;color:#20372f;font-size:14px;font-weight:800}
.layanan-stat span{display:block;color:#829087;font-size:11px;line-height:1.5}
.layanan-heading{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:21px}
.layanan-heading-label{display:block;margin-bottom:7px;color:#0d9488;font-size:10px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase}
.layanan-heading h2{margin:0 0 7px;color:#172b25;font-size:clamp(22px,2.5vw,29px);font-weight:800}
.layanan-heading p{max-width:650px;margin:0;color:#77867e;font-size:13px;line-height:1.8}
.layanan-heading-mark{display:flex;flex:0 0 48px;align-items:center;justify-content:center;width:48px;height:48px;border:1px solid #d6eee2;border-radius:14px;color:#087f70;background:#e9faf1;font-size:20px}
.layanan-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px}
.layanan-card{position:relative;display:flex;flex-direction:column;min-width:0;overflow:hidden;padding:23px 21px 20px;border:1px solid #e4eaf1;border-radius:17px;background:#fff;box-shadow:0 7px 25px rgba(30,45,70,.045);transition:transform .25s ease,box-shadow .25s ease,border-color .25s ease}
.layanan-card:hover{transform:translateY(-5px);border-color:#b8e5d1;box-shadow:0 15px 32px rgba(8,127,112,.1)}
.layanan-card-top{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:21px}
.layanan-card-icon{display:flex;align-items:center;justify-content:center;width:53px;height:53px;border-radius:15px;color:#087f70;background:linear-gradient(145deg,#e8faf1,#d5f5e5);font-size:22px}
.layanan-card-number{color:#c0ccc6;font-size:12px;font-weight:900;letter-spacing:1px}
.layanan-card-category{margin-bottom:8px;color:#0d9488;font-size:9px;font-weight:800;letter-spacing:1px;line-height:1.6}
.layanan-card h3{margin:0 0 11px;color:#20372f;font-size:18px;font-weight:800;line-height:1.4;overflow-wrap:anywhere}
.layanan-card-description{flex:1;margin:0 0 18px;color:#78867f;font-size:12px;line-height:1.85}
.layanan-card-features{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:20px}
.layanan-card-feature{display:inline-flex;align-items:center;gap:5px;padding:6px 8px;border:1px solid #e5f2eb;border-radius:7px;color:#587267;background:#f5fcf7;font-size:10px;font-weight:700}
.layanan-card-feature i{color:#0d9488;font-size:10px}
.layanan-card-link{display:flex;align-items:center;justify-content:space-between;gap:10px;padding-top:15px;border-top:1px solid #edf2ef;color:#087f70!important;font-size:12px;font-weight:800;text-decoration:none!important}
.layanan-card-link i{transition:transform .2s ease}
.layanan-card-link:hover{color:#075c45!important}
.layanan-card-link:hover i{transform:translateX(4px)}
.layanan-card.obat .layanan-card-icon{color:#2563eb;background:linear-gradient(145deg,#eff6ff,#dbeafe)}
.layanan-card.obat .layanan-card-feature i{color:#2563eb}
.layanan-card.kategori .layanan-card-icon{color:#7c3aed;background:linear-gradient(145deg,#f5f0ff,#e9ddff)}
.layanan-card.kategori .layanan-card-feature i{color:#7c3aed}
.layanan-card.informasi .layanan-card-icon{color:#d97706;background:linear-gradient(145deg,#fffbeb,#fef0c7)}
.layanan-card.informasi .layanan-card-feature i{color:#d97706}
.layanan-flow{padding:25px 27px;margin-top:36px;border:1px solid #e2eee7;border-radius:18px;background:linear-gradient(120deg,#f0fdf4,#f8fbff)}
.layanan-flow-heading{margin-bottom:22px}
.layanan-flow-heading span{display:block;margin-bottom:7px;color:#0d9488;font-size:10px;font-weight:800;letter-spacing:1.4px;text-transform:uppercase}
.layanan-flow-heading h2{margin:0 0 7px;color:#172b25;font-size:21px;font-weight:800}
.layanan-flow-heading p{margin:0;color:#77867e;font-size:12px;line-height:1.8}
.layanan-flow-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:20px}
.layanan-flow-item{display:flex;align-items:flex-start;gap:13px;min-width:0}
.layanan-flow-number{display:flex;flex:0 0 37px;align-items:center;justify-content:center;width:37px;height:37px;border:1px solid #cdebd9;border-radius:11px;color:#087f70;background:#fff;font-size:13px;font-weight:900}
.layanan-flow-item h3{margin:2px 0 6px;color:#20372f;font-size:13px;font-weight:800}
.layanan-flow-item p{margin:0;color:#77867e;font-size:11px;line-height:1.8}
.layanan-cta{position:relative;display:flex;align-items:center;justify-content:space-between;gap:25px;overflow:hidden;padding:27px 30px;margin-top:25px;border-radius:17px;color:#fff;background:linear-gradient(115deg,#102a36,#075e54 60%,#0d9488)}
.layanan-cta-content{position:relative;z-index:1}
.layanan-cta h2{margin:0 0 8px;color:#fff;font-size:21px;font-weight:800}
.layanan-cta p{max-width:650px;margin:0;color:rgba(255,255,255,.78);font-size:12px;line-height:1.8}
.layanan-cta-btn{position:relative;z-index:1;display:inline-flex;flex-shrink:0;align-items:center;justify-content:center;gap:9px;padding:12px 17px;border:1px solid #d9f99d;border-radius:10px;color:#173b2e!important;background:#d9f99d;font-size:12px;font-weight:800;text-decoration:none!important;transition:.25s}
.layanan-cta-btn:hover{color:#173b2e!important;background:#ecfccb;transform:translateY(-2px)}
.layanan-disclaimer{display:flex;align-items:flex-start;gap:10px;padding:13px 15px;margin-top:18px;border:1px solid #e5eaf1;border-radius:11px;color:#738177;background:#fff;font-size:11px;line-height:1.8}
.layanan-disclaimer i{margin-top:3px;color:#0d9488;font-size:14px}
@media(max-width:1199.98px){.layanan-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:767.98px){.layanan-section{padding:23px 0 40px}.layanan-container{width:92%}.layanan-hero{padding:27px 23px;border-radius:17px}.layanan-stats{gap:10px;margin-bottom:30px}.layanan-stat{align-items:flex-start;flex-direction:column;padding:13px}.layanan-stat-icon{width:38px;height:38px;flex-basis:38px;font-size:16px}.layanan-stat strong{font-size:12px}.layanan-stat span{font-size:10px}.layanan-heading{align-items:flex-start}.layanan-heading-mark{flex-basis:40px;width:40px;height:40px}.layanan-grid{gap:13px}.layanan-card{padding:18px 15px}.layanan-card-top{margin-bottom:17px}.layanan-card-icon{width:45px;height:45px;font-size:19px}.layanan-card h3{font-size:16px}.layanan-card-description{font-size:11px}.layanan-flow{padding:21px 18px;margin-top:28px}.layanan-flow-grid{grid-template-columns:1fr;gap:18px}.layanan-cta{align-items:flex-start;flex-direction:column;padding:24px 21px}.layanan-cta h2{font-size:19px}}
@media(max-width:419.98px){.layanan-container{width:94%}.layanan-hero{padding:23px 18px}.layanan-hero-actions{flex-direction:column}.layanan-hero-btn{width:100%}.layanan-stats{grid-template-columns:1fr}.layanan-stat{align-items:center;flex-direction:row}.layanan-grid{grid-template-columns:1fr}.layanan-card{padding:21px}.layanan-card-description{font-size:12px}.layanan-heading h2{font-size:22px}.layanan-cta-btn{width:100%}}
@media(prefers-reduced-motion:reduce){.layanan-section *,.layanan-section *::before,.layanan-section *::after{transition-duration:.01ms!important;scroll-behavior:auto!important}}
</style>

<section class="layanan-section">
    <div class="layanan-container">
        <div class="layanan-hero">
            <div class="layanan-hero-content">
                <div class="layanan-eyebrow">
                    <i class="fas fa-heartbeat"></i>
                    Layanan Rumah Sakit Ahmadi
                </div>
                <h1>Pelayanan Kesehatan Terintegrasi</h1>
                <p>Temukan informasi dokter, katalog obat, dan informasi layanan melalui satu halaman yang praktis. RumahSakitAhmadi membantu Anda mengakses informasi yang tersedia dengan lebih mudah dan terarah.</p>
                <div class="layanan-hero-actions">
                    <a href="index.php?page=daftardokter" class="layanan-hero-btn layanan-hero-btn-primary">
                        <i class="fas fa-user-md"></i> Jelajahi Dokter
                    </a>
                    <a href="index.php?page=kontak" class="layanan-hero-btn layanan-hero-btn-outline">
                        <i class="fas fa-comment-medical"></i> Informasi Kontak
                    </a>
                </div>
            </div>
        </div>

        <div class="layanan-stats">
            <div class="layanan-stat">
                <div class="layanan-stat-icon"><i class="fas fa-user-md"></i></div>
                <div>
                    <strong>Informasi Dokter</strong>
                    <span>Profil dan jadwal praktik</span>
                </div>
            </div>
            <div class="layanan-stat">
                <div class="layanan-stat-icon"><i class="fas fa-capsules"></i></div>
                <div>
                    <strong>Katalog Obat</strong>
                    <span>Informasi produk dan stok</span>
                </div>
            </div>
            <div class="layanan-stat">
                <div class="layanan-stat-icon"><i class="fas fa-laptop-medical"></i></div>
                <div>
                    <strong>Akses Informasi</strong>
                    <span>Navigasi informasi lebih praktis</span>
                </div>
            </div>
        </div>

        <div class="layanan-heading">
            <div>
                <span class="layanan-heading-label">Jelajahi Informasi</span>
                <h2>Layanan dan Informasi Kami</h2>
                <p>Pilih bagian yang Anda butuhkan untuk melihat informasi lebih lanjut melalui halaman yang tersedia.</p>
            </div>
            <div class="layanan-heading-mark"><i class="fas fa-th-large"></i></div>
        </div>

        <div class="layanan-grid">
            <?php foreach ($layananList as $layanan): ?>
                <article class="layanan-card <?= htmlspecialchars($layanan['warna']) ?>">
                    <div class="layanan-card-top">
                        <div class="layanan-card-icon">
                            <i class="fas <?= htmlspecialchars($layanan['icon']) ?>"></i>
                        </div>
                        <span class="layanan-card-number"><?= htmlspecialchars($layanan['nomor']) ?></span>
                    </div>
                    <div class="layanan-card-category"><?= htmlspecialchars($layanan['kategori']) ?></div>
                    <h3><?= htmlspecialchars($layanan['judul']) ?></h3>
                    <p class="layanan-card-description"><?= htmlspecialchars($layanan['deskripsi']) ?></p>
                    <div class="layanan-card-features">
                        <?php foreach ($layanan['fitur'] as $fitur): ?>
                            <span class="layanan-card-feature">
                                <i class="fas fa-check-circle"></i>
                                <?= htmlspecialchars($fitur) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                    <a href="<?= htmlspecialchars($layanan['link']) ?>" class="layanan-card-link">
                        <?= htmlspecialchars($layanan['tombol']) ?>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="layanan-flow">
            <div class="layanan-flow-heading">
                <span>Panduan Penggunaan</span>
                <h2>Mulai dari Sini</h2>
                <p>Ikuti langkah sederhana berikut untuk menemukan informasi yang Anda perlukan.</p>
            </div>
            <div class="layanan-flow-grid">
                <div class="layanan-flow-item">
                    <div class="layanan-flow-number">01</div>
                    <div>
                        <h3>Pilih Informasi</h3>
                        <p>Tentukan apakah Anda ingin melihat dokter, katalog obat, atau informasi rumah sakit.</p>
                    </div>
                </div>
                <div class="layanan-flow-item">
                    <div class="layanan-flow-number">02</div>
                    <div>
                        <h3>Periksa Detail</h3>
                        <p>Buka halaman terkait untuk membaca informasi yang tersedia pada aplikasi.</p>
                    </div>
                </div>
                <div class="layanan-flow-item">
                    <div class="layanan-flow-number">03</div>
                    <div>
                        <h3>Hubungi Rumah Sakit</h3>
                        <p>Gunakan halaman kontak untuk mengonfirmasi jadwal, ketersediaan, atau informasi yang belum tercantum.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="layanan-cta">
            <div class="layanan-cta-content">
                <h2>Butuh Informasi Lebih Lanjut?</h2>
                <p>Kunjungi halaman kontak untuk mendapatkan informasi lebih lanjut mengenai Rumah Sakit Ahmadi.</p>
            </div>
            <a href="index.php?page=kontak" class="layanan-cta-btn">
                <i class="fas fa-headset"></i> Hubungi Kami
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="layanan-disclaimer">
            <i class="fas fa-info-circle"></i>
            <div>Informasi pada halaman ini mengikuti data yang tersedia di aplikasi. Untuk memastikan jadwal dokter, ketersediaan obat, dan layanan medis, silakan konfirmasi langsung kepada pihak rumah sakit. Informasi pada halaman ini bukan pengganti konsultasi tenaga medis.</div>
        </div>
    </div>
</section>