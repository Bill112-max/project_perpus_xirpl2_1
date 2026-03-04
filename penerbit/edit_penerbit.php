<?php
include __DIR__ . '/../inc/conect.php';

$id_penerbit = $_GET['id_penerbit'];
$result = mysqli_query($koneksi, "SELECT * FROM tbl_penerbit WHERE id_penerbit='$id_penerbit'");
$penerbit = mysqli_fetch_array($result);

if (!$penerbit) {
    die("Data tidak ditemukan.");
}

if (isset($_POST['update'])) {

    $id_penerbit        = $_POST['id_penerbit'];
    $nama_penerbit      = $_POST['nama_penerbit'];
    $no_tlp_penerbit    = $_POST['no_tlp_penerbit'];
    $nama_sales         = $_POST['nama_sales'];
    $no_tlp_sales       = $_POST['no_tlp_sales'];

    mysqli_query($koneksi,"UPDATE tbl_penerbit SET 
        nama_penerbit='$nama_penerbit',
        no_tlp_penerbit='$no_tlp_penerbit',
        nama_sales='$nama_sales',
        no_tlp_sales='$no_tlp_sales'
        WHERE id_penerbit='$id_penerbit'
    ");

    echo "<script>
        alert('Data berhasil diupdate');
        window.location='dashboard.php?page=penerbit   ';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Penerbit</title>

<style>
body{
    font-family:Segoe UI;
    background:#f4f8ff;
}

.wrapper{
    max-width:650px;
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
.row-3{
    display:grid;
    grid-template-columns:1fr 1fr 1fr;
    gap:12px;
    margin-bottom:15px;
}

.row-2{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
    margin-bottom:15px;
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
    .row-3,
    .row-2{
        grid-template-columns:1fr;
    }
}
</style>

</head>

<body>

<div class="wrapper">
<h2>Edit Penerbit</h2>

<form method="POST">

<div class="row-3">

<div class="field">
<label>ID Penerbit</label>
<input type="text" name="id_penerbit" value="<?= $penerbit['id_penerbit']; ?>" readonly>
</div>

<div class="field">
<label>Nama Penerbit</label>
<input type="text" name="nama_penerbit" value="<?= $penerbit['nama_penerbit']; ?>" required>
</div>

<div class="field">
<label>No Telp Penerbit</label>
<input type="text" name="no_tlp_penerbit" value="<?= $penerbit['no_tlp_penerbit']; ?>" required>
</div>

</div>

<div class="row-2">

<div class="field">
<label>Nama Sales</label>
<input type="text" name="nama_sales" value="<?= $penerbit['nama_sales']; ?>" required>
</div>

<div class="field">
<label>No Telp Sales</label>
<input type="text" name="no_tlp_sales" value="<?= $penerbit['no_tlp_sales']; ?>" required>
</div>

</div>

<button type="submit" name="update">Simpan</button>

</form>
</div>

</body>
</html>