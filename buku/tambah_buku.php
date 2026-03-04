<?php
include __DIR__ . '/../inc/conect.php';
if ($tampil['akses'] != 'admin') {
    echo "<div class='alert alert-danger'>Anda tidak memiliki akses!</div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Input Form</title>

<style>
body {
    font-family: 'Segoe UI', Tahoma;
    background: #f4f6f9;
    margin: 0;
}

.wrapper {
    max-width: 950px;
    margin: 40px auto;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.wrapper h1 {
    text-align: center;
    margin-bottom: 25px;
    color: #0d6efd;
}

/* GRID 6 KOLOM */
.wrapper form {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 14px;
}

/* INPUT */
.wrapper input,
.wrapper textarea,
.wrapper select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 8px;
}

/* FULL */
.full { grid-column: span 6; }

/* 2 KOLOM */
.half { grid-column: span 3; }

/* JUMLAH TERASA LEBAR */
.big-half { grid-column: span 3; }

/* 3 KOLOM */
.third { grid-column: span 2; }

/* BUTTON */
.wrapper button {
    grid-column: span 6;
    padding: 12px;
    background: #0d6efd;
    color: white;
    border: none;
    border-radius: 8px;
}

/* FILE */
.wrapper input[type="file"] {
    padding: 6px;
    background: #f8f9fa;
    border: 1px dashed #ced4da;
}

/* MOBILE */
@media(max-width:768px){
    .wrapper form {
        grid-template-columns: 1fr;
    }
    .full, .half, .big-half, .third {
        grid-column: span 1;
    }
}
</style>
</head>

<body>
<div class="wrapper">
<h1>Input Data Buku</h1>

<form action="dashboard.php?page=simpan_buku" method="post" enctype="multipart/form-data">

<!-- ID & NAMA -->
<label class="half">ID Buku</label>
<label class="half">Nama Buku</label>

<input class="half" type="text" name="id_buku" required>
<input class="half" type="text" name="judul_buku" required>

<!-- SINOPSIS -->
<label class="full">Sinopsis</label>
<textarea class="full" name="sinopsis"></textarea>

<!-- JUMLAH -->
<label class="big-half">Jumlah Halaman</label>
<label class="big-half">Jumlah Buku</label>

<input class="big-half" type="text" name="jumlah_halaman">
<input class="big-half" type="text" name="jumlah_buku">

<!-- 3 KOLOM -->
<label class="third">Kategori</label>
<label class="third">Penerbit</label>
<label class="third">Tahun Terbit</label>

<select class="third" name="id_kategori" required>
<option value="">-- Pilih --</option>
<?php
$kategori = mysqli_query($koneksi, "SELECT * FROM tbl_kategori ORDER BY kategori ASC");
while ($row = mysqli_fetch_assoc($kategori)) {
echo "<option value='{$row['id_kategori']}'>{$row['id_kategori']} - {$row['kategori']}</option>";
}
?>
</select>

<select class="third" name="id_penerbit" required>
<option value="">-- Pilih --</option>
<?php
$penerbit = mysqli_query($koneksi, "SELECT * FROM tbl_penerbit ORDER BY nama_penerbit ASC");
while ($row = mysqli_fetch_assoc($penerbit)) {
echo "<option value='{$row['id_penerbit']}'>{$row['id_penerbit']} - {$row['nama_penerbit']}</option>";
}
?>
</select>

<input class="third" type="text" name="tahun_terbit" required>

<!-- COVER -->
<label class="full">Cover Buku</label>
<input class="full" type="file" name="cover_buku" accept="image/*">

<button type="submit">Simpan</button>

</form>
</div>
</body>
</html>