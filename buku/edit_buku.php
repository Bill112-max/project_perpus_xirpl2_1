<?php
include __DIR__ . '/../inc/conect.php';

$id_buku = $_GET['id_buku'];
$result = mysqli_query($koneksi, "SELECT * FROM tbl_buku WHERE id_buku='$id_buku'");
$buku = mysqli_fetch_array($result);

if (!$buku) {
    die("Data tidak ditemukan");
}

if (isset($_POST['update'])) {

    $judul_buku  = $_POST['judul_buku'];
    $sinopsis    = $_POST['sinopsis'];
    $jumlah_halaman = $_POST['jumlah_halaman'];
    $jumlah_buku  = $_POST['jumlah_buku'];
    $id_kategori  = $_POST['id_kategori'];
    $id_penerbit  = $_POST['id_penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $gambar_lama  = $_POST['gambar_lama'];

    // CEK upload gambar baru
    if ($_FILES['gambar_buku']['name'] != "") {

        $namaFile = $_FILES['gambar_buku']['name'];
        $tmp      = $_FILES['gambar_buku']['tmp_name'];

        move_uploaded_file($tmp, "../upload/".$namaFile);

        $gambar = $namaFile;

    } else {
        $gambar = $gambar_lama;
    }

    mysqli_query($koneksi,"UPDATE tbl_buku SET 
        judul_buku='$judul_buku',
        sinopsis='$sinopsis',
        jumlah_halaman='$jumlah_halaman',
        jumlah_buku='$jumlah_buku',
        id_kategori='$id_kategori',
        id_penerbit='$id_penerbit',
        tahun_terbit='$tahun_terbit',
        gambar_buku='$gambar'
        WHERE id_buku='$id_buku'
    ");

    echo "<script>
        alert('Data berhasil diupdate');
        window.location='dashboard.php?page=buku';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Buku</title>
<style>

body{
    font-family:Segoe UI;
    background:#f5f8ff;
}

.wrapper{
    max-width:700px;
    margin:40px auto;
    padding:25px;
    background:#fff;
    border-radius:10px;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
}

h2{
    text-align:center;
    margin-bottom:20px;
    color:#0d6efd;
}

/* GRID */
.form-row{
    display:grid;
    grid-template-columns:1fr 2fr;
    gap:10px;
    margin-bottom:15px;
}

.form-row-2{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
    margin-bottom:15px;
}

.form-row-3{
    display:grid;
    grid-template-columns:1fr 1fr 1fr;
    gap:10px;
    margin-bottom:15px;
}

input, textarea, select{
    width:100%;
    padding:8px;
    border:1px solid #ccc;
    border-radius:6px;
}

textarea{
    resize:none;
}

img.preview{
    width:90px;
    margin-top:10px;
    border-radius:6px;
}

button{
    width:100%;
    padding:10px;
    background:#0d6efd;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

button:hover{
    background:#0b5ed7;
}

@media(max-width:768px){
    .form-row,
    .form-row-2,
    .form-row-3{
        grid-template-columns:1fr;
    }
}

</style>
</head>

<body>

<div class="wrapper">
<h2>Edit Buku</h2>

<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="gambar_lama" value="<?= $buku['gambar_buku'] ?>">

<!-- ID || JUDUL -->
<div class="form-row">
    <input type="text" value="<?= $buku['id_buku'] ?>" readonly>
    <input type="text" name="judul_buku" value="<?= $buku['judul_buku'] ?>" required>
</div>

<!-- SINOPSIS -->
<textarea name="sinopsis" rows="3"><?= $buku['sinopsis'] ?></textarea>

<!-- JUMLAH -->
<div class="form-row-2">
    <input type="text" name="jumlah_buku" value="<?= $buku['jumlah_buku'] ?>" placeholder="Jumlah Buku">
    <input type="text" name="jumlah_halaman" value="<?= $buku['jumlah_halaman'] ?>" placeholder="Jumlah Halaman">
</div>

<!-- KATEGORI | PENERBIT | TAHUN -->
<div class="form-row-3">

<select name="id_kategori">
<?php
$kategori = mysqli_query($koneksi,"SELECT * FROM tbl_kategori");
while($k = mysqli_fetch_assoc($kategori)){
$sel = ($k['id_kategori']==$buku['id_kategori']) ? "selected":"";
echo "<option value='$k[id_kategori]' $sel>$k[kategori]</option>";
}
?>
</select>

<select name="id_penerbit">
<?php
$penerbit = mysqli_query($koneksi,"SELECT * FROM tbl_penerbit");
while($p = mysqli_fetch_assoc($penerbit)){
$sel = ($p['id_penerbit']==$buku['id_penerbit']) ? "selected":"";
echo "<option value='$p[id_penerbit]' $sel>$p[nama_penerbit]</option>";
}
?>
</select>

<input type="text" name="tahun_terbit" value="<?= $buku['tahun_terbit'] ?>">

</div>

<!-- COVER -->
<label>Gambar Cover</label>
<input type="file" name="gambar_buku">

<img src="../upload/<?= $buku['gambar_buku'] ?>" class="preview">

<br><br>
<button type="submit" name="update">Simpan</button>

</form>
</div>

</body>
</html>