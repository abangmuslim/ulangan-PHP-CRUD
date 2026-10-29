
<?php
/*
|--------------------------------------------------------------------------
| DETAIL OBAT - RUMAH SAKIT AHMADI
| PHP Native + MySQLi + AdminLTE 3.2
|--------------------------------------------------------------------------
*/

// =====================================================
// 1. AMBIL ID OBAT
// =====================================================
$idobat = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$obat = null;
$errorObat = '';

if ($idobat > 0) {
    // Deteksi nama kolom kategori dan merek
    $kolomKategori = [];
    $kolomMerk = [];

    $resultKolom = mysqli_query($koneksi, "SHOW COLUMNS FROM kategori");
    if ($resultKolom) {
        while ($kolom = mysqli_fetch_assoc($resultKolom)) {
            $kolomKategori[] = $kolom['Field'];
        }
    }

    $resultKolom = mysqli_query($koneksi, "SHOW COLUMNS FROM merk");
    if ($resultKolom) {
        while ($kolom = mysqli_fetch_assoc($resultKolom)) {
            $kolomMerk[] = $kolom['Field'];
        }
    }

    $namaKolomKategori = null;
    foreach (['namakategori', 'nama_kategori', 'nama', 'kategori'] as $calon) {
        if (in_array($calon, $kolomKategori, true)) {
            $namaKolomKategori = $calon;
            break;
        }
    }

    $namaKolomMerk = null;
    foreach (['namamerk', 'nama_merk', 'nama', 'merk'] as $calon) {
        if (in_array($calon, $kolomMerk, true)) {
            $namaKolomMerk = $calon;
            break;
        }
    }

    $selectKategori = $namaKolomKategori
        ? "k.`$namaKolomKategori`"
        : "NULL";

    $selectMerk = $namaKolomMerk
        ? "m.`$namaKolomMerk`"
        : "NULL";

    $query = "SELECT
                  o.*,
                  $selectKategori AS namakategori,
                  $selectMerk AS namamerk
              FROM obat o
              LEFT JOIN kategori k ON o.idkategori = k.idkategori
              LEFT JOIN merk m ON o.idmerk = m.idmerk
              WHERE o.idobat = ?
              LIMIT 1";

    $stmt = mysqli_prepare($koneksi, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $idobat);
        mysqli_stmt_execute($stmt);
        $resultObat = mysqli_stmt_get_result($stmt);
        $obat = mysqli_fetch_assoc($resultObat) ?: null;
        mysqli_stmt_close($stmt);
    } else {
        $errorObat = 'Data obat belum dapat ditampilkan.';
    }
} else {
    $errorObat = 'ID obat tidak valid.';
}

// =====================================================
// 2. SIAPKAN DATA TAMPILAN
// =====================================================
if ($obat) {
    $namaObat = trim($obat['namaobat'] ?? 'Obat');
    $namaKategori = trim($obat['namakategori'] ?? '');
    $namaMerk = trim($obat['namamerk'] ?? '');
    $namaKategori = $namaKategori !== '' ? $namaKategori : 'Belum dikategorikan';
    $namaMerk = $namaMerk !== '' ? $namaMerk : 'Tidak dicantumkan';
    $hargaObat = (float)($obat['harga'] ?? 0);
    $stokObat = (int)($obat['stok'] ?? 0);
    $tanggalStok = trim($obat['tanggalstok'] ?? '');

    $foto = trim($obat['foto'] ?? '');
    $fileFoto = basename($foto);
    $pathFoto = 'assets/images/obat/' . $fileFoto;
    $pathFotoFisik = __DIR__ . '/../../assets/images/obat/' . $fileFoto;
    $fotoTersedia = $fileFoto !== '' && is_file($pathFotoFisik);

    $tanggalTampil = '-';
    if ($tanggalStok !== '' && $tanggalStok !== '0000-00-00') {
        $timestamp = strtotime($tanggalStok);
        if ($timestamp !== false) {
            $tanggalTampil = date('d-m-Y', $timestamp);
        }
    }

    $statusStok = $stokObat > 10
        ? 'Tersedia'
        : ($stokObat > 0 ? 'Stok Terbatas' : 'Stok Habis');

    $kelasStok = $stokObat > 10
        ? 'tersedia'
        : ($stokObat > 0 ? 'terbatas' : 'habis');
}
?>

<style>
.detailobat-section{position:relative;min-height:75vh;overflow:hidden;padding:28px 0 45px;background:radial-gradient(ellipse at 10% 5%,rgba(16,185,129,.12),transparent 35%),radial-gradient(ellipse at 95% 30%,rgba(59,130,246,.08),transparent 30%),#f4f7fb}
.detailobat-container{position:relative;z-index:1;width:100%;max-width:1450px;margin:0 auto;padding:0 22px}
.detailobat-back{display:inline-flex;align-items:center;gap:8px;margin-bottom:18px;color:#64748b!important;font-size:.82rem;font-weight:700;text-decoration:none!important;transition:color .2s ease}
.detailobat-back:hover{color:#087f70!important}
.detailobat-hero{position:relative;overflow:hidden;padding:25px 30px;margin-bottom:20px;border-radius:18px;color:#fff;background:linear-gradient(115deg,#102a36 0%,#075e54 52%,#0d9488 100%);box-shadow:0 15px 35px rgba(8,127,112,.15)}
.detailobat-hero::before{content:"";position:absolute;width:250px;height:250px;right:5%;top:-150px;border:38px solid rgba(255,255,255,.07);border-radius:50%}
.detailobat-hero-content{position:relative;z-index:1}
.detailobat-eyebrow{display:flex;align-items:center;gap:8px;margin-bottom:8px;color:#a7f3d0;font-size:.7rem;font-weight:800;letter-spacing:1.5px;text-transform:uppercase}
.detailobat-hero h1{margin:0 0 8px;font-size:clamp(1.45rem,3vw,2.2rem);font-weight:800;line-height:1.25;overflow-wrap:anywhere}
.detailobat-hero p{max-width:650px;margin:0;color:rgba(255,255,255,.78);font-size:.86rem;line-height:1.7}
.detailobat-main{display:grid;grid-template-columns:minmax(280px,.85fr) minmax(0,1.35fr);gap:20px;align-items:stretch}
.detailobat-panel{min-width:0;overflow:hidden;border:1px solid #e4eaf1;border-radius:17px;background:rgba(255,255,255,.94);box-shadow:0 8px 28px rgba(30,45,70,.055)}
.detailobat-photo-panel{display:flex;flex-direction:column;padding:16px}
.detailobat-photo{position:relative;display:flex;align-items:center;justify-content:center;width:100%;min-height:300px;aspect-ratio:1/1;overflow:hidden;border:1px solid #e8f0f4;border-radius:13px;background:radial-gradient(circle at 50% 40%,rgba(16,185,129,.12),transparent 55%),linear-gradient(145deg,#f2fbf8,#eef4ff)}
.detailobat-photo img{width:100%;height:100%;object-fit:contain;padding:15px;transition:transform .35s ease}
.detailobat-photo:hover img{transform:scale(1.04)}
.detailobat-photo-placeholder{color:#0d9488;font-size:5rem;opacity:.8}
.detailobat-photo-caption{display:flex;align-items:center;justify-content:center;gap:7px;margin-top:13px;color:#718096;font-size:.74rem}
.detailobat-photo-caption i{color:#0d9488}
.detailobat-info-panel{padding:23px}
.detailobat-label{margin-bottom:7px;color:#8793a5;font-size:.69rem;font-weight:800;letter-spacing:1.2px;text-transform:uppercase}
.detailobat-name{margin:0 0 13px;color:#1e293b;font-size:clamp(1.35rem,2.5vw,1.9rem);font-weight:800;line-height:1.3;overflow-wrap:anywhere}
.detailobat-tags{display:flex;flex-wrap:wrap;gap:7px;margin-bottom:22px}
.detailobat-tag{display:inline-flex;align-items:center;gap:6px;padding:6px 9px;border:1px solid #e0f2eb;border-radius:7px;color:#087f70;background:#effcf6;font-size:.72rem;font-weight:700}
.detailobat-price-box{position:relative;overflow:hidden;padding:17px 19px;margin-bottom:19px;border:1px solid #d9f4e8;border-radius:13px;background:linear-gradient(115deg,#effcf6,#f7fffb)}
.detailobat-price-box::after{content:"";position:absolute;width:90px;height:90px;right:-35px;bottom:-48px;border:15px solid rgba(16,185,129,.08);border-radius:50%}
.detailobat-price-label{margin-bottom:5px;color:#648477;font-size:.75rem}
.detailobat-price{color:#087f70;font-size:clamp(1.5rem,3vw,2.1rem);font-weight:900;line-height:1.2;overflow-wrap:anywhere}
.detailobat-price-note{margin-top:6px;color:#7b9189;font-size:.68rem}
.detailobat-info-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:11px;margin-bottom:20px}
.detailobat-info-item{display:flex;align-items:flex-start;gap:10px;min-width:0;padding:12px;border:1px solid #edf0f5;border-radius:11px;background:#fff}
.detailobat-info-icon{display:flex;flex:0 0 34px;align-items:center;justify-content:center;width:34px;height:34px;border-radius:9px;color:#2563eb;background:#eaf1ff}
.detailobat-info-item:nth-child(2) .detailobat-info-icon{color:#0d9488;background:#dff9ed}
.detailobat-info-item:nth-child(3) .detailobat-info-icon{color:#d97706;background:#fff1d6}
.detailobat-info-item:nth-child(4) .detailobat-info-icon{color:#7c3aed;background:#f1eaff}
.detailobat-info-text{min-width:0}
.detailobat-info-title{margin-bottom:4px;color:#8a95a5;font-size:.68rem}
.detailobat-info-value{color:#293548;font-size:.82rem;font-weight:800;overflow-wrap:anywhere}
.detailobat-stock-status{display:inline-flex;align-items:center;gap:5px;padding:4px 7px;border-radius:6px;font-size:.68rem;font-weight:800}
.detailobat-stock-status.tersedia{color:#047857;background:#dff9ed}
.detailobat-stock-status.terbatas{color:#b45309;background:#fff1d6}
.detailobat-stock-status.habis{color:#b91c1c;background:#fee2e2}
.detailobat-note{display:flex;align-items:flex-start;gap:10px;padding:12px 14px;border:1px solid #dbeafe;border-radius:10px;color:#526780;background:#f4f8ff;font-size:.74rem;line-height:1.65}
.detailobat-note i{margin-top:3px;color:#3b82f6}
.detailobat-actions{display:flex;flex-wrap:wrap;gap:9px;margin-top:19px}
.detailobat-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 15px;border:1px solid #087f70;border-radius:9px;color:#fff!important;background:#087f70;font-size:.78rem;font-weight:700;text-decoration:none!important;transition:.2s ease}
.detailobat-btn:hover{color:#fff!important;background:#06665b;border-color:#06665b;transform:translateY(-1px)}
.detailobat-btn-outline{color:#087f70!important;background:#fff;border-color:#d7e6e2}
.detailobat-btn-outline:hover{color:#06665b!important;background:#effcf6;border-color:#a7dccc}
.detailobat-notfound{max-width:650px;padding:45px 25px;margin:35px auto;border:1px solid #e4eaf1;border-radius:17px;background:#fff;box-shadow:0 8px 28px rgba(30,45,70,.055);text-align:center}
.detailobat-notfound i{margin-bottom:15px;color:#0d9488;font-size:3.2rem}
.detailobat-notfound h2{margin-bottom:8px;color:#1e293b;font-size:1.35rem;font-weight:800}
.detailobat-notfound p{color:#718096;font-size:.86rem}
@media(max-width:767.98px){.detailobat-section{padding:18px 0 30px}.detailobat-container{padding:0 13px}.detailobat-hero{padding:22px 19px;border-radius:14px}.detailobat-main{grid-template-columns:1fr;gap:14px}.detailobat-photo-panel{padding:12px}.detailobat-photo{min-height:220px;max-height:360px}.detailobat-info-panel{padding:18px 15px}.detailobat-info-grid{gap:8px}}
@media(max-width:420px){.detailobat-container{padding:0 10px}.detailobat-info-grid{grid-template-columns:1fr}.detailobat-actions{flex-direction:column}.detailobat-btn{width:100%}}
@media(prefers-reduced-motion:reduce){.detailobat-section *,.detailobat-section *::before,.detailobat-section *::after{transition-duration:.01ms!important}}
</style>

<section class="detailobat-section">
    <div class="detailobat-container">

        <a href="index.php?page=daftarobat" class="detailobat-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Katalog Obat
        </a>

        <?php if ($obat): ?>

            <div class="detailobat-hero">
                <div class="detailobat-hero-content">
                    <div class="detailobat-eyebrow">
                        <i class="fas fa-shield-alt"></i>
                        Informasi Farmasi
                    </div>
                    <h1>Detail Obat</h1>
                    <p>
                        Informasi obat yang tercatat di Rumah Sakit Ahmadi.
                        Periksa kategori, merek, harga, dan ketersediaan stok
                        pada panel informasi berikut.
                    </p>
                </div>
            </div>

            <div class="detailobat-main">

                <!-- PANEL FOTO -->
                <div class="detailobat-panel detailobat-photo-panel">
                    <div class="detailobat-photo">
                        <?php if ($fotoTersedia): ?>
                            <img
                                src="<?= htmlspecialchars($pathFoto, ENT_QUOTES, 'UTF-8'); ?>"
                                alt="<?= htmlspecialchars($namaObat, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php else: ?>
                            <div class="detailobat-photo-placeholder">
                                <i class="fas fa-prescription-bottle-alt"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="detailobat-photo-caption">
                        <i class="fas fa-check-circle"></i>
                        Data obat Rumah Sakit Ahmadi
                    </div>
                </div>

                <!-- PANEL INFORMASI -->
                <div class="detailobat-panel detailobat-info-panel">
                    <div class="detailobat-label">Informasi Obat</div>
                    <h2 class="detailobat-name">
                        <?= htmlspecialchars($namaObat, ENT_QUOTES, 'UTF-8'); ?>
                    </h2>

                    <div class="detailobat-tags">
                        <span class="detailobat-tag">
                            <i class="fas fa-tag"></i>
                            <?= htmlspecialchars($namaKategori, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                        <span class="detailobat-tag">
                            <i class="fas fa-copyright"></i>
                            <?= htmlspecialchars($namaMerk, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>

                    <div class="detailobat-price-box">
                        <div class="detailobat-price-label">Harga Tercatat</div>
                        <div class="detailobat-price">
                            Rp<?= number_format($hargaObat, 0, ',', '.'); ?>
                        </div>
                        <div class="detailobat-price-note">
                            Harga berdasarkan data yang tersimpan di sistem.
                        </div>
                    </div>

                    <div class="detailobat-info-grid">
                        <div class="detailobat-info-item">
                            <div class="detailobat-info-icon">
                                <i class="fas fa-pills"></i>
                            </div>
                            <div class="detailobat-info-text">
                                <div class="detailobat-info-title">Nama Obat</div>
                                <div class="detailobat-info-value">
                                    <?= htmlspecialchars($namaObat, ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="detailobat-info-item">
                            <div class="detailobat-info-icon">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div class="detailobat-info-text">
                                <div class="detailobat-info-title">Jumlah Stok</div>
                                <div class="detailobat-info-value">
                                    <?= number_format($stokObat, 0, ',', '.'); ?> unit
                                </div>
                            </div>
                        </div>

                        <div class="detailobat-info-item">
                            <div class="detailobat-info-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="detailobat-info-text">
                                <div class="detailobat-info-title">Tanggal Stok</div>
                                <div class="detailobat-info-value">
                                    <?= htmlspecialchars($tanggalTampil, ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="detailobat-info-item">
                            <div class="detailobat-info-icon">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <div class="detailobat-info-text">
                                <div class="detailobat-info-title">Status Stok</div>
                                <div class="detailobat-info-value">
                                    <span class="detailobat-stock-status <?= $kelasStok; ?>">
                                        <i class="fas fa-circle"></i>
                                        <?= htmlspecialchars($statusStok, ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detailobat-note">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            Informasi ini merupakan data katalog obat dan bukan petunjuk
                            penggunaan medis. Penggunaan obat harus mengikuti resep atau
                            arahan dokter maupun tenaga kesehatan yang berwenang.
                            Ketersediaan aktual dapat berubah.
                        </div>
                    </div>

                    <div class="detailobat-actions">
                        <a href="index.php?page=daftarobat" class="detailobat-btn">
                            <i class="fas fa-th-large"></i> Katalog Obat
                        </a>
                        <a href="index.php?page=kontak" class="detailobat-btn detailobat-btn-outline">
                            <i class="fas fa-headset"></i> Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>

        <?php else: ?>

            <div class="detailobat-notfound">
                <i class="fas fa-prescription-bottle-alt"></i>
                <h2>Data Obat Tidak Ditemukan</h2>
                <p>
                    <?= htmlspecialchars(
                        $errorObat !== '' ? $errorObat : 'Obat yang Anda cari tidak tersedia atau mungkin sudah tidak tercatat.',
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>
                </p>
                <a href="index.php?page=daftarobat" class="detailobat-btn">
                    <i class="fas fa-arrow-left"></i> Kembali ke Katalog Obat
                </a>
            </div>

        <?php endif; ?>

    </div>
</section>
