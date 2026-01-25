<?php
include '../inc/conect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Form</title>
    <link rel="stylesheet" href="../css/tambah.css">
</head>
<body>
<div class="wrapper">
    <h1> Input data penerbit </h1>
    <form action="simpan_penerbit.php" method="post">
        <label>id penerbit:</label><br> 
        <input type="text" name="id_penerbit" required><br><br> 
        <label>Nama Penerbit:</label><br> 
        <input type="text" name="nama_penerbit" required><br><br> 
        <label>nomor telephone penerbit:</label><br> 
        <input type="text" name="no_tlp_penerbit" required><br><br>
        <label>Nama Sales:</label><br> 
        <input type="text" name="nama_sales" required><br><br>
        <label>nomor telephone sales:</label><br> 
        <input type="text" name="no_tlp_sales" required><br><br>
        
        <button type="submit">Simpan</button> 
    </form> 
</div>
</body>
</html>