
<?php
$namadokter = $_SESSION['namadokter'] ?? '';
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">Tambah Jadwal Praktik</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboarddokter">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=jadwaldokterdokter">Jadwal Dokter</a></li>
                    <li class="breadcrumb-item active">Tambah Jadwal</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-calendar-plus mr-2"></i>Form Tambah Jadwal Praktik</h3>
                    </div>
                    <form action="index.php?page=jadwaldokterdokter" method="POST">
                        <?= csrfField() ?>
                        <input type="hidden" name="page" value="jadwaldokterdokter">
                        <input type="hidden" name="aksi" value="tambah">
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-1"></i>
                                Silakan tambahkan satu atau beberapa jadwal praktik Anda. Semua jadwal akan otomatis terhubung dengan akun dokter yang sedang login.
                            </div>
                            <div class="form-group">
                                <label for="namadokter"><i class="fas fa-user-md mr-1 text-success"></i>Nama Dokter</label>
                                <input type="text" id="namadokter" class="form-control" value="<?= e($namadokter) ?>" readonly>
                                <small class="form-text text-muted">Identitas dokter diambil otomatis dari akun yang sedang digunakan.</small>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0"><i class="fas fa-list text-success mr-1"></i>Daftar Jadwal Praktik</h5>
                                <button type="button" class="btn btn-primary btn-sm" id="tambahJadwal">
                                    <i class="fas fa-plus mr-1"></i>Tambah Jadwal
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th width="35%">Hari Praktik</th>
                                            <th width="25%">Jam Mulai</th>
                                            <th width="25%">Jam Selesai</th>
                                            <th width="10%" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="daftarJadwal"></tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Pilih hari dan masukkan jam praktik untuk setiap jadwal.</small>
                                <strong>Total Jadwal: <span id="totalJadwal" class="text-success">0</span></strong>
                            </div>
                            <div class="callout callout-warning mt-3 mb-0">
                                <h6><i class="fas fa-exclamation-triangle mr-1"></i>Perhatian</h6>
                                <small>Jam selesai harus lebih besar daripada jam mulai. Pastikan jadwal tidak bertabrakan dengan jadwal praktik lainnya.</small>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="index.php?page=jadwaldokterdokter" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-1"></i>Simpan Semua Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const daftarJadwal = document.getElementById('daftarJadwal');
    const tombolTambah = document.getElementById('tambahJadwal');
    const totalJadwal = document.getElementById('totalJadwal');
    const daftarHari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
    function hitungJadwal() {
        const baris = daftarJadwal.querySelectorAll('tr');
        totalJadwal.textContent = baris.length;
        baris.forEach(function (item, index) {
            item.querySelector('.nomorJadwal').textContent = index + 1;
        });
    }
    function buatJadwal() {
        let pilihanHari = '<option value="">-- Pilih Hari --</option>';
        daftarHari.forEach(function (hari) {
            pilihanHari += `<option value="${hari}">${hari}</option>`;
        });
        const baris = document.createElement('tr');
        baris.innerHTML = `
            <td class="text-center nomorJadwal align-middle"></td>
            <td><select name="haripraktik[]" class="form-control" required>${pilihanHari}</select></td>
            <td><input type="time" name="jam_mulai[]" class="form-control" required></td>
            <td><input type="time" name="jam_selesai[]" class="form-control" required></td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-outline-danger btn-sm hapusJadwal" title="Hapus Jadwal">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        daftarJadwal.appendChild(baris);
        hitungJadwal();
    }
    tombolTambah.addEventListener('click', function () {
        buatJadwal();
    });
    daftarJadwal.addEventListener('click', function (event) {
        const tombol = event.target.closest('.hapusJadwal');
        if (!tombol) return;
        if (daftarJadwal.querySelectorAll('tr').length <= 1) {
            alert('Minimal harus ada satu jadwal praktik.');
            return;
        }
        tombol.closest('tr').remove();
        hitungJadwal();
    });
    buatJadwal();
});
</script>
