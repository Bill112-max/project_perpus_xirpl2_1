<?php
include __DIR__ . '/../inc/conect.php';
if ($tampil['akses'] != 'anggota' && $tampil['akses'] != 'admin') {
    echo "<div class='alert alert-danger'>Anda tidak memiliki akses!</div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<style>
    body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f6f9;
    margin: 0;
    padding: 0;
}

.wrapper {
    max-width: 700px;
    margin: 40px auto;
    background-color: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}
.wrapper h1 {
    text-align: center;
    font-size: 1.8rem;
    margin-bottom: 30px;
    color: #0d6efd;
}
.wrapper label {
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
    color: #333;
}

.wrapper input[type="text"],
.wrapper textarea,
.wrapper select {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 20px;
    border: 1px solid #ced4da;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.wrapper input:focus,
.wrapper textarea:focus,
.wrapper select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 6px rgba(13, 110, 253, 0.3);
    outline: none;
}

.wrapper button {
    width: 100%;
    padding: 12px;
    background-color: #0d6efd;
    color: #fff;
    font-size: 1rem;
    font-weight: 600;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
.wrapper button:hover {
    background-color: #0b5ed7;
}
@media (max-width: 768px) {
    .wrapper {
        margin: 20px;
        padding: 20px;
    }
}
</style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Form</title>
    <link rel="stylesheet" href="../css/tambah.css">
</head>

<body>
    <div class="wrapper">
        <h1> Form Pengajuan Pinjamanan </h1>
        <form action="dashboard.php?page=simpan_peminjam" method="post">
            <label>Judul Buku:</label><br>
            <select name="id_buku" required>
                <option value="">-- Pilih Buku --</option>
                <?php
                $buku = mysqli_query($koneksi, "SELECT * FROM tbl_buku ORDER BY id_buku ASC");
                while ($row = mysqli_fetch_assoc($buku)) {
                    echo "<option value='{$row['id_buku']}'>{$row['judul_buku']}</option>";
                }
                ?>
            </select>
            <label>Sinopsis:</label><br>
            <textarea name="sinopsis"></textarea><br><br>
            <label>Jumlah halaman:</label><br>
            <input type="text" name="jumlah_halaman"><br><br>
            <label>Jumlah buku:</label><br>
            <input type="text" name="jumlah_buku"><br><br>
            <label>kategori(id):</label><br>
            
            </select><br><br>
            <label>penerbit(id):</label><br>
            <select name="id_penerbit" required>
                <option value="">-- Pilih penerbit --</option>
                <?php
                $penerbit = mysqli_query($koneksi, "SELECT * FROM tbl_penerbit ORDER BY nama_penerbit ASC");
                while ($row = mysqli_fetch_assoc($penerbit)) {
                    echo "<option value='{$row['id_penerbit']}'>{$row['id_penerbit']} - {$row['nama_penerbit']}</option>";
                }
                ?>
            </select><br><br>
            <label>Tahun terbit:</label><br>
            <input type="text" name="tahun_terbit" required><br><br>

            <button type="submit">Simpan</button>
        </form>
    </div>
</body>

</html>