<h2>Tambah Pasien</h2>

<form action="proses/prosespasien.php?proses=insert" method="post">
    <p>
        <label>Nama Pasien</label><br>
        <input type="text" name="namapasien" required>
    </p>
    <p>
        <label>Jenis Kelamin</label><br>
        <select name="jeniskelamin" required>
            <option value="">-- Pilih Jenis Kelamin --</option>
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
        </select>
    </p>
    <p>
        <label>Alamat</label><br>
        <textarea name="alamat" rows="3" required></textarea>
    </p>
    <p>
        <label>No. HP</label><br>
        <input type="text" name="nohp" required>
    </p>
    <button type="submit">Simpan</button>
    <a href="index.php?page=pasien">Kembali</a>
</form>