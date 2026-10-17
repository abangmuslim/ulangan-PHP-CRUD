
<?php
/*
|--------------------------------------------------------------------------
| KATALOG OBAT - RUMAH SAKIT AHMADI
| PHP Native + AdminLTE 3.2
|--------------------------------------------------------------------------
| Fitur: Statistik obat, kategori, merek, foto, harga, stok,
| kartu responsif, dan tautan detail obat.
|--------------------------------------------------------------------------
*/

// =====================================================
// 1. FUNGSI PENDUKUNG
// =====================================================
function katalogCariKolomNama($koneksi, $tabel, $kandidat) {
    $kolomTersedia = [];
    $resultKolom = mysqli_query($koneksi, "SHOW COLUMNS FROM `$tabel`");

    if ($resultKolom) {
        while ($kolom = mysqli_fetch_assoc($resultKolom)) {
            $kolomTersedia[] = $kolom['Field'];
        }
    }

    foreach ($kandidat as $namaKolom) {
        if (in_array($namaKolom, $kolomTersedia, true)) {
            return $namaKolom;
        }
    }

    return null;
}

function katalogNamaTabel($koneksi, $tabel, $kolomId, $kolomNama) {
    if ($kolomNama === null) {
        return "NULL";
    }

    return "`$tabel`.`$kolomNama`";
}

// =====================================================
// 2. DETEKSI KOLOM KATEGORI DAN MEREK
// =====================================================
$kolomNamaKategori = katalogCariKolomNama(
    $koneksi,
    'kategori',
    ['namakategori', 'nama_kategori', 'nama', 'kategori']
);

$kolomNamaMerk = katalogCariKolomNama(
    $koneksi,
    'merk',
    ['namamerk', 'namamerk', 'nama_merk', 'nama', 'merk']
);

$selectKategori = katalogNamaTabel(
    $koneksi, 'kategori', 'idkategori', $kolomNamaKategori
);

$selectMerk = katalogNamaTabel(
    $koneksi, 'merk', 'idmerk', $kolomNamaMerk
);

// =====================================================
// 3. RINGKASAN KATALOG
// =====================================================
$queryRingkasan = "SELECT
    (SELECT COUNT(*) FROM obat) AS totalObat,
    (SELECT COUNT(*) FROM kategori) AS totalKategori,
    (SELECT COUNT(*) FROM merk) AS totalMerk";

$resultRingkasan = mysqli_query($koneksi, $queryRingkasan);

$ringkasan = [
    'totalObat' => 0,
    'totalKategori' => 0,
    'totalMerk' => 0
];

if ($resultRingkasan && $dataRingkasan = mysqli_fetch_assoc($resultRingkasan)) {
    $ringkasan = $dataRingkasan;
}

// =====================================================
// 4. DATA OBAT
// =====================================================
$query = "SELECT
              obat.*,
              $selectKategori AS namakategori,
              $selectMerk AS namamerk
          FROM obat
          LEFT JOIN kategori
              ON obat.idkategori = kategori.idkategori
          LEFT JOIN merk
              ON obat.idmerk = merk.idmerk
          ORDER BY obat.idobat DESC";

$result = mysqli_query($koneksi, $query);
?>


<style>
    .katalog-section{min-height:60vh;padding:22px 0 35px;background:radial-gradient(ellipse at top right,rgba(16,185,129,.08),transparent 40%),#f5f7fb}
    .katalog-container{width:100%;max-width:1800px;margin:0 auto;padding:0 18px}
    .katalog-hero{position:relative;overflow:hidden;padding:24px 28px;margin-bottom:18px;color:#fff;border-radius:17px;background:linear-gradient(120deg,#102f35 0%,#087f70 58%,#10b981 100%);box-shadow:0 12px 30px rgba(8,127,112,.15)}
    .katalog-hero::after{content:"";position:absolute;width:230px;height:230px;top:-125px;right:8%;border:35px solid rgba(255,255,255,.07);border-radius:50%;pointer-events:none}
    .katalog-hero-content{position:relative;z-index:1}
    .katalog-eyebrow{display:inline-flex;align-items:center;gap:7px;margin-bottom:7px;color:#c9fff0;font-size:.7rem;font-weight:700;letter-spacing:1.4px;text-transform:uppercase}
    .katalog-hero h1{margin:0 0 7px;font-size:clamp(1.4rem,2.5vw,2rem);font-weight:800;letter-spacing:-.7px;line-height:1.25}
    .katalog-hero h1 span{color:#ead470}
    .katalog-hero p{max-width:560px;margin:0;color:rgba(255,255,255,.82);font-size:.86rem;line-height:1.6}
    .katalog-hero-actions{display:flex;flex-wrap:wrap;gap:8px;margin-top:16px}
    .katalog-hero-btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:8px 13px;border:1px solid rgba(255,255,255,.3);border-radius:8px;color:#fff!important;background:rgba(255,255,255,.12);font-size:.76rem;font-weight:600;text-decoration:none!important;transition:.2s ease}
    .katalog-hero-btn:hover,.katalog-hero-btn-light{color:#075e54!important;background:#fff;border-color:#fff}
    .katalog-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin-bottom:24px}
    .katalog-stat{display:flex;align-items:center;gap:12px;min-width:0;padding:13px 15px;border:1px solid #e9eef4;border-radius:12px;background:#fff;box-shadow:0 4px 14px rgba(24,39,75,.035);transition:transform .2s ease,box-shadow .2s ease}
    .katalog-stat:hover{transform:translateY(-2px);box-shadow:0 8px 18px rgba(24,39,75,.07)}
    .katalog-stat-icon{display:flex;flex:0 0 39px;align-items:center;justify-content:center;width:39px;height:39px;border-radius:10px;font-size:1rem}
    .katalog-stat-icon.green{color:#059669;background:#dff9ed}
    .katalog-stat-icon.blue{color:#2563eb;background:#e5efff}
    .katalog-stat-icon.orange{color:#d97706;background:#fff1d6}
    .katalog-stat-label{margin-bottom:2px;color:#7b8798;font-size:.7rem}
    .katalog-stat-number{color:#1e293b;font-size:1.25rem;font-weight:800;line-height:1.2}
    .katalog-toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:13px;scroll-margin-top:20px}
    .katalog-toolbar h2{margin:0;color:#243047;font-size:1.05rem;font-weight:800}
    .katalog-toolbar p{margin:3px 0 0;color:#8993a3;font-size:.75rem}
    .katalog-total{flex-shrink:0;padding:6px 10px;border-radius:20px;color:#087f70;background:#dff9ed;font-size:.72rem;font-weight:700}
    .katalog-grid{display:grid;grid-template-columns:repeat(10,minmax(0,1fr));gap:12px}
    .katalog-card{display:flex;flex-direction:column;min-width:0;overflow:hidden;border:1px solid #e9eef4;border-radius:10px;background:#fff;transition:transform .2s ease,box-shadow .2s ease}
    .katalog-card:hover{transform:translateY(-3px);box-shadow:0 9px 20px rgba(30,45,70,.10)}
    .katalog-photo-wrapper{position:relative;width:100%;aspect-ratio:1/1;overflow:hidden;background:linear-gradient(135deg,#e5f8f1,#eaf1ff)}
    .katalog-photo{display:block;width:100%;height:100%;object-fit:cover;transition:transform .3s ease}
    .katalog-card:hover .katalog-photo{transform:scale(1.07)}
    .katalog-photo-placeholder{display:flex;align-items:center;justify-content:center;width:100%;height:100%;color:#0d9488;font-size:1.5rem}
    .katalog-category-label{position:absolute;top:5px;left:5px;max-width:calc(100% - 10px);padding:3px 5px;overflow:hidden;border-radius:5px;color:#344256;background:rgba(255,255,255,.95);font-size:.61rem;font-weight:600;text-overflow:ellipsis;white-space:nowrap}
    .katalog-card-body{display:flex;flex:1;flex-direction:column;min-width:0;padding:9px 8px 10px}
    .katalog-product-name{display:-webkit-box;min-height:2.5em;margin:0 0 6px;overflow:hidden;color:#293548;font-size:.76rem;font-weight:700;line-height:1.25;overflow-wrap:anywhere;-webkit-box-orient:vertical;-webkit-line-clamp:2}
    .katalog-meta{display:flex;flex-direction:column;gap:4px;margin-bottom:8px;color:#7b8798;font-size:.65rem}
    .katalog-meta-item{display:flex;align-items:center;gap:5px;min-width:0}
    .katalog-meta-item i{flex-shrink:0;width:11px;color:#98a5b5;text-align:center}
    .katalog-meta-item span{min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
    .katalog-price{margin-top:auto;margin-bottom:5px;color:#087f70;font-size:.88rem;font-weight:800;line-height:1.3;overflow-wrap:anywhere}
    .katalog-stock{margin-bottom:8px;color:#7b8798;font-size:.65rem}
    .katalog-stock i{margin-right:3px}
    .katalog-detail-button{display:flex;align-items:center;justify-content:center;gap:4px;width:100%;padding:6px 3px;border:0;border-radius:6px;color:#fff!important;background:#087f70;font-size:.67rem;font-weight:600;text-align:center;text-decoration:none!important;transition:background .2s ease}
    .katalog-detail-button:hover{color:#fff!important;background:#06665b}
    .katalog-empty{grid-column:1/-1;padding:38px 15px;border:1px dashed #d8e1eb;border-radius:13px;background:#fff;color:#7b8798;text-align:center}
    .katalog-empty i{margin-bottom:10px;color:#0d9488;font-size:2rem}
    .katalog-empty h5{color:#293548;font-weight:700}
    @media(max-width:1599.98px){.katalog-grid{grid-template-columns:repeat(8,minmax(0,1fr))}}
    @media(max-width:1199.98px){.katalog-grid{grid-template-columns:repeat(6,minmax(0,1fr));gap:10px}}
    @media(max-width:991.98px){.katalog-container{padding:0 14px}.katalog-grid{grid-template-columns:repeat(5,minmax(0,1fr))}}
    @media(max-width:767.98px){.katalog-section{padding-top:16px}.katalog-hero{padding:21px 19px;border-radius:13px}.katalog-stats{gap:8px;margin-bottom:20px}.katalog-stat{display:block;padding:10px}.katalog-stat-icon{width:32px;height:32px;margin-bottom:7px;font-size:.85rem}.katalog-stat-label{font-size:.65rem}.katalog-stat-number{font-size:1.05rem}.katalog-grid{grid-template-columns:repeat(4,minmax(0,1fr));gap:9px}.katalog-card-body{padding:8px 6px}}
    @media(max-width:575.98px){.katalog-container{padding:0 10px}.katalog-hero{padding:19px 16px}.katalog-hero p{font-size:.8rem}.katalog-hero-btn{padding:7px 10px;font-size:.7rem}.katalog-stats{gap:6px}.katalog-stat{padding:8px 6px}.katalog-stat-icon{width:29px;height:29px}.katalog-stat-label{min-height:2em;font-size:.61rem}.katalog-stat-number{font-size:.95rem}.katalog-grid{grid-template-columns:repeat(3,minmax(0,1fr));gap:8px}.katalog-product-name{font-size:.72rem}.katalog-meta,.katalog-stock{font-size:.62rem}.katalog-price{font-size:.81rem}.katalog-detail-button{font-size:.63rem}.katalog-toolbar h2{font-size:.95rem}}
    @media(max-width:359.98px){.katalog-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
    @media(prefers-reduced-motion:reduce){.katalog-section *,.katalog-section *::before,.katalog-section *::after{transition-duration:.01ms!important}}
</style>


<section class="katalog-section">
    <div class="katalog-container">

        <div class="katalog-hero">
            <div class="katalog-hero-content">
                <div class="katalog-eyebrow">
                    <i class="fas fa-prescription-bottle-alt"></i>
                    Katalog Farmasi Rumah Sakit
                </div>
                <h1>Informasi <span>Obat Rumah Sakit Ahmadi</span></h1>
                <p>
                    Jelajahi informasi obat yang tercatat di Rumah Sakit Ahmadi,
                    termasuk kategori, merek, harga, dan jumlah stok.
                </p>
                <div class="katalog-hero-actions">
                    <a href="#daftarObat" class="katalog-hero-btn katalog-hero-btn-light">
                        <i class="fas fa-pills"></i> Jelajahi Obat
                    </a>
                    <a href="index.php?page=home" class="katalog-hero-btn">
                        <i class="fas fa-home"></i> Halaman Utama
                    </a>
                </div>
            </div>
        </div>

        <div class="katalog-stats">
            <div class="katalog-stat">
                <div class="katalog-stat-icon green"><i class="fas fa-pills"></i></div>
                <div>
                    <div class="katalog-stat-label">Total Obat</div>
                    <div class="katalog-stat-number">
                        <?= number_format((int)$ringkasan['totalObat'], 0, ',', '.'); ?>
                    </div>
                </div>
            </div>
            <div class="katalog-stat">
                <div class="katalog-stat-icon blue"><i class="fas fa-tags"></i></div>
                <div>
                    <div class="katalog-stat-label">Kategori</div>
                    <div class="katalog-stat-number">
                        <?= number_format((int)$ringkasan['totalKategori'], 0, ',', '.'); ?>
                    </div>
                </div>
            </div>
            <div class="katalog-stat">
                <div class="katalog-stat-icon orange"><i class="fas fa-copyright"></i></div>
                <div>
                    <div class="katalog-stat-label">Merek</div>
                    <div class="katalog-stat-number">
                        <?= number_format((int)$ringkasan['totalMerk'], 0, ',', '.'); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="katalog-toolbar" id="daftarObat">
            <div>
                <h2><i class="fas fa-th-large mr-1" style="color:#087f70;"></i> Daftar Obat</h2>
                <p>Pilih obat untuk melihat informasi selengkapnya.</p>
            </div>
            <?php if ($result): ?>
                <span class="katalog-total">
                    <?= number_format(mysqli_num_rows($result), 0, ',', '.'); ?> Obat
                </span>
            <?php endif; ?>
        </div>

        <div class="katalog-grid">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($obat = mysqli_fetch_assoc($result)): ?>
                    <?php
                    $idobat = (int)($obat['idobat'] ?? 0);
                    $namaObat = trim($obat['namaobat'] ?? 'Obat');
                    $foto = trim($obat['foto'] ?? '');
                    $fileFoto = basename($foto);
                    $pathFoto = 'assets/images/obat/' . $fileFoto;
                    $pathFotoFisik = __DIR__ . '/../../assets/images/obat/' . $fileFoto;
                    $fotoTersedia = $fileFoto !== '' && is_file($pathFotoFisik);
                    $namaKategori = trim($obat['namakategori'] ?? '');
                    $namaMerk = trim($obat['namamerk'] ?? '');
                    $namaKategori = $namaKategori !== '' ? $namaKategori : '-';
                    $namaMerk = $namaMerk !== '' ? $namaMerk : '-';
                    $hargaObat = (float)($obat['harga'] ?? 0);
                    $stokObat = (int)($obat['stok'] ?? 0);
                    ?>
                    <article class="katalog-card">
                        <div class="katalog-photo-wrapper">
                            <?php if ($fotoTersedia): ?>
                                <img
                                    src="<?= htmlspecialchars($pathFoto, ENT_QUOTES, 'UTF-8'); ?>"
                                    alt="<?= htmlspecialchars($namaObat, ENT_QUOTES, 'UTF-8'); ?>"
                                    class="katalog-photo"
                                    loading="lazy">
                            <?php else: ?>
                                <div class="katalog-photo-placeholder">
                                    <i class="fas fa-prescription-bottle-alt"></i>
                                </div>
                            <?php endif; ?>
                            <?php if ($namaKategori !== '-'): ?>
                                <span class="katalog-category-label">
                                    <?= htmlspecialchars($namaKategori, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="katalog-card-body">
                            <h3 class="katalog-product-name"
                                title="<?= htmlspecialchars($namaObat, ENT_QUOTES, 'UTF-8'); ?>">
                                <?= htmlspecialchars($namaObat, ENT_QUOTES, 'UTF-8'); ?>
                            </h3>

                            <div class="katalog-meta">
                                <div class="katalog-meta-item"
                                     title="Kategori: <?= htmlspecialchars($namaKategori, ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="fas fa-tag"></i>
                                    <span><?= htmlspecialchars($namaKategori, ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                                <div class="katalog-meta-item"
                                     title="Merek: <?= htmlspecialchars($namaMerk, ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="fas fa-copyright"></i>
                                    <span><?= htmlspecialchars($namaMerk, ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                            </div>

                            <div class="katalog-price">
                                Rp<?= number_format($hargaObat, 0, ',', '.'); ?>
                            </div>
                            <div class="katalog-stock">
                                <i class="fas fa-cubes"></i>
                                Stok: <?= number_format($stokObat, 0, ',', '.'); ?>
                            </div>
                            <a href="index.php?page=detilobat&amp;id=<?= $idobat; ?>"
                               class="katalog-detail-button">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="katalog-empty">
                    <i class="fas fa-prescription-bottle-alt"></i>
                    <h5>Belum Ada Data Obat</h5>
                    <p>Data obat belum tersedia di katalog. Silakan kunjungi kembali nanti.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
