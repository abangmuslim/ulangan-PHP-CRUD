
<?php
/*
|--------------------------------------------------------------------------
| DAFTAR KATEGORI OBAT - KLINIK AHMADI PAKET 7
| PHP Native + MySQLi + AdminLTE 3.2
|--------------------------------------------------------------------------
*/

// =====================================================
// 1. RINGKASAN KATEGORI
// =====================================================
$queryRingkasan = "SELECT
    (SELECT COUNT(*) FROM kategori) AS totalKategori,
    (SELECT COUNT(*) FROM obat) AS totalObat,
    (SELECT COUNT(*)
        FROM kategori k
        WHERE EXISTS (
            SELECT 1
            FROM obat o
            WHERE o.idkategori = k.idkategori
        )
    ) AS kategoriBerisiObat";

$resultRingkasan = mysqli_query($koneksi, $queryRingkasan);

$ringkasan = [
    'totalKategori' => 0,
    'totalObat' => 0,
    'kategoriBerisiObat' => 0
];

if ($resultRingkasan && $dataRingkasan = mysqli_fetch_assoc($resultRingkasan)) {
    $ringkasan = $dataRingkasan;
}

// =====================================================
// 2. DATA KATEGORI DAN JUMLAH OBAT
// =====================================================
$query = "SELECT
              k.idkategori,
              k.namakategori,
              COUNT(o.idobat) AS jumlahobat
          FROM kategori k
          LEFT JOIN obat o ON o.idkategori = k.idkategori
          GROUP BY k.idkategori, k.namakategori
          ORDER BY k.idkategori DESC";

$result = mysqli_query($koneksi, $query);
?>

<style>
.kategori-section{min-height:60vh;padding:22px 0 35px;background:radial-gradient(ellipse at top right,rgba(16,185,129,.08),transparent 40%),#f5f7fb}
.kategori-container{width:100%;max-width:1800px;margin:0 auto;padding:0 18px}
.kategori-hero{position:relative;overflow:hidden;padding:24px 28px;margin-bottom:18px;color:#fff;border-radius:17px;background:linear-gradient(120deg,#102f35 0%,#087f70 58%,#10b981 100%);box-shadow:0 12px 30px rgba(8,127,112,.15)}
.kategori-hero:after{content:"";position:absolute;width:230px;height:230px;top:-125px;right:8%;border:35px solid rgba(255,255,255,.07);border-radius:50%;pointer-events:none}
.kategori-hero-content{position:relative;z-index:1}
.kategori-eyebrow{display:inline-flex;align-items:center;gap:7px;margin-bottom:7px;color:#c9fff0;font-size:.7rem;font-weight:700;letter-spacing:1.4px;text-transform:uppercase}
.kategori-hero h1{margin:0 0 7px;color:#fff;font-size:clamp(1.4rem,2.5vw,2rem);font-weight:800;letter-spacing:-.7px}
.kategori-hero p{max-width:560px;margin:0;color:rgba(255,255,255,.82);font-size:.86rem;line-height:1.6}
.kategori-hero-actions{display:flex;flex-wrap:wrap;gap:8px;margin-top:16px}
.kategori-hero-btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:8px 13px;border:1px solid rgba(255,255,255,.3);border-radius:8px;color:#fff!important;background:rgba(255,255,255,.12);font-size:.76rem;font-weight:600;text-decoration:none!important;transition:.2s ease}
.kategori-hero-btn:hover{color:#075e54!important;background:#fff;border-color:#fff}
.kategori-hero-btn-light{color:#075e54!important;background:#fff;border-color:#fff}
.kategori-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin-bottom:24px}
.kategori-stat{display:flex;align-items:center;gap:12px;min-width:0;padding:13px 15px;border:1px solid #e9eef4;border-radius:12px;background:#fff;box-shadow:0 4px 14px rgba(24,39,75,.035)}
.kategori-stat-icon{display:flex;flex:0 0 39px;align-items:center;justify-content:center;width:39px;height:39px;border-radius:10px;font-size:1rem}
.kategori-stat-icon.green{color:#059669;background:#dff9ed}
.kategori-stat-icon.blue{color:#2563eb;background:#e5efff}
.kategori-stat-icon.orange{color:#d97706;background:#fff1d6}
.kategori-stat-label{margin-bottom:2px;color:#7b8798;font-size:.7rem}
.kategori-stat-number{color:#1e293b;font-size:1.25rem;font-weight:800;line-height:1.2}
.kategori-list-heading{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:13px}
.kategori-list-heading h2{margin:0;color:#243047;font-size:1.05rem;font-weight:800}
.kategori-list-heading p{margin:3px 0 0;color:#8993a3;font-size:.75rem}
.kategori-total-badge{flex-shrink:0;padding:6px 10px;border-radius:20px;color:#087f70;background:#dff9ed;font-size:.72rem;font-weight:700}
.kategori-search{position:relative;margin-bottom:13px}
.kategori-search input{width:100%;padding:10px 14px 10px 38px;border:1px solid #e3eaf1;border-radius:9px;background:#fff;color:#293548;font-size:.82rem;outline:none}
.kategori-search input:focus{border-color:#10b981;box-shadow:0 0 0 3px rgba(16,185,129,.1)}
.kategori-search i{position:absolute;top:50%;left:14px;transform:translateY(-50%);color:#8993a3}
.kategori-grid{display:grid;grid-template-columns:repeat(10,minmax(0,1fr));gap:12px}
.kategori-card{min-width:0;overflow:hidden;border:1px solid #e9eef4;border-radius:10px;background:#fff;transition:transform .2s ease,box-shadow .2s ease}
.kategori-card:hover{transform:translateY(-3px);box-shadow:0 9px 20px rgba(30,45,70,.1)}
.kategori-photo-wrapper{position:relative;display:flex;align-items:center;justify-content:center;width:100%;aspect-ratio:1/1;overflow:hidden;background:linear-gradient(135deg,#e5f8f1,#eaf1ff)}
.kategori-photo-placeholder{display:flex;align-items:center;justify-content:center;width:100%;height:100%;color:#0d9488;font-size:1.5rem;transition:transform .3s ease}
.kategori-card:hover .kategori-photo-placeholder{transform:scale(1.08)}
.kategori-card-body{padding:9px 8px 10px;text-align:center}
.kategori-name{display:-webkit-box;min-height:2.5em;margin:0 0 5px;overflow:hidden;color:#293548;font-size:.76rem;font-weight:700;line-height:1.25;overflow-wrap:anywhere;-webkit-box-orient:vertical;-webkit-line-clamp:2}
.kategori-count{margin:0 0 8px;color:#7b8798;font-size:.67rem;white-space:nowrap}
.kategori-button{display:flex;align-items:center;justify-content:center;gap:4px;width:100%;padding:6px 3px;border:0;border-radius:6px;color:#fff!important;background:#087f70;font-size:.67rem;font-weight:600;text-decoration:none!important;transition:background .2s ease}
.kategori-button:hover{color:#fff!important;background:#06665b}
.kategori-empty{grid-column:1/-1;padding:38px 15px;border:1px dashed #d8e1eb;border-radius:13px;background:#fff;color:#7b8798;text-align:center}
.kategori-empty i{margin-bottom:10px;color:#0d9488;font-size:2rem}
.kategori-no-result{display:none;grid-column:1/-1;padding:25px 15px;color:#7b8798;text-align:center;font-size:.85rem}
@media(max-width:1599.98px){.kategori-grid{grid-template-columns:repeat(8,minmax(0,1fr))}}
@media(max-width:1199.98px){.kategori-grid{grid-template-columns:repeat(6,minmax(0,1fr));gap:10px}}
@media(max-width:991.98px){.kategori-container{padding:0 14px}.kategori-grid{grid-template-columns:repeat(5,minmax(0,1fr))}}
@media(max-width:767.98px){.kategori-section{padding-top:16px}.kategori-hero{padding:21px 19px;border-radius:13px}.kategori-stats{gap:8px;margin-bottom:20px}.kategori-stat{display:block;padding:10px}.kategori-stat-icon{width:32px;height:32px;margin-bottom:7px;font-size:.85rem}.kategori-stat-label{font-size:.65rem}.kategori-stat-number{font-size:1.05rem}.kategori-grid{grid-template-columns:repeat(4,minmax(0,1fr));gap:9px}.kategori-card-body{padding:8px 6px}}
@media(max-width:575.98px){.kategori-container{padding:0 10px}.kategori-hero{padding:19px 16px}.kategori-hero p{font-size:.8rem}.kategori-hero-btn{padding:7px 10px;font-size:.7rem}.kategori-stats{gap:6px}.kategori-stat{padding:8px 6px}.kategori-stat-icon{width:29px;height:29px}.kategori-stat-label{min-height:2em;font-size:.61rem}.kategori-stat-number{font-size:.95rem}.kategori-grid{grid-template-columns:repeat(3,minmax(0,1fr));gap:8px}.kategori-name{font-size:.72rem}.kategori-count{font-size:.63rem}.kategori-button{font-size:.64rem}.kategori-list-heading h2{font-size:.95rem}}
@media(max-width:359.98px){.kategori-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
</style>

<section class="kategori-section">
    <div class="kategori-container">

        <!-- HERO -->
        <div class="kategori-hero">
            <div class="kategori-hero-content">
                <div class="kategori-eyebrow">
                    <i class="fas fa-layer-group"></i> Katalog Klinik
                </div>

                <h1>Jelajahi Kategori Obat.</h1>

                <p>Temukan obat dengan lebih mudah melalui kategori yang tersedia di Klinik Ahmadi. Pilih kategori untuk melihat daftar obat di dalamnya.</p>

                <div class="kategori-hero-actions">
                    <a href="#daftarKategori" class="kategori-hero-btn kategori-hero-btn-light">
                        <i class="fas fa-th-large"></i> Jelajahi Kategori
                    </a>

                    <a href="index.php?page=daftarobat" class="kategori-hero-btn">
                        <i class="fas fa-pills"></i> Semua Obat
                    </a>
                </div>
            </div>
        </div>

        <!-- STATISTIK -->
        <div class="kategori-stats">

            <div class="kategori-stat">
                <div class="kategori-stat-icon green">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <div class="kategori-stat-label">Total Kategori</div>
                    <div class="kategori-stat-number">
                        <?= number_format((int)$ringkasan['totalKategori'], 0, ',', '.'); ?>
                    </div>
                </div>
            </div>

            <div class="kategori-stat">
                <div class="kategori-stat-icon blue">
                    <i class="fas fa-pills"></i>
                </div>
                <div>
                    <div class="kategori-stat-label">Total Obat</div>
                    <div class="kategori-stat-number">
                        <?= number_format((int)$ringkasan['totalObat'], 0, ',', '.'); ?>
                    </div>
                </div>
            </div>

            <div class="kategori-stat">
                <div class="kategori-stat-icon orange">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="kategori-stat-label">Kategori Berisi Obat</div>
                    <div class="kategori-stat-number">
                        <?= number_format((int)$ringkasan['kategoriBerisiObat'], 0, ',', '.'); ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- JUDUL DAN PENCARIAN -->
        <div class="kategori-list-heading" id="daftarKategori">
            <div>
                <h2>
                    <i class="fas fa-th-large mr-1" style="color:#087f70"></i>
                    Daftar Kategori Obat
                </h2>
                <p>Pilih kategori untuk melihat obat yang tersedia.</p>
            </div>

            <span class="kategori-total-badge">
                <?= number_format((int)$ringkasan['totalKategori'], 0, ',', '.'); ?> Kategori
            </span>
        </div>

        <div class="kategori-search">
            <i class="fas fa-search"></i>
            <input type="text" id="cariKategori" placeholder="Cari nama kategori obat...">
        </div>

        <!-- GRID KATEGORI -->
        <div class="kategori-grid" id="kategoriGrid">

            <?php if ($result && mysqli_num_rows($result) > 0): ?>

                <?php while ($kategori = mysqli_fetch_assoc($result)): ?>

                    <div class="kategori-card" data-nama="<?= htmlspecialchars(mb_strtolower($kategori['namakategori'] ?? '', 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>">

                        <div class="kategori-photo-wrapper">
                            <div class="kategori-photo-placeholder">
                                <i class="fas fa-capsules"></i>
                            </div>
                        </div>

                        <div class="kategori-card-body">

                            <h3 class="kategori-name" title="<?= htmlspecialchars($kategori['namakategori'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                <?= htmlspecialchars($kategori['namakategori'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                            </h3>

                            <p class="kategori-count">
                                <i class="fas fa-pills mr-1"></i>
                                <?= number_format((int)$kategori['jumlahobat'], 0, ',', '.'); ?> obat
                            </p>

                            <a href="index.php?page=detilkategori&amp;id=<?= (int)$kategori['idkategori']; ?>" class="kategori-button">
                                <i class="fas fa-arrow-right"></i> Lihat Obat
                            </a>

                        </div>
                    </div>

                <?php endwhile; ?>

                <div class="kategori-no-result" id="kategoriNoResult">
                    <i class="fas fa-search mr-1"></i>
                    Kategori yang dicari tidak ditemukan.
                </div>

            <?php elseif ($result): ?>

                <div class="kategori-empty">
                    <i class="fas fa-folder-open"></i>
                    <h5 class="font-weight-bold text-dark">Belum Ada Kategori</h5>
                    <p class="mb-0">Data kategori obat belum tersedia.</p>
                </div>

            <?php else: ?>

                <div class="kategori-empty">
                    <i class="fas fa-exclamation-circle"></i>
                    <h5 class="font-weight-bold text-dark">Data Belum Dapat Ditampilkan</h5>
                    <p class="mb-0">Terjadi kesalahan saat mengambil data kategori.</p>
                </div>

            <?php endif; ?>

        </div>
    </div>
</section>

<script>
(function() {
    const input = document.getElementById('cariKategori');
    const grid = document.getElementById('kategoriGrid');
    const noResult = document.getElementById('kategoriNoResult');

    if (!input || !grid) return;

    input.addEventListener('input', function() {
        const kata = this.value.toLocaleLowerCase('id-ID').trim();
        const kartu = grid.querySelectorAll('.kategori-card');
        let tampil = 0;

        kartu.forEach(function(item) {
            const nama = item.getAttribute('data-nama') || '';
            const cocok = nama.includes(kata);

            item.style.display = cocok ? '' : 'none';

            if (cocok) tampil++;
        });

        if (noResult) {
            noResult.style.display = tampil === 0 ? 'block' : 'none';
        }
    });
})();
</script>
