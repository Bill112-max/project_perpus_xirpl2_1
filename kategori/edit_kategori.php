<?php
include __DIR__ . '/../inc/conect.php';

$id_kategori = $_GET['id_kategori'];
$result = mysqli_query($koneksi, "SELECT * FROM tbl_kategori WHERE id_kategori='$id_kategori'");
$_kategori = mysqli_fetch_array($result);

if (!$_kategori) {
    die("Data tidak ditemukan.");
}

if (isset($_POST['update'])) {

    $id_kategori = $_POST['id_kategori'];
    $kategori    = $_POST['kategori'];

    mysqli_query($koneksi,"UPDATE tbl_kategori 
        SET kategori='$kategori'
        WHERE id_kategori='$id_kategori'
    ");

    echo "<script>
        alert('Data berhasil diupdate');
        window.location='dashboard.php?page=kategori';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Kategori</title>

<style>
body{
    font-family:Segoe UI;
    background:#f4f8ff;
}

/* WRAPPER */
.wrapper{
    max-width:500px;
    margin:40px auto;
    padding:25px;
    background:#fff;
    border-radius:10px;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
}

h2{
    text-align:center;
    color:#0d6efd;
    margin-bottom:20px;
}

/* GRID */
.row-2{
    display:grid;
    grid-template-columns:1fr 2fr;
    gap:12px;
    margin-bottom:20px;
}

/* FIELD */
.field{
    display:flex;
    flex-direction:column;
}

label{
    font-size:0.8rem;
    margin-bottom:3px;
    color:#555;
}

input{
    padding:8px;
    border:1px solid #ccc;
    border-radius:6px;
}

/* BUTTON */
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
    .row-2{
        grid-template-columns:1fr;
    }
}
</style>

</head>

<body>

<div class="wrapper">
<h2>Edit Kategori</h2>

<form method="POST">

<div class="row-2">

<div class="field">
<label>ID Kategori</label>
<input type="text" name="id_kategori" value="<?= $_kategori['id_kategori']; ?>" readonly>
</div>

<div class="field">
<label>Nama Kategori</label>
<input type="text" name="kategori" value="<?= $_kategori['kategori']; ?>" required>
</div>

</div>

<button type="submit" name="update">Simpan</button>

</form>
</div>

</body>
</html>