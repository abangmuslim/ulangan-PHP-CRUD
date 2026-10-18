
<?php
$idkategori = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$kategori = null;
$daftarObat = [];
$totalObat = 0;

if ($idkategori && $idkategori > 0) {
    $stmt = mysqli_prepare($koneksi, "SELECT idkategori, namakategori FROM kategori WHERE idkategori=?");
    mysqli_stmt_bind_param($stmt, "i", $idkategori);
    mysqli_stmt_execute($stmt);
    $resultKategori = mysqli_stmt_get_result($stmt);
    $kategori = mysqli_fetch_assoc($resultKategori);
    mysqli_stmt_close($stmt);

    if ($kategori) {
        $stmt = mysqli_prepare($koneksi, "SELECT COUNT(*) AS total FROM obat WHERE idkategori=?");
        mysqli_stmt_bind_param($stmt, "i", $idkategori);
        mysqli_stmt_execute($stmt);
        $resultTotal = mysqli_stmt_get_result($stmt);
        $totalObat = (int) mysqli_fetch_assoc($resultTotal)['total'];
        mysqli_stmt_close($stmt);

        $stmt = mysqli_prepare($koneksi, "SELECT idobat, namaobat, harga, stok, foto FROM obat WHERE idkategori=? ORDER BY idobat DESC");
        mysqli_stmt_bind_param($stmt, "i", $idkategori);
        mysqli_stmt_execute($stmt);
        $resultObat = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($resultObat)) $daftarObat[] = $row;
        mysqli_stmt_close($stmt);
    }
}
?>

<style>
.detailkategori-page{background:radial-gradient(ellipse at top left,#e0f2e9 0%,#f6f9f7 45%,#eef5f2 100%);min-height:75vh;padding:30px 0 45px;color:#18352b}
.detailkategori-container{width:100%;max-width:1800px;margin:auto;padding:0 18px}
.detailkategori-hero{position:relative;overflow:hidden;background:linear-gradient(120deg,#075c45,#087f5b 58%,#159b76);border-radius:22px;padding:32px;color:#fff;box-shadow:0 12px 32px #075c4524;margin-bottom:25px}
.detailkategori-hero:after{content:"";position:absolute;width:240px;height:240px;border:35px solid #ffffff12;border-radius:50%;right:-65px;top:-100px}
.detailkategori-label{display:inline-block;background:#ffffff20;border:1px solid #ffffff30;border-radius:30px;padding:6px 12px;font-size:12px;font-weight:700;letter-spacing:.5px}
.detailkategori-title{font-size:clamp(25px,3vw,38px);font-weight:800;margin:13px 0 8px;line-height:1.2}
.detailkategori-desc{font-size:14px;color:#e1f5eb;margin:0;max-width:750px;line-height:1.7}
.detailkategori-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:22px;position:relative;z-index:1}
.detailkategori-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 16px;border-radius:10px;font-size:13px;font-weight:700;text-decoration:none!important;transition:.2s}
.detailkategori-btn-light{background:#fff;color:#087f5b}
.detailkategori-btn-outline{border:1px solid #ffffff80;color:#fff}
.detailkategori-btn:hover{transform:translateY(-2px);filter:brightness(.96)}
.detailkategori-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:15px;margin-bottom:28px}
.detailkategori-stat{background:#fff;border:1px solid #e2eee7;border-radius:15px;padding:18px;display:flex;align-items:center;gap:14px;box-shadow:0 5px 18px #18352b08}
.detailkategori-stat-icon{width:46px;height:46px;flex-shrink:0;border-radius:13px;background:#e5f5ed;color:#087f5b;display:grid;place-items:center;font-size:20px}
.detailkategori-stat small{display:block;color:#718178;font-size:12px;margin-bottom:4px}
.detailkategori-stat strong{font-size:23px;font-weight:800;color:#18352b;line-height:1.2}
.detailkategori-heading{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin:0 0 17px}
.detailkategori-heading h3{font-size:21px;font-weight:800;margin:0;color:#18352b}
.detailkategori-heading p{font-size:13px;color:#74847b;margin:5px 0 0}
.detailkategori-grid{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:16px}
.detailkategori-card{height:100%;background:#fff;border:1px solid #e1ebe5;border-radius:16px;overflow:hidden;box-shadow:0 5px 18px #18352b08;transition:transform .2s,box-shadow .2s}
.detailkategori-card:hover{transform:translateY(-4px);box-shadow:0 12px 25px #18352b14}
.detailkategori-photo{height:150px;background:linear-gradient(145deg,#e6f6ed,#f5faf7);display:flex;align-items:center;justify-content:center;overflow:hidden}
.detailkategori-photo img{width:100%;height:100%;object-fit:contain;padding:12px}
.detailkategori-photo i{font-size:48px;color:#159b76}
.detailkategori-card-body{padding:14px}
.detailkategori-medicine-name{font-size:14px;font-weight:800;color:#203b30;line-height:1.45;min-height:40px;margin-bottom:9px}
.detailkategori-price{font-size:16px;font-weight:800;color:#087f5b;margin-bottom:7px}
.detailkategori-stock{font-size:12px;color:#6f8176;margin-bottom:13px}
.detailkategori-card-link{display:flex;align-items:center;justify-content:center;gap:7px;background:#e6f5ed;color:#087f5b;padding:9px;border-radius:9px;font-size:12px;font-weight:800;text-decoration:none!important}
.detailkategori-card-link:hover{background:#087f5b;color:#fff}
.detailkategori-empty{background:#fff;border:1px dashed #cbded2;border-radius:17px;padding:45px 20px;text-align:center;color:#74847b}
.detailkategori-empty i{font-size:42px;color:#a5cdb8;margin-bottom:13px}
.detailkategori-empty h4{font-size:18px;font-weight:800;color:#284638}
.detailkategori-alert{background:#fff;border:1px solid #f3d0d0;border-radius:16px;padding:30px;text-align:center;color:#9b3535}
@media(max-width:1399px){.detailkategori-grid{grid-template-columns:repeat(5,minmax(0,1fr))}}
@media(max-width:1199px){.detailkategori-grid{grid-template-columns:repeat(4,minmax(0,1fr))}}
@media(max-width:767px){.detailkategori-page{padding:20px 0 30px}.detailkategori-container{padding:0 12px}.detailkategori-hero{padding:24px 20px;border-radius:17px}.detailkategori-stats{grid-template-columns:1fr;gap:10px}.detailkategori-stat{padding:13px}.detailkategori-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}.detailkategori-photo{height:125px}.detailkategori-card-body{padding:11px}.detailkategori-heading h3{font-size:19px}}
@media(max-width:360px){.detailkategori-grid{grid-template-columns:1fr 1fr}.detailkategori-photo{height:105px}.detailkategori-medicine-name{font-size:13px}}
</style>

<div class="detailkategori-page">
    <div class="detailkategori-container">
        <?php if (!$kategori): ?>
            <div class="detailkategori-alert">
                <i class="fas fa-exclamation-circle fa-2x mb-3"></i>
                <h4 class="font-weight-bold">Kategori Tidak Ditemukan</h4>
                <p class="mb-3">Kategori yang Anda cari tidak tersedia atau telah dihapus.</p>
                <a href="index.php?page=daftarkategori" class="detailkategori-btn detailkategori-btn-light" style="background:#087f5b;color:#fff"><i class="fas fa-arrow-left"></i> Kembali ke Kategori</a>
            </div>
        <?php else: ?>
            <section class="detailkategori-hero">
                <span class="detailkategori-label"><i class="fas fa-layer-group mr-1"></i> INFORMASI KATEGORI OBAT</span>
                <h1 class="detailkategori-title"><?= htmlspecialchars($kategori['namakategori']) ?></h1>
                <p class="detailkategori-desc">Temukan daftar obat dalam kategori ini beserta informasi harga, ketersediaan stok, dan detail masing-masing obat.</p>
                <div class="detailkategori-actions">
                    <a href="index.php?page=daftarkategori" class="detailkategori-btn detailkategori-btn-light"><i class="fas fa-arrow-left"></i> Semua Kategori</a>
                    <a href="index.php?page=daftarobat" class="detailkategori-btn detailkategori-btn-outline"><i class="fas fa-pills"></i> Semua Obat</a>
                </div>
            </section>

            <div class="detailkategori-stats">
                <div class="detailkategori-stat">
                    <div class="detailkategori-stat-icon"><i class="fas fa-layer-group"></i></div>
                    <div><small>Nama Kategori</small><strong style="font-size:17px"><?= htmlspecialchars($kategori['namakategori']) ?></strong></div>
                </div>
                <div class="detailkategori-stat">
                    <div class="detailkategori-stat-icon"><i class="fas fa-pills"></i></div>
                    <div><small>Jumlah Jenis Obat</small><strong><?= number_format($totalObat, 0, ',', '.') ?></strong></div>
                </div>
                <div class="detailkategori-stat">
                    <div class="detailkategori-stat-icon"><i class="fas fa-box-open"></i></div>
                    <div><small>Status Kategori</small><strong style="font-size:17px"><?= $totalObat > 0 ? 'Tersedia' : 'Belum Ada Obat' ?></strong></div>
                </div>
            </div>

            <div class="detailkategori-heading">
                <div>
                    <h3><i class="fas fa-capsules mr-2" style="color:#087f5b"></i>Daftar Obat</h3>
                    <p>Obat yang terdaftar dalam kategori <?= htmlspecialchars($kategori['namakategori']) ?>.</p>
                </div>
                <span class="badge badge-success px-3 py-2"><?= number_format($totalObat, 0, ',', '.') ?> obat</span>
            </div>

            <?php if ($totalObat > 0): ?>
                <div class="detailkategori-grid">
                    <?php foreach ($daftarObat as $obat): ?>
                        <?php
                        $namaFoto = trim((string)($obat['foto'] ?? ''));
                        $fileFoto = basename($namaFoto);
                        $pathFoto = __DIR__ . '/../../assets/images/obat/' . $fileFoto;
                        $urlFoto = 'assets/images/obat/' . rawurlencode($fileFoto);
                        $fotoAda = $namaFoto !== '' && is_file($pathFoto);
                        ?>
                        <article class="detailkategori-card">
                            <div class="detailkategori-photo">
                                <?php if ($fotoAda): ?>
                                    <img src="<?= htmlspecialchars($urlFoto) ?>" alt="<?= htmlspecialchars($obat['namaobat']) ?>" loading="lazy">
                                <?php else: ?>
                                    <i class="fas fa-pills"></i>
                                <?php endif; ?>
                            </div>
                            <div class="detailkategori-card-body">
                                <div class="detailkategori-medicine-name"><?= htmlspecialchars($obat['namaobat']) ?></div>
                                <div class="detailkategori-price">Rp <?= number_format((float)$obat['harga'], 0, ',', '.') ?></div>
                                <div class="detailkategori-stock"><i class="fas fa-box mr-1"></i> Stok: <?= number_format((int)$obat['stok'], 0, ',', '.') ?></div>
                                <a href="index.php?page=detilobat&id=<?= (int)$obat['idobat'] ?>" class="detailkategori-card-link"><i class="fas fa-eye"></i> Lihat Detail</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="detailkategori-empty">
                    <i class="fas fa-box-open"></i>
                    <h4>Belum Ada Obat</h4>
                    <p class="mb-3">Belum ada obat yang terdaftar dalam kategori ini.</p>
                    <a href="index.php?page=daftarobat" class="detailkategori-btn detailkategori-btn-light" style="background:#087f5b;color:#fff"><i class="fas fa-pills"></i> Lihat Semua Obat</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
