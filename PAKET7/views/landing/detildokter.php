<?php
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$dokter = null;
$jadwalDokter = [];
$totalJadwal = 0;

if ($id > 0) {
    $stmt = mysqli_prepare($koneksi, "SELECT iddokter, namadokter, spesialis, nohp, alamat, foto FROM dokter WHERE iddokter = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $hasil = mysqli_stmt_get_result($stmt);
    $dokter = mysqli_fetch_assoc($hasil);
    mysqli_stmt_close($stmt);

    if ($dokter) {
        $stmtJadwal = mysqli_prepare($koneksi, "SELECT haripraktik, jam_mulai, jam_selesai FROM jadwaldokter WHERE iddokter = ? ORDER BY FIELD(haripraktik, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), jam_mulai ASC");
        mysqli_stmt_bind_param($stmtJadwal, "i", $id);
        mysqli_stmt_execute($stmtJadwal);
        $hasilJadwal = mysqli_stmt_get_result($stmtJadwal);

        while ($baris = mysqli_fetch_assoc($hasilJadwal)) {
            $jadwalDokter[] = $baris;
        }

        mysqli_stmt_close($stmtJadwal);
        $totalJadwal = count($jadwalDokter);
    }
}

function eDokter($nilai)
{
    return htmlspecialchars((string) ($nilai ?? ''), ENT_QUOTES, 'UTF-8');
}

function formatJamDokter($jam)
{
    return $jam ? date('H:i', strtotime($jam)) : '-';
}
?>


<style>
.detaildokter-section{padding:35px 0 65px;background:linear-gradient(180deg,#f0fdfa 0%,#f8fafc 260px,#fff 100%);min-height:75vh}
.detaildokter-container{width:95%;max-width:1180px;margin:0 auto}
.detaildokter-back{display:inline-flex;align-items:center;gap:9px;margin-bottom:22px;color:#087f5b;font-size:14px;font-weight:700;text-decoration:none!important;transition:.25s}
.detaildokter-back:hover{color:#075c45;transform:translateX(-4px)}
.detaildokter-hero{position:relative;overflow:hidden;padding:35px 38px;margin-bottom:28px;border-radius:24px;color:#fff;background:linear-gradient(125deg,#064e3b 0%,#087f5b 52%,#0d9488 100%);box-shadow:0 15px 35px rgba(8,127,91,.18)}
.detaildokter-hero::before,.detaildokter-hero::after{content:"";position:absolute;border:1px solid rgba(255,255,255,.13);border-radius:50%;pointer-events:none}
.detaildokter-hero::before{width:270px;height:270px;right:-65px;top:-150px}
.detaildokter-hero::after{width:200px;height:200px;right:65px;bottom:-150px}
.detaildokter-hero-content{position:relative;z-index:1}
.detaildokter-eyebrow{display:inline-flex;align-items:center;gap:8px;padding:7px 12px;margin-bottom:13px;border:1px solid rgba(255,255,255,.25);border-radius:30px;background:rgba(255,255,255,.1);font-size:11px;font-weight:800;letter-spacing:1.3px;text-transform:uppercase}
.detaildokter-hero h2{margin:0 0 10px;color:#fff;font-size:clamp(25px,3vw,36px);font-weight:800;line-height:1.25}
.detaildokter-hero p{max-width:600px;margin:0;color:rgba(255,255,255,.83);font-size:14px;line-height:1.8}
.detaildokter-main{display:grid;grid-template-columns:minmax(280px,.8fr) minmax(0,1.2fr);gap:25px;align-items:start}
.detaildokter-panel{overflow:hidden;border:1px solid #e5eee9;border-radius:20px;background:#fff;box-shadow:0 8px 28px rgba(15,23,42,.045)}
.detaildokter-photo-panel{padding:22px}
.detaildokter-photo-wrap{position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center;min-height:330px;border-radius:16px;background:linear-gradient(145deg,#ecfdf5,#ccfbf1)}
.detaildokter-photo{display:block;width:100%;height:350px;object-fit:cover;object-position:center top}
.detaildokter-photo-placeholder{display:flex;flex-direction:column;align-items:center;gap:12px;color:#0f766e}
.detaildokter-photo-placeholder i{font-size:78px;opacity:.7}
.detaildokter-photo-placeholder span{font-size:12px;font-weight:700}
.detaildokter-photo-caption{padding:20px 4px 3px;text-align:center}
.detaildokter-photo-caption h3{margin:0 0 8px;color:#172b25;font-size:20px;font-weight:800}
.detaildokter-specialty{display:inline-flex;align-items:center;gap:7px;padding:7px 12px;border-radius:30px;color:#087f5b;background:#ecfdf5;font-size:12px;font-weight:700}
.detaildokter-info-panel{padding:25px}
.detaildokter-panel-heading{display:flex;align-items:center;gap:12px;padding-bottom:18px;margin-bottom:20px;border-bottom:1px solid #edf2ef}
.detaildokter-heading-icon{display:flex;align-items:center;justify-content:center;flex:0 0 43px;width:43px;height:43px;border-radius:13px;color:#087f5b;background:#e8f8f0;font-size:18px}
.detaildokter-panel-heading h3{margin:0 0 4px;color:#172b25;font-size:17px;font-weight:800}
.detaildokter-panel-heading p{margin:0;color:#84918b;font-size:12px}
.detaildokter-info-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:13px;margin-bottom:25px}
.detaildokter-info-item{min-width:0;padding:15px;border:1px solid #edf2ef;border-radius:13px;background:#fbfdfc}
.detaildokter-info-label{display:flex;align-items:center;gap:7px;margin-bottom:8px;color:#84918b;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.4px}
.detaildokter-info-label i{color:#0d9488;font-size:13px}
.detaildokter-info-value{overflow-wrap:anywhere;color:#263b33;font-size:13px;font-weight:700;line-height:1.7}
.detaildokter-info-value a{color:#087f5b;text-decoration:none}
.detaildokter-info-value a:hover{color:#075c45}
.detaildokter-schedule-heading{display:flex;align-items:center;justify-content:space-between;gap:12px;margin:5px 0 15px}
.detaildokter-schedule-heading h4{margin:0;color:#172b25;font-size:15px;font-weight:800}
.detaildokter-schedule-count{padding:5px 10px;border-radius:20px;color:#087f5b;background:#ecfdf5;font-size:11px;font-weight:800;white-space:nowrap}
.detaildokter-schedule-list{display:grid;gap:10px}
.detaildokter-schedule-item{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:13px 15px;border:1px solid #e7f2ec;border-radius:12px;background:#fbfefc}
.detaildokter-schedule-day{display:flex;align-items:center;gap:10px;color:#263b33;font-size:13px;font-weight:700}
.detaildokter-schedule-day i{display:flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:10px;color:#087f5b;background:#e8f8f0}
.detaildokter-schedule-time{color:#087f5b;font-size:12px;font-weight:800;text-align:right;white-space:nowrap}
.detaildokter-empty-schedule{padding:18px;border:1px dashed #cbded3;border-radius:12px;color:#7b8982;background:#fbfdfc;font-size:13px;line-height:1.7;text-align:center}
.detaildokter-note{display:flex;align-items:flex-start;gap:11px;padding:14px;margin-top:20px;border:1px solid #d9f3e5;border-radius:12px;color:#48645a;background:#f0fdf4;font-size:12px;line-height:1.7}
.detaildokter-note i{margin-top:2px;color:#0d9488;font-size:16px}
.detaildokter-actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:24px}
.detaildokter-btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;min-height:43px;padding:11px 17px;border:1px solid transparent;border-radius:11px;font-size:12px;font-weight:800;text-decoration:none!important;transition:.25s}
.detaildokter-btn-primary{color:#fff!important;background:linear-gradient(120deg,#087f5b,#0d9488);box-shadow:0 5px 14px rgba(8,127,91,.15)}
.detaildokter-btn-primary:hover{color:#fff;transform:translateY(-2px);box-shadow:0 8px 18px rgba(8,127,91,.22)}
.detaildokter-btn-light{border-color:#dce9e1;color:#48645a!important;background:#fff}
.detaildokter-btn-light:hover{border-color:#a8d9bf;color:#087f5b!important;background:#f0fdf4}
.detaildokter-notfound{max-width:650px;padding:50px 25px;margin:35px auto;border:1px solid #e5eee9;border-radius:22px;background:#fff;box-shadow:0 8px 28px rgba(15,23,42,.045);text-align:center}
.detaildokter-notfound-icon{display:flex;align-items:center;justify-content:center;width:76px;height:76px;margin:0 auto 20px;border-radius:22px;color:#b45309;background:#fffbeb;font-size:32px}
.detaildokter-notfound h3{margin-bottom:10px;color:#172b25;font-size:23px;font-weight:800}
.detaildokter-notfound p{margin-bottom:22px;color:#7b8982;font-size:13px;line-height:1.8}
@media(max-width:767.98px){.detaildokter-section{padding:25px 0 45px}.detaildokter-container{width:92%}.detaildokter-hero{padding:27px 23px;border-radius:19px}.detaildokter-main{grid-template-columns:1fr;gap:18px}.detaildokter-photo-wrap{min-height:260px}.detaildokter-photo{height:290px}.detaildokter-info-panel{padding:21px}}
@media(max-width:419.98px){.detaildokter-info-grid{grid-template-columns:1fr}.detaildokter-schedule-item{align-items:flex-start;flex-direction:column}.detaildokter-schedule-time{padding-left:42px;text-align:left;white-space:normal}.detaildokter-actions{flex-direction:column}.detaildokter-btn{width:100%}}
</style>


<section class="detaildokter-section">
    <div class="detaildokter-container">
        <?php if (!$dokter): ?>
            <div class="detaildokter-notfound">
                <div class="detaildokter-notfound-icon"><i class="fas fa-user-md"></i></div>
                <h3>Dokter Tidak Ditemukan</h3>
                <p>Maaf, data dokter yang Anda cari tidak tersedia atau mungkin sudah tidak aktif. Silakan kembali ke daftar dokter untuk melihat informasi lainnya.</p>
                <a href="index.php?page=daftardokter" class="detaildokter-btn detaildokter-btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Dokter
                </a>
            </div>
        <?php else: ?>
            <a href="index.php?page=daftardokter" class="detaildokter-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Dokter
            </a>

            <div class="detaildokter-hero">
                <div class="detaildokter-hero-content">
                    <div class="detaildokter-eyebrow">
                        <i class="fas fa-heartbeat"></i> Rumah Sakit Ahmadi
                    </div>
                    <h2>Profil Dokter</h2>
                    <p>Kenali dokter kami, lihat informasi lengkap dan jadwal praktik untuk membantu Anda merencanakan kunjungan dengan lebih nyaman.</p>
                </div>
            </div>

            <div class="detaildokter-main">
                <div class="detaildokter-panel detaildokter-photo-panel">
                    <div class="detaildokter-photo-wrap">
                        <?php
                        $fotoDokter = trim((string) ($dokter['foto'] ?? ''));
                        $pathFotoDokter = 'assets/images/dokter/' . basename($fotoDokter);
                        ?>
                        <?php if ($fotoDokter !== '' && $fotoDokter !== 'default.png' && is_file($pathFotoDokter)): ?>
                            <img src="<?= eDokter($pathFotoDokter) ?>" alt="Foto <?= eDokter($dokter['namadokter']) ?>" class="detaildokter-photo">
                        <?php elseif ($fotoDokter === 'default.png' && is_file('assets/images/dokter/default.png')): ?>
                            <img src="assets/images/dokter/default.png" alt="Foto <?= eDokter($dokter['namadokter']) ?>" class="detaildokter-photo">
                        <?php else: ?>
                            <div class="detaildokter-photo-placeholder">
                                <i class="fas fa-user-md"></i>
                                <span>Foto Dokter</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="detaildokter-photo-caption">
                        <h3><?= eDokter($dokter['namadokter']) ?></h3>
                        <span class="detaildokter-specialty">
                            <i class="fas fa-stethoscope"></i>
                            <?= eDokter($dokter['spesialis'] ?: 'Dokter Umum') ?>
                        </span>
                    </div>
                </div>

                <div class="detaildokter-panel detaildokter-info-panel">
                    <div class="detaildokter-panel-heading">
                        <div class="detaildokter-heading-icon"><i class="fas fa-id-card"></i></div>
                        <div>
                            <h3>Informasi Dokter</h3>
                            <p>Informasi kontak dan data dokter</p>
                        </div>
                    </div>

                    <div class="detaildokter-info-grid">
                        <div class="detaildokter-info-item">
                            <div class="detaildokter-info-label"><i class="fas fa-user-md"></i> Nama Dokter</div>
                            <div class="detaildokter-info-value"><?= eDokter($dokter['namadokter']) ?></div>
                        </div>
                        <div class="detaildokter-info-item">
                            <div class="detaildokter-info-label"><i class="fas fa-stethoscope"></i> Spesialisasi</div>
                            <div class="detaildokter-info-value"><?= eDokter($dokter['spesialis'] ?: 'Dokter Umum') ?></div>
                        </div>
                        <div class="detaildokter-info-item">
                            <div class="detaildokter-info-label"><i class="fas fa-phone-alt"></i> Nomor Telepon</div>
                            <div class="detaildokter-info-value">
                                <?php if (!empty($dokter['nohp'])): ?>
                                    <a href="tel:<?= eDokter($dokter['nohp']) ?>"><?= eDokter($dokter['nohp']) ?></a>
                                <?php else: ?>
                                    Belum tersedia
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="detaildokter-info-item">
                            <div class="detaildokter-info-label"><i class="fas fa-map-marker-alt"></i> Alamat</div>
                            <div class="detaildokter-info-value"><?= eDokter($dokter['alamat'] ?: 'Belum tersedia') ?></div>
                        </div>
                    </div>

                    <div class="detaildokter-schedule-heading">
                        <h4><i class="far fa-calendar-alt mr-1"></i> Jadwal Praktik</h4>
                        <span class="detaildokter-schedule-count"><?= $totalJadwal ?> Jadwal</span>
                    </div>

                    <?php if ($totalJadwal > 0): ?>
                        <div class="detaildokter-schedule-list">
                            <?php foreach ($jadwalDokter as $jadwal): ?>
                                <div class="detaildokter-schedule-item">
                                    <div class="detaildokter-schedule-day">
                                        <i class="far fa-calendar-check"></i>
                                        <span><?= eDokter($jadwal['haripraktik']) ?></span>
                                    </div>
                                    <div class="detaildokter-schedule-time">
                                        <i class="far fa-clock mr-1"></i>
                                        <?= formatJamDokter($jadwal['jam_mulai']) ?>–<?= formatJamDokter($jadwal['jam_selesai']) ?> WIB
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="detaildokter-empty-schedule">
                            <i class="far fa-calendar-times mr-1"></i>
                            Jadwal praktik dokter ini belum tersedia. Silakan hubungi pihak rumah sakit untuk mendapatkan informasi lebih lanjut.
                        </div>
                    <?php endif; ?>

                    <div class="detaildokter-note">
                        <i class="fas fa-info-circle"></i>
                        <div><strong>Informasi:</strong> Jadwal praktik dapat berubah sewaktu-waktu. Pastikan Anda mengonfirmasi jadwal sebelum berkunjung ke rumah sakit.</div>
                    </div>

                    <div class="detaildokter-actions">
                        <a href="index.php?page=daftardokter" class="detaildokter-btn detaildokter-btn-primary">
                            <i class="fas fa-users"></i> Lihat Dokter Lain
                        </a>
                        <a href="index.php?page=kontak" class="detaildokter-btn detaildokter-btn-light">
                            <i class="fas fa-headset"></i> Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>