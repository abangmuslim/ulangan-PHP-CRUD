<?php
$kontakInfo = [
    [
        'icon' => 'fa-hospital',
        'judul' => 'Informasi Rumah Sakit',
        'deskripsi' => 'Cari informasi umum mengenai rumah sakit melalui halaman profil.',
        'detail' => 'Profil Rumah Sakit',
        'link' => 'index.php?page=tentang',
        'warna' => 'profil'
    ],
    [
        'icon' => 'fa-user-md',
        'judul' => 'Informasi Dokter',
        'deskripsi' => 'Lihat data dokter dan jadwal praktik yang tercatat pada aplikasi.',
        'detail' => 'Lihat Daftar Dokter',
        'link' => 'index.php?page=daftardokter',
        'warna' => 'dokter'
    ],
    [
        'icon' => 'fa-capsules',
        'judul' => 'Informasi Obat',
        'deskripsi' => 'Jelajahi katalog obat dan informasi stok yang tersedia pada aplikasi.',
        'detail' => 'Lihat Katalog Obat',
        'link' => 'index.php?page=daftarobat',
        'warna' => 'obat'
    ]
];

$kontakPanduan = [
    [
        'icon' => 'fa-search',
        'judul' => 'Tentukan Informasi',
        'deskripsi' => 'Pilih informasi yang ingin Anda ketahui, seperti profil rumah sakit, dokter, atau obat.'
    ],
    [
        'icon' => 'fa-clipboard-check',
        'judul' => 'Periksa Informasi',
        'deskripsi' => 'Baca informasi yang tersedia pada halaman aplikasi sebelum mengajukan pertanyaan.'
    ],
    [
        'icon' => 'fa-comments',
        'judul' => 'Konfirmasi Lebih Lanjut',
        'deskripsi' => 'Untuk informasi resmi yang belum tersedia, gunakan kanal komunikasi rumah sakit yang telah terverifikasi.'
    ]
];
?>

<style>
.kontak-section{position:relative;min-height:75vh;overflow:hidden;padding:32px 0 60px;background:radial-gradient(ellipse at 8% 8%,rgba(16,185,129,.1),transparent 32%),radial-gradient(ellipse at 95% 35%,rgba(59,130,246,.07),transparent 30%),#f4f7fb}
.kontak-container{position:relative;z-index:1;width:95%;max-width:1450px;margin:0 auto}
.kontak-hero{position:relative;overflow:hidden;padding:34px 38px;margin-bottom:28px;border-radius:20px;color:#fff;background:linear-gradient(115deg,#102a36 0%,#075e54 52%,#0d9488 100%);box-shadow:0 15px 35px rgba(8,127,112,.15)}
.kontak-hero::before,.kontak-hero::after{content:"";position:absolute;border:1px solid rgba(255,255,255,.13);border-radius:50%;pointer-events:none}
.kontak-hero::before{width:300px;height:300px;right:-65px;top:-175px;border-width:35px}
.kontak-hero::after{width:190px;height:190px;right:185px;bottom:-145px}
.kontak-hero-content{position:relative;z-index:1;max-width:760px}
.kontak-eyebrow{display:inline-flex;align-items:center;gap:8px;padding:7px 12px;margin-bottom:13px;border:1px solid rgba(255,255,255,.22);border-radius:30px;color:#a7f3d0;background:rgba(255,255,255,.08);font-size:11px;font-weight:800;letter-spacing:1.4px;text-transform:uppercase}
.kontak-hero h1{margin:0 0 12px;color:#fff;font-size:clamp(27px,3.2vw,40px);font-weight:800;line-height:1.25}
.kontak-hero p{max-width:650px;margin:0;color:rgba(255,255,255,.82);font-size:14px;line-height:1.85}
.kontak-hero-actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:23px}
.kontak-hero-btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;min-height:43px;padding:11px 17px;border:1px solid rgba(255,255,255,.25);border-radius:10px;color:#fff!important;background:rgba(255,255,255,.1);font-size:12px;font-weight:800;text-decoration:none!important;transition:.25s}
.kontak-hero-btn-primary{border-color:#d9f99d;color:#173b2e!important;background:#d9f99d}
.kontak-hero-btn-primary:hover{color:#173b2e!important;background:#ecfccb;transform:translateY(-2px)}
.kontak-hero-btn-outline:hover{color:#fff!important;background:rgba(255,255,255,.2);transform:translateY(-2px)}
.kontak-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:15px;margin-bottom:38px}
.kontak-stat{display:flex;align-items:center;gap:13px;min-width:0;padding:17px 19px;border:1px solid #e4eaf1;border-radius:15px;background:rgba(255,255,255,.88);box-shadow:0 5px 20px rgba(30,45,70,.035)}
.kontak-stat-icon{display:flex;flex:0 0 43px;align-items:center;justify-content:center;width:43px;height:43px;border-radius:12px;color:#087f70;background:#e1f8ed;font-size:18px}
.kontak-stat strong{display:block;margin-bottom:4px;color:#20372f;font-size:14px;font-weight:800}
.kontak-stat span{display:block;color:#829087;font-size:11px;line-height:1.5}
.kontak-heading{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:21px}
.kontak-heading-label{display:block;margin-bottom:7px;color:#0d9488;font-size:10px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase}
.kontak-heading h2{margin:0 0 7px;color:#172b25;font-size:clamp(22px,2.5vw,29px);font-weight:800}
.kontak-heading p{max-width:720px;margin:0;color:#77867e;font-size:13px;line-height:1.8}
.kontak-heading-mark{display:flex;flex:0 0 48px;align-items:center;justify-content:center;width:48px;height:48px;border:1px solid #d6eee2;border-radius:14px;color:#087f70;background:#e9faf1;font-size:20px}
.kontak-main{display:grid;grid-template-columns:minmax(0,1.15fr) minmax(300px,.85fr);gap:20px;align-items:start}
.kontak-panel{min-width:0;overflow:hidden;border:1px solid #e4eaf1;border-radius:17px;background:#fff;box-shadow:0 7px 25px rgba(30,45,70,.045)}
.kontak-form-panel{padding:26px}
.kontak-panel-heading{display:flex;align-items:center;gap:13px;padding-bottom:19px;margin-bottom:22px;border-bottom:1px solid #edf2ef}
.kontak-panel-icon{display:flex;flex:0 0 45px;align-items:center;justify-content:center;width:45px;height:45px;border-radius:13px;color:#087f70;background:#e1f8ed;font-size:19px}
.kontak-panel-heading h3{margin:0 0 5px;color:#20372f;font-size:17px;font-weight:800}
.kontak-panel-heading p{margin:0;color:#829087;font-size:11px;line-height:1.7}
.kontak-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
.kontak-form-group{min-width:0}
.kontak-form-group.full{grid-column:1/-1}
.kontak-form-label{display:block;margin-bottom:8px;color:#374b42;font-size:12px;font-weight:800}
.kontak-form-label span{color:#e11d48}
.kontak-form-control{display:block;width:100%;min-width:0;padding:12px 13px;border:1px solid #dfe8e3;border-radius:10px;outline:none;color:#263b33;background:#fbfdfc;font-family:inherit;font-size:12px;transition:border-color .2s,box-shadow .2s}
.kontak-form-control::placeholder{color:#a0aaa4}
.kontak-form-control:focus{border-color:#0d9488;box-shadow:0 0 0 3px rgba(13,148,136,.1);background:#fff}
textarea.kontak-form-control{min-height:135px;resize:vertical;line-height:1.8}
.kontak-form-hint{margin-top:7px;color:#89958e;font-size:10px;line-height:1.7}
.kontak-form-submit{display:inline-flex;align-items:center;justify-content:center;gap:9px;min-height:44px;padding:12px 18px;margin-top:19px;border:1px solid #087f70;border-radius:10px;color:#fff;background:linear-gradient(120deg,#087f70,#0d9488);font-family:inherit;font-size:12px;font-weight:800;cursor:pointer;transition:.2s}
.kontak-form-submit:hover{background:#06665b;transform:translateY(-1px)}
.kontak-form-notice{display:flex;align-items:flex-start;gap:9px;padding:12px 13px;margin-top:17px;border:1px solid #dbeafe;border-radius:10px;color:#526780;background:#f4f8ff;font-size:11px;line-height:1.8}
.kontak-form-notice i{margin-top:3px;color:#3b82f6}
.kontak-side{display:grid;gap:15px}
.kontak-side-panel{padding:22px;border:1px solid #e4eaf1;border-radius:17px;background:#fff;box-shadow:0 7px 25px rgba(30,45,70,.045)}
.kontak-side-heading{display:flex;align-items:center;gap:11px;margin-bottom:17px}
.kontak-side-heading-icon{display:flex;align-items:center;justify-content:center;width:41px;height:41px;border-radius:12px;color:#087f70;background:#e1f8ed;font-size:17px}
.kontak-side-heading h3{margin:0;color:#20372f;font-size:15px;font-weight:800}
.kontak-side-heading p{margin:4px 0 0;color:#829087;font-size:11px}
.kontak-info-list{display:grid;gap:10px}
.kontak-info-item{display:flex;align-items:flex-start;gap:12px;padding:13px;border:1px solid #edf2ef;border-radius:11px;background:#fbfdfc}
.kontak-info-icon{display:flex;flex:0 0 34px;align-items:center;justify-content:center;width:34px;height:34px;border-radius:9px;color:#0d9488;background:#e8f8f0;font-size:14px}
.kontak-info-item h4{margin:1px 0 5px;color:#33483e;font-size:12px;font-weight:800}
.kontak-info-item p{margin:0;color:#7d8b83;font-size:11px;line-height:1.8;overflow-wrap:anywhere}
.kontak-info-item a{color:#087f70;font-weight:700;text-decoration:none}
.kontak-info-item a:hover{color:#075c45;text-decoration:underline}
.kontak-quick-links{display:grid;gap:9px}
.kontak-quick-link{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 13px;border:1px solid #e6eee9;border-radius:10px;color:#42594e!important;background:#fff;font-size:12px;font-weight:700;text-decoration:none!important;transition:.2s}
.kontak-quick-link span{display:flex;align-items:center;gap:10px}
.kontak-quick-link span i{width:17px;color:#0d9488;text-align:center}
.kontak-quick-link>i{color:#9aaba1;font-size:11px;transition:transform .2s}
.kontak-quick-link:hover{border-color:#b8e5d1;color:#087f70!important;background:#f4fcf7}
.kontak-quick-link:hover>i{transform:translateX(3px)}
.kontak-flow{padding:26px 27px;margin-top:36px;border:1px solid #e2eee7;border-radius:18px;background:linear-gradient(120deg,#f0fdf4,#f8fbff)}
.kontak-flow-heading{margin-bottom:22px}
.kontak-flow-heading span{display:block;margin-bottom:7px;color:#0d9488;font-size:10px;font-weight:800;letter-spacing:1.4px;text-transform:uppercase}
.kontak-flow-heading h2{margin:0 0 7px;color:#172b25;font-size:21px;font-weight:800}
.kontak-flow-heading p{margin:0;color:#77867e;font-size:12px;line-height:1.8}
.kontak-flow-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:20px}
.kontak-flow-item{display:flex;align-items:flex-start;gap:13px;min-width:0}
.kontak-flow-number{display:flex;flex:0 0 37px;align-items:center;justify-content:center;width:37px;height:37px;border:1px solid #cdebd9;border-radius:11px;color:#087f70;background:#fff;font-size:13px;font-weight:900}
.kontak-flow-item h3{margin:2px 0 6px;color:#20372f;font-size:13px;font-weight:800}
.kontak-flow-item p{margin:0;color:#77867e;font-size:11px;line-height:1.8}
.kontak-cta{position:relative;display:flex;align-items:center;justify-content:space-between;gap:25px;overflow:hidden;padding:27px 30px;margin-top:25px;border-radius:17px;color:#fff;background:linear-gradient(115deg,#102a36,#075e54 60%,#0d9488)}
.kontak-cta-content{position:relative;z-index:1}
.kontak-cta h2{margin:0 0 8px;color:#fff;font-size:21px;font-weight:800}
.kontak-cta p{max-width:650px;margin:0;color:rgba(255,255,255,.78);font-size:12px;line-height:1.8}
.kontak-cta-btn{position:relative;z-index:1;display:inline-flex;flex-shrink:0;align-items:center;justify-content:center;gap:9px;padding:12px 17px;border:1px solid #d9f99d;border-radius:10px;color:#173b2e!important;background:#d9f99d;font-size:12px;font-weight:800;text-decoration:none!important;transition:.25s}
.kontak-cta-btn:hover{color:#173b2e!important;background:#ecfccb;transform:translateY(-2px)}
.kontak-disclaimer{display:flex;align-items:flex-start;gap:10px;padding:13px 15px;margin-top:18px;border:1px solid #e5eaf1;border-radius:11px;color:#738177;background:#fff;font-size:11px;line-height:1.8}
.kontak-disclaimer i{margin-top:3px;color:#0d9488;font-size:14px}
@media(max-width:1199.98px){.kontak-main{grid-template-columns:minmax(0,1fr) minmax(280px,.8fr)}}
@media(max-width:991.98px){.kontak-main{grid-template-columns:1fr}.kontak-side{grid-template-columns:repeat(2,minmax(0,1fr))}.kontak-side-panel{min-width:0}.kontak-flow-grid{grid-template-columns:1fr;gap:18px}}
@media(max-width:767.98px){.kontak-section{padding:23px 0 40px}.kontak-container{width:92%}.kontak-hero{padding:27px 23px;border-radius:17px}.kontak-stats{gap:10px;margin-bottom:30px}.kontak-stat{align-items:flex-start;flex-direction:column;padding:13px}.kontak-stat-icon{width:38px;height:38px;flex-basis:38px;font-size:16px}.kontak-stat strong{font-size:12px}.kontak-stat span{font-size:10px}.kontak-heading{align-items:flex-start}.kontak-heading-mark{flex-basis:40px;width:40px;height:40px}.kontak-form-panel{padding:21px 17px}.kontak-side{grid-template-columns:1fr}.kontak-flow{padding:21px 18px;margin-top:28px}.kontak-cta{align-items:flex-start;flex-direction:column;padding:24px 21px}.kontak-cta h2{font-size:19px}}
@media(max-width:419.98px){.kontak-container{width:94%}.kontak-hero{padding:23px 18px}.kontak-hero-actions{flex-direction:column}.kontak-hero-btn{width:100%}.kontak-stats{grid-template-columns:1fr}.kontak-stat{align-items:center;flex-direction:row}.kontak-form-grid{grid-template-columns:1fr}.kontak-form-group.full{grid-column:auto}.kontak-form-submit,.kontak-cta-btn{width:100%}.kontak-heading h2{font-size:22px}}
@media(prefers-reduced-motion:reduce){.kontak-section *,.kontak-section *::before,.kontak-section *::after{transition-duration:.01ms!important;scroll-behavior:auto!important}}
</style>

<section class="kontak-section">
    <div class="kontak-container">
        <div class="kontak-hero">
            <div class="kontak-hero-content">
                <div class="kontak-eyebrow">
                    <i class="fas fa-headset"></i>
                    Kontak RumahSakitAhmadi
                </div>
                <h1>Kami Siap Membantu Anda Menemukan Informasi</h1>
                <p>Gunakan halaman kontak ini untuk menemukan informasi yang tersedia, menjelajahi layanan aplikasi, dan mengetahui langkah yang dapat dilakukan untuk memperoleh informasi lebih lanjut dari pihak rumah sakit.</p>
                <div class="kontak-hero-actions">
                    <a href="index.php?page=layanan" class="kontak-hero-btn kontak-hero-btn-primary">
                        <i class="fas fa-th-large"></i> Jelajahi Layanan
                    </a>
                    <a href="index.php?page=tentang" class="kontak-hero-btn kontak-hero-btn-outline">
                        <i class="fas fa-hospital-alt"></i> Tentang Kami
                    </a>
                </div>
            </div>
        </div>

        <div class="kontak-stats">
            <div class="kontak-stat">
                <div class="kontak-stat-icon"><i class="fas fa-user-md"></i></div>
                <div>
                    <strong>Informasi Dokter</strong>
                    <span>Profil dan jadwal praktik</span>
                </div>
            </div>
            <div class="kontak-stat">
                <div class="kontak-stat-icon"><i class="fas fa-capsules"></i></div>
                <div>
                    <strong>Informasi Obat</strong>
                    <span>Katalog dan data produk</span>
                </div>
            </div>
            <div class="kontak-stat">
                <div class="kontak-stat-icon"><i class="fas fa-comments"></i></div>
                <div>
                    <strong>Pusat Informasi</strong>
                    <span>Akses halaman bantuan</span>
                </div>
            </div>
        </div>

        <div class="kontak-heading">
            <div>
                <span class="kontak-heading-label">Pusat Komunikasi</span>
                <h2>Hubungi dan Temukan Informasi</h2>
                <p>Pilih informasi yang diperlukan atau siapkan pertanyaan Anda untuk disampaikan melalui kanal komunikasi resmi rumah sakit.</p>
            </div>
            <div class="kontak-heading-mark"><i class="fas fa-comment-dots"></i></div>
        </div>

        <div class="kontak-main">
            <div class="kontak-panel kontak-form-panel">
                <div class="kontak-panel-heading">
                    <div class="kontak-panel-icon"><i class="fas fa-paper-plane"></i></div>
                    <div>
                        <h3>Formulir Pesan</h3>
                        <p>Siapkan pertanyaan atau pesan yang ingin Anda sampaikan.</p>
                    </div>
                </div>

                <form id="formKontak" method="post">
                    <div class="kontak-form-grid">
                        <div class="kontak-form-group">
                            <label class="kontak-form-label" for="nama">Nama Lengkap <span>*</span></label>
                            <input class="kontak-form-control" type="text" id="nama" name="nama" placeholder="Masukkan nama Anda" autocomplete="name" maxlength="100" required>
                        </div>
                        <div class="kontak-form-group">
                            <label class="kontak-form-label" for="email">Alamat Email <span>*</span></label>
                            <input class="kontak-form-control" type="email" id="email" name="email" placeholder="nama@email.com" autocomplete="email" maxlength="150" required>
                        </div>
                        <div class="kontak-form-group full">
                            <label class="kontak-form-label" for="subjek">Subjek Pesan <span>*</span></label>
                            <input class="kontak-form-control" type="text" id="subjek" name="subjek" placeholder="Contoh: Pertanyaan jadwal dokter" maxlength="150" required>
                        </div>
                        <div class="kontak-form-group full">
                            <label class="kontak-form-label" for="pesan">Isi Pesan <span>*</span></label>
                            <textarea class="kontak-form-control" id="pesan" name="pesan" placeholder="Tuliskan pertanyaan atau informasi yang ingin Anda sampaikan..." maxlength="3000" required></textarea>
                            <div class="kontak-form-hint">Hindari menuliskan kata sandi, data pembayaran, atau informasi medis pribadi yang sensitif.</div>
                        </div>
                    </div>
                    <button type="submit" class="kontak-form-submit">
                        <i class="fas fa-paper-plane"></i> Siapkan Pesan
                    </button>
                </form>

                <div class="kontak-form-notice">
                    <i class="fas fa-info-circle"></i>
                    <div>Formulir ini belum terhubung ke sistem pengiriman pesan. Tombol di atas hanya memvalidasi isian dan menyiapkan pesan; pesan tidak dikirim atau disimpan ke server.</div>
                </div>
            </div>

            <div class="kontak-side">
                <div class="kontak-side-panel">
                    <div class="kontak-side-heading">
                        <div class="kontak-side-heading-icon"><i class="fas fa-address-card"></i></div>
                        <div>
                            <h3>Informasi Kontak</h3>
                            <p>Akses informasi melalui aplikasi</p>
                        </div>
                    </div>
                    <div class="kontak-info-list">
                        <div class="kontak-info-item">
                            <div class="kontak-info-icon"><i class="fas fa-hospital"></i></div>
                            <div>
                                <h4>Profil Rumah Sakit</h4>
                                <p>Lihat informasi umum melalui halaman Tentang.</p>
                                <p><a href="index.php?page=tentang">Buka profil rumah sakit</a></p>
                            </div>
                        </div>
                        <div class="kontak-info-item">
                            <div class="kontak-info-icon"><i class="fas fa-user-md"></i></div>
                            <div>
                                <h4>Informasi Dokter</h4>
                                <p>Periksa data dokter dan jadwal yang tercatat.</p>
                                <p><a href="index.php?page=daftardokter">Lihat daftar dokter</a></p>
                            </div>
                        </div>
                        <div class="kontak-info-item">
                            <div class="kontak-info-icon"><i class="fas fa-map-signs"></i></div>
                            <div>
                                <h4>Informasi Layanan</h4>
                                <p>Jelajahi bagian layanan yang tersedia di aplikasi.</p>
                                <p><a href="index.php?page=layanan">Lihat layanan</a></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kontak-side-panel">
                    <div class="kontak-side-heading">
                        <div class="kontak-side-heading-icon"><i class="fas fa-link"></i></div>
                        <div>
                            <h3>Akses Cepat</h3>
                            <p>Halaman yang mungkin Anda perlukan</p>
                        </div>
                    </div>
                    <div class="kontak-quick-links">
                        <a href="index.php?page=daftardokter" class="kontak-quick-link">
                            <span><i class="fas fa-user-md"></i> Daftar Dokter</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="index.php?page=daftarobat" class="kontak-quick-link">
                            <span><i class="fas fa-capsules"></i> Daftar Obat</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="index.php?page=daftarkategori" class="kontak-quick-link">
                            <span><i class="fas fa-layer-group"></i> Kategori Obat</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="index.php?page=tentang" class="kontak-quick-link">
                            <span><i class="fas fa-info-circle"></i> Tentang Kami</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="kontak-flow">
            <div class="kontak-flow-heading">
                <span>Panduan Komunikasi</span>
                <h2>Bagaimana Mendapatkan Informasi?</h2>
                <p>Ikuti langkah sederhana berikut agar pertanyaan Anda lebih terarah.</p>
            </div>
            <div class="kontak-flow-grid">
                <?php foreach ($kontakPanduan as $index => $panduan): ?>
                    <div class="kontak-flow-item">
                        <div class="kontak-flow-number"><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?></div>
                        <div>
                            <h3><?= htmlspecialchars($panduan['judul']) ?></h3>
                            <p><?= htmlspecialchars($panduan['deskripsi']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="kontak-cta">
            <div class="kontak-cta-content">
                <h2>Mulai Jelajahi Informasi Rumah Sakit</h2>
                <p>Temukan informasi dokter, obat, dan layanan yang tersedia melalui halaman aplikasi RumahSakitAhmadi.</p>
            </div>
            <a href="index.php?page=layanan" class="kontak-cta-btn">
                <i class="fas fa-compass"></i> Jelajahi Layanan
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="kontak-disclaimer">
            <i class="fas fa-info-circle"></i>
            <div>Nomor telepon, alamat email, alamat rumah sakit, jam operasional, dan kanal komunikasi resmi perlu diisi setelah dikonfirmasi. Halaman ini bukan layanan gawat darurat. Untuk kondisi darurat, hubungi layanan darurat resmi di wilayah Anda.</div>
        </div>
    </div>
</section>

<script>
document.getElementById('formKontak')?.addEventListener('submit', function(e) {
    e.preventDefault();
    if (!this.reportValidity()) return;

    const nama = document.getElementById('nama').value.trim();
    const email = document.getElementById('email').value.trim();
    const subjek = document.getElementById('subjek').value.trim();
    const pesan = document.getElementById('pesan').value.trim();

    if (!nama || !email || !subjek || !pesan) return;

    const isiPesan = 'Nama: ' + nama + '\nEmail: ' + email + '\nSubjek: ' + subjek + '\n\nPesan:\n' + pesan;

    const area = document.createElement('textarea');
    area.value = isiPesan;
    area.setAttribute('readonly', '');
    area.style.position = 'fixed';
    area.style.opacity = '0';
    document.body.appendChild(area);
    area.select();

    let tersalin = false;
    try {
        tersalin = document.execCommand('copy');
    } catch (error) {}

    area.remove();

    if (tersalin) {
        alert('Pesan berhasil disiapkan dan disalin. Silakan kirim melalui kanal komunikasi resmi rumah sakit. Pesan belum dikirim ke server.');
    } else {
        alert('Isian Anda sudah divalidasi, tetapi pesan belum dikirim. Silakan salin isi pesan secara manual dan kirim melalui kanal komunikasi resmi rumah sakit.');
    }
});
</script>