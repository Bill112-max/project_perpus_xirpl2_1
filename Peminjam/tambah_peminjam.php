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
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
    margin: 0;
    padding: 0;
}

.wrapper {
    max-width: 600px;
    margin: 40px auto;
    background-color: #ffffff;
    padding: 25px;
    border: 1px solid #ddd;
    border-radius: 8px;
}

.wrapper h1 {
    text-align: center;
    font-size: 1.6rem;
    margin-bottom: 25px;
    color: #333;
}

.wrapper label {
    display: block;
    margin-bottom: 6px;
    font-weight: bold;
    color: #444;
}

.wrapper input[type="text"],
.wrapper input[type="date"],
.wrapper select,
.wrapper textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 18px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 1rem;
}

.wrapper button {
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    cursor: pointer;
}

.wrapper button:hover {
    background-color: #0056b3;
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
            <label>Jumlah Pinjam:</label><br>
<input type="text" name="jumlah_pinjam"><br><br>

<label>Tanggal Peminjaman:</label><br>
<input type="date" name="tanggal_pinjam"><br><br>

<label>Tanggal Pengembalian:</label><br>
<input type="date" name="tanggal_kembali"><br><br>
            <button type="submit">Simpan</button>
        </form>
    </div>
</body>

</html>
        